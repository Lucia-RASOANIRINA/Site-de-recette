<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecetteController;

Route::get('/', [RecetteController::class, 'index']);


