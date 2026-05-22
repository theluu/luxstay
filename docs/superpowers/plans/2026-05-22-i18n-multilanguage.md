# i18n Multilanguage Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add full multilanguage support (vi/en/zh) to LuxeStay — URL-prefixed routes, Blade UI strings, DB content via spatie/laravel-translatable, and vue-i18n for the admin SPA.

**Architecture:** Three independent layers — (1) Laravel `lang/` files + `__()` for Blade UI strings, (2) `spatie/laravel-translatable` JSON `translations` column on 7 models for DB content, (3) `vue-i18n@9` for admin SPA. All public routes wrapped in `/{locale}` prefix with `LocaleMiddleware`. URL defaults ensure existing `route()` calls work without modification.

**Tech Stack:** PHP 8.4 / Laravel 11, spatie/laravel-translatable, Vue 3 / vue-i18n@9, DDEV (MariaDB).

---

## Task 1: Install Packages

**Files:**
- Modify: `composer.json` (via composer)
- Modify: `package.json` (via npm)

- [ ] **Step 1: Install spatie/laravel-translatable**

```bash
ddev composer require spatie/laravel-translatable
```

Expected: `spatie/laravel-translatable` appears in `composer.json` require section.

- [ ] **Step 2: Install vue-i18n@9**

```bash
ddev exec npm install vue-i18n@9
```

Expected: `vue-i18n` appears in `package.json` dependencies.

- [ ] **Step 3: Commit**

```bash
git add composer.json composer.lock package.json package-lock.json
git commit -m "chore: install spatie/laravel-translatable and vue-i18n@9"
```

---

## Task 2: LocaleMiddleware + Register

**Files:**
- Create: `app/Http/Middleware/LocaleMiddleware.php`
- Modify: `bootstrap/app.php`

- [ ] **Step 1: Create LocaleMiddleware**

Create `app/Http/Middleware/LocaleMiddleware.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? config('app.locale');

        if (!in_array($locale, config('app.supported_locales', ['vi']))) {
            abort(404);
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
```

- [ ] **Step 2: Register middleware alias in bootstrap/app.php**

In `bootstrap/app.php`, add `'locale'` alias inside `->withMiddleware()`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin'  => \App\Http\Middleware\EnsureUserIsAdmin::class,
        'locale' => \App\Http\Middleware\LocaleMiddleware::class,
    ]);
})
```

- [ ] **Step 3: Commit**

```bash
git add app/Http/Middleware/LocaleMiddleware.php bootstrap/app.php
git commit -m "feat(i18n): add LocaleMiddleware with URL defaults"
```

---

## Task 3: Config + Helper

**Files:**
- Modify: `config/app.php`
- Create: `app/Helpers/locale_helpers.php`
- Modify: `composer.json` (autoload files)
- Modify: `.env`

- [ ] **Step 1: Add supported_locales to config/app.php**

In `config/app.php`, add after the existing `'locale'` line:

```php
'locale' => env('APP_LOCALE', 'vi'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'vi'),
'supported_locales' => ['vi', 'en', 'zh'],
```

- [ ] **Step 2: Update .env**

In `.env`, update locale lines:

```
APP_LOCALE=vi
APP_FALLBACK_LOCALE=vi
```

- [ ] **Step 3: Create locale helpers**

Create `app/Helpers/locale_helpers.php`:

```php
<?php

if (!function_exists('localeRoute')) {
    function localeRoute(string $locale, string $named, array $params = []): string
    {
        return route($named, array_merge(['locale' => $locale], $params));
    }
}

if (!function_exists('currentLocale')) {
    function currentLocale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('localizedUrl')) {
    /**
     * Prefix an internal URL path with the current locale.
     * External URLs (starting with http) are returned unchanged.
     */
    function localizedUrl(string $path): string
    {
        if (str_starts_with($path, 'http') || str_starts_with($path, '#')) {
            return $path;
        }
        $locale = app()->getLocale();
        return '/' . $locale . '/' . ltrim($path, '/');
    }
}
```

- [ ] **Step 4: Autoload the helper in composer.json**

In `composer.json`, add to `autoload.files`:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/Helpers/locale_helpers.php"
    ]
},
```

- [ ] **Step 5: Dump autoload**

```bash
ddev composer dump-autoload
```

- [ ] **Step 6: Commit**

```bash
git add config/app.php .env app/Helpers/locale_helpers.php composer.json composer.lock
git commit -m "feat(i18n): add supported_locales config and locale helpers"
```

---

## Task 4: Refactor routes/web.php

**Files:**
- Modify: `routes/web.php`
- Modify: `routes/auth.php` (check if auth routes need locale prefix)

- [ ] **Step 1: Wrap all public routes in locale prefix group**

Replace the entire content of `routes/web.php` with:

```php
<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\ActivityController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\VnpayPaymentController;
use Illuminate\Support\Facades\Route;

// Redirect root to default locale
Route::get('/', fn () => redirect('/vi'));

// All public routes with locale prefix
Route::prefix('{locale}')
    ->middleware('locale')
    ->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/search', [SearchController::class, 'index'])->name('search');

        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/suites', [RoomController::class, 'suites'])->name('rooms.suites');
        Route::get('/rooms/{slug}', [RoomController::class, 'show'])->name('rooms.show');

        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
        Route::post('/blog/{slug}/comments', [BlogController::class, 'storeComment'])->name('blog.comment');

        Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
        Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/confirmation', [CheckoutController::class, 'guestConfirmation'])->name('checkout.confirmation');

        Route::get('/payment/vnpay/return', [VnpayPaymentController::class, 'return'])->name('payment.vnpay.return');
        Route::get('/payment/vnpay/ipn', [VnpayPaymentController::class, 'ipn'])->name('payment.vnpay.ipn');

        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');

        Route::middleware('auth')->group(function () {
            Route::get('/account/bookings', [BookingController::class, 'index'])->name('bookings.index');
        });

        Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activity.show');

        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/contact', [PageController::class, 'contact'])->name('contact');
        Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');
        Route::get('/offers', [PageController::class, 'offers'])->name('offers');
        Route::get('/landing', [PageController::class, 'landing'])->name('landing');
        Route::get('/chinh-sach-bao-mat', [PageController::class, 'privacyPolicy'])->name('privacy');
        Route::post('/subscribe', [PageController::class, 'subscribe'])->name('subscribe');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

            Route::get('/account', [AccountController::class, 'index'])->name('account.index');
            Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
            Route::put('/account/edit', [AccountController::class, 'update'])->name('account.update');
            Route::get('/account/address', [AccountController::class, 'address'])->name('account.address');
            Route::put('/account/address', [AccountController::class, 'updateAddress'])->name('account.address.update');
            Route::get('/account/downloads', [AccountController::class, 'downloads'])->name('account.downloads');
            Route::get('/account/orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        });

        require __DIR__.'/auth.php';
    });

// Admin SPA — no locale prefix
Route::get('/admin/{any?}', fn () => view('layouts.admin'))
    ->where('any', '.*')
    ->name('admin');
```

