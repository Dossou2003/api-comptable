<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Utilisateur;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur administrateur
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'Système',
            'email' => 'admin@example.com',
            'mot_de_passe' => Hash::make('password'),
            'role' => 'administrateur',
        ]);

        // Créer un utilisateur comptable
        Utilisateur::create([
            'nom' => 'Comptable',
            'prenom' => 'Test',
            'email' => 'comptable@example.com',
            'mot_de_passe' => Hash::make('password'),
            'role' => 'gestionnaire',
        ]);

        // Appeler les autres seeders
        $this->call([
            ComptesSeeder::class,
        ]);
    }
}
