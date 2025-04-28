<?php

namespace Database\Seeders;

use App\Models\Compte;
use Illuminate\Database\Seeder;

class ComptesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Comptes d'actif
        Compte::create([
            'nom' => 'Banque',
            'code' => '512',
            'type' => 'actif',
            'solde' => 0,
        ]);

        Compte::create([
            'nom' => 'Caisse',
            'code' => '530',
            'type' => 'actif',
            'solde' => 0,
        ]);

        Compte::create([
            'nom' => 'Clients',
            'code' => '411',
            'type' => 'actif',
            'solde' => 0,
        ]);

        // Comptes de passif
        Compte::create([
            'nom' => 'Fournisseurs',
            'code' => '401',
            'type' => 'passif',
            'solde' => 0,
        ]);

        Compte::create([
            'nom' => 'Emprunts',
            'code' => '164',
            'type' => 'passif',
            'solde' => 0,
        ]);

        // Comptes de produits
        Compte::create([
            'nom' => 'Ventes de produits',
            'code' => '701',
            'type' => 'produit',
            'solde' => 0,
        ]);

        Compte::create([
            'nom' => 'Prestations de services',
            'code' => '706',
            'type' => 'produit',
            'solde' => 0,
        ]);

        // Comptes de charges
        Compte::create([
            'nom' => 'Achats de marchandises',
            'code' => '607',
            'type' => 'charge',
            'solde' => 0,
        ]);

        Compte::create([
            'nom' => 'Loyers',
            'code' => '613',
            'type' => 'charge',
            'solde' => 0,
        ]);

        Compte::create([
            'nom' => 'Salaires',
            'code' => '641',
            'type' => 'charge',
            'solde' => 0,
        ]);
    }
}
