<?php

namespace Database\Seeders;

use App\Models\Theaters;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TheatersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Theaters::factory()
            ->count(3)
            ->hasSeats(40) // assuming each theater has 40 seats
            ->create();
    }
}
