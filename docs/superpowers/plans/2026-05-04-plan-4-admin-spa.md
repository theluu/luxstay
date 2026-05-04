# LuxeStay Plan 4: Admin SPA + Account Pages + Dashboard

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the Vue 3 Admin SPA with full CRUD for all modules (rooms, bookings, posts, products, orders, activities), wire all API endpoints with Sanctum token auth, and build the admin dashboard with stats.

**Architecture:** Vue 3 + Vue Router 4 + Pinia + Axios in `resources/js/admin/`. Laravel API routes under `/api/v1/*` protected by `auth:sanctum` + `EnsureUserIsAdmin`. API Resources format all JSON responses consistently. Vite builds admin bundle separately from frontend JS.

**Tech Stack:** Vue 3, Vite, Pinia, Vue Router 4, Axios, TailwindCSS (admin only), Laravel Sanctum, PHPUnit (API tests)

**Prerequisite:** Plans 1, 2 & 3 must be complete.

---

## Task 1: Laravel Sanctum + Admin API Auth

**Files:**
- Modify: `config/sanctum.php` (ensure stateful domains)
- Create: `app/Http/Controllers/Api/AuthController.php`
- Modify: `routes/api.php`
- Create: `tests/Feature/Api/AdminAuthTest.php`

- [ ] **Step 1: Install Sanctum (already in Laravel 13 by default — verify)**

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```
Expected: `personal_access_tokens` table created (or already exists).

- [ ] **Step 2: Write failing tests**

Create `tests/Feature/Api/AdminAuthTest.php`:

```php
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

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(401);
    }
}
```

- [ ] **Step 3: Run tests — expect failures**

```bash
php artisan test tests/Feature/Api/AdminAuthTest.php
```
Expected: FAIL — no routes defined.

- [ ] **Step 4: Replace `routes/api.php`**

```php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public auth endpoints
    Route::post('/auth/login',  [AuthController::class, 'login']);

    // Protected admin endpoints
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);

        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        Route::apiResource('rooms',      RoomController::class);
        Route::apiResource('bookings',   BookingController::class);
        Route::apiResource('posts',      PostController::class);
        Route::apiResource('products',   ProductController::class);
        Route::apiResource('orders',     OrderController::class);
        Route::apiResource('activities', ActivityController::class);
    });
});
```

- [ ] **Step 5: Create AuthController**

```bash
mkdir -p app/Http/Controllers/Api
```

Create `app/Http/Controllers/Api/AuthController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $token = $user->createToken('admin-spa')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()]);
    }
}
```

- [ ] **Step 6: Run auth tests**

```bash
php artisan test tests/Feature/Api/AdminAuthTest.php
```
Expected: All 5 tests pass.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "feat: add Sanctum API auth for admin (login, logout, me)"
```

---

## Task 2: API Resources + CRUD Controllers

**Files:**
- Create: `app/Http/Resources/RoomResource.php`
- Create: `app/Http/Resources/BookingResource.php`
- Create: `app/Http/Resources/PostResource.php`
- Create: `app/Http/Resources/ProductResource.php`
- Create: `app/Http/Resources/OrderResource.php`
- Create: `app/Http/Resources/ActivityResource.php`
- Create: `app/Http/Controllers/Api/RoomController.php`
- Create: `app/Http/Controllers/Api/BookingController.php`
- Create: `app/Http/Controllers/Api/PostController.php`
- Create: `app/Http/Controllers/Api/ProductController.php`
- Create: `app/Http/Controllers/Api/OrderController.php`
- Create: `app/Http/Controllers/Api/ActivityController.php`
- Create: `app/Http/Controllers/Api/DashboardController.php`
- Create: `tests/Feature/Api/AdminCrudTest.php`

- [ ] **Step 1: Create API Resources**

```bash
php artisan make:resource RoomResource
php artisan make:resource BookingResource
php artisan make:resource PostResource
php artisan make:resource ProductResource
php artisan make:resource OrderResource
php artisan make:resource ActivityResource
```

`app/Http/Resources/RoomResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'              => $this->id,
        'name'            => $this->name,
        'slug'            => $this->slug,
        'description'     => $this->description,
        'price_per_night' => $this->price_per_night,
        'max_guests'      => $this->max_guests,
        'size_sqm'        => $this->size_sqm,
        'thumbnail'       => $this->thumbnail,
        'gallery'         => $this->gallery,
        'is_available'    => $this->is_available,
        'room_type'       => ['id' => $this->roomType?->id, 'name' => $this->roomType?->name],
        'amenities'       => $this->amenities?->map(fn ($a) => ['id' => $a->id, 'name' => $a->name]),
        'created_at'      => $this->created_at,
    ];
}
```

`app/Http/Resources/BookingResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'             => $this->id,
        'user'           => ['id' => $this->user?->id, 'name' => $this->user?->name, 'email' => $this->user?->email],
        'room'           => ['id' => $this->room?->id, 'name' => $this->room?->name],
        'check_in'       => $this->check_in?->format('Y-m-d'),
        'check_out'      => $this->check_out?->format('Y-m-d'),
        'guests'         => $this->guests,
        'status'         => $this->status,
        'payment_status' => $this->payment_status,
        'total_price'    => $this->total_price,
        'created_at'     => $this->created_at,
    ];
}
```

