<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'product_category_id', 'name', 'slug', 'description',
        'price', 'stock', 'thumbnail', 'gallery', 'is_active',
    ];
    protected $casts = [
        'gallery'   => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];
    public function category(): BelongsTo { return $this->belongsTo(ProductCategory::class, 'product_category_id'); }
}
