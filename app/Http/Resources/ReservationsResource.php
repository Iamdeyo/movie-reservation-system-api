<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationsResource extends JsonResource
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
            'showtime' => new ShowtimesResource($this->whenLoaded('showtimes')),
            'user' => new UserResource($this->whenLoaded('user')),
            'seats' => SeatsResource::collection($this->whenLoaded('seats')),
            'reservationDate' => $this->reservation_date,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
