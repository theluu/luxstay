<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ProductCategory extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name', 'slug'];
    public function products(): HasMany { return $this->hasMany(Product::class); }
}
