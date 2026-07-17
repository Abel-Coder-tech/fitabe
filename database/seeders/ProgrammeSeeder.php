<?php

namespace Database\Seeders;

use App\Models\Programmes;
use App\Models\ProgrammeDate;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        // Phase 1 — Appel à candidatures
        $p1 = Programmes::create([
            'titre' => 'Phase 1 — Appel à candidatures',
            'description' => null,
            'icone' => 'bi-megaphone-fill',
            'couleur_bordure' => '#9B4D07',
            'date_programme' => '2026-05-29 00:00:00',
            'lieu' => 'En ligne',
            'categorie' => null,
            'ordre_affichage' => 1,
            'est_actif' => true,
        ]);
        $p1->dates()->createMany([
            ['titre' => 'Ouverture des candidatures', 'date' => '2026-05-29 00:00:00', 'lieu' => 'En ligne', 'ordre' => 1],
            ['titre' => 'Clôture des candidatures', 'date' => '2026-07-31 23:59:00', 'lieu' => 'En ligne', 'ordre' => 2],
            ['titre' => 'Annonce des présélectionnés', 'date' => '2026-08-01 12:00:00', 'lieu' => 'En ligne', 'ordre' => 3],
        ]);

        // Phase 2 — Présélections publiques
        $p2 = Programmes::create([
            'titre' => 'Phase 2 — Présélections publiques',
            'description' => null,
            'icone' => 'bi-people-fill',
            'couleur_bordure' => '#9B4D07',
            'date_programme' => '2026-09-05 00:00:00',
            'lieu' => 'Nouveau Rex, Carrefour Kokoyè',
            'categorie' => 'entree_libre',
            'ordre_affichage' => 2,
            'est_actif' => true,
        ]);
        $p2->dates()->createMany([
            ['titre' => 'Samedi 5 Septembre', 'date' => '2026-09-05 00:00:00', 'lieu' => null, 'ordre' => 1],
            ['titre' => 'Dimanche 6 Septembre', 'date' => '2026-09-06 00:00:00', 'lieu' => null, 'ordre' => 2],
            ['titre' => 'Samedi 12 Septembre', 'date' => '2026-09-12 00:00:00', 'lieu' => null, 'ordre' => 3],
            ['titre' => 'Dimanche 13 Septembre', 'date' => '2026-09-13 00:00:00', 'lieu' => null, 'ordre' => 4],
            ['titre' => 'Samedi 19 Septembre', 'date' => '2026-09-19 00:00:00', 'lieu' => null, 'ordre' => 5],
            ['titre' => 'Dimanche 20 Septembre', 'date' => '2026-09-20 00:00:00', 'lieu' => null, 'ordre' => 6],
        ]);

        // Phase 3 — Géant Carnaval
        Programmes::create([
            'titre' => 'Phase 3 — Géant Carnaval',
            'description' => null,
            'icone' => 'bi-music-note-beamed',
            'couleur_bordure' => '#9B4D07',
            'date_programme' => '2026-11-14 00:00:00',
            'lieu' => 'Rues de Porto-Novo',
            'categorie' => null,
            'ordre_affichage' => 3,
            'est_actif' => true,
        ]);

        // Phase 4 — 2ᵉ phase de compétition
        $p4 = Programmes::create([
            'titre' => 'Phase 4 — 2ᵉ phase de compétition',
            'description' => '3 finalistes retenus par catégorie',
            'icone' => 'bi-trophy-fill',
            'couleur_bordure' => '#9B4D07',
            'date_programme' => '2026-11-20 00:00:00',
            'lieu' => 'Nouveau Rex',
            'categorie' => null,
            'ordre_affichage' => 4,
            'est_actif' => true,
        ]);
        $p4->dates()->createMany([
            ['titre' => 'Vendredi 20 Novembre', 'date' => '2026-11-20 00:00:00', 'lieu' => null, 'ordre' => 1],
            ['titre' => 'Samedi 21 Novembre', 'date' => '2026-11-21 00:00:00', 'lieu' => null, 'ordre' => 2],
            ['titre' => 'Dimanche 22 Novembre', 'date' => '2026-11-22 00:00:00', 'lieu' => null, 'ordre' => 3],
        ]);

        // Grande Finale
        Programmes::create([
            'titre' => 'GRANDE FINALE',
            'description' => null,
            'icone' => 'bi-award-fill',
            'couleur_bordure' => '#CA7B05',
            'date_programme' => '2026-11-28 00:00:00',
            'lieu' => 'Esplanade de l\'Assemblée Nationale, Porto-Novo',
            'categorie' => null,
            'ordre_affichage' => 5,
            'est_actif' => true,
        ]);

        // Dîner de Gala VIP
        Programmes::create([
            'titre' => 'Dîner de Gala VIP',
            'description' => 'Partenaires officiels, jurys internationaux, artistes honorés, personnalités institutionnelles. 60-80 convives.',
            'icone' => 'bi-stars',
            'couleur_bordure' => '#9B4D07',
            'date_programme' => '2026-12-06 00:00:00',
            'lieu' => 'Salle IFEDU Tokpota',
            'categorie' => null,
            'ordre_affichage' => 6,
            'est_actif' => true,
        ]);
    }
}