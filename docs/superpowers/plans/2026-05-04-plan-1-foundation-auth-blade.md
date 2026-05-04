# LuxeStay Plan 1: Foundation + Auth + Blade Layout + Frontend Pages

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Set up the Laravel foundation: all database migrations, Eloquent models, Breeze auth, migrate FE static assets to `public/`, build the Blade layout with real header/footer, and wire all 40+ static HTML pages to routed Blade views backed by controllers.

**Architecture:** Laravel 13 web routes serve server-rendered Blade views. Static HTML content is lifted verbatim into Blade views that extend a shared `layouts/app.blade.php`. Controllers query models and inject data; Blade loops replace hardcoded HTML repetition. No API layer or Admin SPA in this plan.

**Tech Stack:** Laravel 13, PHP 8.3, MariaDB (DDEV), Laravel Breeze (Blade), PHPUnit, Vite

**Working directory:** `/Users/mac/Desktop/Project/luxestay`
**FE source:** `FE/luxestay/` (copy-only, never delete)

---

## Task 1: Install Laravel Breeze (Blade Stack)

**Files:**
- Modify: `composer.json` (Breeze added)
- Modify: `routes/web.php`, `routes/auth.php`
- Create: `resources/views/auth/*` (Breeze scaffolding)
- Create: `app/Http/Controllers/Auth/*` (Breeze scaffolding)
- Modify: `package.json` (Breeze adds Tailwind for auth pages only)

- [ ] **Step 1: Require Breeze**

```bash
composer require laravel/breeze --dev
```
Expected: Breeze downloaded, no errors.

- [ ] **Step 2: Install Breeze Blade stack**

```bash
php artisan breeze:install blade
```
Expected output contains: `Breeze scaffolding installed successfully.`

- [ ] **Step 3: Install Node deps and build**

```bash
npm install && npm run build
```
Expected: `public/build/` directory created, no errors.

- [ ] **Step 4: Run existing migrations**

```bash
php artisan migrate
```
Expected: `users`, `sessions`, `cache`, `jobs`, `password_reset_tokens` tables created.

- [ ] **Step 5: Verify Breeze routes exist**

```bash
php artisan route:list | grep -E "login|register|dashboard"
```
Expected: login (GET/POST), register (GET/POST), dashboard (GET) listed.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: install Laravel Breeze (Blade stack)"
```

---

## Task 2: Add `role` Column to Users + Admin Middleware

**Files:**
- Create: `database/migrations/YYYY_MM_DD_add_role_to_users_table.php`
- Modify: `app/Models/User.php`
- Create: `app/Http/Middleware/EnsureUserIsAdmin.php`
- Create: `tests/Feature/Middleware/EnsureUserIsAdminTest.php`

- [ ] **Step 1: Write failing test**

Create `tests/Feature/Middleware/EnsureUserIsAdminTest.php`:

```php
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
```

- [ ] **Step 2: Run test to verify it fails**

```bash
php artisan test tests/Feature/Middleware/EnsureUserIsAdminTest.php
```
Expected: FAIL — `role` column does not exist.

- [ ] **Step 3: Create migration**

```bash
php artisan make:migration add_role_to_users_table --table=users
```

Edit the generated file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
```

- [ ] **Step 4: Run migration**

```bash
php artisan migrate
```

- [ ] **Step 5: Update User model**

Edit `app/Models/User.php` — add `role` to `$fillable` and add `isAdmin()` helper:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
];

public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

- [ ] **Step 6: Create middleware**

```bash
php artisan make:middleware EnsureUserIsAdmin
```

Edit `app/Http/Middleware/EnsureUserIsAdmin.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            return $request->user()
                ? redirect('/')->with('error', 'Unauthorized.')
                : redirect('/login');
        }

        return $next($request);
    }
}
```

- [ ] **Step 7: Register middleware alias in `bootstrap/app.php`**

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ]);
})
```

- [ ] **Step 8: Add stub admin route for testing** (will be replaced by Vue SPA route in Plan 4)

In `routes/web.php` add:

```php
Route::get('/admin', fn () => response('admin ok'))->middleware(['auth', 'admin']);
```

- [ ] **Step 9: Update User factory to support role**

In `database/factories/UserFactory.php` confirm `role` exists in `definition()`:

```php
public function definition(): array
{
    return [
        'name'              => fake()->name(),
        'email'             => fake()->unique()->safeEmail(),
        'email_verified_at' => now(),
        'password'          => static::$password ??= Hash::make('password'),
        'remember_token'    => Str::random(10),
        'role'              => 'user',
    ];
}

