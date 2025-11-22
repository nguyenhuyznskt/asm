<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        Room::all()->each(function ($room) {

            $rows = ['A', 'B', 'C', 'D', 'E']; // 5 hàng
            foreach ($rows as $row) {
                foreach (range(1, 10) as $number) { // 10 ghế mỗi hàng
                    Seat::create([
                        'room_id' => $room->id,
                        'row'     => $row,
                        'number'  => $number,
                        'type'    => 'normal', // ← DÚNG GIÁ TRỊ NẰM TRONG ENUM
                    ]);
                }
            }

        });
    }
}
