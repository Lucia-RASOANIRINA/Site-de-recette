<?php
//pour le page de connexion
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecetteController;

use App\Http\Controllers\CommunityController;

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

Route::get('/community', [CommunityController::class,'index'])->middleware('auth');

Route::get('/UserCommunity', [CommunityController::class,'userCommunity'])->middleware('auth');

Route::post('/community/post', [CommunityController::class,'store'])->middleware('auth');

Route::post('/community/comment', [CommunityController::class,'comment'])->middleware('auth');

Route::post('/community/like/{id}', [CommunityController::class,'like'])->middleware('auth');