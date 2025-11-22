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
        return $this->belongsToMany(Seat::class, 'booking_seats')
            ->withPivot('price')
            ->withTimestamps();
    }
    public function combos()
{
    return $this->hasMany(BookingCombo::class);
}
}
