<?php

namespace Database\Factories;

use App\Models\Seats;
use App\Models\Showtimes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservations>
 */
class ReservationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'showtimes_id' => Showtimes::factory(),
            'seats_id' => Seats::factory(),
            'reservation_date' => $this->faker->dateTimeBetween('-7 days', '+7 days')->format('Y-m-d')
        ];
    }
}