- [ ] **Step 2: Smoke-test routes**

```bash
ddev artisan route:list --path=vi | head -20
```

Expected: routes listed with `vi/` prefix, e.g. `GET|HEAD vi/ home`.

- [ ] **Step 3: Clear route cache**

```bash
ddev artisan optimize:clear
```

- [ ] **Step 4: Commit**

```bash
git add routes/web.php
git commit -m "feat(i18n): wrap all public routes in /{locale} prefix group"
```

---

## Task 5: Lang Files — Vietnamese (vi)

**Files:**
- Create: `lang/vi/common.php`
- Create: `lang/vi/nav.php`
- Create: `lang/vi/auth.php`
- Create: `lang/vi/rooms.php`
- Create: `lang/vi/booking.php`
- Create: `lang/vi/contact.php`
- Create: `lang/vi/footer.php`
- Create: `lang/vi/blog.php`
- Create: `lang/vi/shop.php`

- [ ] **Step 1: Create lang/vi/common.php**

```php
<?php
return [
    'book_now'         => 'Đặt ngay',
    'read_more'        => 'Xem thêm',
    'view_all'         => 'Xem tất cả',
    'submit'           => 'Gửi',
    'save'             => 'Lưu',
    'cancel'           => 'Hủy',
    'search'           => 'Tìm kiếm',
    'loading'          => 'Đang tải...',
    'call_us'          => 'Gọi cho chúng tôi:',
    'email_us'         => 'Email cho chúng tôi:',
    'per_night'        => 'mỗi đêm',
    'from'             => 'Từ',
    'guests'           => 'khách',
    'bed'              => 'Giường',
    'amenities'        => 'Tiện nghi',
    'success'          => 'Thành công!',
    'error'            => 'Có lỗi xảy ra.',
    'search_placeholder' => 'Tìm kiếm phòng, blog, cửa hàng…',
    'search_hint'      => 'Nhấn Enter để tìm kiếm',
];
```

- [ ] **Step 2: Create lang/vi/nav.php**

```php
<?php
return [
    'home'       => 'Trang chủ',
    'rooms'      => 'Phòng',
    'blog'       => 'Blog',
    'shop'       => 'Cửa hàng',
    'about'      => 'Về chúng tôi',
    'contact'    => 'Liên hệ',
    'activities' => 'Hoạt động',
    'offers'     => 'Ưu đãi',
    'account'    => 'Tài khoản',
    'cart'       => 'Giỏ hàng',
    'login'      => 'Đăng nhập',
    'register'   => 'Đăng ký',
    'logout'     => 'Đăng xuất',
];
```

- [ ] **Step 3: Create lang/vi/auth.php**

```php
<?php
return [
    'full_name'              => 'Họ và tên',
    'email'                  => 'Email',
    'password'               => 'Mật khẩu',
    'confirm_password'       => 'Xác nhận mật khẩu',
    'remember_me'            => 'Ghi nhớ đăng nhập',
    'forgot_password'        => 'Quên mật khẩu?',
    'login'                  => 'Đăng nhập',
    'register'               => 'Đăng ký',
    'already_registered'     => 'Đã có tài khoản?',
    'reset_password'         => 'Đặt lại mật khẩu',
    'send_reset_link'        => 'Gửi liên kết đặt lại mật khẩu',
    'forgot_password_notice' => 'Quên mật khẩu? Không sao. Hãy cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một liên kết đặt lại mật khẩu.',
];
```

- [ ] **Step 4: Create lang/vi/rooms.php**

```php
<?php
return [
    'title'          => 'Phòng của chúng tôi',
    'detail_title'   => 'Chi tiết phòng',
    'size'           => 'Diện tích',
    'capacity'       => 'Sức chứa',
    'price_from'     => 'Từ',
    'per_night'      => '/ đêm',
    'book_this_room' => 'Đặt phòng này',
    'amenities'      => 'Tiện nghi',
    'description'    => 'Mô tả',
    'suites'         => 'Phòng Suite',
    'all_rooms'      => 'Tất cả phòng',
    'guests_label'   => ':count khách',
    'beds_label'     => '1 Giường',
];
```

- [ ] **Step 5: Create lang/vi/booking.php**

```php
<?php
return [
    'title'          => 'Đặt phòng',
    'check_in'       => 'Ngày nhận phòng',
    'check_out'      => 'Ngày trả phòng',
    'guests'         => 'Số khách',
    'full_name'      => 'Họ và tên',
    'email'          => 'Email',
    'phone'          => 'Số điện thoại',
    'notes'          => 'Ghi chú',
    'confirm'        => 'Xác nhận đặt phòng',
    'success'        => 'Đặt phòng thành công!',
    'my_bookings'    => 'Đặt phòng của tôi',
];
```

- [ ] **Step 6: Create lang/vi/contact.php**

```php
<?php
return [
    'title'            => 'Liên hệ',
    'subtitle'         => 'Liên hệ với chúng tôi',
    'heading'          => 'Dịch vụ & Hoạt động chất lượng gần bạn',
    'opening_days'     => 'Ngày mở cửa:',
    'opening_days_val' => 'Thứ Hai - Thứ Bảy',
    'opening_hours'    => 'Giờ mở cửa:',
    'phone_booking'    => 'Đặt phòng qua điện thoại:',
    'name'             => 'Họ và tên',
    'email'            => 'Email',
    'phone'            => 'Số điện thoại',
    'subject'          => 'Chủ đề',
    'message'          => 'Tin nhắn',
    'send'             => 'Gửi tin nhắn',
    'success'          => 'Tin nhắn của bạn đã được gửi thành công!',
];
```

- [ ] **Step 7: Create lang/vi/footer.php**

```php
<?php
return [
    'cta_heading'      => 'HÃY BẮT ĐẦU HÀNH TRÌNH CỦA BẠN VỚI LUXESTAY',
    'contact_us'       => 'Liên hệ',
    'newsletter_title' => 'Đăng ký nhận tin tức và ưu đãi độc quyền!',
    'newsletter_email' => 'Email của bạn',
    'newsletter_btn'   => 'ĐĂNG KÝ',
    'newsletter_ok'    => 'Đăng ký thành công!',
    'newsletter_err'   => 'Có lỗi xảy ra, vui lòng thử lại.',
    'copyright'        => 'Bản quyền © :year LuxeStay. Bảo lưu mọi quyền.',
    'privacy'          => 'Chính sách bảo mật',
];
```

- [ ] **Step 8: Create lang/vi/blog.php**

```php
<?php
return [
    'title'         => 'Blog',
    'all_posts'     => 'Tất cả bài viết',
    'read_more'     => 'Đọc thêm',
    'published_at'  => 'Đăng ngày :date',
    'by'            => 'Bởi :author',
    'comments'      => 'Bình luận',
    'leave_comment' => 'Để lại bình luận',
    'your_name'     => 'Họ tên',
    'your_email'    => 'Email',
    'your_comment'  => 'Bình luận',
    'post_comment'  => 'Gửi bình luận',
];
```

