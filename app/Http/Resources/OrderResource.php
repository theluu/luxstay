<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user'             => ['id' => $this->user?->id, 'name' => $this->user?->name],
            'status'           => $this->status,
            'payment_status'   => $this->payment_status,
            'vnpay_txn_ref'     => $this->vnpay_txn_ref,
            'latest_transaction' => new PaymentTransactionResource($this->whenLoaded('transactions', fn () => $this->transactions->first())),
            'subtotal'         => $this->subtotal,
            'shipping_fee'     => $this->shipping_fee,
            'total'            => $this->total,
            'shipping_address' => $this->shipping_address,
            'items'            => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'product_name' => $i->product?->name,
                'quantity'     => $i->quantity,
                'unit_price'   => $i->unit_price,
            ])),
            'created_at'       => $this->created_at,
        ];
    }
}
