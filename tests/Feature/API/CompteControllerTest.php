<?php

namespace Tests\Feature\API;

use App\Models\Compte;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_comptes()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Créer quelques comptes
        Compte::factory()->count(3)->create();

        // Faire une requête à l'API
        $response = $this->getJson('/api/comptes');

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'nom',
                        'code',
                        'type',
                        'solde',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(3, 'data')
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_create_a_compte()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Données pour le nouveau compte
        $compteData = [
            'nom' => 'Banque Test',
            'code' => '512100',
            'type' => Compte::TYPE_ACTIF,
            'solde' => 1000
        ];

        // Faire une requête à l'API
        $response = $this->postJson('/api/comptes', $compteData);

        // Vérifier la réponse
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'nom',
                    'code',
                    'type',
                    'solde',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => $compteData
            ]);

        // Vérifier que le compte a été créé dans la base de données
        $this->assertDatabaseHas('comptes', $compteData);
    }

    /** @test */
    public function it_can_show_a_compte()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Créer un compte
        $compte = Compte::factory()->create();

        // Faire une requête à l'API
        $response = $this->getJson('/api/comptes/' . $compte->id);

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'nom',
                    'code',
                    'type',
                    'solde',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $compte->id,
                    'nom' => $compte->nom,
                    'code' => $compte->code,
                    'type' => $compte->type,
                    'solde' => $compte->solde
                ]
            ]);
    }

    /** @test */
    public function it_can_update_a_compte()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Créer un compte
        $compte = Compte::factory()->create();

        // Données pour la mise à jour
        $updateData = [
            'nom' => 'Banque Mise à jour',
            'code' => '512200',
            'type' => Compte::TYPE_ACTIF,
            'solde' => 2000
        ];

        // Faire une requête à l'API
        $response = $this->putJson('/api/comptes/' . $compte->id, $updateData);

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'nom',
                    'code',
                    'type',
                    'solde',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => $updateData
            ]);

        // Vérifier que le compte a été mis à jour dans la base de données
        $this->assertDatabaseHas('comptes', array_merge(['id' => $compte->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_compte()
    {
        // Créer un utilisateur authentifié
        $user = Utilisateur::factory()->create();
        Sanctum::actingAs($user);

        // Créer un compte
        $compte = Compte::factory()->create();

        // Faire une requête à l'API
        $response = $this->deleteJson('/api/comptes/' . $compte->id);

        // Vérifier la réponse
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Compte supprimé avec succès'
            ]);

        // Vérifier que le compte a été supprimé de la base de données
        $this->assertDatabaseMissing('comptes', ['id' => $compte->id]);
    }
}
