# LuxeStay Plan 2: Rooms + Bookings + VNPay (Bookings)

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make rooms and booking fully dynamic — room availability checking, booking form submission, and VNPay payment for bookings.

**Architecture:** `BookingController` handles the full booking lifecycle: form → create Booking (pending) → VNPay redirect → return/IPN callback → confirm Booking. `VNPayService` encapsulates all gateway logic. A `BookingPolicy` ensures users can only access their own bookings.

**Tech Stack:** Laravel 13, PHP 8.3, MariaDB, VNPay sandbox API, PHPUnit

**Prerequisite:** Plan 1 must be complete (migrations, models, Blade layout, routes all exist).

**Working directory:** `/Users/mac/Desktop/Project/luxestay`

---

## Task 1: Room Availability Logic

**Files:**
- Modify: `app/Models/Room.php`
- Create: `tests/Unit/Models/RoomAvailabilityTest.php`

- [ ] **Step 1: Write failing unit tests**

Create `tests/Unit/Models/RoomAvailabilityTest.php`:

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        $type = RoomType::factory()->create();
        $this->room = Room::factory()->create(['room_type_id' => $type->id]);
    }

    public function test_room_is_available_when_no_bookings(): void
    {
        $this->assertTrue($this->room->isAvailableFor('2026-06-01', '2026-06-05'));
    }

    public function test_room_is_unavailable_when_booking_overlaps(): void
    {
        $user = User::factory()->create();
        Booking::factory()->create([
            'room_id'    => $this->room->id,
            'user_id'    => $user->id,
            'check_in'   => '2026-06-03',
            'check_out'  => '2026-06-08',
            'status'     => 'confirmed',
        ]);

        $this->assertFalse($this->room->isAvailableFor('2026-06-01', '2026-06-05'));
    }

    public function test_room_is_available_when_booking_does_not_overlap(): void
    {
        $user = User::factory()->create();
        Booking::factory()->create([
            'room_id'   => $this->room->id,
            'user_id'   => $user->id,
            'check_in'  => '2026-06-10',
            'check_out' => '2026-06-15',
            'status'    => 'confirmed',
        ]);

        $this->assertTrue($this->room->isAvailableFor('2026-06-01', '2026-06-05'));
    }

    public function test_cancelled_booking_does_not_affect_availability(): void
    {
        $user = User::factory()->create();
        Booking::factory()->create([
            'room_id'   => $this->room->id,
            'user_id'   => $user->id,
            'check_in'  => '2026-06-03',
            'check_out' => '2026-06-08',
            'status'    => 'cancelled',
        ]);

        $this->assertTrue($this->room->isAvailableFor('2026-06-01', '2026-06-05'));
    }

    public function test_calculates_total_price_correctly(): void
    {
        $this->room->price_per_night = 300;
        $this->room->save();

        $this->assertEquals(1500, $this->room->calculatePrice('2026-06-01', '2026-06-06'));
    }
}
```

- [ ] **Step 2: Run test — expect failures**

```bash
php artisan test tests/Unit/Models/RoomAvailabilityTest.php
```
Expected: FAIL — `isAvailableFor` method not found.

- [ ] **Step 3: Add availability methods to Room model**

Add to `app/Models/Room.php`:

```php
public function isAvailableFor(string $checkIn, string $checkOut): bool
{
    return ! $this->bookings()
        ->whereIn('status', ['pending', 'confirmed'])
        ->where('check_in', '<', $checkOut)
        ->where('check_out', '>', $checkIn)
        ->exists();
}

