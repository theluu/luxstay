<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'payment_status',
        'subtotal', 'shipping_fee', 'total',
        'vnpay_txn_ref', 'shipping_address',
    ];
    protected $casts = [
        'shipping_address' => 'array',
        'subtotal'         => 'decimal:2',
        'shipping_fee'     => 'decimal:2',
        'total'            => 'decimal:2',
    ];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function transactions(): MorphMany { return $this->morphMany(PaymentTransaction::class, 'payable'); }
}
