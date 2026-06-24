<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeVote extends Model
{
    public $timestamps = false;

    protected $fillable = ['recette_id', 'recette_nom', 'email'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
