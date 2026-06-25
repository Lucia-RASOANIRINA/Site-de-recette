<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\RecommendationService;

class RecetteController extends Controller
{
    /**
     * Page d'accueil publique (visiteurs et membres non requis).
     *
     * Les recettes sont classées par recommandation (popularité pour un
     * visiteur). Les paramètres de requête permettent l'affichage in-page :
     *   - « q »       : résultats de recherche affichés en haut de page ;
     *   - « recette » : détail d'une recette affiché en haut de page.
     */
    public function index()
    {
        try {
            $recettes = Recette::with(['ingredients', 'user', 'likes'])
                ->withCount('likes')
                ->get();

            $recettes = RecommendationService::order($recettes, Auth::user());

            $search = SearchController::query(request('q'));
            $selectedRecette = $this->resolveSelectedRecette(request('recette'));

            return view('home', compact('recettes', 'search', 'selectedRecette'));
        } catch (\Exception $e) {
            Log::error('Erreur index recettes : ' . $e->getMessage());

            return view('home', [
                'recettes' => collect(),
                'search' => SearchController::query(null),
                'selectedRecette' => null,
            ])->with('error', 'Erreur de chargement des recettes.');
        }
    }

    /**
     * Espace d'accueil de l'utilisateur connecté (UserHome).
     *
     * Les recettes sont recommandées en fonction du profil du membre. Comme
     * pour l'accueil public, la recherche et le détail d'une recette s'affichent
     * directement dans la page via les paramètres « q » et « recette ».
     */
    public function userIndex()
    {
        try {
            $recettes = Recette::with(['ingredients', 'likes', 'user'])
                ->withCount('likes')
                ->get();

            $recettes = RecommendationService::order($recettes, Auth::user());

            $search = SearchController::query(request('q'));
            $selectedRecette = $this->resolveSelectedRecette(request('recette'));

            return view('page.UserHome', compact('recettes', 'search', 'selectedRecette'));
        } catch (\Exception $e) {
            Log::error('Erreur userIndex : ' . $e->getMessage());

            return back()->with('error', 'Erreur de chargement des recettes.');
        }
    }

    /**
     * Charge la recette sélectionnée pour l'affichage in-page, ou null.
     *
     * @param  mixed  $id  Identifiant éventuel transmis via la requête.
     */
    private function resolveSelectedRecette($id): ?Recette
    {
        if (empty($id)) {
            return null;
        }

        $recette = Recette::with(['ingredients', 'user'])->withCount('likes')->find($id);

        if ($recette) {
            $recette->is_liked_by_current = Auth::check()
                && $recette->likes()->where('user_id', Auth::id())->exists();

            // Petite recommandation : autres recettes suggérées selon le profil.
            $autres = Recette::with('user')->withCount('likes')
                ->where('id', '!=', $recette->id)
                ->get();
            $recette->setRelation('autres', RecommendationService::order($autres, Auth::user())->take(4));
        }

        return $recette;
    }

    /**
     * Page dédiée au détail d'une recette (lien direct, partage).
     */
    public function showPage($id)
    {
        $recette = Recette::with(['ingredients', 'user'])->withCount('likes')->findOrFail($id);
        $isLiked = Auth::check() ? $recette->likes()->where('user_id', Auth::id())->exists() : false;
        $autres = Recette::with('user')->withCount('likes')
            ->where('id', '!=', $recette->id)
            ->latest()->limit(4)->get();

        return view('page.recette-detail', compact('recette', 'isLiked', 'autres'));
    }

    /**
     * 3. Gérer le Like (J'adore)
     */
    public function like($id)
    {
        try {
            $recette = Recette::findOrFail($id);
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour liker'
                ], 401);
            }

            // Vérifier si l'utilisateur a déjà liké
            $existingLike = $recette->likes()->where('user_id', $user->id)->first();

            if ($existingLike) {
                // Supprimer le like
                $existingLike->delete();
                // Retrait de l'XP précédemment créditée à l'auteur.
                if ($recette->user && $recette->user_id !== $user->id) {
                    $recette->user->addXp(-10);
                }
                $liked = false;
                $message = 'Like retiré';
            } else {
                // Ajouter le like
                $recette->likes()->create([
                    'user_id' => $user->id,
                    'recette_id' => $recette->id
                ]);
                // L'auteur de la recette gagne de l'XP pour le « coup de cœur » reçu.
                if ($recette->user && $recette->user_id !== $user->id) {
                    $recette->user->addXp(10);
                }
                $liked = true;
                $message = 'Like ajouté';
            }

            // Compter les likes
            $likesCount = $recette->likes()->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur like: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 4. Récupérer une recette pour l'édition
     */
    public function show($id)
    {
        try {
            $recette = Recette::with(['ingredients', 'user', 'likes'])
                ->findOrFail($id);
            
            return response()->json($recette);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvée'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 5. Récupérer toutes les recettes (API)
     */
    public function getAll()
    {
        try {
            $recettes = Recette::with(['ingredients', 'user', 'likes'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'recettes' => $recettes
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getAll: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 6. Vérifier si l'utilisateur a liké une recette
     */
    public function checkLike($id)
    {
        try {
            $recette = Recette::findOrFail($id);
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non connecté'
                ], 401);
            }

            $isLiked = $recette->likes()->where('user_id', $user->id)->exists();
            $likesCount = $recette->likes()->count();

            return response()->json([
                'success' => true,
                'is_liked' => $isLiked,
                'likes_count' => $likesCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

     public function getDetails($id)
    {
        try {
            $recette = Recette::with(['ingredients', 'user', 'likes'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'recette' => $recette
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Recette non trouvée'
            ], 404);
        }
    }
}