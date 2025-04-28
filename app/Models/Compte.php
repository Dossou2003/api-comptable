<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Compte",
 *     title="Compte",
 *     description="Modèle de compte comptable",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="nom", type="string", example="Banque"),
 *     @OA\Property(property="code", type="string", example="512"),
 *     @OA\Property(property="type", type="string", enum={"actif", "passif", "produit", "charge"}, example="actif"),
 *     @OA\Property(property="solde", type="number", format="float", example=1000.00),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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
