<?php

namespace Database\Factories;

use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowtimeFactory extends Factory
{
    protected $model = Showtime::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+7 days');

        return [
            'movie_id' => Movie::factory(),
            'room_id' => Room::factory(),
            'start_time' => $start,
            'end_time' => (clone $start)->modify('+120 minutes'),
            'price' => $this->faker->randomElement([70000, 80000, 90000, 120000]),
        ];
    }
}
