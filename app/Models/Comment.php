<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    
    protected $fillable = [
        'movie_id',
        'name',
        'content',
        'rating',
        'ip_address',
        'likes',
        'dislikes',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
