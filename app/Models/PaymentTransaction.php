<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'payable_type', 'payable_id', 'amount',
        'gateway', 'status', 'gateway_ref', 'gateway_response',
    ];
    protected $casts = [
        'amount'           => 'decimal:2',
        'gateway_response' => 'array',
    ];
    public function payable(): MorphTo { return $this->morphTo(); }
}
