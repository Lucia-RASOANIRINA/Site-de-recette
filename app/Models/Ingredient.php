<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

     // Indiquez les colonnes que l'on peut remplir
    protected $fillable = ['recette_id', 'nom', 'quantite'];

    // Relation inverse (optionnel mais recommandé)
    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
