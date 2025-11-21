<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // public function run(): void
    // {
    //     $this->call([
    //         CinemaSeeder::class,
    //         RoomSeeder::class,
    //         SeatSeeder::class,

    //         MovieSeeder::class,
    //         ShowtimeSeeder::class,

            
    //     ]);

        

    //         // optional
    //         // BookingSeeder::class,
           
    
    // }
    public function run(): void
{
    // tạo 1 rạp
    \App\Models\Cinema::factory(1)->create();

    // mỗi rạp tạo 3 phòng
    \App\Models\Room::factory(6)->create();

    // tạo 50 ghế cho mỗi phòng bằng SeatSeeder riêng
    $this->call(SeatSeeder::class);

    // tạo 5 phim
    \App\Models\Movie::factory(20)->create();

    // mỗi phim tạo 4 suất chiếu
    $this->call(ShowtimeSeeder::class);
}
}
