<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    protected $fillable = ['booking_id', 'service_name', 'quantity', 'unit_price'];
    protected $casts = ['unit_price' => 'decimal:2'];
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
