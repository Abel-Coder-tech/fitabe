<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('parametres')->updateOrInsert(
            ['cle' => 'date_debut_vote'],
            ['valeur' => '2026-08-01 00:00:00', 'updated_at' => now()]
        );
        DB::table('parametres')->updateOrInsert(
            ['cle' => 'date_fin_vote'],
            ['valeur' => '2026-11-22 23:59:00', 'updated_at' => now()]
        );

        DB::table('parametres')->where('cle', 'date_debut_vote')->update(['valeur' => '2026-08-01 00:00:00']);
        DB::table('parametres')->where('cle', 'date_fin_vote')->update(['valeur' => '2026-11-22 23:59:00']);

        DB::table('parametres')->updateOrInsert(
            ['cle' => 'statut_vote'],
            ['valeur' => 'active', 'updated_at' => now()]
        );
        DB::table('parametres')->where('cle', 'statut_vote')->update(['valeur' => 'active']);
    }

    public function down(): void
    {
        DB::table('parametres')->whereIn('cle', ['date_debut_vote', 'date_fin_vote'])->delete();
    }
};