public function calculatePrice(string $checkIn, string $checkOut): float
{
    $nights = (int) \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));

    return $nights * (float) $this->price_per_night;
}
```

Add Booking factory in `database/factories/BookingFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'room_id'        => Room::factory(),
            'check_in'       => fake()->dateTimeBetween('+1 week', '+2 weeks')->format('Y-m-d'),
            'check_out'      => fake()->dateTimeBetween('+3 weeks', '+4 weeks')->format('Y-m-d'),
            'guests'         => fake()->numberBetween(1, 3),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'total_price'    => fake()->randomFloat(2, 200, 5000),
        ];
    }
}
```

- [ ] **Step 4: Run tests**

```bash
php artisan test tests/Unit/Models/RoomAvailabilityTest.php
```
Expected: 5 tests pass.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "feat: add room availability and price calculation to Room model"
```

---

## Task 2: Booking Form + BookingController

**Files:**
- Create: `app/Http/Controllers/Web/BookingController.php`
- Create: `resources/views/pages/booking/create.blade.php`
- Create: `resources/views/pages/booking/confirmation.blade.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/Web/BookingControllerTest.php`

- [ ] **Step 1: Write failing tests**

Create `tests/Feature/Web/BookingControllerTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();
        $type       = RoomType::factory()->create();
        $this->room = Room::factory()->create(['room_type_id' => $type->id]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_booking_form(): void
    {
        $this->get(route('booking.create', $this->room->slug))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_booking_form(): void
    {
        $this->actingAs($this->user)
            ->get(route('booking.create', $this->room->slug))
            ->assertStatus(200)
            ->assertViewHas('room');
    }

    public function test_booking_creates_pending_booking_record(): void
    {
        $this->actingAs($this->user)
            ->post(route('booking.store'), [
                'room_id'   => $this->room->id,
                'check_in'  => '2026-07-01',
                'check_out' => '2026-07-05',
                'guests'    => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id'        => $this->user->id,
            'room_id'        => $this->room->id,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_booking_validates_required_fields(): void
    {
        $this->actingAs($this->user)
            ->post(route('booking.store'), [])
            ->assertSessionHasErrors(['room_id', 'check_in', 'check_out']);
    }

    public function test_booking_rejects_unavailable_room(): void
    {
        $existing = Booking::factory()->create([
            'room_id'   => $this->room->id,
            'user_id'   => $this->user->id,
            'check_in'  => '2026-07-03',
            'check_out' => '2026-07-08',
            'status'    => 'confirmed',
        ]);

        $this->actingAs($this->user)
            ->post(route('booking.store'), [
                'room_id'   => $this->room->id,
                'check_in'  => '2026-07-01',
                'check_out' => '2026-07-05',
                'guests'    => 2,
            ])
            ->assertSessionHasErrors('check_in');
    }

    public function test_user_can_view_own_booking(): void
    {
        $booking = Booking::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('booking.show', $booking->id))
            ->assertStatus(200)
            ->assertViewHas('booking');
    }

    public function test_user_cannot_view_other_users_booking(): void
    {
        $other   = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $other->id]);

        $this->actingAs($this->user)
            ->get(route('booking.show', $booking->id))
            ->assertStatus(403);
    }
}
```

- [ ] **Step 2: Run tests — expect failures**

```bash
php artisan test tests/Feature/Web/BookingControllerTest.php
```
Expected: FAIL — route `booking.create` not found.

- [ ] **Step 3: Add booking routes to `routes/web.php`**

Inside the `Route::middleware('auth')->group(...)` block, add:

```php
Route::get('/booking/{slug}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/confirmation/{booking}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
```

Add import at top: `use App\Http\Controllers\Web\BookingController;`

- [ ] **Step 4: Create BookingController**

