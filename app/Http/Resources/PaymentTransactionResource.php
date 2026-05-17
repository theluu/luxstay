<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payable_type' => class_basename($this->payable_type),
            'payable_id' => $this->payable_id,
            'amount' => $this->amount,
            'gateway' => $this->gateway,
            'status' => $this->status,
            'gateway_ref' => $this->gateway_ref,
            'gateway_response' => $this->gateway_response,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
