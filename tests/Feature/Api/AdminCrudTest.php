<?php

namespace Tests\Feature\Api;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Post;
use App\Models\Product;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->token = $this->admin->createToken('admin')->plainTextToken;
    }

    private function adminJson(string $method, string $url, array $data = [])
    {
        return $this->withToken($this->token)->json($method, $url, $data);
    }

    public function test_admin_can_list_rooms(): void
    {
        Room::factory(3)->create();
        $this->adminJson('GET', '/api/v1/rooms')->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_room(): void
    {
        $type = RoomType::factory()->create();
        $this->adminJson('POST', '/api/v1/rooms', [
            'room_type_id' => $type->id, 'name' => 'New Suite', 'slug' => 'new-suite',
            'price_per_night' => 399, 'max_guests' => 2, 'is_available' => true,
        ])->assertStatus(201)->assertJsonPath('data.name', 'New Suite');
    }

    public function test_admin_can_update_room(): void
    {
        $room = Room::factory()->create();
        $this->adminJson('PUT', "/api/v1/rooms/{$room->id}", [
            'name' => 'Updated Suite', 'slug' => $room->slug,
            'price_per_night' => 500, 'max_guests' => 2, 'is_available' => true,
        ])->assertStatus(200)->assertJsonPath('data.name', 'Updated Suite');
    }

    public function test_admin_can_delete_room(): void
    {
        $room = Room::factory()->create();
        $this->adminJson('DELETE', "/api/v1/rooms/{$room->id}")->assertStatus(204);
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_admin_can_list_bookings(): void
    {
        Booking::factory(5)->create();
        $this->adminJson('GET', '/api/v1/bookings')->assertStatus(200)->assertJsonCount(5, 'data');
    }

    public function test_admin_can_update_booking_status(): void
    {
        $booking = Booking::factory()->create(['status' => 'pending']);
        $this->adminJson('PUT', "/api/v1/bookings/{$booking->id}", ['status' => 'confirmed'])
            ->assertStatus(200)->assertJsonPath('data.status', 'confirmed');
    }

    public function test_admin_can_create_post(): void
    {
        $this->adminJson('POST', '/api/v1/posts', [
            'title' => 'New Blog Post', 'slug' => 'new-blog-post',
            'content' => '<p>Content here</p>', 'status' => 'draft', 'type' => 'standard',
        ])->assertStatus(201)->assertJsonPath('data.title', 'New Blog Post');
    }

    public function test_admin_can_create_product(): void
    {
        $this->adminJson('POST', '/api/v1/products', [
            'name' => 'Luxury Candle', 'slug' => 'luxury-candle',
            'price' => 49.99, 'stock' => 20, 'is_active' => true,
        ])->assertStatus(201)->assertJsonPath('data.name', 'Luxury Candle');
    }

    public function test_admin_can_update_activity(): void
    {
        $activity = Activity::factory()->create(['slug' => 'spa-wellness', 'type' => 'spa']);
        $this->adminJson('PUT', "/api/v1/activities/{$activity->id}", [
            'title' => 'Updated Spa', 'slug' => 'spa-wellness', 'type' => 'spa', 'content' => '<p>New content</p>',
        ])->assertStatus(200)->assertJsonPath('data.title', 'Updated Spa');
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $this->getJson('/api/v1/rooms')->assertStatus(401);
    }

    public function test_admin_can_get_dashboard_stats(): void
    {
        $this->adminJson('GET', '/api/v1/dashboard/stats')
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['total_bookings', 'total_orders', 'total_rooms', 'total_revenue']]);
    }
}
