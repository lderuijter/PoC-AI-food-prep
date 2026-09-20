<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredienten = Ingredient::latest()->get();
        $aantalVoorkeuren = DB::table('ingredient_voorkeuren')->where('user_id', 1)->count();

        return view('ingredienten.index', compact('ingredienten', 'aantalVoorkeuren'));
    }
}
