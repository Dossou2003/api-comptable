<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'societe',
        'email',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'pays',
        'numero_tva',
        'notes',
        'gestionnaire_id',
    ];

    /**
     * Obtenir l'utilisateur qui gère ce client.
     */
    public function gestionnaire()
    {
        return $this->belongsTo(Utilisateur::class, 'gestionnaire_id');
    }

    /**
     * Obtenir les factures associées au client.
     */
    public function factures()
    {
        return $this->hasMany(Facture::class, 'client_id');
    }
}
