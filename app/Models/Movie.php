<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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
        'genre_id'
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function avgRating()
    {
        return $this->comments()->avg('rating');
    }

    public function scopeNowShowing(Builder $query): Builder
    {
        return $query->whereHas('showtimes', function ($q) {
            $q->where('start_time', '>=', now());
        });
    }

    // Sắp chiếu: ngày khởi chiếu > hôm nay
    public function scopeComingSoon(Builder $query): Builder
    {
        return $query->whereDate('release_date', '>', now()->toDateString());
    }

    // Phim nổi bật
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    
}
