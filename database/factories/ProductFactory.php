<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->words(3, true),
            'slug'        => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 10, 500),
            'stock'       => fake()->numberBetween(0, 100),
            'thumbnail'   => null,
            'gallery'     => null,
            'is_active'   => true,
        ];
    }
}
