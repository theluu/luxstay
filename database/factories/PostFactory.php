<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_id'    => User::factory(),
            'title'        => fake()->sentence(),
            'slug'         => fake()->unique()->slug(),
            'excerpt'      => fake()->paragraph(),
            'content'      => fake()->paragraphs(5, true),
            'thumbnail'    => null,
            'type'         => 'standard',
            'status'       => 'published',
            'published_at' => now(),
        ];
    }
}
