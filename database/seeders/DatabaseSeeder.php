<?php

namespace Database\Seeders;

use App\Models\Parametres;
use App\Models\Programmes;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Idempotent : le seeder peut être rejoué sans violer les contraintes
        // uniques (paramètres déjà insérés par les migrations, email du compte).
        User::updateOrCreate(
            ['email' => 'paxevent09@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
            ]
        );

        foreach ([
            'date_debut_vote' => '2026-08-01 00:00:00',
            'date_fin_vote' => '2026-11-22 23:59:00',
            'date_finale' => '2026-11-28 19:00:00',
            'afficher_compteur' => '1',
        ] as $cle => $valeur) {
            Parametres::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
        }

        if (Programmes::count() === 0) {
            $this->call(ProgrammeSeeder::class);
        }
    }
}
