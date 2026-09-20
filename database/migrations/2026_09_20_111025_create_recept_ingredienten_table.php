<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recept_ingredienten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recept_id')->constrained('recepten')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredienten')->cascadeOnDelete();
            $table->unsignedInteger('hoeveelheid_gram');
            $table->timestamps();

            $table->unique(['recept_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recept_ingredienten');
    }
};
