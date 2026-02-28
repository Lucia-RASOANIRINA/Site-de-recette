<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecetteController;
//pour le page de connexion
use App\Http\Controllers\AuthController;

Route::get('/', [RecetteController::class, 'index']);

// Affiche la vue de connexion/inscription
Route::get('/login', function () {
    return view('page.login'); 
})->name('login');

// Gère les actions de soumission des formulaires
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
