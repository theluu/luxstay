<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureUserIsAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/');
    }

    public function test_guest_cannot_access_admin_route(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }
}
