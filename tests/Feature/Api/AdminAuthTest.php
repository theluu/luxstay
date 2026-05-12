<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_receive_token(): void
    {
        $admin = User::factory()->create([
            'email'    => 'admin@luxestay.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@luxestay.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']]);
    }

    public function test_non_admin_cannot_login_to_admin(): void
    {
        User::factory()->create([
            'email'    => 'user@luxestay.com',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email'    => 'user@luxestay.com',
            'password' => 'password',
        ])->assertStatus(403);
    }

    public function test_wrong_credentials_return_422(): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email'    => 'nobody@luxestay.com',
            'password' => 'wrong',
        ])->assertStatus(422);
    }

    public function test_authenticated_admin_can_get_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('admin')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(200)
            ->assertJsonPath('data.id', $admin->id);
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('admin')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertStatus(200);

        // Reset the auth guard so the next request re-evaluates the token from DB.
        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(401);
    }
}
