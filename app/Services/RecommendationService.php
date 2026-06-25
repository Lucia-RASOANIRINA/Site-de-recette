<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Like;

/**
 * Service de recommandation.
 *
 * Personnalise le classement des recettes (et des résultats de recherche)
 * en fonction du profil de l'utilisateur connecté :
 *   - les auteurs déjà appréciés sont mis en avant ;
 *   - les recettes correspondant à la spécialité de l'utilisateur sont valorisées ;
 *   - la popularité (nombre de « coups de cœur ») départage les égalités.
 *
 * Pour un visiteur (non connecté), le classement par défaut s'applique :
 * popularité puis fraîcheur.
 */
class RecommendationService
{
    /**
     * Construit le contexte de recommandation propre à un utilisateur.
     *
     * @return array{liked_author_ids: array<int>, liked_recette_ids: array<int>, specialty_terms: array<string>}
     */
    public static function context(?User $user): array
    {
        if (!$user) {
            return ['liked_author_ids' => [], 'liked_recette_ids' => [], 'specialty_terms' => []];
        }

        $likedRecetteIds = Like::where('user_id', $user->id)
            ->whereNotNull('recette_id')
            ->pluck('recette_id')
            ->all();

        $likedAuthorIds = \App\Models\Recette::whereIn('id', $likedRecetteIds)
            ->pluck('user_id')
            ->unique()
            ->all();

        // Mots-clés issus de la spécialité (ex. « Cuisine du monde » -> [cuisine, monde]).
        $specialtyTerms = collect(preg_split('/[\s&,]+/', (string) $user->specialty))
            ->map(fn ($t) => mb_strtolower(trim($t)))
            ->filter(fn ($t) => mb_strlen($t) >= 4)
            ->values()
            ->all();

        return [
            'liked_author_ids' => $likedAuthorIds,
            'liked_recette_ids' => $likedRecetteIds,
            'specialty_terms' => $specialtyTerms,
        ];
    }

    /**
     * Calcule un score de pertinence pour une recette donnée.
     */
    public static function score($recette, array $context): float
    {
        $score = 0.0;

        // Auteur déjà apprécié par l'utilisateur.
        if (in_array($recette->user_id, $context['liked_author_ids'], true)) {
            $score += 5;
        }

        // Correspondance avec la spécialité de l'utilisateur.
        $haystack = mb_strtolower($recette->titre . ' ' . $recette->description);
        foreach ($context['specialty_terms'] as $term) {
            if ($term !== '' && mb_strpos($haystack, $term) !== false) {
                $score += 3;
                break;
            }
        }

        // Popularité (coups de cœur), pondérée.
        $score += (float) ($recette->likes_count ?? 0) * 0.5;

        return $score;
    }

    /**
     * Trie une collection de recettes selon les recommandations de l'utilisateur.
     * Les recettes déjà aimées sont légèrement reléguées pour favoriser la découverte.
     */
    public static function order(Collection $recettes, ?User $user): Collection
    {
        $context = self::context($user);

        if (empty($context['liked_author_ids']) && empty($context['specialty_terms'])) {
            // Visiteur ou profil sans signal : popularité puis fraîcheur.
            return $recettes->sortByDesc(fn ($r) => [
                $r->likes_count ?? 0,
                optional($r->created_at)->timestamp ?? 0,
            ])->values();
        }

        return $recettes->sortByDesc(function ($r) use ($context) {
            $score = self::score($r, $context);
            // Favoriser la découverte : on relègue légèrement ce qui est déjà aimé.
            if (in_array($r->id, $context['liked_recette_ids'], true)) {
                $score -= 2;
            }
            return $score;
        })->values();
    }
}
