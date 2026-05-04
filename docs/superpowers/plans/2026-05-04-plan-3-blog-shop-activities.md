# LuxeStay Plan 3: Blog + Shop + VNPay Orders + Activities

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Complete all remaining frontend modules: dynamic blog with categories, shop with cart-to-order checkout via VNPay, and activity pages served from DB.

**Architecture:** Blog and Activity use read-only controllers (no user input). Shop checkout creates an `Order` record, then redirects to VNPay — same pattern as Plan 2 bookings but for products. Cart lives in PHP session.

**Tech Stack:** Laravel 13, PHP 8.3, MariaDB, VNPay, PHPUnit

**Prerequisite:** Plans 1 & 2 must be complete.

---

## Task 1: Blog — Dynamic Categories & Posts

**Files:**
- Modify: `app/Http/Controllers/Web/BlogController.php`
- Create: `tests/Feature/Web/BlogTest.php`

Blog controller already exists from Plan 1. This task ensures data queries are correct and adds tests.

- [ ] **Step 1: Write tests**

Create `tests/Feature/Web/BlogTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_shows_only_published_posts(): void
    {
        $user  = User::factory()->create();
        $pub   = Post::factory()->create(['author_id' => $user->id, 'status' => 'published']);
        $draft = Post::factory()->create(['author_id' => $user->id, 'status' => 'draft']);

        $response = $this->get(route('blog.index'));
        $posts    = $response->viewData('posts');

        $this->assertTrue($posts->contains($pub));
        $this->assertFalse($posts->contains($draft));
    }

    public function test_blog_index_passes_categories(): void
    {
        PostCategory::factory()->create(['name' => 'Travel', 'slug' => 'travel']);

        $response = $this->get(route('blog.index'));

        $response->assertViewHas('categories');
    }

    public function test_blog_show_returns_correct_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id, 'slug' => 'my-post', 'status' => 'published']);

        $this->get(route('blog.show', 'my-post'))
            ->assertStatus(200)
            ->assertViewHas('post', fn ($p) => $p->id === $post->id);
    }

    public function test_blog_show_returns_404_for_draft(): void
    {
        $user = User::factory()->create();
        Post::factory()->create(['author_id' => $user->id, 'slug' => 'hidden-post', 'status' => 'draft']);

        $this->get(route('blog.show', 'hidden-post'))->assertStatus(404);
    }

    public function test_blog_show_passes_recent_posts(): void
    {
        $user   = User::factory()->create();
        $target = Post::factory()->create(['author_id' => $user->id, 'slug' => 'target', 'status' => 'published']);
        Post::factory(3)->create(['author_id' => $user->id, 'status' => 'published']);

        $response = $this->get(route('blog.show', 'target'));
        $recent   = $response->viewData('recent');

        $this->assertFalse($recent->contains($target));
        $this->assertLessThanOrEqual(3, $recent->count());
    }
}
```

Add `PostCategoryFactory`:

```bash
php artisan make:factory PostCategoryFactory --model=PostCategory
```

`database/factories/PostCategoryFactory.php`:

```php
public function definition(): array
{
    return [
        'name' => fake()->words(2, true),
        'slug' => fake()->unique()->slug(),
    ];
}
```

- [ ] **Step 2: Run tests**

```bash
php artisan test tests/Feature/Web/BlogTest.php
```
Expected: All 5 tests pass (BlogController already implemented in Plan 1).

- [ ] **Step 3: Commit**

```bash
git add -A
git commit -m "feat: add blog feature tests"
```

---

## Task 2: Shop — CartService Extraction

**Files:**
- Create: `app/Services/CartService.php`
- Modify: `app/Http/Controllers/Web/CartController.php`
- Modify: `app/Http/Controllers/Web/ShopController.php`
- Create: `tests/Unit/Services/CartServiceTest.php`

Cart logic is currently inline in `CartController`. Extract to `CartService` for reuse in `CheckoutController`.

- [ ] **Step 1: Write failing unit tests**

Create `tests/Unit/Services/CartServiceTest.php`:

