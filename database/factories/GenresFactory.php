<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genres>
 */
class GenresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = ['Action', 'Comedy', 'Romance', 'Drama', 'Thriller', 'Horror', 'Sci-Fi', 'Fantasy', 'Animation'];

        return [
            'name' => $this->faker->unique()->randomElement($genres),
        ];
    }
}
