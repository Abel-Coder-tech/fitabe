<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Les catégories accentuées ne tiennent pas dans l'ENUM d'origine.
        // Seul MySQL impose un ALTER ; SQLite/PostgreSQL stockent déjà du texte.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE `candidates` MODIFY `categorie` VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE `resultats` MODIFY `categorie` VARCHAR(100) NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE `candidates` MODIFY `categorie` ENUM('theatre','danse','musique','percussion','art_visuel') NOT NULL");
        DB::statement("ALTER TABLE `resultats` MODIFY `categorie` ENUM('theatre','danse','musique','percussion','art_visuel') NOT NULL");
    }
};
