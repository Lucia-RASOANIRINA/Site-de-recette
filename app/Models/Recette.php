<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    use HasFactory;

    // Colonnes que l’on peut remplir via mass-assignment
    protected $fillable = ['user_id', 'titre', 'description', 'instructions', 'image_path'];

    // Relation avec l’utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