`app/Http/Resources/PostResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'           => $this->id,
        'title'        => $this->title,
        'slug'         => $this->slug,
        'excerpt'      => $this->excerpt,
        'content'      => $this->content,
        'thumbnail'    => $this->thumbnail,
        'type'         => $this->type,
        'status'       => $this->status,
        'category'     => ['id' => $this->category?->id, 'name' => $this->category?->name],
        'author'       => ['id' => $this->author?->id, 'name' => $this->author?->name],
        'published_at' => $this->published_at,
        'created_at'   => $this->created_at,
    ];
}
```

`app/Http/Resources/ProductResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'          => $this->id,
        'name'        => $this->name,
        'slug'        => $this->slug,
        'description' => $this->description,
        'price'       => $this->price,
        'stock'       => $this->stock,
        'thumbnail'   => $this->thumbnail,
        'gallery'     => $this->gallery,
        'is_active'   => $this->is_active,
        'category'    => ['id' => $this->category?->id, 'name' => $this->category?->name],
        'created_at'  => $this->created_at,
    ];
}
```

`app/Http/Resources/OrderResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'               => $this->id,
        'user'             => ['id' => $this->user?->id, 'name' => $this->user?->name],
        'status'           => $this->status,
        'payment_status'   => $this->payment_status,
        'subtotal'         => $this->subtotal,
        'shipping_fee'     => $this->shipping_fee,
        'total'            => $this->total,
        'shipping_address' => $this->shipping_address,
        'items'            => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
            'product_name' => $i->product?->name,
            'quantity'     => $i->quantity,
            'unit_price'   => $i->unit_price,
        ])),
        'created_at'       => $this->created_at,
    ];
}
```

`app/Http/Resources/ActivityResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id'          => $this->id,
        'type'        => $this->type,
        'title'       => $this->title,
        'slug'        => $this->slug,
        'content'     => $this->content,
        'thumbnail'   => $this->thumbnail,
        'hero_image'  => $this->hero_image,
        'is_featured' => $this->is_featured,
        'sort_order'  => $this->sort_order,
        'created_at'  => $this->created_at,
    ];
}
```

- [ ] **Step 2: Write CRUD API tests**

Create `tests/Feature/Api/AdminCrudTest.php`:

```php
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

    private function adminJson(string $method, string $url, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->withToken($this->token)->json($method, $url, $data);
    }

    // Rooms
    public function test_admin_can_list_rooms(): void
    {
        $type = RoomType::factory()->create();
        Room::factory(3)->create(['room_type_id' => $type->id]);

        $this->adminJson('GET', '/api/v1/rooms')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_create_room(): void
    {
        $type = RoomType::factory()->create();

        $this->adminJson('POST', '/api/v1/rooms', [
            'room_type_id'    => $type->id,
            'name'            => 'New Suite',
            'slug'            => 'new-suite',
            'price_per_night' => 399,
            'max_guests'      => 2,
            'is_available'    => true,
        ])->assertStatus(201)->assertJsonPath('data.name', 'New Suite');
    }

    public function test_admin_can_update_room(): void
    {
        $type = RoomType::factory()->create();
        $room = Room::factory()->create(['room_type_id' => $type->id]);

        $this->adminJson('PUT', "/api/v1/rooms/{$room->id}", ['name' => 'Updated Suite', 'slug' => $room->slug, 'price_per_night' => 500, 'max_guests' => 2, 'is_available' => true])
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Suite');
    }

    public function test_admin_can_delete_room(): void
    {
        $type = RoomType::factory()->create();
        $room = Room::factory()->create(['room_type_id' => $type->id]);

        $this->adminJson('DELETE', "/api/v1/rooms/{$room->id}")->assertStatus(204);
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    // Bookings
    public function test_admin_can_list_bookings(): void
    {
        $type = RoomType::factory()->create();
        $room = Room::factory()->create(['room_type_id' => $type->id]);
        Booking::factory(5)->create(['room_id' => $room->id]);

        $this->adminJson('GET', '/api/v1/bookings')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_update_booking_status(): void
    {
        $type    = RoomType::factory()->create();
        $room    = Room::factory()->create(['room_type_id' => $type->id]);
        $booking = Booking::factory()->create(['room_id' => $room->id, 'status' => 'pending']);

        $this->adminJson('PUT', "/api/v1/bookings/{$booking->id}", ['status' => 'confirmed'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'confirmed');
    }

    // Posts
    public function test_admin_can_create_post(): void
    {
        $this->adminJson('POST', '/api/v1/posts', [
            'title'   => 'New Blog Post',
            'slug'    => 'new-blog-post',
            'content' => '<p>Content here</p>',
            'status'  => 'draft',
            'type'    => 'standard',
        ])->assertStatus(201)->assertJsonPath('data.title', 'New Blog Post');
    }

    // Products
    public function test_admin_can_create_product(): void
    {
        $this->adminJson('POST', '/api/v1/products', [
            'name'      => 'Luxury Candle',
            'slug'      => 'luxury-candle',
            'price'     => 49.99,
            'stock'     => 20,
            'is_active' => true,
        ])->assertStatus(201)->assertJsonPath('data.name', 'Luxury Candle');
    }

    // Activities
    public function test_admin_can_update_activity(): void
    {
        $activity = Activity::factory()->create(['slug' => 'spa-wellness', 'type' => 'spa']);

        $this->adminJson('PUT', "/api/v1/activities/{$activity->id}", [
            'title'   => 'Updated Spa',
            'slug'    => 'spa-wellness',
            'type'    => 'spa',
            'content' => '<p>New content</p>',
        ])->assertStatus(200)->assertJsonPath('data.title', 'Updated Spa');
    }

    // Unauthenticated access
    public function test_unauthenticated_request_returns_401(): void
    {
        $this->getJson('/api/v1/rooms')->assertStatus(401);
    }

    // Dashboard stats
    public function test_admin_can_get_dashboard_stats(): void
    {
        $this->adminJson('GET', '/api/v1/dashboard/stats')
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['total_bookings', 'total_orders', 'total_rooms', 'total_revenue']]);
    }
}
```