Create `app/Http/Controllers/Web/BookingController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(string $slug): View
    {
        $room = Room::with(['roomType', 'amenities'])->where('slug', $slug)->firstOrFail();

        return view('pages.booking.create', compact('room'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'room_id'   => 'required|exists:rooms,id',
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests'    => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($request->room_id);

        if (! $room->isAvailableFor($request->check_in, $request->check_out)) {
            return back()->withErrors(['check_in' => 'This room is not available for the selected dates.'])->withInput();
        }

        $totalPrice = $room->calculatePrice($request->check_in, $request->check_out);

        $booking = Booking::create([
            'user_id'        => Auth::id(),
            'room_id'        => $room->id,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'guests'         => $request->guests,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'total_price'    => $totalPrice,
            'special_requests' => $request->special_requests,
        ]);

        // Redirect to VNPay — route added in Task 3; redirects to confirmation for now
        return redirect()->route('booking.show', $booking->id);
    }

    public function show(int $id): View
    {
        $booking = Booking::with(['room.roomType', 'services'])->findOrFail($id);
        abort_if($booking->user_id !== Auth::id(), 403);

        return view('pages.booking.confirmation', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->status === 'confirmed') {
            abort(422, 'Confirmed bookings cannot be self-cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('account.index')->with('success', 'Booking cancelled.');
    }
}
```

- [ ] **Step 5: Create booking views (stubs)**

Create `resources/views/pages/booking/create.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Book ' . $room->name . ' – LuxeStay')

@section('content')
<div class="container py-5">
   <h2>Book: {{ $room->name }}</h2>
   <p>{{ $room->roomType->name }} · ${{ number_format($room->price_per_night, 0) }}/night · Max {{ $room->max_guests }} guests</p>

   @if($errors->any())
      <div class="alert alert-danger">
         @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
      </div>
   @endif

   <form action="{{ route('booking.store') }}" method="POST">
      @csrf
      <input type="hidden" name="room_id" value="{{ $room->id }}">

      <div class="mb-3">
         <label>Check-in Date</label>
         <input type="date" name="check_in" class="form-control" value="{{ old('check_in') }}" required>
      </div>
      <div class="mb-3">
         <label>Check-out Date</label>
         <input type="date" name="check_out" class="form-control" value="{{ old('check_out') }}" required>
      </div>
      <div class="mb-3">
         <label>Guests</label>
         <input type="number" name="guests" class="form-control" value="{{ old('guests', 1) }}" min="1" max="{{ $room->max_guests }}" required>
      </div>
      <div class="mb-3">
         <label>Special Requests (optional)</label>
         <textarea name="special_requests" class="form-control" rows="3">{{ old('special_requests') }}</textarea>
      </div>

      <button type="submit" class="sisf-button">Proceed to Payment</button>
   </form>
</div>
@endsection
```

Create `resources/views/pages/booking/confirmation.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Booking Confirmation – LuxeStay')

@section('content')
<div class="container py-5">
   <h2>Booking {{ ucfirst($booking->status) }}</h2>
   <p>Booking #{{ $booking->id }}</p>
   <p>Room: {{ $booking->room->name }}</p>
   <p>Check-in: {{ $booking->check_in->format('M d, Y') }}</p>
   <p>Check-out: {{ $booking->check_out->format('M d, Y') }}</p>
   <p>Guests: {{ $booking->guests }}</p>
   <p>Total: ${{ number_format($booking->total_price, 2) }}</p>
   <p>Payment: {{ ucfirst($booking->payment_status) }}</p>

   @if($booking->status === 'pending')
      <a href="{{ route('booking.pay', $booking->id) }}" class="sisf-button">Pay Now</a>
      <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" class="d-inline">
         @csrf
         <button type="submit" class="btn btn-outline-danger">Cancel Booking</button>
      </form>
   @endif

   <a href="{{ route('account.index') }}" class="btn btn-link">Back to My Account</a>
</div>
@endsection
```

- [ ] **Step 6: Add `booking.create` link to room show page**

In `resources/views/pages/rooms/show.blade.php`, ensure the booking button links correctly:

```blade
@auth
   <a href="{{ route('booking.create', $room->slug) }}" class="sisf-button">Book This Room</a>
@else
   <a href="{{ route('login') }}" class="sisf-button">Login to Book</a>
@endauth
```

- [ ] **Step 7: Run tests**

