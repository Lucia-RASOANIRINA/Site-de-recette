<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recette; // Assure-toi que le modèle existe et est lié à la table

class RecetteController extends Controller
{
    // Méthode pour afficher toutes les recettes
    public function index()
    {
        // On récupère toutes les recettes avec leurs utilisateurs (relation user)
        $recettes = Recette::with('user')->get();

        // On envoie les recettes à la vue "home"
        return view('home', compact('recettes'));
    }
}