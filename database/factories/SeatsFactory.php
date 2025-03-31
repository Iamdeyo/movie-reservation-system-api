<?php

namespace Database\Factories;

use App\Models\Theaters;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seats>
 */
class SeatsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'theaters_id' => Theaters::factory(),
            'row' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'number' => $this->faker->numberBetween(1, 10),
        ];
    }
}