```bash
php artisan test tests/Feature/Web/BookingControllerTest.php
```
Expected: All 7 tests pass.

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "feat: add booking form and BookingController with availability validation"
```

---

## Task 3: VNPay Service + Booking Payment Flow

**Files:**
- Create: `app/Services/VNPayService.php`
- Modify: `app/Http/Controllers/Web/BookingController.php`
- Modify: `routes/web.php`
- Create: `tests/Unit/Services/VNPayServiceTest.php`
- Modify: `.env` (add VNPay credentials)

- [ ] **Step 1: Add VNPay credentials to `.env`**

```env
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL="${APP_URL}/vnpay/booking/return"
```

Add to `config/services.php`:

```php
'vnpay' => [
    'tmn_code'    => env('VNPAY_TMN_CODE'),
    'hash_secret' => env('VNPAY_HASH_SECRET'),
    'url'         => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url'  => env('VNPAY_RETURN_URL'),
],
```

- [ ] **Step 2: Write failing unit tests**

Create `tests/Unit/Services/VNPayServiceTest.php`:

```php
<?php

namespace Tests\Unit\Services;

use App\Services\VNPayService;
use Tests\TestCase;

class VNPayServiceTest extends TestCase
{
    private VNPayService $service;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.vnpay.tmn_code'    => 'TESTCODE',
            'services.vnpay.hash_secret' => 'testhashsecretkey123456789012345',
            'services.vnpay.url'         => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'services.vnpay.return_url'  => 'https://luxestay.ddev.site/vnpay/booking/return',
        ]);
        $this->service = new VNPayService();
    }

    public function test_create_payment_url_returns_valid_url(): void
    {
        $url = $this->service->createPaymentUrl(
            txnRef: 'BOOKING-1',
            amount: 500000,
            orderInfo: 'Thanh toan dat phong #1',
            returnUrl: 'https://luxestay.ddev.site/vnpay/booking/return',
            ipAddr: '127.0.0.1',
        );

        $this->assertStringStartsWith('https://sandbox.vnpayment.vn', $url);
        $this->assertStringContainsString('vnp_TmnCode=TESTCODE', $url);
        $this->assertStringContainsString('vnp_Amount=50000000', $url);
        $this->assertStringContainsString('vnp_SecureHash=', $url);
    }

    public function test_verify_return_detects_invalid_signature(): void
    {
        $params = [
            'vnp_ResponseCode' => '00',
            'vnp_TxnRef'       => 'BOOKING-1',
            'vnp_Amount'       => '50000000',
            'vnp_SecureHash'   => 'invalid_hash',
        ];

        $this->assertFalse($this->service->verifyReturn($params));
    }

    public function test_amount_is_multiplied_by_100_for_vnpay(): void
    {
        $url = $this->service->createPaymentUrl(
            txnRef: 'BOOKING-2',
            amount: 300,
            orderInfo: 'Test',
            returnUrl: 'https://luxestay.ddev.site/vnpay/booking/return',
            ipAddr: '127.0.0.1',
        );

        $this->assertStringContainsString('vnp_Amount=30000', $url);
    }
}
```

- [ ] **Step 3: Run tests — expect failures**

```bash
php artisan test tests/Unit/Services/VNPayServiceTest.php
```
Expected: FAIL — `VNPayService` class not found.

- [ ] **Step 4: Create VNPayService**

Create `app/Services/VNPayService.php`:

```php
<?php

namespace App\Services;

class VNPayService
{
    private string $tmnCode;
    private string $hashSecret;
    private string $vnpayUrl;

    public function __construct()
    {
        $this->tmnCode    = config('services.vnpay.tmn_code');
        $this->hashSecret = config('services.vnpay.hash_secret');
        $this->vnpayUrl   = config('services.vnpay.url');
    }

