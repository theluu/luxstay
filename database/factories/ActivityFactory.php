<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type'        => fake()->randomElement(['spa','golf','hiking','skiing','water_sports','fitness','nature','restaurant','event']),
            'title'       => fake()->words(3, true),
            'slug'        => fake()->unique()->slug(),
            'content'     => fake()->paragraphs(3, true),
            'thumbnail'   => null,
            'hero_image'  => null,
            'is_featured' => false,
            'sort_order'  => 0,
        ];
    }
}
