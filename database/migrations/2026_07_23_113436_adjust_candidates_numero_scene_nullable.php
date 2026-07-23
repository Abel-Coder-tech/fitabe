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
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropUnique(['numero_scene']);
            $table->unsignedTinyInteger('numero_scene')->nullable()->change();
            $table->unique('numero_scene');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropUnique(['numero_scene']);
            $table->unsignedTinyInteger('numero_scene')->change();
            $table->unique('numero_scene');
        });
    }
};
