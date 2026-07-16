<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['photo', 'video']);
            $table->string('titre', 200)->nullable();
            $table->string('chemin_fichier', 255)->nullable();
            $table->string('lien_youtube', 255)->nullable();
            $table->year('annee_edition')->nullable();
            $table->unsignedTinyInteger('ordre_affichage')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};