<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recette extends Model
{
    use HasFactory;

    // Définir les champs remplissables
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'instructions',
        'image_path'
    ];

    // Relation avec User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec Ingredients
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    // Relation avec Likes
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // Relation avec les votes publics (visiteurs anonymes - recette de la semaine)
    public function votes(): HasMany
    {
        return $this->hasMany(RecipeVote::class);
    }

    // Compter les likes
    public function likesCount()
    {
        return $this->likes()->count();
    }

    // Vérifier si l'utilisateur a liké
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}