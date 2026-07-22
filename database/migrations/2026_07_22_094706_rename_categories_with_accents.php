<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $mapping = [
            'theatre' => 'théâtre',
            'art_visuel' => 'arts visuels',
            'stylisme_modélisme' => 'stylisme/modélisme',
        ];

        foreach ($mapping as $old => $new) {
            DB::table('candidates')->where('categorie', $old)->update(['categorie' => $new]);
            DB::table('resultats')->where('categorie', $old)->update(['categorie' => $new]);
        }
    }

    public function down(): void
    {
        $mapping = [
            'théâtre' => 'theatre',
            'arts visuels' => 'art_visuel',
            'stylisme/modélisme' => 'stylisme_modélisme',
        ];

        foreach ($mapping as $old => $new) {
            DB::table('candidates')->where('categorie', $old)->update(['categorie' => $new]);
            DB::table('resultats')->where('categorie', $old)->update(['categorie' => $new]);
        }
    }
};