- [ ] **Step 9: Create lang/vi/shop.php**

```php
<?php
return [
    'title'          => 'Cửa hàng',
    'cart'           => 'Giỏ hàng',
    'checkout'       => 'Thanh toán',
    'add_to_cart'    => 'Thêm vào giỏ',
    'quantity'       => 'Số lượng',
    'total'          => 'Tổng cộng',
    'subtotal'       => 'Tạm tính',
    'remove'         => 'Xóa',
    'empty_cart'     => 'Giỏ hàng trống',
    'order_success'  => 'Đặt hàng thành công!',
    'price'          => 'Giá',
    'order_summary'  => 'Tóm tắt đơn hàng',
];
```

- [ ] **Step 10: Commit**

```bash
git add lang/vi/
git commit -m "feat(i18n): add Vietnamese lang files"
```

---

## Task 6: Lang Files — English (en) and Chinese (zh)

**Files:**
- Create: `lang/en/common.php`, `lang/en/nav.php`, `lang/en/auth.php`, `lang/en/rooms.php`, `lang/en/booking.php`, `lang/en/contact.php`, `lang/en/footer.php`, `lang/en/blog.php`, `lang/en/shop.php`
- Create: `lang/zh/` (same files)

- [ ] **Step 1: Create all English lang files**

Create `lang/en/common.php`:
```php
<?php
return [
    'book_now'         => 'Book Now',
    'read_more'        => 'Read More',
    'view_all'         => 'View All',
    'submit'           => 'Submit',
    'save'             => 'Save',
    'cancel'           => 'Cancel',
    'search'           => 'Search',
    'loading'          => 'Loading...',
    'call_us'          => 'Call Us:',
    'email_us'         => 'Email Us:',
    'per_night'        => 'per night',
    'from'             => 'From',
    'guests'           => 'guests',
    'bed'              => 'Bed',
    'amenities'        => 'Amenities',
    'success'          => 'Success!',
    'error'            => 'An error occurred.',
    'search_placeholder' => 'Search rooms, blog, shop…',
    'search_hint'      => 'Press Enter to search',
];
```

Create `lang/en/nav.php`:
```php
<?php
return [
    'home'       => 'Home',
    'rooms'      => 'Rooms',
    'blog'       => 'Blog',
    'shop'       => 'Shop',
    'about'      => 'About',
    'contact'    => 'Contact',
    'activities' => 'Activities',
    'offers'     => 'Offers',
    'account'    => 'Account',
    'cart'       => 'Cart',
    'login'      => 'Login',
    'register'   => 'Register',
    'logout'     => 'Logout',
];
```

Create `lang/en/auth.php`:
```php
<?php
return [
    'full_name'              => 'Full Name',
    'email'                  => 'Email',
    'password'               => 'Password',
    'confirm_password'       => 'Confirm Password',
    'remember_me'            => 'Remember me',
    'forgot_password'        => 'Forgot your password?',
    'login'                  => 'Log in',
    'register'               => 'Register',
    'already_registered'     => 'Already registered?',
    'reset_password'         => 'Reset Password',
    'send_reset_link'        => 'Email Password Reset Link',
    'forgot_password_notice' => 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.',
];
```

Create `lang/en/rooms.php`:
```php
<?php
return [
    'title'          => 'Our Rooms',
    'detail_title'   => 'Room Detail',
    'size'           => 'Size',
    'capacity'       => 'Capacity',
    'price_from'     => 'From',
    'per_night'      => '/ night',
    'book_this_room' => 'Book This Room',
    'amenities'      => 'Amenities',
    'description'    => 'Description',
    'suites'         => 'Suites',
    'all_rooms'      => 'All Rooms',
    'guests_label'   => ':count guests',
    'beds_label'     => '1 Bed',
];
```

Create `lang/en/booking.php`:
```php
<?php
return [
    'title'          => 'Book a Room',
    'check_in'       => 'Check-in Date',
    'check_out'      => 'Check-out Date',
    'guests'         => 'Guests',
    'full_name'      => 'Full Name',
    'email'          => 'Email',
    'phone'          => 'Phone',
    'notes'          => 'Notes',
    'confirm'        => 'Confirm Booking',
    'success'        => 'Booking successful!',
    'my_bookings'    => 'My Bookings',
];
```

Create `lang/en/contact.php`:
```php
<?php
return [
    'title'            => 'Contact',
    'subtitle'         => 'Get In Touch',
    'heading'          => 'Quality Services & Activities Near You',
    'opening_days'     => 'Opening Days:',
    'opening_days_val' => 'Monday - Saturday',
    'opening_hours'    => 'Opening Hours:',
    'phone_booking'    => 'Book by Phone:',
    'name'             => 'Full Name',
    'email'            => 'Email',
    'phone'            => 'Phone',
    'subject'          => 'Subject',
    'message'          => 'Message',
    'send'             => 'Send Message',
    'success'          => 'Your message has been sent successfully!',
];
```

Create `lang/en/footer.php`:
```php
<?php
return [
    'cta_heading'      => 'START YOUR JOURNEY WITH LUXESTAY',
    'contact_us'       => 'Contact Us',
    'newsletter_title' => 'Subscribe for news and exclusive offers!',
    'newsletter_email' => 'Your email',
    'newsletter_btn'   => 'SUBSCRIBE',
    'newsletter_ok'    => 'Subscribed successfully!',
    'newsletter_err'   => 'An error occurred, please try again.',
    'copyright'        => 'Copyright © :year LuxeStay. All rights reserved.',
    'privacy'          => 'Privacy Policy',
];
```

Create `lang/en/blog.php`:
```php
<?php
return [
    'title'         => 'Blog',
    'all_posts'     => 'All Posts',
    'read_more'     => 'Read More',
    'published_at'  => 'Published :date',
    'by'            => 'By :author',
    'comments'      => 'Comments',
    'leave_comment' => 'Leave a Comment',
    'your_name'     => 'Your Name',
    'your_email'    => 'Your Email',
    'your_comment'  => 'Comment',
    'post_comment'  => 'Post Comment',
];
```

Create `lang/en/shop.php`:
```php
<?php
return [
    'title'          => 'Shop',
    'cart'           => 'Cart',
    'checkout'       => 'Checkout',
    'add_to_cart'    => 'Add to Cart',
    'quantity'       => 'Quantity',
    'total'          => 'Total',
    'subtotal'       => 'Subtotal',
    'remove'         => 'Remove',
    'empty_cart'     => 'Your cart is empty',
    'order_success'  => 'Order placed successfully!',
    'price'          => 'Price',
    'order_summary'  => 'Order Summary',
];
```

- [ ] **Step 2: Create all Chinese (Simplified) lang files**

