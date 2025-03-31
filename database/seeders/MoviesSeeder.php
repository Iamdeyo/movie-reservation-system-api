<?php

namespace Database\Seeders;

use App\Models\Genres;
use App\Models\Movies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = Genres::all(); // get all existing genres

        Movies::factory()
            ->count(50)
            ->create()
            ->each(function ($movie) use ($genres) {
                $movie->genres()->attach(
                    $genres->random(rand(1, 3))->pluck('id')->toArray() // assign 1-3 random genres
                );
            });
    }
}
