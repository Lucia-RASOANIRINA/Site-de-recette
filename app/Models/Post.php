<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'user_id',
        'type',
        'content',
        'image_path',
        'recette_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

}