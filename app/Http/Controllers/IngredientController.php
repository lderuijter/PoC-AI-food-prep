<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredienten = Ingredient::latest()->get();
        return view('ingredienten.index', compact('ingredienten'));
    }
}
