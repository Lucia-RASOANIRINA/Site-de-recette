<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette;
use App\Models\RecipeVote;
use App\Models\Like;

class StatsController extends Controller
{
    /**
     * Classement des recettes de la semaine.
     * Score = likes des 7 derniers jours + votes publics des 7 derniers jours.
     * Accessible a tous (visiteurs sans compte inclus).
     */
    public static function weeklyRanking($limit = 5)
    {
        $since = now()->subDays(7);

        $likes = Like::whereNotNull('recette_id')
            ->where('created_at', '>=', $since)
            ->selectRaw('recette_id, COUNT(*) as c')
            ->groupBy('recette_id')
            ->pluck('c', 'recette_id');

        $votes = RecipeVote::where('created_at', '>=', $since)
            ->selectRaw('recette_id, COUNT(*) as c')
            ->groupBy('recette_id')
            ->pluck('c', 'recette_id');

        $ids = $likes->keys()->merge($votes->keys())->unique();

        $recettes = Recette::with('user')->whereIn('id', $ids)->get()
            ->map(function ($r) use ($likes, $votes) {
                $r->week_likes = (int) ($likes[$r->id] ?? 0);
                $r->week_votes = (int) ($votes[$r->id] ?? 0);
                $r->week_score = $r->week_likes + $r->week_votes;
                return $r;
            })
            ->sortByDesc('week_score')
            ->values();

        return $recettes->take($limit);
    }

    /**
     * Page publique : la recette la plus aimee de la semaine.
     */
    public function weekly()
    {
        $ranking = self::weeklyRanking(10);
        $top = $ranking->first();
        $recettes = Recette::orderBy('titre')->get(['id', 'titre']); // pour l'autocompletion du formulaire

        return view('page.recette-semaine', compact('ranking', 'top', 'recettes'));
    }

    /**
     * Vote public pour une recette de la semaine.
     * Le visiteur saisit le NOM de la recette et son EMAIL.
     * Anti-redondance : un email ne vote qu'une fois par recette
     * (mais peut voter pour plusieurs recettes differentes).
     */
    public function vote(Request $request)
    {
        $request->validate([
            'recette_nom' => 'required|string|max:200',
            'email'       => 'required|email|max:200',
        ]);

        $nom   = trim($request->recette_nom);
        $email = mb_strtolower(trim($request->email));

        $recette = Recette::whereRaw('LOWER(titre) = ?', [mb_strtolower($nom)])->first()
            ?? Recette::whereRaw('LOWER(titre) LIKE ?', ['%' . mb_strtolower($nom) . '%'])->first();

        if (!$recette) {
            return $this->voteResponse($request, false, "Aucune recette ne correspond à « {$nom} ».");
        }

        $already = RecipeVote::where('recette_id', $recette->id)
            ->where('email', $email)
            ->exists();

        if ($already) {
            return $this->voteResponse($request, false, "Vous avez déjà voté pour « {$recette->titre} » avec cet email.");
        }

        RecipeVote::create([
            'recette_id'  => $recette->id,
            'recette_nom' => $recette->titre,
            'email'       => $email,
        ]);

        return $this->voteResponse($request, true, "Merci ! Votre coup de cœur pour « {$recette->titre} » est enregistré.");
    }

    private function voteResponse(Request $request, bool $success, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $success, 'message' => $message]);
        }
        return back()->with($success ? 'vote_success' : 'vote_error', $message);
    }
}
