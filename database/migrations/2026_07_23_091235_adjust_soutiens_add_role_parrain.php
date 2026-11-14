<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // `role_parrain` est déjà créée par create_soutiens_table sur les
        // installations neuves : on ne l'ajoute que si elle manque vraiment.
        if (! Schema::hasColumn('soutiens', 'role_parrain')) {
            Schema::table('soutiens', function ($table) {
                $table->string('role_parrain', 100)->nullable()->after('titre');
            });
        }

        // Colonnes héritées, présentes uniquement sur certaines bases historiques.
        $aSupprimer = array_values(array_filter(
            ['organisation', 'ordre_affichage'],
            fn (string $colonne) => Schema::hasColumn('soutiens', $colonne)
        ));

        if ($aSupprimer !== []) {
            Schema::table('soutiens', fn ($table) => $table->dropColumn($aSupprimer));
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('soutiens', 'organisation')) {
            Schema::table('soutiens', fn ($table) => $table->string('organisation', 200)->nullable()->after('titre'));
        }
        if (! Schema::hasColumn('soutiens', 'ordre_affichage')) {
            Schema::table('soutiens', fn ($table) => $table->unsignedTinyInteger('ordre_affichage')->default(0));
        }
        if (Schema::hasColumn('soutiens', 'role_parrain')) {
            Schema::table('soutiens', fn ($table) => $table->dropColumn('role_parrain'));
        }
    }
};
