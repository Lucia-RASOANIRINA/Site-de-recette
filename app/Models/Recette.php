<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importer la relation de Laravel, pas une classe imaginaire dans Models
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Recette extends Model
{
    use HasFactory;

    public function ingredients(): HasMany
    {
        // En utilisant ::class, PHP vérifie l'existence de la classe
        return $this->hasMany(Ingredient::class);
    }

    public function likes()
    {
        // On précise le modèle Like (que nous allons vérifier à l'étape 2)
        return $this->hasMany(Like::class);
    }
}
