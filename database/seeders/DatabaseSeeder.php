<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
    // \App\Models\Cinema::factory(1)->create();

    // // mỗi rạp tạo 3 phòng
    // \App\Models\Room::factory(6)->create();

    // tạo 50 ghế cho mỗi phòng bằng SeatSeeder riêng
    User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Super Admin',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]
    );
    $this->call(CinemaSeeder::class);
    $this->call(RoomSeeder::class);
    $this->call(SeatSeeder::class);

    // tạo 5 phim
    $this->call( ComboSeeder::class); 
    \App\Models\Movie::factory(20)->create();

    // mỗi phim tạo 4 suất chiếu
 
       
    $this->call(ShowtimeSeeder::class);
    $this->call(BookingSeeder::class);
}
}
