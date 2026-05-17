<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Deluxe Room',    'slug' => 'deluxe-room',    'description' => 'Spacious rooms with premium furnishings and panoramic views.'],
            ['name' => 'Suite',          'slug' => 'suite',          'description' => 'Luxurious suites with separate living areas and exclusive amenities.'],
            ['name' => 'Villa',          'slug' => 'villa',          'description' => 'Private villas with personal pools and butler service.'],
            ['name' => 'Penthouse',      'slug' => 'penthouse',      'description' => 'Top-floor penthouses with unparalleled skyline views.'],
            ['name' => 'Presidential Suite', 'slug' => 'presidential-suite', 'description' => 'The pinnacle of luxury — our most exclusive accommodation.'],
        ];

        foreach ($types as $type) {
            RoomType::firstOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
