<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Le nombre d'ovations par achat n'est plus plafonné : colonnes élargies.
        // SQLite stocke déjà les entiers sur 64 bits, aucune modification requise.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE votes MODIFY quantite INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE votes MODIFY montant BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE votes MODIFY quantite SMALLINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE votes MODIFY montant INT UNSIGNED NOT NULL');
    }
};
