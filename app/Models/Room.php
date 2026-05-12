<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id', 'name', 'slug', 'description',
        'price_per_night', 'max_guests', 'size_sqm',
        'thumbnail', 'gallery', 'is_available',
    ];
    protected $casts = [
        'gallery'         => 'array',
        'is_available'    => 'boolean',
        'price_per_night' => 'decimal:2',
    ];
    public function roomType(): BelongsTo { return $this->belongsTo(RoomType::class); }
    public function amenities(): BelongsToMany { return $this->belongsToMany(Amenity::class, 'room_amenity'); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
}
