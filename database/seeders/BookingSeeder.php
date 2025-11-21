<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Showtime;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $showtime = Showtime::first();

        if ($showtime) {
            Booking::factory(3)->create([
                'showtime_id' => $showtime->id
            ]);
        }
    }
}
