<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            'date_debut_vote' => '2026-08-01 00:00:00',
            'date_fin_vote'   => '2026-11-22 23:59:00',
        ];

        foreach ($defaults as $cle => $valeur) {
            DB::table('parametres')->updateOrInsert(
                ['cle' => $cle],
                ['valeur' => $valeur, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('parametres')->whereIn('cle', ['date_debut_vote', 'date_fin_vote'])->delete();
    }
};
