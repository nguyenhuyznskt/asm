<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Hành động',
            'Tình cảm',
            'Hài hước',
            'Kinh dị',
            'Phiêu lưu',
            'Hoạt hình',
            'Tâm lý',
            'Khoa học viễn tưởng',
            'Chiến tranh',
            'Hình sự',
        ];

        foreach ($genres as $name) {
            Genre::firstOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'description' => 'Thể loại phim: ' . $name,
                ]
            );
        }
    }
}

