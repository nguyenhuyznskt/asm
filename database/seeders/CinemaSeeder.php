<?php

namespace Database\Seeders;

use App\Models\Cinema;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        Cinema::factory()->count(1)->create();
    }
}
