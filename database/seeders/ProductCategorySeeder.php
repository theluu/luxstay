<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Skincare',        'slug' => 'skincare'],
            ['name' => 'Aromatherapy',    'slug' => 'aromatherapy'],
            ['name' => 'Bath & Body',     'slug' => 'bath-body'],
            ['name' => 'Wellness',        'slug' => 'wellness'],
            ['name' => 'Gift Sets',       'slug' => 'gift-sets'],
        ];

        foreach ($categories as $cat) {
            ProductCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
