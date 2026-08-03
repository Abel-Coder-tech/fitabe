<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places_categories', function (Blueprint $table) {
            $table->id();
            $table->string('categorie', 100)->unique();
            $table->unsignedInteger('places')->default(20);
            $table->timestamps();
        });

        $categories = [
            'théâtre',
            'danse',
            'musique',
            'percussion',
            'arts visuels',
            'stylisme/modélisme',
        ];

        foreach ($categories as $categorie) {
            DB::table('places_categories')->updateOrInsert(
                ['categorie' => $categorie],
                ['places' => 20, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('places_categories');
    }
};
