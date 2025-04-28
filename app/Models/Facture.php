<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'factures';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero',
        'date_emission',
        'date_echeance',
        'statut',
        'montant_ht',
        'taux_tva',
        'montant_tva',
        'montant_ttc',
        'notes',
        'client_id',
        'createur_id',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'montant_ht' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    /**
     * Obtenir le client associé à cette facture.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Obtenir l'utilisateur qui a créé cette facture.
     */
    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }

    /**
     * Obtenir les lignes de facture associées à cette facture.
     */
    public function lignesFacture()
    {
        return $this->hasMany(LigneFacture::class, 'facture_id');
    }
}
