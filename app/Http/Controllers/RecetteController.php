<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette; 
use Illuminate\Support\Facades\Auth;

class RecetteController extends Controller
{
    // 1. Pour la page d'accueil publique (sans forcément être connecté)
    public function index()
    {
        $recettes = Recette::with('ingredients')->get();
        return view('home', compact('recettes'));
    }

    // 2. POUR L'ESPACE UTILISATEUR (UserHome) - C'est ici qu'on ajoute les likes
    public function userIndex()
    {
        // On récupère les recettes avec ingredients ET likes
        $recettes = Recette::with(['ingredients', 'likes'])->get();

        // On renvoie vers la vue UserHome avec la variable définie
        return view('page.UserHome', compact('recettes'));
    }

    // 3. Méthode pour enregistrer le Like (J'adore)
    public function like($id)
    {
        $recette = Recette::findOrFail($id);
        $user = Auth::user();

        $like = $recette->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['status' => 'unliked']);
        } else {
            $recette->likes()->create(['user_id' => $user->id]);
            return response()->json(['status' => 'liked']);
        }
    }
}