public function admin(): static
{
    return $this->state(fn (array $attributes) => ['role' => 'admin']);
}
```

- [ ] **Step 10: Run tests**

```bash
php artisan test tests/Feature/Middleware/EnsureUserIsAdminTest.php
```
Expected: 3 tests pass.

- [ ] **Step 11: Commit**

```bash
git add -A
git commit -m "feat: add user role column and EnsureUserIsAdmin middleware"
```

---

## Task 3: Migrations — Rooms Tables

**Files:**
- Create: `database/migrations/*_create_room_types_table.php`
- Create: `database/migrations/*_create_rooms_table.php`
- Create: `database/migrations/*_create_amenities_table.php`
- Create: `database/migrations/*_create_room_amenity_table.php`

- [ ] **Step 1: Create migrations**

```bash
php artisan make:migration create_room_types_table
php artisan make:migration create_rooms_table
php artisan make:migration create_amenities_table
php artisan make:migration create_room_amenity_table
```

- [ ] **Step 2: Fill room_types migration**

```php
public function up(): void
{
    Schema::create('room_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Step 3: Fill rooms migration**

```php
public function up(): void
{
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price_per_night', 10, 2);
        $table->unsignedTinyInteger('max_guests')->default(2);
        $table->unsignedSmallInteger('size_sqm')->nullable();
        $table->string('thumbnail')->nullable();
        $table->json('gallery')->nullable();
        $table->boolean('is_available')->default(true);
        $table->timestamps();
    });
}
```

- [ ] **Step 4: Fill amenities migration**

```php
public function up(): void
{
    Schema::create('amenities', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('icon')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Step 5: Fill room_amenity pivot migration**

```php
public function up(): void
{
    Schema::create('room_amenity', function (Blueprint $table) {
        $table->foreignId('room_id')->constrained()->cascadeOnDelete();
        $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
        $table->primary(['room_id', 'amenity_id']);
    });
}
```

- [ ] **Step 6: Run migrations**

```bash
php artisan migrate
```
Expected: 4 new tables created.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "feat: add room_types, rooms, amenities, room_amenity migrations"
```

---

## Task 4: Migrations — Bookings, Blog, Shop, Activities, Payments

**Files:**
- Create: 9 migration files (see steps)

- [ ] **Step 1: Create all migrations**

```bash
php artisan make:migration create_bookings_table
php artisan make:migration create_booking_services_table
php artisan make:migration create_post_categories_table
php artisan make:migration create_posts_table
php artisan make:migration create_product_categories_table
php artisan make:migration create_products_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_activities_table
php artisan make:migration create_payment_transactions_table
```

- [ ] **Step 2: Fill bookings migration**

```php
public function up(): void
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('room_id')->constrained()->cascadeOnDelete();
        $table->date('check_in');
        $table->date('check_out');
        $table->unsignedTinyInteger('guests')->default(1);
        $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
        $table->decimal('total_price', 10, 2);
        $table->string('vnpay_txn_ref')->nullable()->unique();
        $table->text('special_requests')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Step 3: Fill booking_services migration**

```php
public function up(): void
{
    Schema::create('booking_services', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->string('service_name');
        $table->unsignedTinyInteger('quantity')->default(1);
        $table->decimal('unit_price', 10, 2);
        $table->timestamps();
    });
}
```

- [ ] **Step 4: Fill post_categories migration**

```php
public function up(): void
{
    Schema::create('post_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->timestamps();
    });
}
```

- [ ] **Step 5: Fill posts migration**

```php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('post_category_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('excerpt')->nullable();
        $table->longText('content');
        $table->string('thumbnail')->nullable();
        $table->enum('type', ['standard', 'video', 'quote'])->default('standard');
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->timestamp('published_at')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Step 6: Fill product_categories migration**

```php
public function up(): void
{
    Schema::create('product_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->timestamps();
    });
}
```

- [ ] **Step 7: Fill products migration**

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->unsignedInteger('stock')->default(0);
        $table->string('thumbnail')->nullable();
        $table->json('gallery')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
```

- [ ] **Step 8: Fill orders migration**

```php
public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
        $table->decimal('subtotal', 10, 2);
        $table->decimal('shipping_fee', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        $table->string('vnpay_txn_ref')->nullable()->unique();
        $table->json('shipping_address');
        $table->timestamps();
    });
}
```

- [ ] **Step 9: Fill order_items migration**

```php
public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('quantity');
        $table->decimal('unit_price', 10, 2);
        $table->timestamps();
    });
}
```

- [ ] **Step 10: Fill activities migration**

```php
public function up(): void
{
    Schema::create('activities', function (Blueprint $table) {
        $table->id();
        $table->enum('type', [
            'spa', 'golf', 'hiking', 'skiing', 'water_sports',
            'fitness', 'nature', 'restaurant', 'event',
        ]);
        $table->string('title');
        $table->string('slug')->unique();
        $table->longText('content')->nullable();
        $table->string('thumbnail')->nullable();
        $table->string('hero_image')->nullable();
        $table->boolean('is_featured')->default(false);
        $table->unsignedSmallInteger('sort_order')->default(0);
        $table->timestamps();
    });
}
```

- [ ] **Step 11: Fill payment_transactions migration**

```php
public function up(): void
{
    Schema::create('payment_transactions', function (Blueprint $table) {
        $table->id();
        $table->morphs('payable');
        $table->decimal('amount', 10, 2);
        $table->string('gateway')->default('vnpay');
        $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
        $table->string('gateway_ref')->nullable();
        $table->json('gateway_response')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Step 12: Run all migrations**

```bash
php artisan migrate
```
Expected: All 10 new tables created with no errors.

- [ ] **Step 13: Commit**

```bash
git add -A
git commit -m "feat: add all domain migrations (bookings, blog, shop, activities, payments)"
```

---

## Task 5: Eloquent Models

**Files:**
- Create: `app/Models/RoomType.php`
- Create: `app/Models/Room.php`
- Create: `app/Models/Amenity.php`
- Create: `app/Models/Booking.php`
- Create: `app/Models/BookingService.php`
- Create: `app/Models/PostCategory.php`
- Create: `app/Models/Post.php`
- Create: `app/Models/ProductCategory.php`
- Create: `app/Models/Product.php`
- Create: `app/Models/Order.php`
- Create: `app/Models/OrderItem.php`
- Create: `app/Models/Activity.php`
- Create: `app/Models/PaymentTransaction.php`
- Modify: `app/Models/User.php`

- [ ] **Step 1: Create RoomType model**

```bash
php artisan make:model RoomType
```

`app/Models/RoomType.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
```

- [ ] **Step 2: Create Room model**

```bash
php artisan make:model Room
```

`app/Models/Room.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_type_id', 'name', 'slug', 'description',
        'price_per_night', 'max_guests', 'size_sqm',
        'thumbnail', 'gallery', 'is_available',
    ];

    protected $casts = [
        'gallery'      => 'array',
        'is_available' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
```

- [ ] **Step 3: Create Amenity model**

```bash
php artisan make:model Amenity
```

`app/Models/Amenity.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    protected $fillable = ['name', 'icon'];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class);
    }
}
```

- [ ] **Step 4: Create Booking model**

```bash
php artisan make:model Booking
```

`app/Models/Booking.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'room_id', 'check_in', 'check_out',
        'guests', 'status', 'payment_status',
        'total_price', 'vnpay_txn_ref', 'special_requests',
    ];

    protected $casts = [
        'check_in'    => 'date',
        'check_out'   => 'date',
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(PaymentTransaction::class, 'payable');
    }
}
```

- [ ] **Step 5: Create BookingService model**

```bash
php artisan make:model BookingService
```

`app/Models/BookingService.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    protected $fillable = ['booking_id', 'service_name', 'quantity', 'unit_price'];

    protected $casts = ['unit_price' => 'decimal:2'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
```

- [ ] **Step 6: Create PostCategory and Post models**

```bash
php artisan make:model PostCategory
php artisan make:model Post
```

`app/Models/PostCategory.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
```

`app/Models/Post.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'post_category_id', 'author_id', 'title', 'slug',
        'excerpt', 'content', 'thumbnail', 'type', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
```

- [ ] **Step 7: Create ProductCategory and Product models**

```bash
php artisan make:model ProductCategory
php artisan make:model Product
```

`app/Models/ProductCategory.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

`app/Models/Product.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'product_category_id', 'name', 'slug', 'description',
        'price', 'stock', 'thumbnail', 'gallery', 'is_active',
    ];

    protected $casts = [
        'gallery'   => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
```

- [ ] **Step 8: Create Order, OrderItem models**

```bash
php artisan make:model Order
php artisan make:model OrderItem
```

`app/Models/Order.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'payment_status',
        'subtotal', 'shipping_fee', 'total',
        'vnpay_txn_ref', 'shipping_address',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'subtotal'         => 'decimal:2',
        'shipping_fee'     => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(PaymentTransaction::class, 'payable');
    }
}
```

`app/Models/OrderItem.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price'];

    protected $casts = ['unit_price' => 'decimal:2'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
```

- [ ] **Step 9: Create Activity model**

```bash
php artisan make:model Activity
```

`app/Models/Activity.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'type', 'title', 'slug', 'content',
        'thumbnail', 'hero_image', 'is_featured', 'sort_order',
    ];

    protected $casts = ['is_featured' => 'boolean'];
}
```

- [ ] **Step 10: Create PaymentTransaction model**

```bash
php artisan make:model PaymentTransaction
```

`app/Models/PaymentTransaction.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'payable_type', 'payable_id', 'amount',
        'gateway', 'status', 'gateway_ref', 'gateway_response',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
```

- [ ] **Step 11: Update User model with bookings/orders relations**

Add to `app/Models/User.php`:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function bookings(): HasMany
{
    return $this->hasMany(Booking::class);
}

public function orders(): HasMany
{
    return $this->hasMany(Order::class);
}

public function posts(): HasMany
{
    return $this->hasMany(Post::class, 'author_id');
}
```

- [ ] **Step 12: Commit**

```bash
git add -A
git commit -m "feat: add all Eloquent models with relationships"
```

---

## Task 6: Migrate FE Static Assets to `public/`

**Files:**
- Create: `public/css/` (copy from `FE/luxestay/css/`)
- Create: `public/js/` (copy from `FE/luxestay/js/`)
- Create: `public/images/` (copy from `FE/luxestay/images/`)
- Create: `public/webfonts/` (copy from `FE/luxestay/webfonts/`)

- [ ] **Step 1: Copy all FE assets**

```bash
cp -r FE/luxestay/css public/css
cp -r FE/luxestay/js public/js
cp -r FE/luxestay/images public/images
cp -r FE/luxestay/webfonts public/webfonts
```

- [ ] **Step 2: Verify**

```bash
ls public/css public/js public/images | head -10
```
Expected: `bootstrap.min.css`, `custom.css`, `jquery-3.7.1.min.js`, etc. listed.

- [ ] **Step 3: Commit**

```bash
git add -A
git commit -m "feat: migrate FE static assets to public/"
```

---

## Task 7: Blade Layout — `layouts/app.blade.php`

**Files:**
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/components/header.blade.php`
- Create: `resources/views/components/footer.blade.php`

The layout wraps every frontend page. The header HTML is lines 47–249 of `FE/luxestay/index.html`. The footer is lines 1652–1869. All `href="*.html"` become `route()` calls. All `src="images/..."` become `asset('images/...')`.

- [ ] **Step 1: Create header component**

Create `resources/views/components/header.blade.php`:

```blade
<header id="sisf-page-header" class="sisf-main-header">
   <div class="header-top">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="header_col">
                  <div class="sisf-widget-holder sisf--left">
                     <div class="sisf-icon-list-item sisf-icon--icon-pack">
                        <a href="tel:6176232338" target="_self">
                        <span class="sisf-e-title-inner">
                        <span class="sisf-e-title-text"><span class="sisf-icon-simple-line-icons icon-call-out sisf-icon sisf-e me-1"></span> Call us: (617) 623-2338</span>
                        </span>
                        </a>
                     </div>
                  </div>
                  <div class="header-center-icons">
                     <a class="me-3"><i class="fa-brands fa-facebook"></i></a>
                     <a class="me-3"><i class="fa-brands fa-instagram"></i></a>
                     <a class="me-3"><i class="fa-brands fa-linkedin"></i></a>
                     <a class="me-3"><i class="fa-brands fa-x-twitter"></i></a>
                  </div>
                  <div class="mail-us">
                     <a href="mailto:info@luxestay.com"><span class="sisf-e-title-text"><span class="sisf-icon-simple-line-icons icon-envelope-open sisf-icon sisf-e me-2"></span> Mail us : info@luxestay.com</span></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="sisf-page-header-inner" class="sisf-skin--light sisf-page position-relative">
      <div class="container-fluid">
         <div class="sisf-divided-header-left-wrapper d-flex align-items-center justify-content-between">
            <div class="sisf-icon-list-item">
               <div class="input-group">
                  <span class="pt-1 text-white border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                  <input type="text" class="form-control text-white shadow-none border-0" placeholder="Search...">
               </div>
            </div>
            <nav class="navbar navbar-expand-lg">
               <div class="collapse navbar-collapse main-menu">
                  <div class="nav-menu-wrapper">
                     <ul class="navbar-nav mr-auto" id="menu">
                        <li class="nav-item submenu mega-menu-item">
                           <a class="nav-link" href="{{ route('home') }}">Home<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu mega-menu">
                              <li class="nav-item">
                                 <a href="{{ route('home') }}" class="page-link"><img src="{{ asset('images/mega-menu_home_page1.png') }}" class="w-100" alt="LuxeStay"></a>
                                 <a class="nav-link" href="{{ route('home') }}">Mountain Home Page</a>
                              </li>
                           </ul>
                        </li>
                        <li class="nav-item submenu">
                           <a class="nav-link" href="{{ route('rooms.index') }}">Rooms<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('rooms.suites') }}">Rooms & Suits</a></li>
                           </ul>
                        </li>
                        <li class="nav-item submenu">
                           <a class="nav-link" href="#">Pages<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('offers') }}">Offers &amp; Promotions</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'restaurant') }}">Restaurant</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                           </ul>
                        </li>
                        <li class="nav-item submenu mega-menu-item">
                           <a class="nav-link" href="{{ route('shop.index') }}">Shop<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu mega-menu mega-menu1">
                              <li class="nav-item">
                                 <h5 class="sisf-menu-title">Shop Related Pages</h5>
                                 <a class="nav-link" href="{{ route('shop.index') }}">Shop Main</a>
                                 <a class="nav-link" href="{{ route('shop.show', ':slug') }}">Shop Single</a>
                                 <a class="nav-link" href="{{ route('cart.index') }}">Cart</a>
                                 <a class="nav-link" href="{{ route('checkout.index') }}">Checkout</a>
                                 <a class="nav-link" href="{{ route('account.index') }}">My account</a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </div>
               </div>
            </nav>
         </div>
         <!-- Logo -->
         <a class="navbar-brand sisf-header-logo-link mobile-none" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="LuxeStay">
         </a>
         <a class="navbar-brand sisf-header-logo-link mobile-block" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="LuxeStay">
         </a>
         <div class="sisf-divided-header-right-wrapper justify-content-between d-flex align-items-center">
            <nav class="navbar navbar-expand-lg">
               <div class="collapse navbar-collapse main-menu">
                  <div class="nav-menu-wrapper">
                     <ul class="navbar-nav mr-auto" id="menu2">
                        <li class="nav-item submenu">
                           <a class="nav-link" href="#">Activities<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'event-wedding') }}">Event &amp; Wedding</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'fitness-and-wellness') }}">Fitness and Wellness</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'golf-courses') }}">Golf Courses</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'hiking-and-trekking') }}">Hiking and Trekking</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'leisure-and-entertainment') }}">Leisure and Entertainment</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'nature-and-exploration') }}">Nature and Exploration</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'ski-snowboarding') }}">Ski &amp; Snowboarding</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'spa-wellness') }}">Spa &amp; Wellness</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'unique-experiences') }}">Unique Experiences</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'water-sports') }}">Water Sports</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('activities.show', 'winter-hiking') }}">Winter Hiking</a></li>
                           </ul>
                        </li>
                        <li class="nav-item submenu">
                           <a class="nav-link" href="{{ route('blog.index') }}">Blogs<i class="fas fa-chevron-down custom-toggle-icon px-3"></i></a>
                           <ul class="sub-menu">
                              <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('blog.show', ':slug') }}">Blog Single</a></li>
                           </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}">Landing</a></li>
                     </ul>
                  </div>
               </div>
            </nav>
            <div class="sisf-widget-holder sisf--two d-flex align-items-center">
               <div class="header-btn">
                  @auth
                     <a href="{{ route('account.index') }}" class="sisf-button sisf-layout--outlined">My Account</a>
                  @else
                     <a href="{{ route('login') }}" class="sisf-button sisf-layout--outlined">Book Now</a>
                  @endauth
               </div>
            </div>
         </div>
         <div class="navbar-toggle"></div>
         <div class="responsive-menu"></div>
      </div>
   </div>
</header>
```

- [ ] **Step 2: Create footer component**

Create `resources/views/components/footer.blade.php`:

```blade
<footer class="main-footer">
   <div class="sisf-page-footer-inner-area">
      <div class="sisf-page-footer-top-area">
         <div class="container">
            <div class="row align-items-center">
               <div class="col-md-7">
                  <div class="footer-top-heading">
                     <h3>LET'S START YOUR JOURNEY WITH<br> LUXESTAY</h3>
                  </div>
               </div>
               <div class="col-md-3">
                  <p class="sisf-e-title mb-0">
                     <a href="tel:+150262517802">
                     <span class="sisf-icon-simple-line-icons icon-screen-smartphone sisf-icon sisf-e"></span>
                     +1 502 6251 7802</a>
                  </p>
                  <p class="sisf-e-title mb-0">
                     <a href="mailto:info@luxestay.com">
                     <span class="sisf-icon-simple-line-icons icon-envelope-open sisf-icon sisf-e"></span>
                     info@luxestay.com</a>
                  </p>
               </div>
               <div class="col-md-2 d-flex justify-content-end">
                  <div class="contact-button">
                     <a href="{{ route('contact') }}" class="sisf-m footer-btn">
                     <span class="text-uppercase">Contact Us</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="sisf-page-footer-middle-area comman-bg">
         <div class="container">
            <div class="row">
               <div class="col-md-7 ms-auto me-auto">
                  <h5 class="subscription text-center">Subscribe now for updates and exclusive offers!</h5>
                  <div class="subscription-container text-center wow fadeInUp">
                     <form class="d-flex justify-content-center align-items-center">
                        <input type="email" class="form-control" placeholder="Your email">
                        <button type="submit" class="btn btn-link ms-2">SUBSCRIBE</button>
                     </form>
                  </div>
               </div>
               <div class="footer-social-links d-flex align-items-center justify-content-center wow fadeInUp">
                  <ul class="mb-0">
                     <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                     <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                     <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                     <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                  </ul>
               </div>
               <div class="menu-footer-menu-container d-flex align-items-center justify-content-center wow fadeInUp">
                  <ul class="menu list-unstyled p-0 mb-0 d-flex align-items-center">
                     <li class="menu-item text-uppercase"><a href="{{ route('home') }}">Home</a></li>
                     <li class="menu-item text-uppercase"><a href="{{ route('about') }}">About Us</a></li>
                     <li class="menu-item text-uppercase"><a href="{{ route('rooms.index') }}">Rooms</a></li>
                     <li class="menu-item text-uppercase"><a href="{{ route('shop.index') }}">Shop</a></li>
                     <li class="menu-item text-uppercase"><a href="{{ route('blog.index') }}">Blogs</a></li>
                     <li class="menu-item text-uppercase"><a href="{{ route('contact') }}">Contacts</a></li>
                  </ul>
               </div>
               <div class="gallery-container gallery-items">
                  @foreach(['footer_img1','footer_img2','footer_img3','footer_img4','footer_img5'] as $img)
                  <div class="gallery-item single-center">
                     <div class="wow fadeInUp">
                        <a href="{{ asset('images/'.$img.'.png') }}">
                           <figure><img src="{{ asset('images/'.$img.'.png') }}" alt="LuxeStay"></figure>
                        </a>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
      <div class="sisf-page-footer-bottom-area">
         <div class="container">
            <div class="footer-copyright">
               <div class="row align-items-center">
                  <div class="col-lg-6 col-md-6">
                     <div class="footer-copyright-text wow fadeInUp">
                        <p class="text-black">&copy; {{ date('Y') }} Luxestay. All Rights Reserved.</p>
                     </div>
                  </div>
                  <div class="col-lg-6 col-md-6">
                     <div class="footer-privacy-policy wow fadeInUp">
                        <ul><li><a href="#" class="text-black">Privacy Policy</a></li></ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</footer>
<!-- Back to Top -->
<div class="back-to-top-button">
   <button class="back-to-top" id="backToTop">
   <span class="mt-1"><span class="icon-arrow-up"></span></span>
   </button>
</div>
<!-- JS -->
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/validator.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/SmoothScroll.js') }}"></script>
<script src="{{ asset('js/parallaxie.js') }}"></script>
<script src="{{ asset('js/gsap.min.js') }}"></script>
<script src="{{ asset('js/magiccursor.js') }}"></script>
<script src="{{ asset('js/SplitText.js') }}"></script>
<script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('js/jquery.mb.YTPlayer.min.js') }}"></script>
<script src="{{ asset('js/plyr.js') }}"></script>
<script src="{{ asset('js/wow.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/ripple.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('js/function.js') }}"></script>
@stack('scripts')
```

- [ ] **Step 3: Create `layouts/app.blade.php`**

Create `resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="@yield('meta_description', 'LuxeStay – Luxury Hotel & Resort Booking')">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>@yield('title', 'LuxeStay – Luxury Hotel & Resort Booking')</title>
   <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&display=swap" rel="stylesheet">
   <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-line-icons/css/simple-line-icons.css">
   <link href="{{ asset('css/slicknav.min.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
   <link href="{{ asset('css/all.css') }}" rel="stylesheet">
   <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
   <link rel="stylesheet" href="{{ asset('css/plyr.css') }}">
   <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
   @stack('styles')
</head>
<body>
   <x-header />
   @yield('content')
   <x-footer />
</body>
</html>
```

- [ ] **Step 4: Create `layouts/admin.blade.php`** (SPA shell, no header/footer)

Create `resources/views/layouts/admin.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>LuxeStay Admin</title>
   @vite(['resources/js/admin/main.js'])
</head>
<body>
   <div id="admin-app"></div>
</body>
</html>
```

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "feat: add Blade layouts, header and footer components"
```

---

## Task 8: Update Vite Config for Admin SPA Entry

**Files:**
- Modify: `vite.config.js`

- [ ] **Step 1: Update vite.config.js**

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

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
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
```

- [ ] **Step 2: Create empty admin entry point** (will be built out in Plan 4)

Create `resources/js/admin/main.js`:

```js
// Admin SPA entry point — implemented in Plan 4
document.getElementById('admin-app').textContent = 'Admin coming soon.';
```

- [ ] **Step 3: Commit**

```bash
git add -A
git commit -m "feat: add admin SPA entry point to Vite config"
```

---

## Task 9: Web Routes

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Replace `routes/web.php` entirely**

```php
<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\ActivityController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\OrderController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/suites', [RoomController::class, 'suites'])->name('rooms.suites');
Route::get('/rooms/{slug}', [RoomController::class, 'show'])->name('rooms.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');

Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/offers', [PageController::class, 'offers'])->name('offers');
Route::get('/landing', [PageController::class, 'landing'])->name('landing');

// Auth-protected user routes
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account/edit', [AccountController::class, 'update'])->name('account.update');
    Route::get('/account/address', [AccountController::class, 'address'])->name('account.address');
    Route::put('/account/address', [AccountController::class, 'updateAddress'])->name('account.address.update');
    Route::get('/account/downloads', [AccountController::class, 'downloads'])->name('account.downloads');
    Route::get('/account/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin SPA shell (Vue Router handles sub-routes)
Route::get('/admin/{any?}', fn () => view('layouts.admin'))
    ->where('any', '.*')
    ->middleware(['auth', 'admin'])
    ->name('admin');

require __DIR__.'/auth.php';
```

- [ ] **Step 2: Verify routes are registered**

```bash
php artisan route:list | grep -v "breeze\|sanctum\|ignition" | head -40
```
Expected: All named routes (home, rooms.index, blog.index, etc.) listed.

- [ ] **Step 3: Commit**

```bash
git add routes/web.php
git commit -m "feat: add all web routes for frontend pages"
```

---

## Task 10: Web Controllers (Stub Implementations)

**Files:**
- Create: `app/Http/Controllers/Web/HomeController.php`
- Create: `app/Http/Controllers/Web/RoomController.php`
- Create: `app/Http/Controllers/Web/BlogController.php`
- Create: `app/Http/Controllers/Web/ShopController.php`
- Create: `app/Http/Controllers/Web/CartController.php`
- Create: `app/Http/Controllers/Web/CheckoutController.php`
- Create: `app/Http/Controllers/Web/ActivityController.php`
- Create: `app/Http/Controllers/Web/PageController.php`
- Create: `app/Http/Controllers/Web/AccountController.php`
- Create: `app/Http/Controllers/Web/OrderController.php`
- Create: `tests/Feature/Web/FrontendRoutesTest.php`

- [ ] **Step 1: Write failing feature tests**

Create `tests/Feature/Web/FrontendRoutesTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Activity;
use App\Models\Post;
use App\Models\Product;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_200(): void
    {
        $this->get(route('home'))->assertStatus(200);
    }

    public function test_rooms_index_returns_200(): void
    {
        $this->get(route('rooms.index'))->assertStatus(200);
    }

    public function test_rooms_suites_returns_200(): void
    {
        $this->get(route('rooms.suites'))->assertStatus(200);
    }

    public function test_room_show_returns_200(): void
    {
        $type = RoomType::factory()->create();
        $room = Room::factory()->create(['room_type_id' => $type->id, 'slug' => 'deluxe-suite']);

        $this->get(route('rooms.show', $room->slug))->assertStatus(200);
    }

    public function test_blog_index_returns_200(): void
    {
        $this->get(route('blog.index'))->assertStatus(200);
    }

    public function test_blog_show_returns_200(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id, 'slug' => 'test-post', 'status' => 'published']);

        $this->get(route('blog.show', $post->slug))->assertStatus(200);
    }

    public function test_shop_index_returns_200(): void
    {
        $this->get(route('shop.index'))->assertStatus(200);
    }

    public function test_cart_index_returns_200(): void
    {
        $this->get(route('cart.index'))->assertStatus(200);
    }

    public function test_checkout_redirects_guest_to_login(): void
    {
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
    }

    public function test_activity_show_returns_200(): void
    {
        Activity::factory()->create(['slug' => 'spa-wellness', 'type' => 'spa']);

        $this->get(route('activities.show', 'spa-wellness'))->assertStatus(200);
    }

    public function test_about_returns_200(): void
    {
        $this->get(route('about'))->assertStatus(200);
    }

    public function test_contact_returns_200(): void
    {
        $this->get(route('contact'))->assertStatus(200);
    }

    public function test_account_redirects_guest(): void
    {
        $this->get(route('account.index'))->assertRedirect(route('login'));
    }

    public function test_account_accessible_when_authenticated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('account.index'))->assertStatus(200);
    }
}
```

- [ ] **Step 2: Run tests — expect failures (controllers don't exist)**

```bash
php artisan test tests/Feature/Web/FrontendRoutesTest.php
```
Expected: Multiple FAILs with "Target class does not exist" errors.

- [ ] **Step 3: Create Web controllers directory and HomeController**

```bash
mkdir -p app/Http/Controllers/Web
```

Create `app/Http/Controllers/Web/HomeController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home');
    }
}
```

- [ ] **Step 4: Create RoomController**

Create `app/Http/Controllers/Web/RoomController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::with('roomType')->where('is_available', true)->get();

        return view('pages.rooms.index', compact('rooms'));
    }

    public function suites(): View
    {
        $rooms = Room::with('roomType')->where('is_available', true)->get();

        return view('pages.rooms.suites', compact('rooms'));
    }

    public function show(string $slug): View
    {
        $room = Room::with(['roomType', 'amenities'])->where('slug', $slug)->firstOrFail();

        return view('pages.rooms.show', compact('room'));
    }
}
```

- [ ] **Step 5: Create BlogController**

Create `app/Http/Controllers/Web/BlogController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts      = Post::published()->with('category', 'author')->latest('published_at')->paginate(9);
        $categories = PostCategory::withCount(['posts' => fn ($q) => $q->published()])->get();

        return view('pages.blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug): View
    {
        $post   = Post::published()->where('slug', $slug)->with('category', 'author')->firstOrFail();
        $recent = Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();

        return view('pages.blog.show', compact('post', 'recent'));
    }
}
```

- [ ] **Step 6: Create ShopController**

Create `app/Http/Controllers/Web/ShopController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $products   = Product::where('is_active', true)->with('category')->paginate(12);
        $categories = ProductCategory::withCount('products')->get();

        return view('pages.shop.index', compact('products', 'categories'));
    }

    public function show(string $slug): View
    {
        $product  = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related  = Product::where('product_category_id', $product->product_category_id)
                        ->where('id', '!=', $product->id)
                        ->where('is_active', true)
                        ->take(4)->get();

        return view('pages.shop.show', compact('product', 'related'));
    }
}
```

- [ ] **Step 7: Create CartController**

Create `app/Http/Controllers/Web/CartController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart  = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $items[] = ['product' => $product, 'quantity' => $qty];
                $total   += $product->price * $qty;
            }
        }

        return view('pages.shop.cart', compact('items', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);

        $cart                            = session()->get('cart', []);
        $cart[$request->product_id]      = ($cart[$request->product_id] ?? 0) + ($request->quantity ?? 1);
        session()->put('cart', $cart);

        return back()->with('success', 'Added to cart.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required']);

        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Removed from cart.');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'required|integer|min:1']);

        $cart                       = session()->get('cart', []);
        $cart[$request->product_id] = $request->quantity;
        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated.');
    }
}
```

- [ ] **Step 8: Create CheckoutController (stub)**

Create `app/Http/Controllers/Web/CheckoutController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cart  = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $product = \App\Models\Product::find($id);
            if ($product) {
                $items[] = ['product' => $product, 'quantity' => $qty];
                $total   += $product->price * $qty;
            }
        }

        return view('pages.shop.checkout', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        // VNPay flow implemented in Plan 3
        return redirect()->route('checkout.index')->with('info', 'Payment coming soon.');
    }
}
```

- [ ] **Step 9: Create ActivityController**

Create `app/Http/Controllers/Web/ActivityController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function show(string $slug): View
    {
        $activity = Activity::where('slug', $slug)->firstOrFail();

        return view('pages.activities.show', compact('activity'));
    }
}
```

- [ ] **Step 10: Create PageController**

Create `app/Http/Controllers/Web/PageController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View   { return view('pages.about'); }
    public function contact(): View { return view('pages.contact'); }
    public function offers(): View  { return view('pages.offers'); }
    public function landing(): View { return view('pages.landing'); }
}
```

- [ ] **Step 11: Create AccountController**

Create `app/Http/Controllers/Web/AccountController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('pages.account.index', ['user' => Auth::user()]);
    }

    public function edit(): View
    {
        return view('pages.account.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name  = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('account.edit')->with('success', 'Account updated.');
    }

    public function address(): View
    {
        return view('pages.account.address', ['user' => Auth::user()]);
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        // Stored in session/user meta — implement when needed
        return redirect()->route('account.address')->with('success', 'Address saved.');
    }

    public function downloads(): View
    {
        return view('pages.account.downloads', ['user' => Auth::user()]);
    }
}
```

- [ ] **Step 12: Create OrderController**

Create `app/Http/Controllers/Web/OrderController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(10);

        return view('pages.account.orders', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product');

        return view('pages.account.order-detail', compact('order'));
    }
}
```

- [ ] **Step 13: Add model factories (needed for tests)**

```bash
php artisan make:factory RoomTypeFactory --model=RoomType
php artisan make:factory RoomFactory --model=Room
php artisan make:factory PostFactory --model=Post
php artisan make:factory ActivityFactory --model=Activity
php artisan make:factory ProductFactory --model=Product
```

`database/factories/RoomTypeFactory.php`:
```php
public function definition(): array
{
    return [
        'name'        => fake()->words(2, true),
        'slug'        => fake()->unique()->slug(),
        'description' => fake()->paragraph(),
    ];
}
```

`database/factories/RoomFactory.php`:
```php
public function definition(): array
{
    return [
        'room_type_id'   => RoomType::factory(),
        'name'           => fake()->words(3, true),
        'slug'           => fake()->unique()->slug(),
        'description'    => fake()->paragraph(),
        'price_per_night'=> fake()->randomFloat(2, 100, 1000),
        'max_guests'     => fake()->numberBetween(1, 4),
        'size_sqm'       => fake()->numberBetween(20, 120),
        'thumbnail'      => null,
        'gallery'        => null,
        'is_available'   => true,
    ];
}
```

`database/factories/PostFactory.php`:
```php
public function definition(): array
{
    return [
        'author_id'   => User::factory(),
        'title'       => fake()->sentence(),
        'slug'        => fake()->unique()->slug(),
        'excerpt'     => fake()->paragraph(),
        'content'     => fake()->paragraphs(5, true),
        'thumbnail'   => null,
        'type'        => 'standard',
        'status'      => 'published',
        'published_at'=> now(),
    ];
}
```

`database/factories/ActivityFactory.php`:
```php
public function definition(): array
{
    return [
        'type'      => fake()->randomElement(['spa','golf','hiking','skiing','water_sports','fitness','nature','restaurant','event']),
        'title'     => fake()->words(3, true),
        'slug'      => fake()->unique()->slug(),
        'content'   => fake()->paragraphs(3, true),
        'thumbnail' => null,
        'hero_image'=> null,
        'is_featured'=> false,
        'sort_order'=> 0,
    ];
}
```

`database/factories/ProductFactory.php`:
```php
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
```

- [ ] **Step 14: Run tests — expect failures (views don't exist)**

```bash
php artisan test tests/Feature/Web/FrontendRoutesTest.php
```
Expected: FAILs with "View [pages.home] not found" errors — correct, views come next.

- [ ] **Step 15: Commit**

```bash
git add -A
git commit -m "feat: add all web controllers and model factories"
```

---

## Task 11: Blade Views — All Frontend Pages

**Files:**
- Create: `resources/views/pages/home.blade.php`
- Create: `resources/views/pages/rooms/index.blade.php`
- Create: `resources/views/pages/rooms/suites.blade.php`
- Create: `resources/views/pages/rooms/show.blade.php`
- Create: `resources/views/pages/blog/index.blade.php`
- Create: `resources/views/pages/blog/show.blade.php`
- Create: `resources/views/pages/shop/index.blade.php`
- Create: `resources/views/pages/shop/show.blade.php`
- Create: `resources/views/pages/shop/cart.blade.php`
- Create: `resources/views/pages/shop/checkout.blade.php`
- Create: `resources/views/pages/activities/show.blade.php`
- Create: `resources/views/pages/about.blade.php`
- Create: `resources/views/pages/contact.blade.php`
- Create: `resources/views/pages/offers.blade.php`
- Create: `resources/views/pages/landing.blade.php`
- Create: `resources/views/pages/account/index.blade.php`
- Create: `resources/views/pages/account/edit.blade.php`
- Create: `resources/views/pages/account/address.blade.php`
- Create: `resources/views/pages/account/orders.blade.php`
- Create: `resources/views/pages/account/downloads.blade.php`

**Conversion rules for every page:**
1. Open the source HTML from `FE/luxestay/<name>.html`
2. Copy everything between `</header>` and `<!-- Footer Start -->` as the `@section('content')` body
3. Replace every `href="*.html"` with the matching `route()` call (see route table in spec section 4)
4. Replace every `src="images/..."` with `{{ asset('images/...') }}`
5. Replace every `href="images/..."` with `{{ asset('images/...') }}`
6. For sections with repeated room/product/post cards, replace hardcoded HTML blocks with `@foreach` loops over the injected variable

- [ ] **Step 1: Create home page view**

Create `resources/views/pages/home.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'LuxeStay – Luxury Hotel & Resort Booking')

@section('content')
{{-- Copy content from FE/luxestay/index.html lines 251–1651
     Apply conversion rules above.
     For the hero section: keep video src as-is (external URL).
     For room cards section: replace with @foreach($rooms as $room) loop (add $rooms to HomeController if needed).
--}}
@endsection
```

> **Implementation note:** Open `FE/luxestay/index.html` lines 251–1651 and paste into the `@section('content')` block. Then apply the 5 conversion rules. The hero slider, testimonials, and gallery sections are fully static — no PHP changes needed. Any section displaying rooms should use a `@foreach` loop when the `$rooms` variable is passed from `HomeController`.

- [ ] **Step 2: Update HomeController to pass data**

Update `app/Http/Controllers/Web/HomeController.php`:

```php
use App\Models\Room;

public function index(): View
{
    $featuredRooms = Room::with('roomType')
        ->where('is_available', true)
        ->take(6)
        ->get();

    return view('pages.home', compact('featuredRooms'));
}
```

- [ ] **Step 3: Create rooms index view**

Create `resources/views/pages/rooms/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Rooms – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/rooms.html — content between </header> and <!-- Footer Start -->
     Apply conversion rules.
     Replace hardcoded room card HTML blocks with:
--}}
@foreach($rooms as $room)
<div class="col-lg-4 col-md-6">
   <div class="room-card">
      <div class="room-img">
         <img src="{{ $room->thumbnail ? asset('storage/'.$room->thumbnail) : asset('images/rooms-img1.png') }}" alt="{{ $room->name }}">
      </div>
      <div class="room-content">
         <span class="room-type">{{ $room->roomType->name }}</span>
         <h4><a href="{{ route('rooms.show', $room->slug) }}">{{ $room->name }}</a></h4>
         <p class="room-price">From ${{ number_format($room->price_per_night, 0) }} / night</p>
         <a href="{{ route('rooms.show', $room->slug) }}" class="sisf-button sisf-layout--outlined">View Details</a>
      </div>
   </div>
</div>
@endforeach
@endsection
```

- [ ] **Step 4: Create room single view**

Create `resources/views/pages/rooms/show.blade.php`:

```blade
@extends('layouts.app')

@section('title', $room->name . ' – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/room-single.html — content between </header> and <!-- Footer Start -->
     Apply conversion rules.
     Replace hardcoded room name with {{ $room->name }}
     Replace hardcoded price with {{ number_format($room->price_per_night, 2) }}
     Replace hardcoded description with {!! nl2br(e($room->description)) !!}
     Replace amenity list with @foreach($room->amenities as $amenity)
--}}
@endsection
```

- [ ] **Step 5: Create rooms suites view**

Create `resources/views/pages/rooms/suites.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Rooms & Suites – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/rooms-suits.html — content between </header> and <!-- Footer Start -->
     Apply conversion rules.
     Replace room card blocks with @foreach($rooms as $room) using same card HTML as rooms/index.blade.php
--}}
@foreach($rooms as $room)
<div class="col-lg-4 col-md-6">
   <div class="room-card">
      <div class="room-img">
         <img src="{{ $room->thumbnail ? asset('storage/'.$room->thumbnail) : asset('images/rooms-img1.png') }}" alt="{{ $room->name }}">
      </div>
      <div class="room-content">
         <span class="room-type">{{ $room->roomType->name }}</span>
         <h4><a href="{{ route('rooms.show', $room->slug) }}">{{ $room->name }}</a></h4>
         <p class="room-price">From ${{ number_format($room->price_per_night, 0) }} / night</p>
         <a href="{{ route('rooms.show', $room->slug) }}" class="sisf-button sisf-layout--outlined">View Details</a>
      </div>
   </div>
</div>
@endforeach
@endsection
```

- [ ] **Step 6: Create blog index view**

Create `resources/views/pages/blog/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Blog – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/blogs.html — content between </header> and <!-- Footer Start -->
     Apply conversion rules.
     Replace post card blocks with: --}}
@foreach($posts as $post)
<div class="col-lg-4 col-md-6">
   <div class="blog-card">
      @if($post->thumbnail)
      <div class="blog-img">
         <img src="{{ asset('storage/'.$post->thumbnail) }}" alt="{{ $post->title }}">
      </div>
      @endif
      <div class="blog-content">
         <span class="blog-category">{{ $post->category?->name }}</span>
         <h4><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
         <p>{{ $post->excerpt }}</p>
         <div class="blog-meta">
            <span>{{ $post->published_at->format('M d, Y') }}</span>
            <span>{{ $post->author->name }}</span>
         </div>
         <a href="{{ route('blog.show', $post->slug) }}" class="read-more">Read More</a>
      </div>
   </div>
</div>
@endforeach
{{ $posts->links() }}
@endsection
```

- [ ] **Step 7: Create blog show view**

Create `resources/views/pages/blog/show.blade.php`:

```blade
@extends('layouts.app')

@section('title', $post->title . ' – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/blog-single.html — content between </header> and <!-- Footer Start -->
     Apply conversion rules.
     Replace hardcoded title with {{ $post->title }}
     Replace hardcoded content with {!! $post->content !!}
     Replace hardcoded author with {{ $post->author->name }}
     Replace hardcoded date with {{ $post->published_at->format('M d, Y') }}
     Replace recent posts sidebar with @foreach($recent as $r)
--}}
@endsection
```

- [ ] **Step 8: Create shop views**

Create `resources/views/pages/shop/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Shop – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/shop.html — content between </header> and <!-- Footer Start -->
     Apply conversion rules.
     Replace product card blocks with: --}}
@foreach($products as $product)
<div class="col-lg-3 col-md-6">
   <div class="product-card">
      <div class="product-img">
         <img src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : asset('images/product-img1.png') }}" alt="{{ $product->name }}">
         <div class="product-actions">
            <form action="{{ route('cart.add') }}" method="POST">
               @csrf
               <input type="hidden" name="product_id" value="{{ $product->id }}">
               <input type="hidden" name="quantity" value="1">
               <button type="submit" class="btn-add-cart">Add to Cart</button>
            </form>
         </div>
      </div>
      <div class="product-content">
         <h5><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h5>
         <span class="price">${{ number_format($product->price, 2) }}</span>
      </div>
   </div>
</div>
@endforeach
{{ $products->links() }}
@endsection
```

Create `resources/views/pages/shop/show.blade.php`:

```blade
@extends('layouts.app')

@section('title', $product->name . ' – LuxeStay Shop')

@section('content')
{{-- Source: FE/luxestay/shop-single.html
     Apply conversion rules.
     Replace hardcoded product name/price/description with $product->name, $product->price, etc.
     Add to cart form: --}}
<form action="{{ route('cart.add') }}" method="POST">
   @csrf
   <input type="hidden" name="product_id" value="{{ $product->id }}">
   <div class="quantity-input">
      <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
   </div>
   <button type="submit" class="sisf-button">Add to Cart</button>
</form>
@endsection
```

Create `resources/views/pages/shop/cart.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Cart – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/cart.html
     Apply conversion rules.
     Replace cart table rows with: --}}
@forelse($items as $item)
<tr>
   <td><img src="{{ $item['product']->thumbnail ? asset('storage/'.$item['product']->thumbnail) : asset('images/product-img1.png') }}" width="60"></td>
   <td>{{ $item['product']->name }}</td>
   <td>${{ number_format($item['product']->price, 2) }}</td>
   <td>
      <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
         @csrf
         <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
         <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px">
         <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
      </form>
   </td>
   <td>${{ number_format($item['product']->price * $item['quantity'], 2) }}</td>
   <td>
      <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
         @csrf
         <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
         <button type="submit" class="btn btn-sm btn-danger">×</button>
      </form>
   </td>
</tr>
@empty
<tr><td colspan="6" class="text-center">Your cart is empty.</td></tr>
@endforelse
{{-- Total row --}}
<tr><td colspan="4"></td><td><strong>Total</strong></td><td><strong>${{ number_format($total, 2) }}</strong></td></tr>
<a href="{{ route('checkout.index') }}" class="sisf-button">Proceed to Checkout</a>
@endsection
```

Create `resources/views/pages/shop/checkout.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Checkout – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/checkout.html
     Apply conversion rules.
     The actual checkout POST form and VNPay redirect are implemented in Plan 3.
     For now render the form UI pointing to route('checkout.store'): --}}
<form action="{{ route('checkout.store') }}" method="POST">
   @csrf
   {{-- Copy checkout form fields from FE/luxestay/checkout.html --}}
   <button type="submit" class="sisf-button">Place Order</button>
</form>
@endsection
```

- [ ] **Step 9: Create activity show view**

Create `resources/views/pages/activities/show.blade.php`:

```blade
@extends('layouts.app')

@section('title', $activity->title . ' – LuxeStay')

@section('content')
{{-- This single template serves all activity pages (spa, golf, hiking, etc.).
     The hero image, title, and content come from the $activity model.
     Source HTML structure from any activity page, e.g. FE/luxestay/spa-wellness.html.
     Apply conversion rules.
     Replace hardcoded hero image with: --}}
<div class="hero-bg" style="background-image: url('{{ $activity->hero_image ? asset('storage/'.$activity->hero_image) : asset('images/'.$activity->slug.'-hero_img.png') }}')"></div>
<h1>{{ $activity->title }}</h1>
{!! $activity->content !!}
@endsection
```

- [ ] **Step 10: Create static page views**

Create `resources/views/pages/about.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'About Us – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/about-us.html — apply conversion rules. Fully static. --}}
@endsection
```

Create `resources/views/pages/contact.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Contact – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/contact.html — apply conversion rules. Fully static. --}}
@endsection
```

Create `resources/views/pages/offers.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Offers & Promotions – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/offers-promotions.html — apply conversion rules. Fully static. --}}
@endsection
```

Create `resources/views/pages/landing.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'LuxeStay – Welcome')

@section('content')
{{-- Source: FE/luxestay/landing.html — apply conversion rules. Fully static. --}}
@endsection
```

- [ ] **Step 11: Create account views**

Create `resources/views/pages/account/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'My Account – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/my-account.html — apply conversion rules.
     Replace login/account info with: --}}
<p>Welcome, {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>
<a href="{{ route('account.edit') }}">Edit Account</a>
<a href="{{ route('orders.index') }}">My Orders</a>
@endsection
```

Create `resources/views/pages/account/edit.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Edit Account – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/edit-account.html — apply conversion rules. --}}
@if(session('success'))
   <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form action="{{ route('account.update') }}" method="POST">
   @csrf @method('PUT')
   <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
   <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
   <input type="password" name="password" placeholder="New password (optional)">
   <input type="password" name="password_confirmation" placeholder="Confirm password">
   @error('name') <span class="text-danger">{{ $message }}</span> @enderror
   @error('email') <span class="text-danger">{{ $message }}</span> @enderror
   @error('password') <span class="text-danger">{{ $message }}</span> @enderror
   <button type="submit" class="sisf-button">Save Changes</button>
</form>
@endsection
```

Create `resources/views/pages/account/address.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Edit Address – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/edit-address.html — apply conversion rules. --}}
<form action="{{ route('account.address.update') }}" method="POST">
   @csrf @method('PUT')
   {{-- Copy address form fields from FE/luxestay/edit-address.html --}}
   <button type="submit" class="sisf-button">Save Address</button>
</form>
@endsection
```

Create `resources/views/pages/account/orders.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'My Orders – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/orders.html — apply conversion rules. --}}
@forelse($orders as $order)
<div class="order-row">
   <span>#{{ $order->id }}</span>
   <span>{{ $order->created_at->format('M d, Y') }}</span>
   <span>{{ ucfirst($order->status) }}</span>
   <span>${{ number_format($order->total, 2) }}</span>
   <a href="{{ route('orders.show', $order) }}">View</a>
</div>
@empty
<p>No orders yet.</p>
@endforelse
{{ $orders->links() }}
@endsection
```

Create `resources/views/pages/account/downloads.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Downloads – LuxeStay')

@section('content')
{{-- Source: FE/luxestay/downloads.html — apply conversion rules. Fully static for now. --}}
<p>No downloads available.</p>
@endsection
```

Create `resources/views/pages/account/order-detail.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' – LuxeStay')

@section('content')
<h2>Order #{{ $order->id }}</h2>
<p>Status: {{ ucfirst($order->status) }}</p>
<p>Total: ${{ number_format($order->total, 2) }}</p>
<table>
   <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
   <tbody>
   @foreach($order->items as $item)
   <tr>
      <td>{{ $item->product->name }}</td>
      <td>{{ $item->quantity }}</td>
      <td>${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
   </tr>
   @endforeach
   </tbody>
</table>
@endsection
```

- [ ] **Step 12: Run all frontend tests**

```bash
php artisan test tests/Feature/Web/FrontendRoutesTest.php
```
Expected: All 14 tests pass.

- [ ] **Step 13: Commit**

```bash
git add -A
git commit -m "feat: add all Blade views for frontend pages"
```

---

## Task 12: Database Seeders

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: `database/seeders/RoomSeeder.php`
- Create: `database/seeders/BlogSeeder.php`
- Create: `database/seeders/ShopSeeder.php`
- Create: `database/seeders/ActivitySeeder.php`

- [ ] **Step 1: Create RoomSeeder**

```bash
php artisan make:seeder RoomSeeder
```

`database/seeders/RoomSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Deluxe Room',      'slug' => 'deluxe-room'],
            ['name' => 'Suite',             'slug' => 'suite'],
            ['name' => 'Presidential Suite','slug' => 'presidential-suite'],
            ['name' => 'Mountain View',     'slug' => 'mountain-view'],
        ];

        foreach ($types as $t) {
            RoomType::firstOrCreate(['slug' => $t['slug']], $t);
        }

        $amenities = ['WiFi', 'Air Conditioning', 'Mini Bar', 'Room Service', 'Jacuzzi', 'Mountain View', 'Balcony', 'King Bed', 'Smart TV'];
        foreach ($amenities as $name) {
            Amenity::firstOrCreate(['name' => $name], ['icon' => 'fa-check']);
        }

        $rooms = [
            ['name' => 'Deluxe Mountain Room',     'slug' => 'deluxe-mountain-room',     'type' => 'deluxe-room',         'price' => 299, 'guests' => 2, 'size' => 45],
            ['name' => 'Junior Suite',             'slug' => 'junior-suite',             'type' => 'suite',               'price' => 499, 'guests' => 2, 'size' => 65],
            ['name' => 'Luxury Suite',             'slug' => 'luxury-suite',             'type' => 'suite',               'price' => 699, 'guests' => 3, 'size' => 90],
            ['name' => 'Presidential Suite',       'slug' => 'presidential-suite-room',  'type' => 'presidential-suite',  'price' => 1299,'guests' => 4, 'size' => 150],
            ['name' => 'Mountain View Double',     'slug' => 'mountain-view-double',     'type' => 'mountain-view',       'price' => 349, 'guests' => 2, 'size' => 50],
            ['name' => 'Mountain View King Suite', 'slug' => 'mountain-view-king-suite', 'type' => 'mountain-view',       'price' => 599, 'guests' => 3, 'size' => 80],
        ];

        foreach ($rooms as $r) {
            $type = RoomType::where('slug', $r['type'])->first();
            $room = Room::firstOrCreate(['slug' => $r['slug']], [
                'room_type_id'    => $type->id,
                'name'            => $r['name'],
                'description'     => 'Experience luxury in our ' . $r['name'] . '. Featuring stunning mountain views, premium amenities, and world-class service.',
                'price_per_night' => $r['price'],
                'max_guests'      => $r['guests'],
                'size_sqm'        => $r['size'],
                'is_available'    => true,
            ]);

            $room->amenities()->syncWithoutDetaching(
                Amenity::inRandomOrder()->take(4)->pluck('id')
            );
        }
    }
}
```

- [ ] **Step 2: Create BlogSeeder**

```bash
php artisan make:seeder BlogSeeder
```

`database/seeders/BlogSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'name' => 'LuxeStay Admin', 'email' => 'admin@luxestay.com']);

        $categories = ['Travel Tips', 'Luxury Living', 'Hotel News', 'Wellness', 'Gastronomy'];
        foreach ($categories as $name) {
            PostCategory::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        Post::factory(12)->create(['author_id' => $admin->id]);
    }
}
```

- [ ] **Step 3: Create ShopSeeder**

```bash
php artisan make:seeder ShopSeeder
```

`database/seeders/ShopSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $cats = ['Spa & Wellness', 'Hotel Merchandise', 'Gourmet', 'Accessories'];
        foreach ($cats as $name) {
            ProductCategory::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        Product::factory(20)->create();
    }
}
```

- [ ] **Step 4: Create ActivitySeeder**

```bash
php artisan make:seeder ActivitySeeder
```

`database/seeders/ActivitySeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            ['type' => 'spa',          'title' => 'Spa & Wellness',           'slug' => 'spa-wellness',             'is_featured' => true,  'sort_order' => 1],
            ['type' => 'golf',         'title' => 'Golf Courses',             'slug' => 'golf-courses',             'is_featured' => true,  'sort_order' => 2],
            ['type' => 'hiking',       'title' => 'Hiking and Trekking',      'slug' => 'hiking-and-trekking',      'is_featured' => false, 'sort_order' => 3],
            ['type' => 'skiing',       'title' => 'Ski & Snowboarding',       'slug' => 'ski-snowboarding',         'is_featured' => false, 'sort_order' => 4],
            ['type' => 'water_sports', 'title' => 'Water Sports',             'slug' => 'water-sports',             'is_featured' => false, 'sort_order' => 5],
            ['type' => 'fitness',      'title' => 'Fitness and Wellness',     'slug' => 'fitness-and-wellness',     'is_featured' => false, 'sort_order' => 6],
            ['type' => 'nature',       'title' => 'Nature and Exploration',   'slug' => 'nature-and-exploration',   'is_featured' => false, 'sort_order' => 7],
            ['type' => 'nature',       'title' => 'Unique Experiences',       'slug' => 'unique-experiences',       'is_featured' => false, 'sort_order' => 8],
            ['type' => 'hiking',       'title' => 'Winter Hiking',            'slug' => 'winter-hiking',            'is_featured' => false, 'sort_order' => 9],
            ['type' => 'fitness',      'title' => 'Leisure and Entertainment','slug' => 'leisure-and-entertainment','is_featured' => false, 'sort_order' => 10],
            ['type' => 'restaurant',   'title' => 'Restaurant',               'slug' => 'restaurant',               'is_featured' => true,  'sort_order' => 11],
            ['type' => 'event',        'title' => 'Event & Wedding',          'slug' => 'event-wedding',            'is_featured' => true,  'sort_order' => 12],
        ];

        foreach ($activities as $a) {
            Activity::firstOrCreate(['slug' => $a['slug']], array_merge($a, [
                'content' => '<p>Discover the ultimate luxury experience at LuxeStay. Our world-class facilities and expert staff ensure an unforgettable stay.</p>',
            ]));
        }
    }
}
```

- [ ] **Step 5: Update DatabaseSeeder**

`database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoomSeeder::class,
            BlogSeeder::class,
            ShopSeeder::class,
            ActivitySeeder::class,
        ]);
    }
}
```

- [ ] **Step 6: Run seeders**

```bash
php artisan db:seed
```
Expected: All seeders run with no errors. Rooms, posts, products, activities created.

- [ ] **Step 7: Smoke test key pages in browser**

```bash
php artisan serve
```
Visit: `http://localhost:8000` → home page loads with header/footer.
Visit: `http://localhost:8000/rooms` → rooms list loads.
Visit: `http://localhost:8000/blog` → blog list loads.
Visit: `http://localhost:8000/activities/spa-wellness` → spa page loads.
Visit: `http://localhost:8000/shop` → shop loads.
Visit: `http://localhost:8000/admin` → redirects to login (correct).

- [ ] **Step 8: Run full test suite**

```bash
php artisan test
```
Expected: All tests pass.

- [ ] **Step 9: Final commit**

```bash
git add -A
git commit -m "feat: add database seeders and complete Plan 1 (Foundation + Auth + Blade + Frontend)"
```

---

## Plan 1 Complete ✓

**What was built:**
- All DB migrations (15 tables)
- All Eloquent models with relationships
- Laravel Breeze auth (login, register, password reset)
- Admin middleware (role-based)
- FE assets migrated to `public/`
- Blade layout with real header/footer components
- All 40+ HTML pages converted to routed Blade views
- Seeders with realistic data

**Next:** [Plan 2 — Rooms + Bookings + VNPay Bookings](2026-05-04-plan-2-rooms-bookings-vnpay.md)
