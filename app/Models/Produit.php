<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'produits';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference',
        'nom',
        'description',
        'prix_unitaire',
        'taux_tva',
        'categorie_id',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
    ];

    /**
     * Obtenir la catégorie associée à ce produit.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Obtenir les lignes de facture associées à ce produit.
     */
    public function lignesFacture()
    {
        return $this->hasMany(LigneFacture::class, 'produit_id');
    }
}
