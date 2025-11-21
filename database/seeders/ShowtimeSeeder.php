<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Illuminate\Database\Seeder;

class ShowtimeSeeder extends Seeder
{
    public function run(): void
    {
        $room = Room::first();

        Movie::all()->each(function ($movie) use ($room) {
            foreach (range(1, 3) as $i) {
                $start = now()->addDays(rand(0, 5))->setTime(rand(9, 22), 0);

                Showtime::create([
                    'movie_id' => $movie->id,
                    'room_id'  => $room->id,
                    'start_time' => $start,
                    'end_time'   => (clone $start)->addMinutes($movie->duration_minutes),
                    'price'      => rand(70000, 120000),
                ]);
            }
        });
    }
}
