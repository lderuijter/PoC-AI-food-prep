<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredienten = [
            ['naam' => 'Kipfilet', 'categorie' => 'vlees', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 165, 'eiwitten_per_100g' => 31, 'koolhydraten_per_100g' => 0, 'vetten_per_100g' => 3.6, 'nova_groep' => 1, 'is_vegetarisch' => false, 'is_veganistisch' => false],
            ['naam' => 'Rundergehakt (mager)', 'categorie' => 'vlees', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 176, 'eiwitten_per_100g' => 26, 'koolhydraten_per_100g' => 0, 'vetten_per_100g' => 8, 'nova_groep' => 1, 'is_vegetarisch' => false, 'is_veganistisch' => false],
            ['naam' => 'Zalm', 'categorie' => 'vis', 'smaakprofiel' => 'umami', 'calorieen_per_100g' => 208, 'eiwitten_per_100g' => 20, 'koolhydraten_per_100g' => 0, 'vetten_per_100g' => 13, 'nova_groep' => 1, 'is_vegetarisch' => false, 'is_veganistisch' => false],
            ['naam' => 'Tonijn (in water)', 'categorie' => 'vis', 'smaakprofiel' => 'umami', 'calorieen_per_100g' => 116, 'eiwitten_per_100g' => 26, 'koolhydraten_per_100g' => 0, 'vetten_per_100g' => 1, 'nova_groep' => 2, 'is_vegetarisch' => false, 'is_veganistisch' => false],
            ['naam' => 'Kippenei', 'categorie' => 'eieren', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 155, 'eiwitten_per_100g' => 13, 'koolhydraten_per_100g' => 1.1, 'vetten_per_100g' => 11, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => false],
            ['naam' => 'Griekse yoghurt', 'categorie' => 'zuivel', 'smaakprofiel' => 'zuur', 'calorieen_per_100g' => 59, 'eiwitten_per_100g' => 10, 'koolhydraten_per_100g' => 3.6, 'vetten_per_100g' => 0.4, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => false],
            ['naam' => 'Kwark (mager)', 'categorie' => 'zuivel', 'smaakprofiel' => 'zuur', 'calorieen_per_100g' => 60, 'eiwitten_per_100g' => 12, 'koolhydraten_per_100g' => 4, 'vetten_per_100g' => 0.2, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => false],
            ['naam' => 'Kikkererwten', 'categorie' => 'peulvruchten', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 164, 'eiwitten_per_100g' => 9, 'koolhydraten_per_100g' => 27, 'vetten_per_100g' => 2.6, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Linzen', 'categorie' => 'peulvruchten', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 116, 'eiwitten_per_100g' => 9, 'koolhydraten_per_100g' => 20, 'vetten_per_100g' => 0.4, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Havermout', 'categorie' => 'granen', 'smaakprofiel' => 'neutraal', 'calorieen_per_100g' => 379, 'eiwitten_per_100g' => 13, 'koolhydraten_per_100g' => 67, 'vetten_per_100g' => 7, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Zilvervliesrijst', 'categorie' => 'granen', 'smaakprofiel' => 'neutraal', 'calorieen_per_100g' => 111, 'eiwitten_per_100g' => 2.6, 'koolhydraten_per_100g' => 23, 'vetten_per_100g' => 0.9, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Broccoli', 'categorie' => 'groente', 'smaakprofiel' => 'bitter', 'calorieen_per_100g' => 34, 'eiwitten_per_100g' => 2.8, 'koolhydraten_per_100g' => 7, 'vetten_per_100g' => 0.4, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Zoete aardappel', 'categorie' => 'groente', 'smaakprofiel' => 'zoet', 'calorieen_per_100g' => 86, 'eiwitten_per_100g' => 1.6, 'koolhydraten_per_100g' => 20, 'vetten_per_100g' => 0.1, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Banaan', 'categorie' => 'fruit', 'smaakprofiel' => 'zoet', 'calorieen_per_100g' => 89, 'eiwitten_per_100g' => 1.1, 'koolhydraten_per_100g' => 23, 'vetten_per_100g' => 0.3, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Amandelen', 'categorie' => 'noten_zaden', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 579, 'eiwitten_per_100g' => 21, 'koolhydraten_per_100g' => 22, 'vetten_per_100g' => 50, 'nova_groep' => 1, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Pindakaas', 'categorie' => 'noten_zaden', 'smaakprofiel' => 'zoet', 'calorieen_per_100g' => 588, 'eiwitten_per_100g' => 25, 'koolhydraten_per_100g' => 20, 'vetten_per_100g' => 50, 'nova_groep' => 2, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Wei-eiwitpoeder', 'categorie' => 'sportvoeding', 'smaakprofiel' => 'zoet', 'calorieen_per_100g' => 380, 'eiwitten_per_100g' => 80, 'koolhydraten_per_100g' => 8, 'vetten_per_100g' => 6, 'nova_groep' => 4, 'is_vegetarisch' => true, 'is_veganistisch' => false],
            ['naam' => 'Eiwitreep', 'categorie' => 'sportvoeding', 'smaakprofiel' => 'zoet', 'calorieen_per_100g' => 350, 'eiwitten_per_100g' => 30, 'koolhydraten_per_100g' => 35, 'vetten_per_100g' => 10, 'nova_groep' => 4, 'is_vegetarisch' => true, 'is_veganistisch' => false],
            ['naam' => 'Volkoren brood', 'categorie' => 'granen', 'smaakprofiel' => 'neutraal', 'calorieen_per_100g' => 250, 'eiwitten_per_100g' => 9, 'koolhydraten_per_100g' => 41, 'vetten_per_100g' => 3.5, 'nova_groep' => 3, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Belegen kaas', 'categorie' => 'zuivel', 'smaakprofiel' => 'hartig', 'calorieen_per_100g' => 400, 'eiwitten_per_100g' => 25, 'koolhydraten_per_100g' => 0, 'vetten_per_100g' => 33, 'nova_groep' => 3, 'is_vegetarisch' => true, 'is_veganistisch' => false],
            ['naam' => 'Chips', 'categorie' => 'bewerkt_snack', 'smaakprofiel' => 'zout', 'calorieen_per_100g' => 536, 'eiwitten_per_100g' => 6, 'koolhydraten_per_100g' => 53, 'vetten_per_100g' => 34, 'nova_groep' => 4, 'is_vegetarisch' => true, 'is_veganistisch' => true],
            ['naam' => 'Chocoladereep', 'categorie' => 'bewerkt_snack', 'smaakprofiel' => 'zoet', 'calorieen_per_100g' => 546, 'eiwitten_per_100g' => 7, 'koolhydraten_per_100g' => 58, 'vetten_per_100g' => 31, 'nova_groep' => 4, 'is_vegetarisch' => true, 'is_veganistisch' => false],
        ];

        foreach ($ingredienten as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
