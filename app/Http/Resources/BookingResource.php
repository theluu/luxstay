<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'user'           => ['id' => $this->user?->id, 'name' => $this->user?->name, 'email' => $this->user?->email],
            'room'           => ['id' => $this->room?->id, 'name' => $this->room?->name],
            'check_in'       => $this->check_in?->format('Y-m-d'),
            'check_out'      => $this->check_out?->format('Y-m-d'),
            'guests'         => $this->guests,
            'status'         => $this->status,
            'payment_status' => $this->payment_status,
            'total_price'    => $this->total_price,
            'created_at'     => $this->created_at,
        ];
    }
}
