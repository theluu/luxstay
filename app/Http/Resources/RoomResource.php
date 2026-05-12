<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'slug'            => $this->slug,
            'description'     => $this->description,
            'price_per_night' => $this->price_per_night,
            'max_guests'      => $this->max_guests,
            'size_sqm'        => $this->size_sqm,
            'thumbnail'       => $this->thumbnail,
            'gallery'         => $this->gallery,
            'is_available'    => $this->is_available,
            'room_type'       => ['id' => $this->roomType?->id, 'name' => $this->roomType?->name],
            'amenities'       => $this->amenities?->map(fn ($a) => ['id' => $a->id, 'name' => $a->name]),
            'created_at'      => $this->created_at,
        ];
    }
}
