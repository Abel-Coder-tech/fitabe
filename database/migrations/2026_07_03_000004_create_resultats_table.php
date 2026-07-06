<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->string('annee_edition', 10);
            $table->string('categorie', 100);
            $table->integer('prix');
            $table->string('candidat_nom', 200);
            $table->string('candidat_photo', 255)->nullable();
            $table->integer('nombre_votes')->default(0);
            $table->decimal('note_jury', 4, 2)->nullable();
            $table->decimal('score_public', 4, 2)->nullable();
            $table->decimal('score_final', 4, 2)->nullable();
            $table->timestamps();

            $table->unique(['annee_edition', 'categorie', 'prix']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }
};
