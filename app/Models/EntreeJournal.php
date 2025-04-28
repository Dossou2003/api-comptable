<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
