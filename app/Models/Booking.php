<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id', 'check_in', 'check_out',
        'guests', 'status', 'payment_status',
        'total_price', 'vnpay_txn_ref', 'special_requests',
    ];
    protected $casts = [
        'check_in'    => 'date',
        'check_out'   => 'date',
        'total_price' => 'decimal:2',
        'guests'      => 'integer',
    ];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function services(): HasMany { return $this->hasMany(BookingService::class); }
    public function transactions(): MorphMany { return $this->morphMany(PaymentTransaction::class, 'payable'); }
}
