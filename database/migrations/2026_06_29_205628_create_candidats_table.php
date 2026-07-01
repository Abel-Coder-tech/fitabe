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
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('nom_scene', 150)->nullable();
            $table->string('categorie', 100);
            $table->integer('numero_scene')->nullable();
            $table->string('photo', 255)->nullable();
            $table->text('biographie')->nullable();
            $table->integer('nombre_votes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
