<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'utilisateurs';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'adresse',
        'role',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mot_de_passe' => 'hashed',
        ];
    }

    /**
     * Obtenir les factures créées par l'utilisateur.
     */
    public function factures()
    {
        return $this->hasMany(Facture::class, 'createur_id');
    }

    /**
     * Obtenir les clients associés à l'utilisateur.
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'gestionnaire_id');
    }
}
