<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChallenge extends Model
{
    protected $table = 'ai_challenges';
    
    protected $fillable = ['title', 'description', 'ingredients', 'instructions', 'difficulty', 'duration', 'expires_at', 'is_active'];

    protected $casts = [
        'ingredients' => 'array',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}