Create `lang/zh/common.php`:
```php
<?php
return [
    'book_now'         => '立即预订',
    'read_more'        => '阅读更多',
    'view_all'         => '查看全部',
    'submit'           => '提交',
    'save'             => '保存',
    'cancel'           => '取消',
    'search'           => '搜索',
    'loading'          => '加载中...',
    'call_us'          => '致电我们：',
    'email_us'         => '发送邮件：',
    'per_night'        => '每晚',
    'from'             => '起价',
    'guests'           => '位客人',
    'bed'              => '张床',
    'amenities'        => '设施',
    'success'          => '成功！',
    'error'            => '发生错误。',
    'search_placeholder' => '搜索客房、博客、商店…',
    'search_hint'      => '按回车键搜索',
];
```

Create `lang/zh/nav.php`:
```php
<?php
return [
    'home'       => '首页',
    'rooms'      => '客房',
    'blog'       => '博客',
    'shop'       => '商店',
    'about'      => '关于我们',
    'contact'    => '联系我们',
    'activities' => '活动',
    'offers'     => '优惠',
    'account'    => '账户',
    'cart'       => '购物车',
    'login'      => '登录',
    'register'   => '注册',
    'logout'     => '退出',
];
```

Create `lang/zh/auth.php`:
```php
<?php
return [
    'full_name'              => '姓名',
    'email'                  => '电子邮件',
    'password'               => '密码',
    'confirm_password'       => '确认密码',
    'remember_me'            => '记住我',
    'forgot_password'        => '忘记密码？',
    'login'                  => '登录',
    'register'               => '注册',
    'already_registered'     => '已有账户？',
    'reset_password'         => '重置密码',
    'send_reset_link'        => '发送重置密码链接',
    'forgot_password_notice' => '忘记了密码？没关系。请提供您的电子邮件地址，我们将向您发送重置密码的链接。',
];
```

Create `lang/zh/rooms.php`:
```php
<?php
return [
    'title'          => '我们的客房',
    'detail_title'   => '客房详情',
    'size'           => '面积',
    'capacity'       => '容纳',
    'price_from'     => '起价',
    'per_night'      => '/ 晚',
    'book_this_room' => '预订此房间',
    'amenities'      => '设施',
    'description'    => '描述',
    'suites'         => '套房',
    'all_rooms'      => '所有客房',
    'guests_label'   => ':count 位客人',
    'beds_label'     => '1 张床',
];
```

Create `lang/zh/booking.php`:
```php
<?php
return [
    'title'          => '预订客房',
    'check_in'       => '入住日期',
    'check_out'      => '退房日期',
    'guests'         => '客人数量',
    'full_name'      => '姓名',
    'email'          => '电子邮件',
    'phone'          => '电话',
    'notes'          => '备注',
    'confirm'        => '确认预订',
    'success'        => '预订成功！',
    'my_bookings'    => '我的预订',
];
```

Create `lang/zh/contact.php`:
```php
<?php
return [
    'title'            => '联系我们',
    'subtitle'         => '联系方式',
    'heading'          => '优质服务与活动，近在咫尺',
    'opening_days'     => '营业日：',
    'opening_days_val' => '周一 - 周六',
    'opening_hours'    => '营业时间：',
    'phone_booking'    => '电话预订：',
    'name'             => '姓名',
    'email'            => '电子邮件',
    'phone'            => '电话',
    'subject'          => '主题',
    'message'          => '留言',
    'send'             => '发送消息',
    'success'          => '您的消息已成功发送！',
];
```

Create `lang/zh/footer.php`:
```php
<?php
return [
    'cta_heading'      => '开启您与 LUXESTAY 的旅程',
    'contact_us'       => '联系我们',
    'newsletter_title' => '订阅获取新闻与专属优惠！',
    'newsletter_email' => '您的邮箱',
    'newsletter_btn'   => '订阅',
    'newsletter_ok'    => '订阅成功！',
    'newsletter_err'   => '发生错误，请重试。',
    'copyright'        => '版权所有 © :year LuxeStay。保留所有权利。',
    'privacy'          => '隐私政策',
];
```

Create `lang/zh/blog.php`:
```php
<?php
return [
    'title'         => '博客',
    'all_posts'     => '所有文章',
    'read_more'     => '阅读更多',
    'published_at'  => '发布于 :date',
    'by'            => '作者：:author',
    'comments'      => '评论',
    'leave_comment' => '发表评论',
    'your_name'     => '您的姓名',
    'your_email'    => '您的邮箱',
    'your_comment'  => '评论内容',
    'post_comment'  => '提交评论',
];
```

Create `lang/zh/shop.php`:
```php
<?php
return [
    'title'          => '商店',
    'cart'           => '购物车',
    'checkout'       => '结账',
    'add_to_cart'    => '加入购物车',
    'quantity'       => '数量',
    'total'          => '合计',
    'subtotal'       => '小计',
    'remove'         => '移除',
    'empty_cart'     => '购物车为空',
    'order_success'  => '订单提交成功！',
    'price'          => '价格',
    'order_summary'  => '订单摘要',
];
```

- [ ] **Step 3: Commit**

```bash
git add lang/
git commit -m "feat(i18n): add English and Chinese lang files"
```

---

## Task 7: Replace Blade Hardcoded Text with __()

**Files:**
- Modify: `resources/views/components/header.blade.php`
- Modify: `resources/views/components/footer.blade.php`
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/pages/contact.blade.php`
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/views/auth/register.blade.php`
- Modify: `resources/views/auth/forgot-password.blade.php`

- [ ] **Step 1: Update header.blade.php**

Replace hardcoded strings:
- `"Gọi cho chúng tôi:"` → `{{ __('common.call_us') }}`
- `"Email cho chúng tôi:"` → `{{ __('common.email_us') }}`
- `"Hoạt động"` → `{{ __('nav.activities') }}`
- Search placeholder `"Tìm kiếm phòng, blog, cửa hàng…"` → `{{ __('common.search_placeholder') }}`
- `"Nhấn Enter để tìm kiếm"` → `{{ __('common.search_hint') }}`
- Nav item URLs that start with `/` need locale prefix via `localizedUrl()`:

For nav items rendered in the `@foreach($navItems as $item)` loop, apply locale prefix to internal URLs:
```blade
<a class="nav-link" href="{{ localizedUrl($item['url']) }}">
    {{ $item['translations'][currentLocale()] ?? $item['label'] }}
</a>
```
Apply the same pattern to `$navRightItems` loop and all children.

For the "Hoạt động" submenu with activities:
```blade
<a class="nav-link" href="#">{{ __('nav.activities') }}<i ...></i></a>
...
<a class="nav-link" href="{{ route('activity.show', [$activity->slug]) }}">
    {{ $activity->title }}
</a>
```

- [ ] **Step 2: Update footer.blade.php**