```php
<?php

namespace Tests\Unit\Services;

use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartService $cart;

    protected function setUp(): void
    {
        parent::setUp();
        Session::flush();
        $this->cart = new CartService();
    }

    public function test_add_item_to_empty_cart(): void
    {
        $this->cart->add(productId: 1, quantity: 2);

        $this->assertEquals([1 => 2], $this->cart->items());
    }

    public function test_adding_same_item_increments_quantity(): void
    {
        $this->cart->add(productId: 1, quantity: 2);
        $this->cart->add(productId: 1, quantity: 3);

        $this->assertEquals([1 => 5], $this->cart->items());
    }

    public function test_remove_item(): void
    {
        $this->cart->add(productId: 1, quantity: 2);
        $this->cart->add(productId: 2, quantity: 1);
        $this->cart->remove(productId: 1);

        $this->assertEquals([2 => 1], $this->cart->items());
    }

    public function test_update_quantity(): void
    {
        $this->cart->add(productId: 1, quantity: 2);
        $this->cart->update(productId: 1, quantity: 5);

        $this->assertEquals([1 => 5], $this->cart->items());
    }

    public function test_clear_empties_cart(): void
    {
        $this->cart->add(productId: 1, quantity: 2);
        $this->cart->clear();

        $this->assertEmpty($this->cart->items());
    }

    public function test_count_returns_total_item_count(): void
    {
        $this->cart->add(productId: 1, quantity: 2);
        $this->cart->add(productId: 2, quantity: 3);

        $this->assertEquals(5, $this->cart->count());
    }
}
```

- [ ] **Step 2: Run tests — expect failures**

```bash
php artisan test tests/Unit/Services/CartServiceTest.php
```
Expected: FAIL — `CartService` not found.

- [ ] **Step 3: Create CartService**

Create `app/Services/CartService.php`:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function items(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart                = $this->items();
        $cart[$productId]    = ($cart[$productId] ?? 0) + $quantity;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart             = $this->items();
        $cart[$productId] = $quantity;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum($this->items());
    }
}
```

- [ ] **Step 4: Refactor CartController to use CartService**

Replace `app/Http/Controllers/Web/CartController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        $items = [];
        $total = 0;

        foreach ($this->cart->items() as $id => $qty) {
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'integer|min:1',
        ]);

        $this->cart->add((int) $request->product_id, (int) ($request->quantity ?? 1));

        return back()->with('success', 'Added to cart.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required']);

        $this->cart->remove((int) $request->product_id);

        return back()->with('success', 'Removed from cart.');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $this->cart->update((int) $request->product_id, (int) $request->quantity);

        return back()->with('success', 'Cart updated.');
    }
}
```

- [ ] **Step 5: Run tests**

```bash
php artisan test tests/Unit/Services/CartServiceTest.php
```
Expected: 6 tests pass.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: extract CartService and refactor CartController"
```

---

## Task 3: Shop Checkout → Order → VNPay

**Files:**
- Modify: `app/Http/Controllers/Web/CheckoutController.php`
- Create: `app/Http/Controllers/Web/VNPayOrderController.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/Web/CheckoutTest.php`
- Create: `tests/Feature/Web/VNPayOrderTest.php`

- [ ] **Step 1: Write failing tests**

Create `tests/Feature/Web/CheckoutTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_requires_auth(): void
    {
        $this->post(route('checkout.store'), [])->assertRedirect(route('login'));
    }

    public function test_checkout_with_empty_cart_redirects_back(): void
    {
        $user = User::factory()->create();
        Session::put('cart', []);

        $this->actingAs($user)
            ->post(route('checkout.store'), [
                'first_name' => 'Nguyen',
                'last_name'  => 'Van A',
                'address'    => '123 Le Loi',
                'city'       => 'Ho Chi Minh',
                'email'      => 'test@example.com',
                'phone'      => '0912345678',
            ])
            ->assertRedirect(route('cart.index'));
    }

    public function test_checkout_creates_order_and_redirects_to_vnpay(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['price' => 200, 'stock' => 10]);
        Session::put('cart', [$product->id => 2]);

        config([
            'services.vnpay.tmn_code'    => 'TESTCODE',
            'services.vnpay.hash_secret' => 'testhashsecretkey123456789012345',
            'services.vnpay.url'         => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'first_name' => 'Nguyen',
            'last_name'  => 'Van A',
            'address'    => '123 Le Loi',
            'city'       => 'Ho Chi Minh',
            'email'      => 'test@example.com',
            'phone'      => '0912345678',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 400]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_checkout_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('checkout.store'), [])
            ->assertSessionHasErrors(['first_name', 'last_name', 'address', 'city', 'email']);
    }
}
```

- [ ] **Step 2: Run tests — expect failures**

```bash
php artisan test tests/Feature/Web/CheckoutTest.php
```
Expected: FAIL — checkout store currently just redirects with 'coming soon'.

- [ ] **Step 3: Update CheckoutController**

