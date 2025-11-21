<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $cinema = Cinema::first();

        Room::create([
            'cinema_id' => $cinema->id,
            'name' => 'Phòng 1',
            'total_seats' => 50,
        ]);
    }
}
