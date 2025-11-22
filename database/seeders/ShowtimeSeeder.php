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
        $rooms  = Room::all();
        $movies = Movie::all();
    
        foreach ($movies as $movie) {
            // mỗi phim tạo 5 suất chiếu
            foreach (range(1, 5) as $i) {
                // chọn ngẫu nhiên 1 phòng bất kỳ trong tất cả phòng
                $room = $rooms->random();
    
                $start = now()
                    ->addDays(rand(0, 5))
                    ->setTime(rand(9, 22), 0);
    
                Showtime::create([
                    'movie_id'   => $movie->id,
                    'room_id'    => $room->id,
                    'start_time' => $start,
                    'end_time'   => (clone $start)->addMinutes($movie->duration_minutes),
                    'price'      => rand(70000, 120000),
                ]);
            }
        }
    }
}
