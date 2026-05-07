<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_id'    => RoomType::factory(),
            'name'            => fake()->words(3, true),
            'slug'            => fake()->unique()->slug(),
            'description'     => fake()->paragraph(),
            'price_per_night' => fake()->randomFloat(2, 100, 1000),
            'max_guests'      => fake()->numberBetween(1, 4),
            'size_sqm'        => fake()->numberBetween(20, 120),
            'thumbnail'       => null,
            'gallery'         => null,
            'is_available'    => true,
        ];
    }
}
