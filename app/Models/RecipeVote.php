<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Vote public pour la « recette de la semaine ».
 *
 * Émis par un visiteur via le nom de la recette et son adresse email.
 * Une contrainte d'unicité (recette_id, email) garantit qu'un même email ne
 * vote qu'une seule fois par recette, tout en autorisant le vote pour plusieurs
 * recettes différentes.
 */
class RecipeVote extends Model
{
    /**
     * La table ne gère qu'un « created_at » (renseigné par défaut en base).
     */
    public $timestamps = false;

    /**
     * Attributs autorisés en assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = ['recette_id', 'recette_nom', 'email'];

    /**
     * Conversions de types des attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
