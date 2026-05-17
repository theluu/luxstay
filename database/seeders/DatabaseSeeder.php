<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@luxestay.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        $this->call([
            SiteSettingSeeder::class,
            RoomTypeSeeder::class,
            AmenitySeeder::class,
            RoomSeeder::class,
            PostCategorySeeder::class,
            PostSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ActivitySeeder::class,
        ]);
    }
}
