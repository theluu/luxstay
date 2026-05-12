<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'room_id'        => Room::factory(),
            'check_in'       => now()->addDays(10)->format('Y-m-d'),
            'check_out'      => now()->addDays(14)->format('Y-m-d'),
            'guests'         => fake()->numberBetween(1, 4),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'total_price'    => fake()->randomFloat(2, 200, 2000),
        ];
    }
}