- [ ] **Step 3: Run tests — expect failures**

```bash
php artisan test tests/Feature/Api/AdminCrudTest.php
```
Expected: FAIL — API controllers don't exist.

- [ ] **Step 4: Create API CRUD controllers**

Create `app/Http/Controllers/Api/RoomController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return RoomResource::collection(Room::with('roomType', 'amenities')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'room_type_id'    => 'required|exists:room_types,id',
            'name'            => 'required|string|max:255',
            'slug'            => 'required|string|unique:rooms,slug',
            'description'     => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests'      => 'required|integer|min:1',
            'size_sqm'        => 'nullable|integer',
            'thumbnail'       => 'nullable|string',
            'is_available'    => 'boolean',
        ]);

        $room = Room::create($data);

        return (new RoomResource($room->load('roomType', 'amenities')))->response()->setStatusCode(201);
    }

    public function show(Room $room): RoomResource
    {
        return new RoomResource($room->load('roomType', 'amenities'));
    }

    public function update(Request $request, Room $room): RoomResource
    {
        $data = $request->validate([
            'room_type_id'    => 'sometimes|exists:room_types,id',
            'name'            => 'sometimes|string|max:255',
            'slug'            => 'sometimes|string|unique:rooms,slug,' . $room->id,
            'description'     => 'nullable|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'max_guests'      => 'sometimes|integer|min:1',
            'size_sqm'        => 'nullable|integer',
            'thumbnail'       => 'nullable|string',
            'is_available'    => 'boolean',
        ]);

        $room->update($data);

        return new RoomResource($room->load('roomType', 'amenities'));
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json(null, 204);
    }
}
```

Create `app/Http/Controllers/Api/BookingController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return BookingResource::collection(
            Booking::with('user', 'room')->latest()->paginate(20)
        );
    }

    public function show(Booking $booking): BookingResource
    {
        return new BookingResource($booking->load('user', 'room', 'services'));
    }

    public function update(Request $request, Booking $booking): BookingResource
    {
        $data = $request->validate([
            'status'         => 'sometimes|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'sometimes|in:unpaid,paid,refunded',
        ]);

        $booking->update($data);

        return new BookingResource($booking->load('user', 'room'));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Use the web booking flow.'], 422);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json(null, 204);
    }
}
```

Create `app/Http/Controllers/Api/PostController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('category', 'author')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|unique:posts,slug',
            'excerpt'          => 'nullable|string',
            'content'          => 'required|string',
            'thumbnail'        => 'nullable|string',
            'type'             => 'in:standard,video,quote',
            'status'           => 'in:draft,published',
            'post_category_id' => 'nullable|exists:post_categories,id',
            'published_at'     => 'nullable|date',
        ]);

        $data['author_id'] = Auth::id();
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        return (new PostResource($post->load('category', 'author')))->response()->setStatusCode(201);
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post->load('category', 'author'));
    }

    public function update(Request $request, Post $post): PostResource
    {
        $data = $request->validate([
            'title'            => 'sometimes|string|max:255',
            'slug'             => 'sometimes|string|unique:posts,slug,' . $post->id,
            'excerpt'          => 'nullable|string',
            'content'          => 'sometimes|string',
            'thumbnail'        => 'nullable|string',
            'type'             => 'in:standard,video,quote',
            'status'           => 'in:draft,published',
            'post_category_id' => 'nullable|exists:post_categories,id',
            'published_at'     => 'nullable|date',
        ]);

        if (($data['status'] ?? null) === 'published' && ! $post->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return new PostResource($post->load('category', 'author'));
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
```

