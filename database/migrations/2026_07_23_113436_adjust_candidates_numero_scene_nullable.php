<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `candidates` MODIFY `numero_scene` TINYINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `candidates` MODIFY `numero_scene` TINYINT UNSIGNED NOT NULL');
    }
};
