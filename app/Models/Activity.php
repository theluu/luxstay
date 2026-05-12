<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'title', 'slug', 'content',
        'thumbnail', 'hero_image', 'is_featured', 'sort_order',
    ];
    protected $casts = ['is_featured' => 'boolean'];
}
