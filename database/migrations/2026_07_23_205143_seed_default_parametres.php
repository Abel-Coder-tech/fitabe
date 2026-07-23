<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('parametres')->truncate();

        $defaults = [
            ['cle' => 'contact_telephone', 'valeur' => '+229 01 66 16 75 88', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'contact_email', 'valeur' => 'strategemediaevents@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'social_facebook', 'valeur' => 'https://www.facebook.com/share/1WhHoPqx9H/', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'social_instagram', 'valeur' => 'https://www.instagram.com/fitab_talents_artistiques_pn/', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'social_youtube', 'valeur' => 'https://www.youtube.com/@TalentsArtistiques', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'social_tiktok', 'valeur' => 'https://www.tiktok.com/@fitab_talent_artistique', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'hero_titre', 'valeur' => 'FITAB 2026', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'hero_sous_titre', 'valeur' => 'Festival International des Talents Artistiques du Bénin', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'texte_info_vote', 'valeur' => "L'entrée aux présélections est libre et gratuite. Les ovations sont un critère officiel de sélection.", 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'texte_mediatheque', 'valeur' => 'Photos et vidéos des éditions du FITAB. Revivez les plus grands moments du festival.', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($defaults as $row) {
            DB::table('parametres')->updateOrInsert(
                ['cle' => $row['cle']],
                $row
            );
        }
    }

    public function down(): void
    {
        DB::table('parametres')->truncate();
    }
};
