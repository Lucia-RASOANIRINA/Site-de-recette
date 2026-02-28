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

    public function getRatingAttribute() {
        $likes = $this->likes()->count();
        // Base de 3.5 étoiles, +0.1 par like, max 5.0
        $note = 3.5 + ($likes * 0.1);
        return min(5, $note);
    }
}
