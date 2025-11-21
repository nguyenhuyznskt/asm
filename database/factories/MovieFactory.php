<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug'  => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'description' => $this->faker->paragraph(),
            'duration_minutes' => $this->faker->numberBetween(90, 150),
            'poster_url' => 'https://via.placeholder.com/300x450?text=Movie',
            'banner_url' => 'https://via.placeholder.com/1200x400?text=Banner',
            'release_date' => $this->faker->dateTimeBetween('-6 months', '+6 months'),
            'age_rating' => $this->faker->randomElement(['P', '13+', '16+', '18+']),
        ];
    }
}