Create `app/Http/Controllers/Api/ProductController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::with('category')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'slug'                 => 'required|string|unique:products,slug',
            'description'          => 'nullable|string',
            'price'                => 'required|numeric|min:0',
            'stock'                => 'required|integer|min:0',
            'thumbnail'            => 'nullable|string',
            'is_active'            => 'boolean',
            'product_category_id'  => 'nullable|exists:product_categories,id',
        ]);

        $product = Product::create($data);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category'));
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $data = $request->validate([
            'name'                => 'sometimes|string|max:255',
            'slug'                => 'sometimes|string|unique:products,slug,' . $product->id,
            'description'         => 'nullable|string',
            'price'               => 'sometimes|numeric|min:0',
            'stock'               => 'sometimes|integer|min:0',
            'thumbnail'           => 'nullable|string',
            'is_active'           => 'boolean',
            'product_category_id' => 'nullable|exists:product_categories,id',
        ]);

        $product->update($data);

        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
```

Create `app/Http/Controllers/Api/OrderController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(Order::with('user')->latest()->paginate(20));
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load('user', 'items.product'));
    }

    public function update(Request $request, Order $order): OrderResource
    {
        $data = $request->validate([
            'status'         => 'sometimes|in:pending,processing,completed,cancelled',
            'payment_status' => 'sometimes|in:unpaid,paid,refunded',
        ]);

        $order->update($data);

        return new OrderResource($order->load('user', 'items.product'));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Use the web checkout flow.'], 422);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
```

Create `app/Http/Controllers/Api/ActivityController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ActivityResource::collection(Activity::orderBy('sort_order')->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'        => 'required|in:spa,golf,hiking,skiing,water_sports,fitness,nature,restaurant,event',
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:activities,slug',
            'content'     => 'nullable|string',
            'thumbnail'   => 'nullable|string',
            'hero_image'  => 'nullable|string',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
        ]);

        $activity = Activity::create($data);

        return (new ActivityResource($activity))->response()->setStatusCode(201);
    }

    public function show(Activity $activity): ActivityResource
    {
        return new ActivityResource($activity);
    }

    public function update(Request $request, Activity $activity): ActivityResource
    {
        $data = $request->validate([
            'type'        => 'sometimes|in:spa,golf,hiking,skiing,water_sports,fitness,nature,restaurant,event',
            'title'       => 'sometimes|string|max:255',
            'slug'        => 'sometimes|string|unique:activities,slug,' . $activity->id,
            'content'     => 'nullable|string',
            'thumbnail'   => 'nullable|string',
            'hero_image'  => 'nullable|string',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
        ]);

        $activity->update($data);

        return new ActivityResource($activity);
    }

    public function destroy(Activity $activity): JsonResponse
    {
        $activity->delete();

        return response()->json(null, 204);
    }
}
```

Create `app/Http/Controllers/Api/DashboardController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_bookings' => Booking::count(),
                'total_orders'   => Order::count(),
                'total_rooms'    => Room::count(),
                'total_revenue'  => Booking::where('payment_status', 'paid')->sum('total_price')
                                  + Order::where('payment_status', 'paid')->sum('total'),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'pending_orders'   => Order::where('status', 'pending')->count(),
            ],
        ]);
    }
}
```

- [ ] **Step 5: Run all API tests**

```bash
php artisan test tests/Feature/Api/
```
Expected: All tests pass.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: add all admin API controllers, resources, and CRUD tests"
```

---

## Task 3: Vue 3 Admin SPA — Project Setup

**Files:**
- Modify: `resources/js/admin/main.js`
- Create: `resources/js/admin/router/index.js`
- Create: `resources/js/admin/stores/auth.js`
- Create: `resources/js/admin/stores/api.js`
- Create: `resources/js/admin/views/LoginView.vue`
- Create: `resources/js/admin/views/DashboardView.vue`
- Create: `resources/js/admin/components/AppLayout.vue`

- [ ] **Step 1: Install Vue 3 and dependencies**

```bash
npm install vue@3 vue-router@4 pinia axios @vitejs/plugin-vue
```

- [ ] **Step 2: Update vite.config.js to use Vue plugin**

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin/main.js',
            ],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    server: {
        watch: { ignored: ['**/storage/framework/views/**'] },
    },
});
```

- [ ] **Step 3: Create Axios API instance**

Create `resources/js/admin/stores/api.js`:

```js
import axios from 'axios'

const api = axios.create({
    baseURL: '/api/v1',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
})

api.interceptors.request.use(config => {
    const token = localStorage.getItem('admin_token')
    if (token) config.headers.Authorization = `Bearer ${token}`
    return config
})

api.interceptors.response.use(
    res => res,
    err => {
        if (err.response?.status === 401) {
            localStorage.removeItem('admin_token')
            window.location.href = '/admin/login'
        }
        return Promise.reject(err)
    }
)

export default api
```

- [ ] **Step 4: Create Pinia auth store**

Create `resources/js/admin/stores/auth.js`:

```js
import { defineStore } from 'pinia'
import api from './api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user:  JSON.parse(localStorage.getItem('admin_user') || 'null'),
        token: localStorage.getItem('admin_token') || null,
    }),

    getters: {
        isAuthenticated: state => !! state.token,
    },

    actions: {
        async login(email, password) {
            const { data } = await api.post('/auth/login', { email, password })
            this.token = data.token
            this.user  = data.user
            localStorage.setItem('admin_token', data.token)
            localStorage.setItem('admin_user', JSON.stringify(data.user))
        },

        async logout() {
            try { await api.post('/auth/logout') } catch {}
            this.token = null
            this.user  = null
            localStorage.removeItem('admin_token')
            localStorage.removeItem('admin_user')
        },
    },
})
```

