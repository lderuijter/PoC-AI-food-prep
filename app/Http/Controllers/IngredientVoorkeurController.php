<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientVoorkeurController extends Controller
{
    public function store(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'voorkeur' => 'required|integer|between:1,5',
        ]);

        $userId = 1; // vaste test-user zolang er geen login is

        $ingredient->gebruikers()->syncWithoutDetaching([
            $userId => ['voorkeur' => $validated['voorkeur']],
        ]);

        return back();
    }
}
