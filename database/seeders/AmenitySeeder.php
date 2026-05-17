<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Free Wi-Fi',         'icon' => 'fa-wifi'],
            ['name' => 'Air Conditioning',   'icon' => 'fa-snowflake'],
            ['name' => 'Flat-screen TV',     'icon' => 'fa-tv'],
            ['name' => 'Mini Bar',           'icon' => 'fa-wine-bottle'],
            ['name' => 'Private Pool',       'icon' => 'fa-swimming-pool'],
            ['name' => 'Ocean View',         'icon' => 'fa-water'],
            ['name' => 'Spa Bathtub',        'icon' => 'fa-bath'],
            ['name' => 'Room Service',       'icon' => 'fa-concierge-bell'],
            ['name' => 'Balcony',            'icon' => 'fa-door-open'],
            ['name' => 'Safe Box',           'icon' => 'fa-lock'],
            ['name' => 'Coffee Maker',       'icon' => 'fa-coffee'],
            ['name' => 'King-size Bed',      'icon' => 'fa-bed'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(['name' => $amenity['name']], $amenity);
        }
    }
}
