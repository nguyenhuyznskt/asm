<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Showtime;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'showtime_id' => Showtime::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'total_price' => $this->faker->numberBetween(70000, 200000),
            'status' => 'confirmed',
        ];
    }
}
