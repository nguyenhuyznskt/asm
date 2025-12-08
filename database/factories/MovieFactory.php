<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Genre;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        // dùng seed random để mỗi phim 1 ảnh khác nhau
        $seed = $this->faker->unique()->numberBetween(1, 9999);

        return [
            'title' => $title,
            'slug'  => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'description' => $this->faker->paragraph(),
            'duration_minutes' => $this->faker->numberBetween(90, 150),
            // Ảnh poster dọc 300x450
            'poster_url' => "https://picsum.photos/seed/poster{$seed}/300/450",

            // Ảnh banner ngang 1200x400
            'banner_url' => "https://picsum.photos/seed/banner{$seed}/1200/400",
            'release_date' => $this->faker->dateTimeBetween('-6 months', '+6 months'),
            'age_rating' => $this->faker->randomElement(['P', '13+', '16+', '18+']),
            'is_featured'  => $this->faker->boolean(20), // 20% phim là nổi bật
            'genre_id'     => Genre::inRandomOrder()->first()->id,
        ];
    }
}
