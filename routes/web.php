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
