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
        Schema::create('ingredienten', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('categorie')->nullable();
            $table->string('smaakprofiel')->nullable(); // zoet, zuur, bitter, zout, umami, pittig
            $table->unsignedInteger('calorieen_per_100g')->nullable();
            $table->decimal('eiwitten_per_100g', 5, 2)->nullable();
            $table->decimal('koolhydraten_per_100g', 5, 2)->nullable();
            $table->decimal('vetten_per_100g', 5, 2)->nullable();
            $table->unsignedTinyInteger('nova_groep')->nullable(); // 1 t/m 4
            $table->boolean('is_vegetarisch')->default(true);
            $table->boolean('is_veganistisch')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
