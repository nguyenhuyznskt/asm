<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Combo extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'is_active',
    ];

    public function bookingCombos()
    {
        return $this->hasMany(BookingCombo::class);
    }
}
