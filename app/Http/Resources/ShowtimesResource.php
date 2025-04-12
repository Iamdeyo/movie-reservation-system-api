<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowtimesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->id,
            "movieId" => $this->movies_id,
            "movie" => new MoviesResource($this->whenLoaded('movies')),
            "theaterId" => $this->theaters_id,
            "theater" => new TheatersResource($this->whenLoaded('theaters')),
            "startTime" => $this->start_time,
            "endTime" => $this->end_time,
            "endDate" => $this->end_date,
            "price" => $this->price,
        ];
    }
}