- [ ] **Step 5: Create Vue Router**

Create `resources/js/admin/router/index.js`:

```js
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import LoginView     from '../views/LoginView.vue'
import DashboardView from '../views/DashboardView.vue'
import RoomsView     from '../views/Rooms/RoomsView.vue'
import RoomFormView  from '../views/Rooms/RoomFormView.vue'
import BookingsView  from '../views/Bookings/BookingsView.vue'
import PostsView     from '../views/Posts/PostsView.vue'
import PostFormView  from '../views/Posts/PostFormView.vue'
import ProductsView  from '../views/Products/ProductsView.vue'
import ProductFormView from '../views/Products/ProductFormView.vue'
import OrdersView    from '../views/Orders/OrdersView.vue'
import ActivitiesView from '../views/Activities/ActivitiesView.vue'

const routes = [
    { path: '/admin/login',         component: LoginView,       meta: { public: true }},
    { path: '/admin',               component: DashboardView    },
    { path: '/admin/rooms',         component: RoomsView        },
    { path: '/admin/rooms/create',  component: RoomFormView     },
    { path: '/admin/rooms/:id/edit',component: RoomFormView     },
    { path: '/admin/bookings',      component: BookingsView     },
    { path: '/admin/posts',         component: PostsView        },
    { path: '/admin/posts/create',  component: PostFormView     },
    { path: '/admin/posts/:id/edit',component: PostFormView     },
    { path: '/admin/products',      component: ProductsView     },
    { path: '/admin/products/create', component: ProductFormView },
    { path: '/admin/products/:id/edit', component: ProductFormView },
    { path: '/admin/orders',        component: OrdersView       },
    { path: '/admin/activities',    component: ActivitiesView   },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to) => {
    const auth = useAuthStore()
    if (! to.meta.public && ! auth.isAuthenticated) {
        return { path: '/admin/login' }
    }
})

export default router
```

- [ ] **Step 6: Create main.js**

Replace `resources/js/admin/main.js`:

```js
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index'
import App from './App.vue'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#admin-app')
```

- [ ] **Step 7: Create App.vue**

Create `resources/js/admin/App.vue`:

```vue
<template>
  <RouterView />
</template>
```

- [ ] **Step 8: Create LoginView.vue**

Create `resources/js/admin/views/LoginView.vue`:

```vue
<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white shadow rounded-lg p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">LuxeStay Admin</h1>
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Email</label>
          <input v-model="email" type="email" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-6">
          <label class="block text-sm font-medium mb-1">Password</label>
          <input v-model="password" type="password" required class="w-full border rounded px-3 py-2" />
        </div>
        <p v-if="error" class="text-red-500 text-sm mb-4">{{ error }}</p>
        <button type="submit" :disabled="loading" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth     = useAuthStore()
const router   = useRouter()
const email    = ref('')
const password = ref('')
const error    = ref('')
const loading  = ref(false)

async function handleLogin() {
  error.value   = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/admin')
  } catch (e) {
    error.value = e.response?.data?.message || 'Login failed.'
  } finally {
    loading.value = false
  }
}
</script>
```

- [ ] **Step 9: Create AppLayout.vue** (sidebar + nav)

Create `resources/js/admin/components/AppLayout.vue`:

```vue
<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="p-4 border-b">
        <h2 class="font-bold text-lg">LuxeStay Admin</h2>
      </div>
      <nav class="flex-1 p-4 space-y-1">
        <RouterLink v-for="link in navLinks" :key="link.to" :to="link.to"
          class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 text-sm"
          active-class="bg-gray-100 font-semibold">
          {{ link.label }}
        </RouterLink>
      </nav>
      <div class="p-4 border-t">
        <p class="text-xs text-gray-500 mb-2">{{ auth.user?.name }}</p>
        <button @click="handleLogout" class="text-sm text-red-500 hover:underline">Logout</button>
      </div>
    </aside>
    <!-- Main content -->
    <main class="flex-1 overflow-auto p-6">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth   = useAuthStore()
const router = useRouter()

const navLinks = [
  { to: '/admin',            label: 'Dashboard'   },
  { to: '/admin/rooms',      label: 'Rooms'       },
  { to: '/admin/bookings',   label: 'Bookings'    },
  { to: '/admin/posts',      label: 'Blog Posts'  },
  { to: '/admin/products',   label: 'Products'    },
  { to: '/admin/orders',     label: 'Orders'      },
  { to: '/admin/activities', label: 'Activities'  },
]

async function handleLogout() {
  await auth.logout()
  router.push('/admin/login')
}
</script>
```

- [ ] **Step 10: Create DashboardView.vue**

