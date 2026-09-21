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
            'Kipfilet met zoete aardappel en broccoli' => [
                'Kipfilet' => 150, 'Zoete aardappel' => 200, 'Broccoli' => 150,
            ],
            'Ontbijtbowl met magere kwark, banaan en amandelen' => [
                'Kwark (mager)' => 200, 'Banaan' => 100, 'Amandelen' => 20,
            ],
            'Griekse yoghurt met pindakaas en banaan' => [
                'Griekse yoghurt' => 200, 'Pindakaas' => 20, 'Banaan' => 100,
            ],
            'Tonijnsalade met kikkererwten en broccoli' => [
                'Tonijn (in water)' => 120, 'Kikkererwten' => 150, 'Broccoli' => 100,
            ],
            'Havermout met amandelen en banaan' => [
                'Havermout' => 60, 'Amandelen' => 20, 'Banaan' => 100,
            ],
            'Zalm met zilvervliesrijst en broccoli' => [
                'Zalm' => 150, 'Zilvervliesrijst' => 150, 'Broccoli' => 150,
            ],
            'Linzencurry met zilvervliesrijst' => [
                'Linzen' => 200, 'Zilvervliesrijst' => 150,
            ],
            'Proteïnebowl met banaan en pindakaas' => [
                'Wei-eiwitpoeder' => 30, 'Banaan' => 100, 'Pindakaas' => 15,
            ],
            'Volkoren boterham met belegen kaas' => [
                'Volkoren brood' => 60,
                'Belegen kaas' => 30,
            ],
            'Volkoren boterham met pindakaas' => [
                'Volkoren brood' => 60,
                'Pindakaas' => 30,
            ],
            'Snack met chips en chocoladereep' => [
                'Chips' => 50, 'Chocoladereep' => 50,
            ],
            'Eiwitrijke snack met eiwitreep en magere kwark' => [
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