    public function createPaymentUrl(
        string $txnRef,
        int    $amount,
        string $orderInfo,
        string $returnUrl,
        string $ipAddr,
    ): string {
        $params = [
            'vnp_Version'    => '2.1.0',
            'vnp_Command'    => 'pay',
            'vnp_TmnCode'    => $this->tmnCode,
            'vnp_Amount'     => $amount * 100,
            'vnp_CurrCode'   => 'VND',
            'vnp_TxnRef'     => $txnRef,
            'vnp_OrderInfo'  => $orderInfo,
            'vnp_OrderType'  => 'other',
            'vnp_Locale'     => 'vn',
            'vnp_ReturnUrl'  => $returnUrl,
            'vnp_IpAddr'     => $ipAddr,
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_ExpireDate' => now()->addMinutes(15)->format('YmdHis'),
        ];

        ksort($params);

        $query     = http_build_query($params);
        $hash      = hash_hmac('sha512', $query, $this->hashSecret);
        $params['vnp_SecureHash'] = $hash;

        return $this->vnpayUrl . '?' . http_build_query($params);
    }

    public function verifyReturn(array $params): bool
    {
        $secureHash = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $query         = http_build_query($params);
        $expectedHash  = hash_hmac('sha512', $query, $this->hashSecret);

        return hash_equals($expectedHash, $secureHash);
    }

    public function isSuccessResponse(array $params): bool
    {
        return ($params['vnp_ResponseCode'] ?? '') === '00';
    }
}
```

- [ ] **Step 5: Run tests**

```bash
php artisan test tests/Unit/Services/VNPayServiceTest.php
```
Expected: 3 tests pass.

- [ ] **Step 6: Add payment routes to `routes/web.php`**

```php
use App\Http\Controllers\Web\VNPayBookingController;

Route::middleware('auth')->group(function () {
    Route::get('/booking/{booking}/pay', [VNPayBookingController::class, 'redirect'])->name('booking.pay');
});

