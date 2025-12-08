<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Nếu sau này phim có cột genre_id
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
