<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soutiens', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('photo', 255);
            $table->text('citation')->nullable();
            $table->string('titre', 200)->nullable();
            $table->string('role_parrain', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soutiens');
    }
};
