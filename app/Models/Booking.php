<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'showtime_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }
}
