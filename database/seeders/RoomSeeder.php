<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Cinema::all()->each(function ($cinema) {

            foreach (range(1, 6) as $number) {
                Room::create([
                    'cinema_id'   => $cinema->id,
                    'name'        => 'Phòng ' . $number,
                    'total_seats' => 60,  // hoặc số tuỳ mày
                ]);
            }

        });
    }
}
