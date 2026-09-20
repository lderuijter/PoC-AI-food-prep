<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recept;
use Illuminate\Database\Seeder;

class ReceptSeeder extends Seeder
{
    public function run(): void
    {
        $recepten = [
            'Kip met zoete aardappel en broccoli' => [
                'Kipfilet' => 150, 'Zoete aardappel' => 200, 'Broccoli' => 150,
            ],
            'Ontbijtbowl met kwark en banaan' => [
                'Kwark (mager)' => 200, 'Banaan' => 100, 'Amandelen' => 20,
            ],
            'Griekse yoghurt met pindakaas' => [
                'Griekse yoghurt' => 200, 'Pindakaas' => 20, 'Banaan' => 100,
            ],
            'Tonijnsalade met kikkererwten' => [
                'Tonijn (in water)' => 120, 'Kikkererwten' => 150, 'Broccoli' => 100,
            ],
            'Havermout met amandelen en banaan' => [
                'Havermout' => 60, 'Amandelen' => 20, 'Banaan' => 100,
            ],
            'Zalm met zilvervliesrijst en broccoli' => [
                'Zalm' => 150, 'Zilvervliesrijst' => 150, 'Broccoli' => 150,
            ],
            'Linzencurry met rijst' => [
                'Linzen' => 200, 'Zilvervliesrijst' => 150,
            ],
            'Proteïneshake bowl' => [
                'Wei-eiwitpoeder' => 30, 'Banaan' => 100, 'Pindakaas' => 15,
            ],
            'Volkoren boterham' => [
                'Volkoren brood' => 60,
            ],
            'Snackplank' => [
                'Chips' => 50, 'Chocoladereep' => 50,
            ],
            'Eiwitreep en kwark snack' => [
                'Eiwitreep' => 40, 'Kwark (mager)' => 150,
            ],
        ];

        foreach ($recepten as $naam => $ingredienten) {
            $recept = Recept::create(['naam' => $naam]);

            foreach ($ingredienten as $ingredientNaam => $gram) {
                $ingredient = Ingredient::where('naam', $ingredientNaam)->first();

                if ($ingredient) {
                    $recept->ingredienten()->attach($ingredient->id, ['hoeveelheid_gram' => $gram]);
                }
            }
        }
    }
}
