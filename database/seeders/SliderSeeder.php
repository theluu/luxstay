<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        if (Slider::exists()) return;

        Slider::insert([
            [
                'type'       => 'video',
                'title'      => "PREMIER MOUNTAIN RETREAT FOR\nRELAXATION AND RECREATION",
                'media_url'  => 'https://luxestay.wpthemeverse.com/wp-content/uploads/2024/08/video-hotel1-1.mp4',
                'sort_order' => 1,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'image',
                'title'      => "SOPHISTICATED ALPINE RESORT IN\nSWITZERLAND'S HEARTLAND",
                'media_url'  => 'images/home1-hero_img.png',
                'sort_order' => 2,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type'       => 'image',
                'title'      => "EXCLUSIVE HIGH-ALTITUDE\nSANCTUARY AND ADVENTURE\nRESORT",
                'media_url'  => 'images/home1_hero_img2.png',
                'sort_order' => 3,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
