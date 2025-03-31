<?php

namespace Database\Factories;

use App\Models\Movies;
use App\Models\Theaters;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showtimes>
 */
class ShowtimesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+7 days');
        $duration = $this->faker->numberBetween(90, 180);
        $end = (clone $start)->modify("+$duration minutes");

        return [
            'movies_id' => Movies::factory(),
            'theaters_id' => Theaters::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'price' => $this->faker->randomFloat(2, 5, 20),
        ];
    }
}
