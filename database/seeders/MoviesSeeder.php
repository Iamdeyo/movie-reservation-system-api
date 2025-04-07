<?php

namespace Database\Seeders;

use App\Models\Genres;
use App\Models\Movies;
use Illuminate\Database\Seeder;

class MoviesSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                "title" => "Run",
                "description" => "Chloe, a teenager who is confined to a wheelchair, is homeschooled by her mother, Diane. Chloe soon becomes suspicious of her mother and begins to suspect that she may be harboring a dark secret",
                "poster" => "https://m.media-amazon.com/images/M/MV5BOTViNDViNDgtNDc0Ny00NWZkLTljMzctYjgzNzI5MTYwZjJlXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 90
            ],
            [
                "title" => "Batman",
                "description" => "Gotham City. Crime boss Carl Grissom (Jack Palance) effectively runs the town but there's a new crime fighter in town - Batman (Michael Keaton). Grissom's right-hand man is Jack Napier (Jack Nicholson), a brutal man who is not entirely sane... After falling out between the two Grissom has Napier set up with the Police and Napier falls to his apparent death in a vat of chemicals. However, he soon reappears as The Joker and starts a reign of terror in Gotham City. Meanwhile, reporter Vicki Vale (Kim Basinger) is in the city to do an article on Batman. She soon starts a relationship with Batman's everyday persona, billionaire Bruce Wayne.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BYzZmZWViM2EtNzhlMi00NzBlLWE0MWEtZDFjMjk3YjIyNTBhXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 126
            ],
            [
                "title" => "Superman/Batman: Apocalypse",
                "description" => "Batman discovers a mysterious teen-aged girl with super-human powers and a connection to Superman. When the girl comes to the attention of Darkseid, the evil overlord of Apokolips, events take a decidedly dangerous turn.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BMjk3ODhmNjgtZjllOC00ZWZjLTkwYzQtNzc1Y2ZhMjY2ODE0XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg",
                "duration" => 78
            ],
            [
                "title" => "Mufasa: The Lion King",
                "description" => "Mufasa, a cub lost and alone, meets a sympathetic lion named Taka, the heir to a royal bloodline. The chance meeting sets in motion an expansive journey of a group of misfits searching for their destiny.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BYjBkOWUwODYtYWI3YS00N2I0LWEyYTktOTJjM2YzOTc3ZDNlXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 118
            ],
            [
                "title" => "A Working Man",
                "description" => "Levon Cade left his profession behind to work construction and be a good dad to his daughter. But when a local girl vanishes, he's asked to return to the skills that made him a mythic figure in the shadowy world of counter-terrorism.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BYmQxZGIxNTYtYTQwMy00ODdkLWI0MmQtM2E0ZmIyNmYzMGMzXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 116
            ],
            [
                "title" => "Black Bag",
                "description" => "When intelligence agent Kathryn Woodhouse is suspected of betraying the nation, her husband - also a legendary agent - faces the ultimate test of whether to be loyal to his marriage, or his country.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BNzA1OWU4NDMtMDUxMC00NWI4LWJhYjUtYWQ0OGQ5MTc2NDRjXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 93
            ],
            [
                "title" => "The Actor",
                "description" => "When New York actor Paul Cole is beaten and left for dead in 1950s Ohio, he loses his memory and finds himself stranded in a mysterious small town where he struggles to get back home and reclaim what he's lost.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BOGU4NjQyZjEtMWFhNS00MzU2LTk4OWMtNjExN2VkYTg4YmIyXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 90
            ],
            [
                "title" => "Snow White",
                "description" => "A live-action re-imaging of Walt Disney's 1937 animated classic about a beautiful young princess who, while being stalked by a jealous queen, seeks shelter in a cabin in a forest inhabited by seven dwarfs with entirely different personalities and must come face-to-face with bravery to the truth to her past of her mother's death since her childbirth.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BY2RlZDc3ZjYtYWJlNC00MzFiLTlmMGItYmUzYjY3Mjc1Yzc2XkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 109
            ],
            [
                "title" => "The Electric State",
                "description" => "An orphaned teen hits the road with a mysterious robot to find her long-lost brother, teaming up with a smuggler and his wisecracking sidekick.",
                "poster" => "https://m.media-amazon.com/images/M/MV5BMjQ1MTYzZmItMTkzYy00NzdmLTlhYmMtMzk5YjhiOThmZDgwXkEyXkFqcGc@._V1_SX300.jpg",
                "duration" => 128
            ]
        ];

        $genres = Genres::all();

        foreach ($movies as $movieData) {
            $movie = Movies::create($movieData);
            $movie->genres()->attach(
                $genres->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
