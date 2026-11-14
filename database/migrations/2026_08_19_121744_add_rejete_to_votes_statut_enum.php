<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const STATUTS = ['en_attente', 'confirme', 'echoue', 'rejete'];

    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE votes MODIFY COLUMN statut ENUM('en_attente', 'confirme', 'echoue', 'rejete') DEFAULT 'en_attente'");

            return;
        }

        // SQLite / PostgreSQL : la contrainte CHECK de l'enum doit être reconstruite.
        Schema::table('votes', function (Blueprint $table) {
            $table->enum('statut', self::STATUTS)->default('en_attente')->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE votes MODIFY COLUMN statut ENUM('en_attente', 'confirme', 'echoue') DEFAULT 'en_attente'");

            return;
        }

        Schema::table('votes', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'confirme', 'echoue'])->default('en_attente')->change();
        });
    }
};
