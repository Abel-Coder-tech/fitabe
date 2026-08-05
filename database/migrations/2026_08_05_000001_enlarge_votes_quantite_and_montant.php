<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Le nombre d'ovations par achat n'est plus plafonné : colonnes élargies
        DB::statement('ALTER TABLE votes MODIFY quantite INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE votes MODIFY montant BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE votes MODIFY quantite SMALLINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE votes MODIFY montant INT UNSIGNED NOT NULL');
    }
};
