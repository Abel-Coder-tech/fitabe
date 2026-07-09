<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make date_programme nullable and add new columns
        Schema::table('programmes', function (Blueprint $table) {
            $table->string('icone', 50)->nullable()->after('description');
            $table->string('couleur_bordure', 7)->nullable()->after('icone');
        });

        // Create programme_dates table for timeline sub-events
        Schema::create('programme_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->string('titre', 200)->nullable();
            $table->dateTime('date');
            $table->string('lieu', 255)->nullable();
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_dates');

        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn(['icone', 'couleur_bordure']);
        });
    }
};
