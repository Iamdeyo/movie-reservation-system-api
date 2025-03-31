<?php

namespace Database\Seeders;

use App\Models\Genres;
use App\Models\Movies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowtimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movies::factory()
            ->count(5)
            ->hasShowtimes(3) // each movie has 3 showtimes
            ->create()
            ->each(function ($movie) {
                $genreIds = Genres::inRandomOrder()->take(2)->pluck('id');
                $movie->genres()->attach($genreIds);
            });
    }
}
