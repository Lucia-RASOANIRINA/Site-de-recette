<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recette;
use App\Models\Post;
use App\Models\User;
use App\Services\RecommendationService;

/**
 * Recherche globale de la plateforme.
 *
 * Couvre les recettes, les publications et les membres. Pour un utilisateur
 * connecté, les recettes trouvées sont reclassées selon son profil
 * (recommandations). Le point d'entrée redirige le résultat vers la page
 * adaptée au rôle de l'internaute.
 */
class SearchController extends Controller
{
    /**
     * Exécute la recherche et retourne un jeu de résultats réutilisable par les
     * pages d'accueil et le tableau de bord.
     *
     * @param  string|null  $q  Terme recherché.
     * @return array{q: string, recettes: \Illuminate\Support\Collection, posts: \Illuminate\Support\Collection, users: \Illuminate\Support\Collection, total: int}
     */
    public static function query(?string $q): array
    {
        $q = trim((string) $q);

        if ($q === '') {
            return ['q' => '', 'recettes' => collect(), 'posts' => collect(), 'users' => collect(), 'total' => 0];
        }

        $like = '%' . $q . '%';

        // Recettes correspondant au titre ou à la description.
        $recettes = Recette::with('user')->withCount('likes')
            ->where(function ($builder) use ($like) {
                $builder->where('titre', 'like', $like)
                        ->orWhere('description', 'like', $like);
            })
            ->limit(24)
            ->get();

        // Classement personnalisé selon le profil de l'utilisateur connecté.
        $recettes = RecommendationService::order($recettes, Auth::user());

        // Publications correspondant au contenu.
        $posts = Post::with('user')->withCount(['likes', 'comments'])
            ->where('content', 'like', $like)
            ->latest()
            ->limit(24)
            ->get();

        // Membres correspondant au nom, à la spécialité ou à la ville (hors administrateurs).
        $users = User::where('role', '!=', 'admin')
            ->where(function ($builder) use ($like) {
                $builder->where('name', 'like', $like)
                        ->orWhere('specialty', 'like', $like)
                        ->orWhere('city', 'like', $like);
            })
            ->limit(24)
            ->get();

        return [
            'q' => $q,
            'recettes' => $recettes,
            'posts' => $posts,
            'users' => $users,
            'total' => $recettes->count() + $posts->count() + $users->count(),
        ];
    }

    /**
     * Point d'entrée de la recherche.
     *
     * Redirige le résultat vers la page appropriée selon le rôle :
     *   - administrateur : tableau de bord ;
     *   - membre connecté : page d'accueil utilisateur ;
     *   - visiteur : page des recettes (accueil public).
     */
    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->to('/admin?q=' . urlencode($q));
        }

        if (Auth::check()) {
            return redirect()->to('/UserHome?q=' . urlencode($q));
        }

        return redirect()->to('/?q=' . urlencode($q));
    }
}
