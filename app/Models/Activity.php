<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Activity extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'type', 'title', 'slug', 'content',
        'thumbnail', 'hero_image', 'is_featured', 'sort_order',
    ];
    protected $casts = ['is_featured' => 'boolean'];
}
