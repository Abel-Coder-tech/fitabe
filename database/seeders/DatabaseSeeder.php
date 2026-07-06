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
            ['cle' => 'vote_mode', 'valeur' => 'off', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'vote_deadline', 'valeur' => '2026-07-31 23:59:59', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'prix_du_vote', 'valeur' => '100', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'afficher_compteur', 'valeur' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
