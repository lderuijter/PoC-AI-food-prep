<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientVoorkeurController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IngredientController::class, 'index'])->name('ingredienten.index');

Route::post('/ingredienten/{ingredient}/voorkeur', [IngredientVoorkeurController::class, 'store'])
    ->name('voorkeuren.store');
