<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Movie extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration_minutes',
        'poster_url',
        'banner_url',
        'release_date',
        'age_rating',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
    
}
