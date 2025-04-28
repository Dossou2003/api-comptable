<?php

namespace Tests\Unit\Models;

use App\Models\Compte;
use App\Models\EntreeJournal;
use App\Models\Transaction;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_transaction()
    {
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();
        
        $transaction = Transaction::factory()->create([
            'date' => '2023-05-15',
            'description' => 'Test Transaction',
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ]);

        $this->assertDatabaseHas('transactions', [
            'date' => '2023-05-15',
            'description' => 'Test Transaction',
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ]);

        $this->assertEquals('2023-05-15', $transaction->date);
        $this->assertEquals('Test Transaction', $transaction->description);
        $this->assertEquals($compteDebit->id, $transaction->compte_debit_id);
        $this->assertEquals($compteCredit->id, $transaction->compte_credit_id);
        $this->assertEquals(500, $transaction->montant);
    }

    /** @test */
    public function it_has_compte_debit_relationship()
    {
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();
        
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id
        ]);

        $this->assertEquals($compteDebit->id, $transaction->compteDebit->id);
    }

    /** @test */
    public function it_has_compte_credit_relationship()
    {
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();
        
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id
        ]);

        $this->assertEquals($compteCredit->id, $transaction->compteCredit->id);
    }

    /** @test */
    public function it_has_entree_journal_relationship()
    {
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();
        $utilisateur = Utilisateur::factory()->create();
        
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id
        ]);

        $entreeJournal = EntreeJournal::factory()->create([
            'transaction_id' => $transaction->id,
            'utilisateur_id' => $utilisateur->id
        ]);

        $this->assertEquals($entreeJournal->id, $transaction->entreeJournal->id);
    }
}
