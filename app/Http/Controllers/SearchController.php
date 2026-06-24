<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recette;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    /**
     * Recherche intelligente sur les recettes, publications et utilisateurs.
     * Retourne un tableau de resultats reutilisable par les pages d'accueil / dashboard.
     */
    public static function query(?string $q): array
    {
        $q = trim((string) $q);
        if ($q === '') {
            return ['q' => '', 'recettes' => collect(), 'posts' => collect(), 'users' => collect(), 'total' => 0];
        }

        $like = '%' . $q . '%';

        $recettes = Recette::with('user')->withCount('likes')
            ->where(function ($w) use ($like) {
                $w->where('titre', 'like', $like)->orWhere('description', 'like', $like);
            })
            ->orderByDesc('likes_count')->limit(24)->get();

        $posts = Post::with('user')->withCount(['likes', 'comments'])
            ->where('content', 'like', $like)
            ->latest()->limit(24)->get();

        $users = User::where('role', '!=', 'admin')
            ->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                  ->orWhere('specialty', 'like', $like)
                  ->orWhere('city', 'like', $like);
            })
            ->limit(24)->get();

        return [
            'q' => $q,
            'recettes' => $recettes,
            'posts' => $posts,
            'users' => $users,
            'total' => $recettes->count() + $posts->count() + $users->count(),
        ];
    }

    /**
     * Point d'entree de la recherche : redirige le resultat vers la page adaptee
     * - Admin  -> tableau de bord
     * - Membre -> page d'accueil utilisateur
     * - Visiteur -> page des recettes (accueil public)
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
