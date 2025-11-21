<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'cinema_id' => Cinema::factory(),
            'name' => 'Phòng ' . $this->faker->numberBetween(1, 5),
            'total_seats' => 50,
        ];
    }
}
