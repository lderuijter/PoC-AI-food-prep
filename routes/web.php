<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientVoorkeurController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IngredientController::class, 'index'])->name('ingredienten.index');

Route::post('/voorkeuren/reset', [IngredientVoorkeurController::class, 'reset'])->name('voorkeuren.reset');

Route::post('/ingredienten/{ingredient}/voorkeur', [IngredientVoorkeurController::class, 'store'])
    ->name('voorkeuren.store');

Route::get('/aanbevelingen', [RecommendationController::class, 'index'])->name('aanbevelingen.index');
