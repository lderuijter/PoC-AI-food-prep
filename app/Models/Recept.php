<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recept extends Model
{
    protected $fillable = ['naam', 'beschrijving'];
    protected $table = 'recepten';

    public function ingredienten()
    {
        return $this->belongsToMany(Ingredient::class, 'recept_ingredienten')
            ->withPivot('hoeveelheid_gram')
            ->withTimestamps();
    }
}