Replace hardcoded strings:
- `"HÃY BẮT ĐẦU HÀNH TRÌNH CỦA BẠN VỚI LUXESTAY"` → `{{ __('footer.cta_heading') }}`
- `"Liên hệ"` (footer CTA button) → `{{ __('footer.contact_us') }}`
- `"Đăng ký nhận tin tức và ưu đãi độc quyền!"` → `{{ __('footer.newsletter_title') }}`
- `placeholder="Email của bạn"` → `placeholder="{{ __('footer.newsletter_email') }}"`
- `"ĐĂNG KÝ"` button text → `{{ __('footer.newsletter_btn') }}`
- Footer menu item URLs — apply `localizedUrl()`:
  ```blade
  <a href="{{ localizedUrl($footerItem['url']) }}">{{ $footerItem['label'] }}</a>
  ```
- Default fallback footer menu items — replace hardcoded labels with `__()` keys:
  ```php
  ['label' => __('nav.home'),    'url' => '/', ...],
  ['label' => __('nav.about'),   'url' => '/about', ...],
  ['label' => __('nav.rooms'),   'url' => '/rooms', ...],
  ['label' => __('nav.shop'),    'url' => '/shop', ...],
  ['label' => __('nav.blog'),    'url' => '/blog', ...],
  ['label' => __('nav.contact'), 'url' => '/contact', ...],
  ```

- [ ] **Step 3: Update layouts/app.blade.php**

Replace search overlay strings:
- `placeholder="Tìm kiếm phòng, blog, cửa hàng…"` → `placeholder="{{ __('common.search_placeholder') }}"`
- `"Nhấn Enter để tìm kiếm"` → `{{ __('common.search_hint') }}`

- [ ] **Step 4: Update contact.blade.php**

Replace:
- `'Liên hệ'` (h1) → `{{ __('contact.title') }}`
- `'Liên hệ với chúng tôi'` → `{{ __('contact.subtitle') }}`
- `'Dịch vụ & Hoạt động chất lượng gần bạn'` → `{{ __('contact.heading') }}`
- `'Ngày mở cửa:'` → `{{ __('contact.opening_days') }}`
- `'Thứ Hai - Thứ Bảy'` → `{{ __('contact.opening_days_val') }}`
- `'Giờ mở cửa:'` → `{{ __('contact.opening_hours') }}`
- `'Đặt phòng qua điện thoại:'` → `{{ __('contact.phone_booking') }}`
- Contact form field labels/placeholders → `__('contact.*')` keys

- [ ] **Step 5: Update auth views**

In `auth/login.blade.php`:
```blade
<x-input-label for="email" :value="__('auth.email')" />
<x-input-label for="password" :value="__('auth.password')" />
<span class="ms-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
<a ...>{{ __('auth.forgot_password') }}</a>
<x-primary-button ...>{{ __('auth.login') }}</x-primary-button>
```

In `auth/register.blade.php`:
```blade
<x-input-label for="name" :value="__('auth.full_name')" />
<x-input-label for="email" :value="__('auth.email')" />
<x-input-label for="password" :value="__('auth.password')" />
<x-input-label for="password_confirmation" :value="__('auth.confirm_password')" />
<a ...>{{ __('auth.already_registered') }}</a>
<x-primary-button ...>{{ __('auth.register') }}</x-primary-button>
```

In `auth/forgot-password.blade.php`:
```blade
{{ __('auth.forgot_password_notice') }}
<x-input-label for="email" :value="__('auth.email')" />
<x-primary-button>{{ __('auth.send_reset_link') }}</x-primary-button>
```

- [ ] **Step 6: Smoke-test in browser**

```bash
curl -sk https://luxestay.ddev.site/vi/ | grep -c "__\|undefined"
```

Expected: 0 (no raw `__()` calls leaking into HTML).

- [ ] **Step 7: Commit**

```bash
git add resources/views/
git commit -m "feat(i18n): replace hardcoded Blade strings with __() keys"
```

---

## Task 8: Database Migrations — expand column types for JSON

**Note:** spatie/laravel-translatable stores JSON translations **in the existing columns** (e.g. `name` becomes `{"vi":"Phòng Deluxe","en":"Deluxe Room","zh":"豪华客房"}`). No separate `translations` column is added. We only need to widen `VARCHAR` columns to `TEXT` so they can safely hold longer JSON strings.

**Files:**
- Create: `database/migrations/2026_05_22_000001_widen_translatable_columns.php`

- [ ] **Step 1: Create migration**

```bash
ddev artisan make:migration widen_translatable_columns
```

Open the generated file and replace its content:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen VARCHAR string columns to TEXT so they can store JSON translations.
        // TEXT/LONGTEXT columns (description, content, excerpt) are already wide enough.
        Schema::table('rooms', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('title')->change();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->text('title')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->text('title')->nullable()->change();
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('rooms',              fn (Blueprint $t) => $t->string('name')->change());
        Schema::table('posts',              fn (Blueprint $t) => $t->string('title')->change());
        Schema::table('activities',         fn (Blueprint $t) => $t->string('title')->change());
        Schema::table('products',           fn (Blueprint $t) => $t->string('name')->change());
        Schema::table('sliders',            fn (Blueprint $t) => $t->string('title')->nullable()->change());
        Schema::table('post_categories',    fn (Blueprint $t) => $t->string('name')->change());
        Schema::table('product_categories', fn (Blueprint $t) => $t->string('name')->change());
    }
};
```

- [ ] **Step 2: Run migration**

```bash
ddev artisan migrate
```

Expected: `Migrating: 2026_05_22_000001_widen_translatable_columns` ... `Migrated`.

- [ ] **Step 3: Commit**

```bash
git add database/migrations/
git commit -m "feat(i18n): widen translatable VARCHAR columns to TEXT for JSON storage"
```

---

## Task 9: Add HasTranslations to Models

**Files:**
- Modify: `app/Models/Room.php`
- Modify: `app/Models/Post.php`
- Modify: `app/Models/Activity.php`
- Modify: `app/Models/Product.php`
- Modify: `app/Models/Slider.php`
- Modify: `app/Models/PostCategory.php`
- Modify: `app/Models/ProductCategory.php`

- [ ] **Step 1: Update Room model**

Add `use HasTranslations;` and `$translatable`. Do NOT add `'translations'` to `$fillable` — spatie stores JSON in the existing `name`/`description` columns, no separate column exists:

```php
use Spatie\Translatable\HasTranslations;

class Room extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'room_type_id', 'name', 'slug', 'description',
        'price_per_night', 'max_guests', 'size_sqm',
        'thumbnail', 'gallery', 'is_available',
    ];
    // ... rest unchanged
}
```

- [ ] **Step 2: Update Post model**

```php
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'excerpt', 'content'];

    protected $fillable = [
        'post_category_id', 'author_id', 'title', 'slug',
        'excerpt', 'content', 'thumbnail', 'type', 'status', 'published_at',
    ];
    // ... rest unchanged
}
```

- [ ] **Step 3: Update Activity model**

```php
use Spatie\Translatable\HasTranslations;