Route::get('/vnpay/booking/return', [VNPayBookingController::class, 'return'])->name('vnpay.booking.return');
Route::post('/vnpay/ipn', [VNPayBookingController::class, 'ipn'])->name('vnpay.ipn');
```

- [ ] **Step 6b: Update `BookingController@store` redirect now that `booking.pay` route exists**

In `app/Http/Controllers/Web/BookingController.php` update the final return line of `store()`:

```php
// Replace the existing redirect line with:
return redirect()->route('booking.pay', $booking->id);
```

- [ ] **Step 7: Create VNPayBookingController**

Create `app/Http/Controllers/Web/VNPayBookingController.php`:

```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Services\VNPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VNPayBookingController extends Controller
{
    public function __construct(private VNPayService $vnpay) {}

    public function redirect(int $bookingId): RedirectResponse
    {
        $booking = Booking::findOrFail($bookingId);
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->payment_status === 'paid', 422);

        $txnRef = 'BK-' . $booking->id . '-' . time();
        $booking->update(['vnpay_txn_ref' => $txnRef]);

        $url = $this->vnpay->createPaymentUrl(
            txnRef:    $txnRef,
            amount:    (int) $booking->total_price,
            orderInfo: 'Dat phong #' . $booking->id . ' tai LuxeStay',
            returnUrl: route('vnpay.booking.return'),
            ipAddr:    request()->ip(),
        );

        return redirect()->away($url);
    }

    public function return(Request $request): RedirectResponse
    {
        $params = $request->query();

        if (! $this->vnpay->verifyReturn($params)) {
            return redirect()->route('account.index')->with('error', 'Invalid payment signature.');
        }

        $booking = Booking::where('vnpay_txn_ref', $params['vnp_TxnRef'])->firstOrFail();

        DB::transaction(function () use ($booking, $params) {
            if ($this->vnpay->isSuccessResponse($params)) {
                $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);
            }

            PaymentTransaction::create([
                'payable_type'     => Booking::class,
                'payable_id'       => $booking->id,
                'amount'           => $params['vnp_Amount'] / 100,
                'gateway'          => 'vnpay',
                'status'           => $this->vnpay->isSuccessResponse($params) ? 'success' : 'failed',
                'gateway_ref'      => $params['vnp_TransactionNo'] ?? null,
                'gateway_response' => $params,
            ]);
        });

        return redirect()->route('booking.show', $booking->id)
            ->with(
                $this->vnpay->isSuccessResponse($params) ? 'success' : 'error',
                $this->vnpay->isSuccessResponse($params) ? 'Payment successful! Your booking is confirmed.' : 'Payment failed. Please try again.'
            );
    }

    public function ipn(Request $request): Response
    {
        $params = $request->query();

        if (! $this->vnpay->verifyReturn($params)) {
            return response('{"RspCode":"97","Message":"Invalid Signature"}');
        }

        $booking = Booking::where('vnpay_txn_ref', $params['vnp_TxnRef'])->first();

        if (! $booking) {
            return response('{"RspCode":"01","Message":"Order not found"}');
        }

        if ($booking->payment_status === 'paid') {
            return response('{"RspCode":"02","Message":"Order already confirmed"}');
        }

        if ($this->vnpay->isSuccessResponse($params)) {
            $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);
        }

        return response('{"RspCode":"00","Message":"Confirm success"}');
    }
}
```

- [ ] **Step 8: Write feature test for VNPay booking flow**

Create `tests/Feature/Web/VNPayBookingTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VNPayBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.vnpay.tmn_code'    => 'TESTCODE',
            'services.vnpay.hash_secret' => 'testhashsecretkey123456789012345',
            'services.vnpay.url'         => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'services.vnpay.return_url'  => 'https://luxestay.ddev.site/vnpay/booking/return',
        ]);
    }

    public function test_pay_route_redirects_to_vnpay(): void
    {
        $user    = User::factory()->create();
        $type    = RoomType::factory()->create();
        $room    = Room::factory()->create(['room_type_id' => $type->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);

        $response = $this->actingAs($user)->get(route('booking.pay', $booking->id));

        $response->assertRedirect();
        $this->assertStringContainsString('sandbox.vnpayment.vn', $response->headers->get('Location'));
    }

    public function test_successful_vnpay_return_confirms_booking(): void
    {
        $user    = User::factory()->create();
        $type    = RoomType::factory()->create();
        $room    = Room::factory()->create(['room_type_id' => $type->id]);
        $booking = Booking::factory()->create([
            'user_id'        => $user->id,
            'room_id'        => $room->id,
            'vnpay_txn_ref'  => 'BK-TEST-001',
            'total_price'    => 500,
            'payment_status' => 'unpaid',
        ]);

        // Build a valid return URL params with correct signature
        $service = new \App\Services\VNPayService();
        $params  = [
            'vnp_ResponseCode'  => '00',
            'vnp_TxnRef'        => 'BK-TEST-001',
            'vnp_Amount'        => '50000',
            'vnp_TransactionNo' => '12345678',
        ];
        ksort($params);
        $hash                   = hash_hmac('sha512', http_build_query($params), 'testhashsecretkey123456789012345');
        $params['vnp_SecureHash'] = $hash;

        $this->get(route('vnpay.booking.return', $params));

        $this->assertDatabaseHas('bookings', [
            'id'             => $booking->id,
            'status'         => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    public function test_failed_vnpay_return_does_not_confirm_booking(): void
    {
        $user    = User::factory()->create();
        $type    = RoomType::factory()->create();
        $room    = Room::factory()->create(['room_type_id' => $type->id]);
        $booking = Booking::factory()->create([
            'user_id'       => $user->id,
            'room_id'       => $room->id,
            'vnpay_txn_ref' => 'BK-TEST-002',
            'total_price'   => 500,
        ]);

        $params = [
            'vnp_ResponseCode'  => '24',
            'vnp_TxnRef'        => 'BK-TEST-002',
            'vnp_Amount'        => '50000',
            'vnp_TransactionNo' => '12345679',
        ];
        ksort($params);
        $hash                   = hash_hmac('sha512', http_build_query($params), 'testhashsecretkey123456789012345');
        $params['vnp_SecureHash'] = $hash;

        $this->get(route('vnpay.booking.return', $params));

        $this->assertDatabaseMissing('bookings', [
            'id'             => $booking->id,
            'payment_status' => 'paid',
        ]);
    }
}
```

- [ ] **Step 9: Run all booking tests**

```bash
php artisan test tests/Feature/Web/BookingControllerTest.php tests/Feature/Web/VNPayBookingTest.php tests/Unit/Services/VNPayServiceTest.php
```
Expected: All tests pass.

- [ ] **Step 10: Commit**

```bash
git add -A
git commit -m "feat: add VNPayService and booking payment flow with return/IPN handlers"
```

---

## Task 4: Account — Booking History

**Files:**
- Modify: `app/Http/Controllers/Web/AccountController.php`
- Modify: `resources/views/pages/account/index.blade.php`
- Create: `tests/Feature/Web/AccountBookingHistoryTest.php`

- [ ] **Step 1: Write tests**

Create `tests/Feature/Web/AccountBookingHistoryTest.php`:

```php
<?php

namespace Tests\Feature\Web;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountBookingHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_page_shows_user_bookings(): void
    {
        $user    = User::factory()->create();
        $type    = RoomType::factory()->create();
        $room    = Room::factory()->create(['room_type_id' => $type->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);

        $this->actingAs($user)
            ->get(route('account.index'))
            ->assertStatus(200)
            ->assertViewHas('bookings');
    }

    public function test_account_page_does_not_show_other_users_bookings(): void
    {
        $user    = User::factory()->create();
        $other   = User::factory()->create();
        $type    = RoomType::factory()->create();
        $room    = Room::factory()->create(['room_type_id' => $type->id]);
        Booking::factory()->create(['user_id' => $other->id, 'room_id' => $room->id]);

        $response = $this->actingAs($user)->get(route('account.index'));
        $bookings = $response->viewData('bookings');

        $this->assertCount(0, $bookings);
    }
}
```

- [ ] **Step 2: Update AccountController@index**

In `app/Http/Controllers/Web/AccountController.php`:

```php
public function index(): View
{
    $user     = Auth::user();
    $bookings = $user->bookings()->with('room.roomType')->latest()->take(5)->get();

    return view('pages.account.index', compact('user', 'bookings'));
}
```

- [ ] **Step 3: Update account/index.blade.php to show bookings**

In `resources/views/pages/account/index.blade.php`, add booking list:

```blade
<h4>Recent Bookings</h4>
@forelse($bookings as $booking)
<div class="booking-row">
   <span>{{ $booking->room->name }}</span>
   <span>{{ $booking->check_in->format('M d, Y') }} → {{ $booking->check_out->format('M d, Y') }}</span>
   <span>{{ ucfirst($booking->status) }}</span>
   <span>${{ number_format($booking->total_price, 2) }}</span>
   <a href="{{ route('booking.show', $booking->id) }}">View</a>
</div>
@empty
<p>No bookings yet. <a href="{{ route('rooms.index') }}">Browse rooms</a></p>
@endforelse
```

- [ ] **Step 4: Run tests**

```bash
php artisan test tests/Feature/Web/AccountBookingHistoryTest.php
```
Expected: 2 tests pass.

- [ ] **Step 5: Run full test suite**

```bash
php artisan test
```
Expected: All tests pass.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "feat: complete Plan 2 (Rooms + Bookings + VNPay)"
```

---

## Plan 2 Complete ✓

**What was built:**
- Room availability checking with date overlap detection
- Booking form with validation and availability guard
- VNPayService (URL generation, HMAC-SHA512 signature, verify return/IPN)
- Full booking payment flow: create → VNPay redirect → return handler → confirm
- IPN endpoint for server-to-server verification
- Account page shows booking history

**Next:** [Plan 3 — Blog + Shop + VNPay Orders + Activities](2026-05-04-plan-3-blog-shop-activities.md)
