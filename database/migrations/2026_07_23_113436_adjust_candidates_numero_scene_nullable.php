<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `candidates` MODIFY `numero_scene` INT UNSIGNED NULL');

            return;
        }

        // SQLite / PostgreSQL : reconstruction portable via le schema builder.
        Schema::table('candidates', function (Blueprint $table) {
            $table->unsignedInteger('numero_scene')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `candidates` MODIFY `numero_scene` TINYINT UNSIGNED NOT NULL');

            return;
        }

        Schema::table('candidates', function (Blueprint $table) {
            $table->unsignedTinyInteger('numero_scene')->nullable(false)->change();
        });
    }
};
