<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{

    protected $fillable = [
        'title',
        'description',
        'ingredients_imposes',
        'status'
    ];

}