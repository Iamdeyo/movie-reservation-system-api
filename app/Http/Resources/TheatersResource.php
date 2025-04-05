<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TheatersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //  'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
        return [
            'id' => $this->id,
            'name' => $this->name,
            'seats' => SeatsResource::collection($this->whenLoaded('seats')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
