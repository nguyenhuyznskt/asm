<?php

namespace Database\Seeders;

use App\Models\Cinema;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        $cinemas = [
            'Laravel Cinema Hà Nội',
            'Laravel Cinema Hồ Chí Minh',
            'Laravel Cinema Đà Nẵng',
            'Laravel Cinema Hải Phòng',
            'Laravel Cinema Cần Thơ',
        ];

        foreach ($cinemas as $name) {
            Cinema::create([
                'name'    => $name,
                'address' => 'Địa chỉ demo của ' . $name,
                'city'    => $name,
            ]);
        }
    }
}