class Activity extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'type', 'title', 'slug', 'content',
        'thumbnail', 'hero_image', 'is_featured', 'sort_order',
    ];
    // ... rest unchanged
}
```

- [ ] **Step 4: Update Product model**

```php
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'product_category_id', 'name', 'slug', 'description',
        'price', 'stock', 'thumbnail', 'gallery', 'is_active',
    ];
    // ... rest unchanged
}
```

- [ ] **Step 5: Update Slider model**

```php
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasTranslations;

    public $translatable = ['title'];

    protected $fillable = ['type', 'title', 'media_url', 'sort_order', 'is_active'];
    // ... rest unchanged
}
```

- [ ] **Step 6: Update PostCategory model**

```php
use Spatie\Translatable\HasTranslations;

class PostCategory extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name', 'slug', 'translations'];
    // ... rest unchanged
}
```

- [ ] **Step 7: Update ProductCategory model**

```php
use Spatie\Translatable\HasTranslations;

class ProductCategory extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name', 'slug', 'translations'];
    // ... rest unchanged
}
```

- [ ] **Step 8: Verify via tinker**

```bash
ddev artisan tinker --execute="
\$r = App\Models\Room::first();
app()->setLocale('en');
echo \$r->name;
"
```

Expected: returns the room name (falls back to stored value since no translation set yet).

- [ ] **Step 9: Commit**

```bash
git add app/Models/
git commit -m "feat(i18n): add HasTranslations trait to 7 models"
```

---

## Task 10: Locale Switcher Blade Component + hreflang

**Files:**
- Create: `resources/views/components/locale-switcher.blade.php`
- Modify: `resources/views/components/header.blade.php`
- Modify: `resources/views/layouts/app.blade.php`

- [ ] **Step 1: Create locale-switcher component**

Create `resources/views/components/locale-switcher.blade.php`:

```blade
@php
    $locales = [
        'vi' => ['flag' => '🇻🇳', 'label' => 'VI'],
        'en' => ['flag' => '🇬🇧', 'label' => 'EN'],
        'zh' => ['flag' => '🇨🇳', 'label' => 'ZH'],
    ];
    $current = app()->getLocale();

    // Build switcher URLs: replace {locale} in current route
    $routeName   = request()->route()?->getName() ?? 'home';
    $routeParams = request()->route()?->parameters() ?? [];
@endphp

