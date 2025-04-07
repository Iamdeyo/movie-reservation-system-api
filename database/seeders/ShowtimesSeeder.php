<?php

namespace Database\Seeders;

use App\Models\Genres;
use App\Models\Movies;
use App\Models\Showtimes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowtimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Showtimes::factory()->count(2)->create();
    }
}
