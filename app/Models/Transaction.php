<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Transaction",
 *     title="Transaction",
 *     description="Modèle de transaction comptable",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="date", type="string", format="date", example="2023-01-15"),
 *     @OA\Property(property="description", type="string", example="Paiement facture client"),
 *     @OA\Property(property="compte_debit_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="compte_credit_id", type="integer", format="int64", example=3),
 *     @OA\Property(property="montant", type="number", format="float", example=1500.00),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="compteDebit",
 *         ref="#/components/schemas/Compte"
 *     ),
 *     @OA\Property(
 *         property="compteCredit",
 *         ref="#/components/schemas/Compte"
 *     ),
 *     @OA\Property(
 *         property="entreeJournal",
 *         ref="#/components/schemas/EntreeJournal"
 *     )
 * )
 */
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
