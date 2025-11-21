<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $room = Room::first();

        foreach (range('A', 'E') as $row) {
            foreach (range(1, 10) as $num) {
                Seat::create([
                    'room_id' => $room->id,
                    'row' => $row,
                    'number' => $num,
                    'type' => $num > 8 ? 'vip' : 'normal',
                ]);
            }
        }
    }
}
