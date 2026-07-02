<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('nom_scene', 150)->nullable();
            $table->enum('categorie', ['theatre', 'danse', 'musique', 'percussion', 'art_visuel']);
            $table->unsignedTinyInteger('numero_scene')->unique();
            $table->string('photo', 255);
            $table->text('biographie')->nullable();
            $table->unsignedInteger('nombre_votes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};