<?php

namespace Database\Seeders;

use App\Models\Genres;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Genres::factory()
            ->count(9)
            ->sequence(
                ['name' => 'Action'],
                ['name' => 'Comedy'],
                ['name' => 'Romance'],
                ['name' => 'Drama'],
                ['name' => 'Thriller'],
                ['name' => 'Horror'],
                ['name' => 'Sci-Fi'],
                ['name' => 'Fantasy'],
                ['name' => 'Animation']
            )
            ->create();
    }
}
