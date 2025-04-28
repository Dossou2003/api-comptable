<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'description',
        'compte_debit_id',
        'compte_credit_id',
        'montant',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Obtenir le compte débité.
     */
    public function compteDebit()
    {
        return $this->belongsTo(Compte::class, 'compte_debit_id');
    }

    /**
     * Obtenir le compte crédité.
     */
    public function compteCredit()
    {
        return $this->belongsTo(Compte::class, 'compte_credit_id');
    }

    /**
     * Obtenir l'entrée de journal associée.
     */
    public function entreeJournal()
    {
        return $this->hasOne(EntreeJournal::class);
    }
}
