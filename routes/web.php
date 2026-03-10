<?php
//pour le page de connexion
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecetteController;


Route::get('/', [RecetteController::class, 'index']);

// Affiche la vue de connexion/inscription
Route::get('/login', function () {
    return view('page.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Route pour l'espace connecté (UserHome)
Route::get('/UserHome', [RecetteController::class, 'userIndex'])->middleware('auth')->name('user.home');

// Route pour le bouton "J'adore"
Route::post('/recettes/{id}/like', [RecetteController::class, 'like'])->middleware('auth');