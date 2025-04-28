<?php

namespace Tests\Feature\API;

use App\Models\Compte;
use App\Models\Transaction;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_transactions()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Créer des comptes
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();

        // Créer quelques transactions
        Transaction::factory()->count(3)->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id
        ]);

        // Faire une requête à l'API
        $response = $this->getJson('/api/transactions');

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'date',
                        'description',
                        'compte_debit_id',
                        'compte_credit_id',
                        'montant',
                        'created_at',
                        'updated_at',
                        'compte_debit',
                        'compte_credit'
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data')
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function comptable_can_create_a_transaction()
    {
        // Créer un utilisateur comptable authentifié
        $user = Utilisateur::factory()->create(['role' => 'comptable']);
        Sanctum::actingAs($user);

        // Créer des comptes
        $compteDebit = Compte::factory()->create(['type' => Compte::TYPE_ACTIF, 'solde' => 0]);
        $compteCredit = Compte::factory()->create(['type' => Compte::TYPE_PASSIF, 'solde' => 0]);

        // Données pour la nouvelle transaction
        $transactionData = [
            'date' => '2023-05-15',
            'description' => 'Test Transaction',
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ];

        // Faire une requête à l'API
        $response = $this->postJson('/api/transactions', $transactionData);

        // Vérifier la réponse
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'date',
                    'description',
                    'compte_debit_id',
                    'compte_credit_id',
                    'montant',
                    'created_at',
                    'updated_at',
                    'compte_debit',
                    'compte_credit',
                    'entree_journal'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'date' => '2023-05-15',
                    'description' => 'Test Transaction',
                    'compte_debit_id' => $compteDebit->id,
                    'compte_credit_id' => $compteCredit->id,
                    'montant' => 500
                ]
            ]);

        // Vérifier que la transaction a été créée dans la base de données
        $this->assertDatabaseHas('transactions', [
            'date' => '2023-05-15',
            'description' => 'Test Transaction',
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ]);

        // Vérifier que les soldes des comptes ont été mis à jour
        $this->assertDatabaseHas('comptes', [
            'id' => $compteDebit->id,
            'solde' => 500 // Pour un compte d'actif, un débit augmente le solde
        ]);

        $this->assertDatabaseHas('comptes', [
            'id' => $compteCredit->id,
            'solde' => 500 // Pour un compte de passif, un crédit augmente le solde
        ]);
    }

    /** @test */
    public function non_comptable_cannot_create_a_transaction()
    {
        // Créer un utilisateur non-comptable authentifié
        $user = Utilisateur::factory()->create(['role' => 'utilisateur']);
        Sanctum::actingAs($user);

        // Créer des comptes
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();

        // Données pour la nouvelle transaction
        $transactionData = [
            'date' => '2023-05-15',
            'description' => 'Test Transaction',
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ];

        // Faire une requête à l'API
        $response = $this->postJson('/api/transactions', $transactionData);

        // Vérifier que l'accès est refusé
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Accès non autorisé. Rôle comptable requis.'
            ]);

        // Vérifier que la transaction n'a pas été créée
        $this->assertDatabaseMissing('transactions', [
            'description' => 'Test Transaction'
        ]);
    }

    /** @test */
    public function it_can_show_a_transaction()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Créer des comptes
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();

        // Créer une transaction
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id
        ]);

        // Faire une requête à l'API
        $response = $this->getJson('/api/transactions/' . $transaction->id);

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'date',
                    'description',
                    'compte_debit_id',
                    'compte_credit_id',
                    'montant',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $transaction->id,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'compte_debit_id' => $compteDebit->id,
                    'compte_credit_id' => $compteCredit->id,
                    'montant' => $transaction->montant
                ]
            ]);
    }

    /** @test */
    public function admin_can_delete_a_transaction()
    {
        // Créer un utilisateur admin authentifié
        $user = Utilisateur::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);

        // Créer des comptes
        $compteDebit = Compte::factory()->create(['type' => Compte::TYPE_ACTIF, 'solde' => 500]);
        $compteCredit = Compte::factory()->create(['type' => Compte::TYPE_PASSIF, 'solde' => 500]);

        // Créer une transaction
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ]);

        // Faire une requête à l'API
        $response = $this->deleteJson('/api/transactions/' . $transaction->id);

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Transaction supprimée avec succès'
            ]);

        // Vérifier que la transaction a été supprimée
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);

        // Vérifier que les soldes des comptes ont été mis à jour
        $this->assertDatabaseHas('comptes', [
            'id' => $compteDebit->id,
            'solde' => 0 // Le solde devrait être remis à 0
        ]);

        $this->assertDatabaseHas('comptes', [
            'id' => $compteCredit->id,
            'solde' => 0 // Le solde devrait être remis à 0
        ]);
    }

    /** @test */
    public function non_admin_cannot_delete_a_transaction()
    {
        // Créer un utilisateur non-admin authentifié
        $user = Utilisateur::factory()->create(['role' => 'comptable']);
        Sanctum::actingAs($user);

        // Créer des comptes
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();

        // Créer une transaction
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id
        ]);

        // Faire une requête à l'API
        $response = $this->deleteJson('/api/transactions/' . $transaction->id);

        // Vérifier que l'accès est refusé
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Accès non autorisé. Rôle admin requis.'
            ]);

        // Vérifier que la transaction n'a pas été supprimée
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }
}