Create `resources/js/admin/views/DashboardView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    <div v-if="loading" class="text-gray-500">Loading stats...</div>
    <div v-else class="grid grid-cols-2 gap-4 lg:grid-cols-4">
      <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-lg shadow p-4">
        <p class="text-sm text-gray-500">{{ stat.label }}</p>
        <p class="text-2xl font-bold mt-1">{{ stat.value }}</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import api from '../stores/api'

const loading = ref(true)
const stats   = ref([])

onMounted(async () => {
  try {
    const { data } = await api.get('/dashboard/stats')
    const d = data.data
    stats.value = [
      { label: 'Total Bookings',  value: d.total_bookings  },
      { label: 'Total Orders',    value: d.total_orders    },
      { label: 'Total Rooms',     value: d.total_rooms     },
      { label: 'Revenue ($)',     value: Number(d.total_revenue).toLocaleString() },
    ]
  } finally {
    loading.value = false
  }
})
</script>
```

- [ ] **Step 11: Commit**

```bash
git add -A
git commit -m "feat: add Vue 3 Admin SPA shell (login, dashboard, layout, router, auth store)"
```

---

## Task 4: Admin SPA — All CRUD Views

**Files:** (create all listed files)

- [ ] **Step 1: Create Rooms list and form views**

Create `resources/js/admin/views/Rooms/RoomsView.vue`:

```vue
<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Rooms</h1>
      <RouterLink to="/admin/rooms/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Room</RouterLink>
    </div>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Name</th>
          <th class="text-left p-3">Type</th>
          <th class="text-left p-3">Price/Night</th>
          <th class="text-left p-3">Available</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="room in rooms" :key="room.id" class="border-t">
          <td class="p-3">{{ room.name }}</td>
          <td class="p-3">{{ room.room_type?.name }}</td>
          <td class="p-3">${{ room.price_per_night }}</td>
          <td class="p-3">{{ room.is_available ? 'Yes' : 'No' }}</td>
          <td class="p-3 flex gap-2">
            <RouterLink :to="`/admin/rooms/${room.id}/edit`" class="text-blue-600 hover:underline">Edit</RouterLink>
            <button @click="deleteRoom(room.id)" class="text-red-500 hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const rooms   = ref([])
const loading = ref(true)

onMounted(async () => {
  const { data } = await api.get('/rooms')
  rooms.value   = data.data
  loading.value = false
})

async function deleteRoom(id) {
  if (! confirm('Delete this room?')) return
  await api.delete(`/rooms/${id}`)
  rooms.value = rooms.value.filter(r => r.id !== id)
}
</script>
```

Create `resources/js/admin/views/Rooms/RoomFormView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Room' : 'New Room' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-lg">
      <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input v-model="form.name" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Price / Night ($)</label>
        <input v-model="form.price_per_night" type="number" min="0" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Max Guests</label>
        <input v-model="form.max_guests" type="number" min="1" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" v-model="form.is_available" id="avail" />
        <label for="avail" class="text-sm">Available</label>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm">
        {{ saving ? 'Saving...' : 'Save' }}
      </button>
    </form>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const route  = useRoute()
const router = useRouter()
const isEdit = computed(() => !! route.params.id)
const saving = ref(false)
const error  = ref('')
const form   = ref({ name: '', slug: '', price_per_night: '', max_guests: 2, is_available: true, description: '', room_type_id: null })

onMounted(async () => {
  if (isEdit.value) {
    const { data } = await api.get(`/rooms/${route.params.id}`)
    Object.assign(form.value, data.data)
  }
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/rooms/${route.params.id}`, form.value)
    } else {
      await api.post('/rooms', form.value)
    }
    router.push('/admin/rooms')
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}
</script>
```

- [ ] **Step 2: Create Bookings view**

Create `resources/js/admin/views/Bookings/BookingsView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Bookings</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">#</th>
          <th class="text-left p-3">Guest</th>
          <th class="text-left p-3">Room</th>
          <th class="text-left p-3">Check-in</th>
          <th class="text-left p-3">Check-out</th>
          <th class="text-left p-3">Status</th>
          <th class="text-left p-3">Payment</th>
          <th class="text-left p-3">Total</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="b in bookings" :key="b.id" class="border-t">
          <td class="p-3">{{ b.id }}</td>
          <td class="p-3">{{ b.user?.name }}</td>
          <td class="p-3">{{ b.room?.name }}</td>
          <td class="p-3">{{ b.check_in }}</td>
          <td class="p-3">{{ b.check_out }}</td>
          <td class="p-3">
            <select :value="b.status" @change="updateStatus(b.id, $event.target.value)" class="border rounded px-2 py-1 text-xs">
              <option v-for="s in statuses" :key="s">{{ s }}</option>
            </select>
          </td>
          <td class="p-3">{{ b.payment_status }}</td>
          <td class="p-3">${{ b.total_price }}</td>
          <td class="p-3">
            <button @click="deleteBooking(b.id)" class="text-red-500 text-xs hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const bookings = ref([])
const loading  = ref(true)
const statuses = ['pending', 'confirmed', 'cancelled', 'completed']

onMounted(async () => {
  const { data } = await api.get('/bookings')
  bookings.value = data.data
  loading.value  = false
})

async function updateStatus(id, status) {
  await api.put(`/bookings/${id}`, { status })
}

