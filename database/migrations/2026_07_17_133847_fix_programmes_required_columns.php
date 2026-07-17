<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            if (Schema::hasColumn('programmes', 'date_activite')) {
                $table->date('date_activite')->nullable()->change();
            }
            if (Schema::hasColumn('programmes', 'heure_debut')) {
                $table->time('heure_debut')->nullable()->change();
            }
            if (Schema::hasColumn('programmes', 'heure_fin')) {
                $table->time('heure_fin')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            if (Schema::hasColumn('programmes', 'date_activite')) {
                $table->date('date_activite')->nullable(false)->change();
            }
            if (Schema::hasColumn('programmes', 'heure_debut')) {
                $table->time('heure_debut')->nullable(false)->change();
            }
            if (Schema::hasColumn('programmes', 'heure_fin')) {
                $table->time('heure_fin')->nullable()->change();
            }
        });
    }
};
