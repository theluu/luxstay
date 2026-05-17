<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Travel Tips',     'slug' => 'travel-tips'],
            ['name' => 'Dining',          'slug' => 'dining'],
            ['name' => 'Wellness & Spa',  'slug' => 'wellness-spa'],
            ['name' => 'Events',          'slug' => 'events'],
            ['name' => 'Destination',     'slug' => 'destination'],
        ];

        foreach ($categories as $cat) {
            PostCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