<div class="locale-switcher position-relative">
    <button class="locale-switcher-toggle" type="button" aria-label="Select language">
        {{ $locales[$current]['flag'] }} {{ $locales[$current]['label'] }}
        <i class="fas fa-chevron-down" style="font-size:10px;margin-left:4px"></i>
    </button>
    <ul class="locale-switcher-dropdown">
        @foreach($locales as $code => $info)
            @php
                try {
                    $url = route($routeName, array_merge($routeParams, ['locale' => $code]));
                } catch (\Throwable $e) {
                    $url = '/' . $code;
                }
            @endphp
            <li>
                <a href="{{ $url }}" class="{{ $code === $current ? 'active' : '' }}">
                    {{ $info['flag'] }} {{ $info['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<style>
.locale-switcher { display:inline-block; }
.locale-switcher-toggle {
    background:none; border:1px solid rgba(255,255,255,.3); color:#fff;
    padding:4px 10px; border-radius:6px; cursor:pointer; font-size:13px;
    display:flex; align-items:center; gap:4px;
}
.locale-switcher-toggle:hover { border-color:rgba(255,255,255,.7); }
.locale-switcher-dropdown {
    display:none; position:absolute; top:110%; right:0; background:#1a1a2e;
    border:1px solid rgba(255,255,255,.15); border-radius:8px; padding:6px 0;
    min-width:110px; list-style:none; margin:0; z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,.3);
}
.locale-switcher:hover .locale-switcher-dropdown { display:block; }
.locale-switcher-dropdown a {
    display:block; padding:7px 14px; color:rgba(255,255,255,.75); text-decoration:none; font-size:13px;
}
.locale-switcher-dropdown a:hover,
.locale-switcher-dropdown a.active { color:#fff; background:rgba(255,255,255,.08); }
</style>
```

- [ ] **Step 2: Add switcher to header**

In `resources/views/components/header.blade.php`, inside `.sisf-widget-holder.sisf--two`, add `<x-locale-switcher />` after the cart icon and before login/account links.

- [ ] **Step 3: Add hreflang tags to layouts/app.blade.php**

In the `<head>` section of `resources/views/layouts/app.blade.php`, add before `@stack('styles')`:

```blade
@php
    $hreflangRoute = request()->route()?->getName() ?? 'home';
    $hreflangParams = request()->route()?->parameters() ?? [];
@endphp
@foreach(config('app.supported_locales') as $hreflangLocale)
    @php
        try {
            $hreflangUrl = route($hreflangRoute, array_merge($hreflangParams, ['locale' => $hreflangLocale]));
        } catch (\Throwable $e) {
            $hreflangUrl = url('/' . $hreflangLocale);
        }
    @endphp
    <link rel="alternate" hreflang="{{ $hreflangLocale }}" href="{{ $hreflangUrl }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ url('/vi') }}">
```

- [ ] **Step 4: Test switcher in browser**

```bash
curl -sk https://luxestay.ddev.site/vi/ | grep "locale-switcher"
```

Expected: component HTML found in output.

- [ ] **Step 5: Commit**

```bash
git add resources/views/components/locale-switcher.blade.php \
        resources/views/components/header.blade.php \
        resources/views/layouts/app.blade.php
git commit -m "feat(i18n): add locale switcher component and hreflang tags"
```

---

## Task 11: Extend Admin API for Translations

**Files:**
- Modify: `app/Http/Controllers/Api/` — all controllers that serve translatable models

For each controller that handles Room, Post, Activity, Product, Slider, PostCategory, ProductCategory:
- GET: include `translations` in response (spatie auto-includes it via `$model->toArray()`)
- PUT/POST: extract `translations` from request and save each locale via `setTranslations()`

- [ ] **Step 1: Create a reusable trait for saving and returning translations**

Create `app/Http/Controllers/Api/Concerns/SavesTranslations.php`:

```php
<?php

namespace App\Http\Controllers\Api\Concerns;

trait SavesTranslations
{
    /**
     * Apply per-locale translation data to a spatie translatable model.
     *
     * Request format: { "translations": { "name": {"vi": "...", "en": "...", "zh": "..."} } }
     * Spatie stores each field's JSON in that field's own column (no separate column).
     */
    protected function applyTranslations($model, array $translations): void
    {
        foreach ($translations as $field => $localeValues) {
            if (is_array($localeValues) && in_array($field, $model->translatable ?? [])) {
                $model->setTranslations($field, $localeValues);
            }
        }
    }

    /**
     * Return all locale translations for all translatable fields.
     * Use this in show/index responses so the admin can populate tab panels.
     * Example: {"name": {"vi": "...", "en": "..."}, "description": {...}}
     */
    protected function allTranslations($model): array
    {
        $result = [];
        foreach ($model->translatable ?? [] as $field) {
            $result[$field] = $model->getTranslations($field);
        }
        return $result;
    }
}
```

- [ ] **Step 2: Find all admin API controllers for translatable models**

```bash
ls /home/theluu/Desktop/Projects/luxstay/app/Http/Controllers/Api/
```

Identify controllers for: rooms, posts, activities, products, sliders, post categories, product categories. For each, apply the `SavesTranslations` trait and update `show`/`update` methods:

```php
use App\Http\Controllers\Api\Concerns\SavesTranslations;

class RoomController extends Controller
{
    use SavesTranslations;

    public function show(Room $room): JsonResponse
    {
        return response()->json([
            'data' => array_merge($room->toArray(), [
                'all_translations' => $this->allTranslations($room),
            ]),
        ]);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([/* existing rules */]);
        $room->fill($validated);
        if ($request->has('translations')) {
            $this->applyTranslations($room, $request->input('translations', []));
        }
        $room->save();
        return response()->json([
            'data' => array_merge($room->toArray(), [
                'all_translations' => $this->allTranslations($room),
            ]),
        ]);
    }
}
```

Apply the same `show` and `update` pattern to: `PostController`, `ActivityController`, `ProductController`, `SliderController`, and any category controllers.

The GET list (index) endpoints do **not** need `all_translations` — the admin loads translations only when opening the edit form (single record).

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/Api/Concerns/ app/Http/Controllers/Api/
git commit -m "feat(i18n): extend admin API controllers to handle translations"
```

---

## Task 12: vue-i18n Admin Setup

**Files:**
- Create: `resources/js/admin/i18n.js`
- Create: `resources/js/admin/locales/vi.json`
- Create: `resources/js/admin/locales/en.json`
- Create: `resources/js/admin/locales/zh.json`
- Modify: `resources/js/admin/main.js`

- [ ] **Step 1: Create locale files**

Create `resources/js/admin/locales/vi.json`:
```json
{
  "common": {
    "save": "Lưu",
    "cancel": "Hủy",
    "delete": "Xóa",
    "edit": "Sửa",
    "create": "Tạo mới",
    "search": "Tìm kiếm",
    "loading": "Đang tải...",
    "confirm_delete": "Bạn có chắc muốn xóa?",
    "yes": "Có",
    "no": "Không",
    "actions": "Thao tác",
    "status": "Trạng thái",
    "active": "Hoạt động",
    "inactive": "Không hoạt động"
  },
  "nav": {
    "dashboard": "Bảng điều khiển",
    "rooms": "Phòng",
    "posts": "Bài viết",
    "activities": "Hoạt động",
    "products": "Sản phẩm",
    "orders": "Đơn hàng",
    "bookings": "Đặt phòng",
    "sliders": "Slider",
    "settings": "Cài đặt",
    "menu": "Menu",
    "footer": "Footer",
    "about": "Về chúng tôi",
    "comments": "Bình luận",
    "messages": "Tin nhắn",
    "subscribers": "Người đăng ký",
    "payments": "Thanh toán"
  },
  "rooms": {
    "title": "Quản lý phòng",
    "add": "Thêm phòng",
    "name": "Tên phòng",
    "price": "Giá/đêm",
    "capacity": "Sức chứa",
    "size": "Diện tích"
  },
  "translations": {
    "tab_label": "Bản dịch",
    "vi": "🇻🇳 Tiếng Việt",
    "en": "🇬🇧 English",
    "zh": "🇨🇳 中文",
    "title_field": "Tiêu đề",
    "content_field": "Nội dung",
    "description_field": "Mô tả",
    "name_field": "Tên",
    "excerpt_field": "Tóm tắt"
  }
}
```

Create `resources/js/admin/locales/en.json`:
```json
{
  "common": {
    "save": "Save",
    "cancel": "Cancel",
    "delete": "Delete",
    "edit": "Edit",
    "create": "Create",
    "search": "Search",
    "loading": "Loading...",
    "confirm_delete": "Are you sure you want to delete?",
    "yes": "Yes",
    "no": "No",
    "actions": "Actions",
    "status": "Status",
    "active": "Active",
    "inactive": "Inactive"
  },
  "nav": {
    "dashboard": "Dashboard",
    "rooms": "Rooms",
    "posts": "Posts",
    "activities": "Activities",
    "products": "Products",
    "orders": "Orders",
    "bookings": "Bookings",
    "sliders": "Sliders",
    "settings": "Settings",
    "menu": "Menu",
    "footer": "Footer",
    "about": "About",
    "comments": "Comments",
    "messages": "Messages",
    "subscribers": "Subscribers",
    "payments": "Payments"
  },
  "rooms": {
    "title": "Room Management",
    "add": "Add Room",
    "name": "Room Name",
    "price": "Price/night",
    "capacity": "Capacity",
    "size": "Size"
  },
  "translations": {
    "tab_label": "Translations",
    "vi": "🇻🇳 Vietnamese",
    "en": "🇬🇧 English",
    "zh": "🇨🇳 Chinese",
    "title_field": "Title",
    "content_field": "Content",
    "description_field": "Description",
    "name_field": "Name",
    "excerpt_field": "Excerpt"
  }
}
```

Create `resources/js/admin/locales/zh.json`:
```json
{
  "common": {
    "save": "保存",
    "cancel": "取消",
    "delete": "删除",
    "edit": "编辑",
    "create": "新建",
    "search": "搜索",
    "loading": "加载中...",
    "confirm_delete": "确定要删除吗？",
    "yes": "是",
    "no": "否",
    "actions": "操作",
    "status": "状态",
    "active": "启用",
    "inactive": "禁用"
  },
  "nav": {
    "dashboard": "仪表板",
    "rooms": "客房",
    "posts": "文章",
    "activities": "活动",
    "products": "商品",
    "orders": "订单",
    "bookings": "预订",
    "sliders": "轮播图",
    "settings": "设置",
    "menu": "菜单",
    "footer": "页脚",
    "about": "关于",
    "comments": "评论",
    "messages": "消息",
    "subscribers": "订阅者",
    "payments": "支付"
  },
  "rooms": {
    "title": "客房管理",
    "add": "添加客房",
    "name": "房间名称",
    "price": "每晚价格",
    "capacity": "容纳人数",
    "size": "面积"
  },
  "translations": {
    "tab_label": "翻译",
    "vi": "🇻🇳 越南语",
    "en": "🇬🇧 英语",
    "zh": "🇨🇳 中文",
    "title_field": "标题",
    "content_field": "内容",
    "description_field": "描述",
    "name_field": "名称",
    "excerpt_field": "摘要"
  }
}
```

- [ ] **Step 2: Create i18n.js**

Create `resources/js/admin/i18n.js`:

```js
import { createI18n } from 'vue-i18n'
import vi from './locales/vi.json'
import en from './locales/en.json'
import zh from './locales/zh.json'

const savedLocale = localStorage.getItem('admin_locale') || 'vi'

export const i18n = createI18n({
    legacy: false,
    locale: savedLocale,
    fallbackLocale: 'vi',
    messages: { vi, en, zh },
})
```

- [ ] **Step 3: Update main.js to use i18n**

Replace `resources/js/admin/main.js`:

```js
import '../../css/admin.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router/index'
import App from './App.vue'
import { i18n } from './i18n'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(i18n)
app.mount('#admin-app')
```

- [ ] **Step 4: Commit**

```bash
git add resources/js/admin/i18n.js resources/js/admin/locales/ resources/js/admin/main.js
git commit -m "feat(i18n): set up vue-i18n in admin SPA with vi/en/zh locales"
```

---

## Task 13: Admin Locale Switcher + Translation Tab Panels

**Files:**
- Modify: `resources/js/admin/components/AppLayout.vue`
- Modify: `resources/js/admin/views/Rooms/RoomFormView.vue`
- Modify: `resources/js/admin/views/Posts/PostFormView.vue`

- [ ] **Step 1: Add locale switcher to AppLayout.vue**

In the bottom section of the sidebar (near user avatar), add a locale switcher after the user info block. Replace the closing `</div>` of the `px-4 py-4 border-t` section to include:

```vue
<template>
  <!-- ... existing sidebar content ... -->
  
  <!-- Locale switcher — add inside the px-4 py-4 border-t div, after user info -->
  <div class="flex gap-1 mt-2" :class="collapsed ? 'justify-center' : ''">
    <button
      v-for="loc in ['vi', 'en', 'zh']"
      :key="loc"
      @click="switchLocale(loc)"
      class="text-xs px-2 py-1 rounded transition-colors"
      :class="currentLocale === loc
        ? 'bg-white/20 text-white font-bold'
        : 'text-purple-300 hover:text-white hover:bg-white/10'"
    >
      {{ loc.toUpperCase() }}
    </button>
  </div>
</template>
```

In the `<script setup>` of `AppLayout.vue`, add:

```js
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
const currentLocale = computed(() => locale.value)

function switchLocale(loc) {
  locale.value = loc
  localStorage.setItem('admin_locale', loc)
}
```

- [ ] **Step 2: Add translation tabs to RoomFormView.vue**

In the room create/edit form, wrap the translatable fields (`name`, `description`) in a tab panel. Read the current structure of `RoomFormView.vue` first, then add:

```vue
<!-- Translation tabs -->
<div class="mb-6">
  <div class="flex gap-2 mb-4 border-b border-gray-200">
    <button
      v-for="tab in [
        { code: 'vi', label: '🇻🇳 Tiếng Việt' },
        { code: 'en', label: '🇬🇧 English' },
        { code: 'zh', label: '🇨🇳 中文' }
      ]"
      :key="tab.code"
      type="button"
      @click="activeTranslationTab = tab.code"
      class="px-4 py-2 text-sm font-medium transition-colors"
      :class="activeTranslationTab === tab.code
        ? 'border-b-2 border-indigo-500 text-indigo-600'
        : 'text-gray-500 hover:text-gray-700'"
    >
      {{ tab.label }}
    </button>
  </div>

  <template v-for="loc in ['vi', 'en', 'zh']" :key="loc">
    <div v-show="activeTranslationTab === loc">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          {{ $t('translations.name_field') }}
        </label>
        <input
          v-model="form.translations.name[loc]"
          type="text"
          class="w-full border rounded-lg px-3 py-2 text-sm"
        />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          {{ $t('translations.description_field') }}
        </label>
        <textarea
          v-model="form.translations.description[loc]"
          rows="4"
          class="w-full border rounded-lg px-3 py-2 text-sm"
        ></textarea>
      </div>
    </div>
  </template>
</div>
```

Add to the component's `data`/`ref` state:
```js
const activeTranslationTab = ref('vi')

// In form state, add translations object:
const form = ref({
  // ... existing fields ...
  translations: {
    name: { vi: '', en: '', zh: '' },
    description: { vi: '', en: '', zh: '' },
  }
})
```

When loading an existing room (edit mode), populate `form.translations` from the API response's `translations` field.

When submitting, include `translations` in the payload sent to `PUT /api/admin/rooms/{id}`.

- [ ] **Step 3: Apply same tab pattern to PostFormView.vue**

Same pattern as Step 2, but fields are `title`, `excerpt`, `content`:

```js
const form = ref({
  // ... existing fields ...
  translations: {
    title:   { vi: '', en: '', zh: '' },
    excerpt: { vi: '', en: '', zh: '' },
    content: { vi: '', en: '', zh: '' },
  }
})
```

Tabs show `title` and `excerpt` fields; `content` (rich text / textarea) also shown per tab.

- [ ] **Step 4: Build assets**

```bash
ddev exec npm run build
```

Expected: build completes without errors.

- [ ] **Step 5: Commit**

```bash
git add resources/js/admin/
git commit -m "feat(i18n): admin locale switcher and translation tab panels"
```

---

## Task 14: Final Verification

- [ ] **Step 1: Clear all caches**

```bash
ddev artisan optimize:clear
```

- [ ] **Step 2: Test root redirect**

```bash
curl -sk -o /dev/null -w "%{http_code} %{redirect_url}" https://luxestay.ddev.site/
```

Expected: `302 https://luxestay.ddev.site/vi`.

- [ ] **Step 3: Test locale switching via URL**

```bash
curl -sk -o /dev/null -w "%{http_code}" https://luxestay.ddev.site/vi/
curl -sk -o /dev/null -w "%{http_code}" https://luxestay.ddev.site/en/
curl -sk -o /dev/null -w "%{http_code}" https://luxestay.ddev.site/zh/
```

Expected: all return `200`.

- [ ] **Step 4: Test invalid locale returns 404**

```bash
curl -sk -o /dev/null -w "%{http_code}" https://luxestay.ddev.site/fr/
```

Expected: `404`.

- [ ] **Step 5: Test translated UI string**

```bash
curl -sk https://luxestay.ddev.site/en/ | grep -i "call us\|book now\|activities"
curl -sk https://luxestay.ddev.site/zh/ | grep -o "立即预订\|活动"
```

Expected: English/Chinese strings appear.

- [ ] **Step 6: Test hreflang tags**

```bash
curl -sk https://luxestay.ddev.site/vi/ | grep "hreflang"
```

Expected: 4 `<link rel="alternate" hreflang="...">` tags.

- [ ] **Step 7: Final commit**

```bash
git add .wolf/memory.md .wolf/anatomy.md
git commit -m "feat(i18n): complete multilanguage implementation (vi/en/zh)"
```
