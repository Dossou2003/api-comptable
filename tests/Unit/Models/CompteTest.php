<?php

namespace Tests\Unit\Models;

use App\Models\Compte;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_compte()
    {
        $compte = Compte::factory()->create([
            'nom' => 'Banque Test',
            'code' => '512100',
            'type' => Compte::TYPE_ACTIF,
            'solde' => 1000
        ]);

        $this->assertDatabaseHas('comptes', [
            'nom' => 'Banque Test',
            'code' => '512100',
            'type' => Compte::TYPE_ACTIF,
            'solde' => 1000
        ]);

        $this->assertEquals('Banque Test', $compte->nom);
        $this->assertEquals('512100', $compte->code);
        $this->assertEquals(Compte::TYPE_ACTIF, $compte->type);
        $this->assertEquals(1000, $compte->solde);
    }

    /** @test */
    public function it_has_transactions_debit_relationship()
    {
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();
        
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ]);

        $this->assertTrue($compteDebit->transactionsDebit->contains($transaction));
        $this->assertFalse($compteCredit->transactionsDebit->contains($transaction));
    }

    /** @test */
    public function it_has_transactions_credit_relationship()
    {
        $compteDebit = Compte::factory()->create();
        $compteCredit = Compte::factory()->create();
        
        $transaction = Transaction::factory()->create([
            'compte_debit_id' => $compteDebit->id,
            'compte_credit_id' => $compteCredit->id,
            'montant' => 500
        ]);

        $this->assertTrue($compteCredit->transactionsCredit->contains($transaction));
        $this->assertFalse($compteDebit->transactionsCredit->contains($transaction));
    }
}
