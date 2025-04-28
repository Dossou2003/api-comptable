<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneFacture extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'lignes_facture';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'quantite',
        'prix_unitaire',
        'montant_ht',
        'taux_tva',
        'montant_tva',
        'montant_ttc',
        'facture_id',
        'produit_id',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    /**
     * Obtenir la facture associée à cette ligne de facture.
     */
    public function facture()
    {
        return $this->belongsTo(Facture::class, 'facture_id');
    }

    /**
     * Obtenir le produit associé à cette ligne de facture.
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }
}
