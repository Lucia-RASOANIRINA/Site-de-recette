<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette; 

class RecetteController extends Controller
{
    // Méthode pour afficher toutes les recettes
    public function index()
    {
        // On récupère toutes les recettes avec leurs ingredients (relation recettes ingredients)
        $recettes = Recette::with('ingredients')->get();

        // On envoie les recettes à la vue "home"
        return view('home', compact('recettes'));
    }
}