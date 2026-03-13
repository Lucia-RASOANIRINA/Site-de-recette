<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    // Pas besoin de 'updated_at' car ta table ne l'a pas
    public $timestamps = false; 
    
    // On définit explicitement les champs remplissables
    protected $fillable = ['recette_id', 'user_id', 'post_id'];

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