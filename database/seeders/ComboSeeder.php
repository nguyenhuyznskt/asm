<?php

namespace Database\Seeders;

use App\Models\Combo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Combo::insert([
            [
                'name'        => 'Combo 1: Bắp + Nước',
                'description' => '1 bắp nhỏ + 1 nước ngọt',
                'price'       => 70000,
            ],
            [
                'name'        => 'Combo 2: Bắp lớn + Nước',
                'description' => '1 bắp lớn + 1 nước ngọt',
                'price'       => 90000,
            ],
            [
                'name'        => 'Combo 3: 2 nước + 1 bắp',
                'description' => '1 bắp + 2 nước ngọt',
                'price'       => 120000,
            ],
        ]);
    }
}
