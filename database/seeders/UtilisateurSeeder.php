<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur administrateur
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'telephone' => '+33612345678',
            'adresse' => '123 Rue de Paris',
            'role' => 'admin',
        ]);

        // Créer un utilisateur comptable
        Utilisateur::create([
            'nom' => 'Comptable',
            'prenom' => 'User',
            'email' => 'comptable@example.com',
            'password' => Hash::make('password'),
            'telephone' => '+33612345679',
            'adresse' => '456 Rue de Lyon',
            'role' => 'comptable',
        ]);

        // Créer un utilisateur standard
        Utilisateur::create([
            'nom' => 'Standard',
            'prenom' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'telephone' => '+33612345680',
            'adresse' => '789 Rue de Marseille',
            'role' => 'utilisateur',
        ]);
    }
}
