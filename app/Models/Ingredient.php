<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredienten';

    protected $fillable = [
        'naam',
        'categorie',
        'smaakprofiel',
        'calorieen_per_100g',
        'eiwitten_per_100g',
        'koolhydraten_per_100g',
        'vetten_per_100g',
        'nova_groep',
        'is_vegetarisch',
        'is_veganistisch',
    ];

    protected $casts = [
        'is_vegetarisch' => 'boolean',
        'is_veganistisch' => 'boolean',
        'eiwitten_per_100g' => 'decimal:2',
        'koolhydraten_per_100g' => 'decimal:2',
        'vetten_per_100g' => 'decimal:2',
    ];

    public function gebruikers()
    {
        return $this->belongsToMany(User::class, 'ingredient_voorkeuren')
            ->withPivot('voorkeur')
            ->withTimestamps();
    }
}