Replace `app/Http/Controllers/Web/CheckoutController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\VNPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService  $cart,
        private VNPayService $vnpay,
    ) {}

    public function index(): View
    {
        $items = [];
        $total = 0;

        foreach ($this->cart->items() as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $items[] = ['product' => $product, 'quantity' => $qty];
                $total   += $product->price * $qty;
            }
        }

        return view('pages.shop.checkout', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'nullable|string|max:20',
        ]);

        $cartItems = $this->cart->items();

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cartItems))->get()->keyBy('id');
        $subtotal = 0;

        foreach ($cartItems as $id => $qty) {
            $product  = $products->get($id);
            $subtotal += $product ? $product->price * $qty : 0;
        }

        $order = DB::transaction(function () use ($request, $cartItems, $products, $subtotal) {
            $order = Order::create([
                'user_id'          => Auth::id(),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'subtotal'         => $subtotal,
                'shipping_fee'     => 0,
                'total'            => $subtotal,
                'shipping_address' => [
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'address'    => $request->address,
                    'city'       => $request->city,
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                ],
            ]);

            foreach ($cartItems as $id => $qty) {
                $product = $products->get($id);
                if ($product) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'unit_price' => $product->price,
                    ]);
                }
            }

            return $order;
        });

        return redirect()->route('order.pay', $order->id);
    }
}
```

- [ ] **Step 4: Add order payment route to `routes/web.php`**

Inside `middleware('auth')` group:

```php
use App\Http\Controllers\Web\VNPayOrderController;

Route::get('/order/{order}/pay', [VNPayOrderController::class, 'redirect'])->name('order.pay');
```

Outside auth group:

```php
Route::get('/vnpay/order/return', [VNPayOrderController::class, 'return'])->name('vnpay.order.return');
```

- [ ] **Step 5: Create VNPayOrderController**

Create `app/Http/Controllers/Web/VNPayOrderController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\CartService;
use App\Services\VNPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VNPayOrderController extends Controller
{
    public function __construct(
        private VNPayService $vnpay,
        private CartService  $cart,
    ) {}

    public function redirect(int $orderId): RedirectResponse
    {
        $order = Order::findOrFail($orderId);
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->payment_status === 'paid', 422);

        $txnRef = 'ORD-' . $order->id . '-' . time();
        $order->update(['vnpay_txn_ref' => $txnRef]);

        $url = $this->vnpay->createPaymentUrl(
            txnRef:    $txnRef,
            amount:    (int) $order->total,
            orderInfo: 'Thanh toan don hang #' . $order->id . ' tai LuxeStay',
            returnUrl: route('vnpay.order.return'),
            ipAddr:    request()->ip(),
        );

        return redirect()->away($url);
    }

    public function return(Request $request): RedirectResponse
    {
        $params = $request->query();

        if (! $this->vnpay->verifyReturn($params)) {
            return redirect()->route('orders.index')->with('error', 'Invalid payment signature.');
        }

        $order = Order::where('vnpay_txn_ref', $params['vnp_TxnRef'])->firstOrFail();

        DB::transaction(function () use ($order, $params) {
            if ($this->vnpay->isSuccessResponse($params)) {
                $order->update(['status' => 'processing', 'payment_status' => 'paid']);
                $this->cart->clear();
            }

            PaymentTransaction::create([
                'payable_type'     => Order::class,
                'payable_id'       => $order->id,
                'amount'           => $params['vnp_Amount'] / 100,
                'gateway'          => 'vnpay',
                'status'           => $this->vnpay->isSuccessResponse($params) ? 'success' : 'failed',
                'gateway_ref'      => $params['vnp_TransactionNo'] ?? null,
                'gateway_response' => $params,
            ]);
        });

        return redirect()->route('orders.show', $order->id)
            ->with(
                $this->vnpay->isSuccessResponse($params) ? 'success' : 'error',
                $this->vnpay->isSuccessResponse($params) ? 'Payment successful! Your order is confirmed.' : 'Payment failed. Please try again.'
            );
    }
}
```

- [ ] **Step 6: Write VNPay order feature test**

Create `tests/Feature/Web/VNPayOrderTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VNPayOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.vnpay.tmn_code'    => 'TESTCODE',
            'services.vnpay.hash_secret' => 'testhashsecretkey123456789012345',
            'services.vnpay.url'         => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        ]);
    }

    public function test_successful_vnpay_return_confirms_order(): void
    {
        $user  = User::factory()->create();
        $order = Order::factory()->create([
            'user_id'        => $user->id,
            'vnpay_txn_ref'  => 'ORD-TEST-001',
            'total'          => 400,
            'payment_status' => 'unpaid',
        ]);

        $params = [
            'vnp_ResponseCode'  => '00',
            'vnp_TxnRef'        => 'ORD-TEST-001',
            'vnp_Amount'        => '40000',
            'vnp_TransactionNo' => '87654321',
        ];
        ksort($params);
        $hash                   = hash_hmac('sha512', http_build_query($params), 'testhashsecretkey123456789012345');
        $params['vnp_SecureHash'] = $hash;

        $this->get(route('vnpay.order.return', $params));

        $this->assertDatabaseHas('orders', [
            'id'             => $order->id,
            'status'         => 'processing',
            'payment_status' => 'paid',
        ]);
    }
}
```

Add `OrderFactory`:

