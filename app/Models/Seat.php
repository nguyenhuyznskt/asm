<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'row',
        'number',
        'type',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seats')
            ->withPivot('price')
            ->withTimestamps();
    }
}
