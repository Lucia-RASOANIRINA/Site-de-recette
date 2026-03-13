<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable=[
    'user_id',
    'post_id',
    'recette_id',
    'content'
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