async function deleteBooking(id) {
  if (! confirm('Delete booking?')) return
  await api.delete(`/bookings/${id}`)
  bookings.value = bookings.value.filter(b => b.id !== id)
}
</script>
```

- [ ] **Step 3: Create Posts views**

Create `resources/js/admin/views/Posts/PostsView.vue`:

```vue
<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Blog Posts</h1>
      <RouterLink to="/admin/posts/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Post</RouterLink>
    </div>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Title</th>
          <th class="text-left p-3">Category</th>
          <th class="text-left p-3">Status</th>
          <th class="text-left p-3">Published</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="post in posts" :key="post.id" class="border-t">
          <td class="p-3">{{ post.title }}</td>
          <td class="p-3">{{ post.category?.name }}</td>
          <td class="p-3">{{ post.status }}</td>
          <td class="p-3">{{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '—' }}</td>
          <td class="p-3 flex gap-2">
            <RouterLink :to="`/admin/posts/${post.id}/edit`" class="text-blue-600 hover:underline text-xs">Edit</RouterLink>
            <button @click="deletePost(post.id)" class="text-red-500 hover:underline text-xs">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const posts   = ref([])
const loading = ref(true)

onMounted(async () => {
  const { data } = await api.get('/posts')
  posts.value   = data.data
  loading.value = false
})

async function deletePost(id) {
  if (! confirm('Delete post?')) return
  await api.delete(`/posts/${id}`)
  posts.value = posts.value.filter(p => p.id !== id)
}
</script>
```

Create `resources/js/admin/views/Posts/PostFormView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Post' : 'New Post' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
      <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input v-model="form.title" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Excerpt</label>
        <textarea v-model="form.excerpt" rows="2" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Content (HTML)</label>
        <textarea v-model="form.content" rows="10" required class="w-full border rounded px-3 py-2 text-sm font-mono"></textarea>
      </div>
      <div class="flex gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">Status</label>
          <select v-model="form.status" class="w-full border rounded px-3 py-2 text-sm">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">Type</label>
          <select v-model="form.type" class="w-full border rounded px-3 py-2 text-sm">
            <option value="standard">Standard</option>
            <option value="video">Video</option>
            <option value="quote">Quote</option>
          </select>
        </div>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm">
        {{ saving ? 'Saving...' : 'Save' }}
      </button>
    </form>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const route  = useRoute()
const router = useRouter()
const isEdit = computed(() => !! route.params.id)
const saving = ref(false)
const error  = ref('')
const form   = ref({ title: '', slug: '', excerpt: '', content: '', status: 'draft', type: 'standard' })

onMounted(async () => {
  if (isEdit.value) {
    const { data } = await api.get(`/posts/${route.params.id}`)
    Object.assign(form.value, data.data)
  }
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/posts/${route.params.id}`, form.value)
    } else {
      await api.post('/posts', form.value)
    }
    router.push('/admin/posts')
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}
</script>
```

- [ ] **Step 4: Create Products views** (follow exact same pattern as Posts)

Create `resources/js/admin/views/Products/ProductsView.vue`:

```vue
<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Products</h1>
      <RouterLink to="/admin/products/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Product</RouterLink>
    </div>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Name</th>
          <th class="text-left p-3">Price</th>
          <th class="text-left p-3">Stock</th>
          <th class="text-left p-3">Active</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products" :key="p.id" class="border-t">
          <td class="p-3">{{ p.name }}</td>
          <td class="p-3">${{ p.price }}</td>
          <td class="p-3">{{ p.stock }}</td>
          <td class="p-3">{{ p.is_active ? 'Yes' : 'No' }}</td>
          <td class="p-3 flex gap-2">
            <RouterLink :to="`/admin/products/${p.id}/edit`" class="text-blue-600 hover:underline text-xs">Edit</RouterLink>
            <button @click="deleteProduct(p.id)" class="text-red-500 hover:underline text-xs">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const products = ref([])
const loading  = ref(true)

onMounted(async () => {
  const { data } = await api.get('/products')
  products.value = data.data
  loading.value  = false
})

async function deleteProduct(id) {
  if (! confirm('Delete product?')) return
  await api.delete(`/products/${id}`)
  products.value = products.value.filter(p => p.id !== id)
}
</script>
```

Create `resources/js/admin/views/Products/ProductFormView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Product' : 'New Product' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-lg">
      <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input v-model="form.name" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Price ($)</label>
        <input v-model="form.price" type="number" min="0" step="0.01" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Stock</label>
        <input v-model="form.stock" type="number" min="0" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" v-model="form.is_active" id="active" />
        <label for="active" class="text-sm">Active</label>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm">
        {{ saving ? 'Saving...' : 'Save' }}
      </button>
    </form>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const route  = useRoute()
const router = useRouter()
const isEdit = computed(() => !! route.params.id)
const saving = ref(false)
const error  = ref('')
const form   = ref({ name: '', slug: '', price: '', stock: 0, description: '', is_active: true })

onMounted(async () => {
  if (isEdit.value) {
    const { data } = await api.get(`/products/${route.params.id}`)
    Object.assign(form.value, data.data)
  }
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/products/${route.params.id}`, form.value)
    } else {
      await api.post('/products', form.value)
    }
    router.push('/admin/products')
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}
</script>
```

- [ ] **Step 5: Create Orders view**

Create `resources/js/admin/views/Orders/OrdersView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Orders</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">#</th>
          <th class="text-left p-3">Customer</th>
          <th class="text-left p-3">Total</th>
          <th class="text-left p-3">Status</th>
          <th class="text-left p-3">Payment</th>
          <th class="text-left p-3">Date</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="o in orders" :key="o.id" class="border-t">
          <td class="p-3">{{ o.id }}</td>
          <td class="p-3">{{ o.user?.name }}</td>
          <td class="p-3">${{ o.total }}</td>
          <td class="p-3">
            <select :value="o.status" @change="updateStatus(o.id, $event.target.value)" class="border rounded px-2 py-1 text-xs">
              <option v-for="s in statuses" :key="s">{{ s }}</option>
            </select>
          </td>
          <td class="p-3">{{ o.payment_status }}</td>
          <td class="p-3">{{ new Date(o.created_at).toLocaleDateString() }}</td>
          <td class="p-3">
            <button @click="deleteOrder(o.id)" class="text-red-500 text-xs hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const orders   = ref([])