```bash
php artisan make:factory OrderFactory --model=Order
```

`database/factories/OrderFactory.php`:

```php
public function definition(): array
{
    return [
        'user_id'          => User::factory(),
        'status'           => 'pending',
        'payment_status'   => 'unpaid',
        'subtotal'         => 400,
        'shipping_fee'     => 0,
        'total'            => 400,
        'shipping_address' => ['address' => '123 Test St', 'city' => 'HCMC'],
    ];
}
```

- [ ] **Step 7: Run all shop/checkout tests**

```bash
php artisan test tests/Feature/Web/CheckoutTest.php tests/Feature/Web/VNPayOrderTest.php tests/Unit/Services/CartServiceTest.php
```
Expected: All tests pass.

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "feat: add shop checkout, Order creation, and VNPay order payment flow"
```

---

## Task 4: Activity Pages — All Slugs Seeded & Verified

**Files:**
- Modify: `app/Http/Controllers/Web/ActivityController.php`
- Create: `tests/Feature/Web/ActivityTest.php`

- [ ] **Step 1: Write tests**

Create `tests/Feature/Web/ActivityTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_page_returns_200_for_existing_slug(): void
    {
        Activity::factory()->create(['slug' => 'spa-wellness', 'type' => 'spa']);

        $this->get(route('activities.show', 'spa-wellness'))->assertStatus(200);
    }

    public function test_activity_page_returns_404_for_unknown_slug(): void
    {
        $this->get(route('activities.show', 'does-not-exist'))->assertStatus(404);
    }

    public function test_all_seeded_activity_slugs_return_200(): void
    {
        $this->artisan('db:seed', ['--class' => 'ActivitySeeder']);

        $slugs = [
            'spa-wellness', 'golf-courses', 'hiking-and-trekking',
            'ski-snowboarding', 'water-sports', 'fitness-and-wellness',
            'nature-and-exploration', 'unique-experiences', 'winter-hiking',
            'leisure-and-entertainment', 'restaurant', 'event-wedding',
        ];

        foreach ($slugs as $slug) {
            $this->get(route('activities.show', $slug))->assertStatus(200, "Failed for slug: $slug");
        }
    }

    public function test_activity_view_receives_activity_model(): void
    {
        Activity::factory()->create(['slug' => 'golf-courses', 'type' => 'golf', 'title' => 'Golf Courses']);

        $this->get(route('activities.show', 'golf-courses'))
            ->assertViewHas('activity', fn ($a) => $a->title === 'Golf Courses');
    }
}
```

- [ ] **Step 2: Run tests**

```bash
php artisan test tests/Feature/Web/ActivityTest.php
```
Expected: All 4 tests pass (ActivityController already implemented in Plan 1).

- [ ] **Step 3: Commit**

```bash
git add -A
git commit -m "feat: add activity feature tests and verify all 12 activity slugs"
```

---

## Task 5: Run Full Test Suite

- [ ] **Step 1: Run all tests**

```bash
php artisan test
```
Expected: All tests pass, no failures.

- [ ] **Step 2: Run seeders on fresh DB**

```bash
php artisan migrate:fresh --seed
```
Expected: No errors. All tables populated.

- [ ] **Step 3: Smoke test critical user journeys**

Start the dev server:

```bash
composer run dev
```

Verify the following in browser:

| Journey | URL | Expected |
|---|---|---|
| Home page | `https://luxestay.ddev.site/` | Loads with header/footer |
| Rooms list | `/rooms` | Shows seeded rooms |
| Room detail | `/rooms/deluxe-mountain-room` | Shows room info + Book button |
| Blog list | `/blog` | Shows 12 seeded posts |
| Blog post | `/blog/<any-slug>` | Shows post content |
| Shop | `/shop` | Shows 20 products |
| Cart | `/cart` | Shows cart (empty) |
| Activity — Spa | `/activities/spa-wellness` | Loads activity page |
| Activity — Golf | `/activities/golf-courses` | Loads activity page |
| Account (guest) | `/account` | Redirects to /login |
| Register | `/register` | Breeze register form |
| Book room (auth) | `/booking/deluxe-mountain-room` | Shows booking form |

- [ ] **Step 4: Final commit**

```bash
git add -A
git commit -m "feat: complete Plan 3 (Blog + Shop + VNPay Orders + Activities)"
```

---

## Plan 3 Complete ✓

**What was built:**
- Blog: published-only posts, categories, paginated listing, single post with recent sidebar
- CartService: extracted, session-based, fully tested
- Shop checkout: form → Order creation → VNPay redirect → return handler → cart cleared
- Activities: all 12 slugs served from DB, tested

**Next:** [Plan 4 — Admin SPA + Account Pages + Dashboard](2026-05-04-plan-4-admin-spa.md)
