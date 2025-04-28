<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'comptes';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'code',
        'type',
        'solde',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'solde' => 'decimal:2',
    ];

    /**
     * Les types de comptes possibles.
     */
    const TYPE_ACTIF = 'actif';
    const TYPE_PASSIF = 'passif';
    const TYPE_PRODUIT = 'produit';
    const TYPE_CHARGE = 'charge';

    /**
     * Obtenir les transactions où ce compte est débité.
     */
    public function transactionsDebit()
    {
        return $this->hasMany(Transaction::class, 'compte_debit_id');
    }

    /**
     * Obtenir les transactions où ce compte est crédité.
     */
    public function transactionsCredit()
    {
        return $this->hasMany(Transaction::class, 'compte_credit_id');
    }
}
