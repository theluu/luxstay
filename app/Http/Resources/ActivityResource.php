<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'type'        => $this->type,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'content'     => $this->content,
            'thumbnail'   => $this->thumbnail,
            'hero_image'  => $this->hero_image,
            'is_featured' => $this->is_featured,
            'sort_order'  => $this->sort_order,
            'created_at'  => $this->created_at,
        ];
    }
}
