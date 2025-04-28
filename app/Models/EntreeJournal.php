<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="EntreeJournal",
 *     title="EntreeJournal",
 *     description="Modèle d'entrée dans le journal comptable",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="transaction_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="utilisateur_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="transaction",
 *         ref="#/components/schemas/Transaction"
 *     ),
 *     @OA\Property(
 *         property="utilisateur",
 *         ref="#/components/schemas/Utilisateur"
 *     )
 * )
 */
class EntreeJournal extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'entrees_journal';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'utilisateur_id',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Obtenir la transaction associée.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Obtenir l'utilisateur qui a créé l'entrée.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
