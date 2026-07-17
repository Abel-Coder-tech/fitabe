<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            if (!Schema::hasColumn('programmes', 'date_programme')) {
                $table->dateTime('date_programme')->nullable()->after('couleur_bordure');
            }
            if (!Schema::hasColumn('programmes', 'categorie')) {
                $table->string('categorie', 50)->nullable()->after('date_programme');
            }
            if (!Schema::hasColumn('programmes', 'est_actif')) {
                $table->boolean('est_actif')->default(true)->after('ordre_affichage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn(['date_programme', 'categorie', 'est_actif']);
        });
    }
};
