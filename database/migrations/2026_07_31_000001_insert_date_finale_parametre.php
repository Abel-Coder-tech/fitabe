<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('parametres')->updateOrInsert(
            ['cle' => 'date_finale'],
            ['valeur' => '2026-11-28 19:00:00', 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        DB::table('parametres')->where('cle', 'date_finale')->delete();
    }
};
