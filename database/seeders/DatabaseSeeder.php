<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Parametres;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'paxevent09@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        Parametres::insert([
            ['cle' => 'date_debut_vote', 'valeur' => '2026-08-01 00:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'date_fin_vote', 'valeur' => '2026-11-22 23:59:00', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'afficher_compteur', 'valeur' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->call(ProgrammeSeeder::class);
    }
}