const loading  = ref(true)
const statuses = ['pending', 'processing', 'completed', 'cancelled']

onMounted(async () => {
  const { data } = await api.get('/orders')
  orders.value  = data.data
  loading.value = false
})

async function updateStatus(id, status) {
  await api.put(`/orders/${id}`, { status })
}

async function deleteOrder(id) {
  if (! confirm('Delete order?')) return
  await api.delete(`/orders/${id}`)
  orders.value = orders.value.filter(o => o.id !== id)
}
</script>
```

- [ ] **Step 6: Create Activities view**

Create `resources/js/admin/views/Activities/ActivitiesView.vue`:

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Activities</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else class="grid gap-4">
      <div v-for="a in activities" :key="a.id" class="bg-white rounded shadow p-4 flex justify-between items-start">
        <div>
          <h3 class="font-semibold">{{ a.title }}</h3>
          <p class="text-sm text-gray-500">{{ a.type }} · slug: {{ a.slug }}</p>
        </div>
        <div class="flex gap-2">
          <button @click="editActivity(a)" class="text-blue-600 text-sm hover:underline">Edit</button>
        </div>
      </div>
    </div>

    <!-- Inline edit modal -->
    <div v-if="editing" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-lg">
        <h2 class="text-lg font-bold mb-4">Edit: {{ editing.title }}</h2>
        <div class="space-y-3">
          <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input v-model="editing.title" class="w-full border rounded px-3 py-2 text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Content (HTML)</label>
            <textarea v-model="editing.content" rows="6" class="w-full border rounded px-3 py-2 text-sm font-mono"></textarea>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" v-model="editing.is_featured" id="feat" />
            <label for="feat" class="text-sm">Featured</label>
          </div>
        </div>
        <div class="flex gap-3 mt-4">
          <button @click="saveActivity" :disabled="saving" class="bg-black text-white px-4 py-2 rounded text-sm">
            {{ saving ? 'Saving...' : 'Save' }}
          </button>
          <button @click="editing = null" class="text-gray-600 text-sm hover:underline">Cancel</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const activities = ref([])
const loading    = ref(true)
const editing    = ref(null)
const saving     = ref(false)

onMounted(async () => {
  const { data } = await api.get('/activities')
  activities.value = data.data
  loading.value    = false
})

function editActivity(a) {
  editing.value = { ...a }
}

async function saveActivity() {
  saving.value = true
  await api.put(`/activities/${editing.value.id}`, editing.value)
  const idx = activities.value.findIndex(a => a.id === editing.value.id)
  if (idx !== -1) activities.value[idx] = { ...editing.value }
  editing.value = null
  saving.value  = false
}
</script>
```

- [ ] **Step 7: Build admin SPA**

```bash
npm run build
```
Expected: Build completes. `public/build/` contains admin JS bundle.

- [ ] **Step 8: Test Admin SPA in browser**

Visit `https://luxestay.ddev.site/admin` → redirects to `/admin/login`.
Login with admin credentials (from seeder: `admin@luxestay.com` / `password`).
Expected: Dashboard loads with stats. Sidebar shows all nav links.

Navigate to each section: Rooms, Bookings, Posts, Products, Orders, Activities.
Expected: Each section loads data from API.

Create a new room, save. Expected: appears in list.
Edit a booking status. Expected: updates immediately.

- [ ] **Step 9: Run final test suite**

```bash
php artisan test
```
Expected: All tests pass.

- [ ] **Step 10: Final commit**

```bash
git add -A
git commit -m "feat: complete Plan 4 (Admin SPA — all CRUD views, API auth, dashboard)"
```

---

## Plan 4 Complete ✓ — Project Complete ✓

**What was built:**
- Laravel Sanctum API auth for admin (token-based, admin-only)
- Full REST API for all modules (rooms, bookings, posts, products, orders, activities)
- API Resources for consistent JSON shape
- DashboardController with aggregated stats
- Vue 3 Admin SPA: login, dashboard, rooms CRUD, bookings management, posts CRUD, products CRUD, orders management, activities editing
- Vue Router with auth guards
- Pinia auth store with localStorage persistence
- Axios API instance with token injection and 401 auto-redirect

**Full system is now complete across all 4 plans.**
