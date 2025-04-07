<?php

namespace Database\Factories;

use App\Models\Movies;
use App\Models\Theaters;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowtimesFactory extends Factory
{
    public function definition(): array
    {
        // Get a random movie or create one if none exists
        $movie = Movies::inRandomOrder()->first() ?? Movies::factory()->create();

        // Generate showtime between 8AM and 11PM
        $start = $this->faker->dateTimeBetween('today 8:00', 'today 23:00');
        $end = (clone $start)->modify("+{$movie->duration} minutes");

        // Adjust if showtime crosses midnight
        if ($end->format('H:i') < $start->format('H:i')) {
            $start = $this->faker->dateTimeBetween('today 8:00', 'today 22:00');
            $end = (clone $start)->modify("+{$movie->duration} minutes");
        }

        return [
            'movies_id' => $movie->id,
            'theaters_id' => Theaters::inRandomOrder()->first()?->id ?? Theaters::factory(),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'price' => $this->faker->randomFloat(2, 5000, 7500), // Realistic cinema pricing
        ];
    }
}
