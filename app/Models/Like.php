<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * « Coup de cœur » porté sur une recette ou une publication.
 *
 * La table ne possède pas de colonne « updated_at » : l'horodatage
 * automatique d'Eloquent est désactivé. La colonne « created_at » est
 * renseignée par défaut au niveau de la base (valeur courante à l'insertion).
 */
class Like extends Model
{
    /**
     * Désactive la gestion automatique des horodatages (pas de « updated_at »).
     */
    public $timestamps = false;

    /**
     * Attributs autorisés en assignation de masse.
     *
     * @var array<int, string>
     */
    protected $fillable = ['recette_id', 'user_id', 'post_id'];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}