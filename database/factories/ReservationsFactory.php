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
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'showtimes_id' => Showtimes::factory(),
            'reservation_date' => $this->faker->dateTimeBetween('-7 days', '+7 days')->format('Y-m-d')
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($reservation) {
            // Attach 1-3 random seats to the reservation
            $seatIds = Seats::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $reservation->seats()->attach($seatIds);
        });
    }
}
