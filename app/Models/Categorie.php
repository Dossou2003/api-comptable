<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Obtenir les produits associés à cette catégorie.
     */
    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}
