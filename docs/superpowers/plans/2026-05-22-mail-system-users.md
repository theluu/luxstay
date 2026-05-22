# Mail System + User Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add SMTP mail config in admin settings (dynamic, stored in DB), send transactional emails for contact/booking/subscribe/registration, add user management list in admin, and add phone field to registration.

**Architecture:** `MailSettingController` stores SMTP keys in `site_settings` (same key-value pattern as `PaymentSettingController`). `AppServiceProvider::boot()` reads them at startup and reconfigures Laravel's Mail dynamically. Five Mailable classes share a base Blade layout. `UsersView.vue` is a read-only admin list with search and role filter.

**Tech Stack:** Laravel Mailable + Blade email templates, Vue 3 (Composition API), Axios, Tailwind CSS, DDEV (ddev artisan / ddev exec).

---

## File Map

**Create:**
- `app/Http/Controllers/Api/MailSettingController.php` — SMTP CRUD + test-email endpoint
- `app/Http/Controllers/Api/UserController.php` — admin user list
- `app/Mail/ContactReceived.php` — admin notification on contact form
- `app/Mail/ContactAutoReply.php` — auto-reply to form sender
- `app/Mail/BookingConfirmation.php` — booking confirmation to guest/user
- `app/Mail/SubscriberWelcome.php` — welcome email for new subscriber
- `app/Mail/WelcomeEmail.php` — welcome email for new registered user
- `resources/views/emails/layouts/base.blade.php` — shared luxury hotel email layout
- `resources/views/emails/contact-received.blade.php`
- `resources/views/emails/contact-auto-reply.blade.php`
- `resources/views/emails/booking-confirmation.blade.php`
- `resources/views/emails/subscriber-welcome.blade.php`
- `resources/views/emails/welcome.blade.php`
- `resources/js/admin/views/Users/UsersView.vue` — admin user list page
- `database/migrations/2026_05_22_100000_add_phone_to_users_table.php`

**Modify:**
- `app/Providers/AppServiceProvider.php` — dynamic mail config in `boot()`
- `app/Models/User.php` — add `phone` to `#[Fillable]`
- `app/Http/Controllers/Web/PageController.php` — dispatch emails on contact + subscribe
- `app/Http/Controllers/Web/BookingController.php` — dispatch email on booking confirmed
- `app/Http/Controllers/Auth/RegisteredUserController.php` — phone field + welcome email
- `resources/views/auth/register.blade.php` — add phone input
- `routes/api.php` — add mail-settings + users routes
- `resources/js/admin/views/Settings/SettingsView.vue` — add mail config section
- `resources/js/admin/router/index.js` — add `/admin/users` route
- `resources/js/admin/components/AppLayout.vue` — add Users nav link + icon
- `resources/js/admin/locales/vi.json` — add `nav.users` key
- `resources/js/admin/locales/en.json` — add `nav.users` key
- `resources/js/admin/locales/zh.json` — add `nav.users` key

---

## Task 1: Migration — Add phone to users table

**Files:**
- Create: `database/migrations/2026_05_22_100000_add_phone_to_users_table.php`
- Modify: `app/Models/User.php`

- [ ] **Step 1: Create migration**

```php
<?php
// database/migrations/2026_05_22_100000_add_phone_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
```

- [ ] **Step 2: Update User model — add `phone` to fillable**

In `app/Models/User.php`, replace the `#[Fillable]` attribute:

```php
#[Fillable(['name', 'email', 'phone', 'password', 'role'])]
```

- [ ] **Step 3: Run migration**

```bash
ddev artisan migrate
```

Expected output contains: `2026_05_22_100000_add_phone_to_users_table ... DONE`

- [ ] **Step 4: Commit**

```bash
git add database/migrations/2026_05_22_100000_add_phone_to_users_table.php app/Models/User.php
git commit -m "feat: add phone field to users table"
```

---

## Task 2: Dynamic Mail Config in AppServiceProvider

**Files:**
- Modify: `app/Providers/AppServiceProvider.php`

- [ ] **Step 1: Update `AppServiceProvider::boot()`**

```php
<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Models\SiteSetting;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer(['components.header', 'components.footer', 'layouts.app'], NavigationComposer::class);

        try {
            $mailer = SiteSetting::get('mail_mailer');
            if ($mailer && $mailer !== 'log') {
                config([
                    'mail.default'                      => $mailer,
                    'mail.mailers.smtp.host'            => SiteSetting::get('mail_host', '127.0.0.1'),
                    'mail.mailers.smtp.port'            => (int) SiteSetting::get('mail_port', 587),
                    'mail.mailers.smtp.username'        => SiteSetting::get('mail_username'),
                    'mail.mailers.smtp.password'        => SiteSetting::get('mail_password'),
                    'mail.mailers.smtp.encryption'      => SiteSetting::get('mail_encryption', 'tls') ?: null,
                    'mail.from.address'                 => SiteSetting::get('mail_from_address', config('mail.from.address')),
                    'mail.from.name'                    => SiteSetting::get('mail_from_name', config('mail.from.name')),
                ]);
            }
        } catch (\Exception) {
            // DB not ready (e.g. fresh install, migrations pending) — use .env defaults
        }
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Providers/AppServiceProvider.php
git commit -m "feat: dynamic SMTP config from site_settings at boot"
```

---

## Task 3: MailSettingController (API)

**Files:**
- Create: `app/Http/Controllers/Api/MailSettingController.php`
- Modify: `routes/api.php`

- [ ] **Step 1: Create controller**

```php
<?php
// app/Http/Controllers/Api/MailSettingController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailSettingController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json(['data' => $this->settings()]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mail_mailer'       => 'required|in:smtp,log,sendmail',
            'mail_host'         => 'nullable|string|max:255',
            'mail_port'         => 'nullable|integer|min:1|max:65535',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|in:tls,ssl,starttls,',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name'    => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            if ($key === 'mail_password' && trim((string) $value) === '') {
                continue; // keep existing password if empty submitted
            }
            SiteSetting::set($key, $value ?? '');
        }

        return response()->json(['data' => $this->settings()]);
    }

    public function testEmail(Request $request): JsonResponse
    {
        $request->validate(['to' => 'required|email']);

        try {
            // Re-apply config so the test uses freshly saved settings
            $mailer = SiteSetting::get('mail_mailer', 'log');
            if ($mailer && $mailer !== 'log') {
                config([
                    'mail.default'                 => $mailer,
                    'mail.mailers.smtp.host'       => SiteSetting::get('mail_host'),
                    'mail.mailers.smtp.port'       => (int) SiteSetting::get('mail_port', 587),
                    'mail.mailers.smtp.username'   => SiteSetting::get('mail_username'),
                    'mail.mailers.smtp.password'   => SiteSetting::get('mail_password'),
                    'mail.mailers.smtp.encryption' => SiteSetting::get('mail_encryption', 'tls') ?: null,
                    'mail.from.address'            => SiteSetting::get('mail_from_address', config('mail.from.address')),
                    'mail.from.name'               => SiteSetting::get('mail_from_name', config('mail.from.name')),
                ]);
            }

            Mail::raw('Đây là email test từ LuxeStay Admin. Cấu hình SMTP hoạt động tốt!', function ($msg) use ($request) {
                $msg->to($request->to)->subject('🏨 LuxeStay — Test Email');
            });

            return response()->json(['message' => 'Email test đã được gửi tới ' . $request->to]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gửi thất bại: ' . $e->getMessage()], 422);
        }
    }

    private function settings(): array
    {
        return [
            'mail_mailer'           => SiteSetting::get('mail_mailer', 'log'),
            'mail_host'             => SiteSetting::get('mail_host', ''),
            'mail_port'             => (int) SiteSetting::get('mail_port', 587),
            'mail_username'         => SiteSetting::get('mail_username', ''),
            'mail_password'         => '',
            'mail_has_password'     => !empty(SiteSetting::get('mail_password')),
            'mail_encryption'       => SiteSetting::get('mail_encryption', 'tls'),
            'mail_from_address'     => SiteSetting::get('mail_from_address', config('mail.from.address', '')),
            'mail_from_name'        => SiteSetting::get('mail_from_name', config('mail.from.name', '')),
        ];
    }
}
```

- [ ] **Step 2: Add routes to `routes/api.php`**

Inside the `auth:sanctum + admin` middleware group, after the payment-settings routes, add:

```php
use App\Http\Controllers\Api\MailSettingController;
use App\Http\Controllers\Api\UserController;
// (add these to the existing use block at top of file)

// inside the middleware group:
Route::get('/mail-settings',    [MailSettingController::class, 'show']);
Route::put('/mail-settings',    [MailSettingController::class, 'update']);
Route::post('/mail-settings/test', [MailSettingController::class, 'testEmail']);

Route::get('/users', [UserController::class, 'index']);
```

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/Api/MailSettingController.php routes/api.php
git commit -m "feat: MailSettingController — CRUD + test email API"
```

---

## Task 4: UserController (API)

**Files:**
- Create: `app/Http/Controllers/Api/UserController.php`

- [ ] **Step 1: Create controller**

```php
<?php
// app/Http/Controllers/Api/UserController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()->select('id', 'name', 'email', 'phone', 'role', 'created_at');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }

        if ($request->filled('role') && in_array($request->role, ['admin', 'user'])) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);

        return response()->json($users);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Http/Controllers/Api/UserController.php
git commit -m "feat: UserController — admin user list with search and role filter"
```

---

## Task 5: Email Base Layout + 5 Blade Templates

**Files:**
- Create: `resources/views/emails/layouts/base.blade.php`
- Create: `resources/views/emails/contact-received.blade.php`
- Create: `resources/views/emails/contact-auto-reply.blade.php`
- Create: `resources/views/emails/booking-confirmation.blade.php`
- Create: `resources/views/emails/subscriber-welcome.blade.php`
- Create: `resources/views/emails/welcome.blade.php`

- [ ] **Step 1: Create base layout**

```blade
{{-- resources/views/emails/layouts/base.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('subject', 'LuxeStay')</title>
  <style>
    body { margin:0; padding:0; background:#f4f4f5; font-family:'Helvetica Neue',Arial,sans-serif; color:#1a1a1a; }
    .wrapper { max-width:600px; margin:32px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .header { background:#0d0d0d; padding:32px 40px; text-align:center; }
    .header h1 { margin:0; color:#e8c97a; font-size:26px; letter-spacing:3px; font-weight:300; text-transform:uppercase; }
    .header p { margin:6px 0 0; color:#9ca3af; font-size:12px; letter-spacing:2px; text-transform:uppercase; }
    .content { padding:40px; }
    .content h2 { font-size:20px; margin:0 0 16px; color:#111; }
    .content p { line-height:1.7; color:#374151; margin:0 0 14px; font-size:15px; }
    .detail-box { background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:20px 24px; margin:20px 0; }
    .detail-box table { width:100%; border-collapse:collapse; }
    .detail-box td { padding:6px 0; font-size:14px; color:#374151; vertical-align:top; }
    .detail-box td:first-child { font-weight:600; color:#111; width:40%; padding-right:12px; }
    .btn { display:inline-block; background:#0d0d0d; color:#e8c97a !important; padding:13px 32px; border-radius:4px; text-decoration:none; font-size:14px; font-weight:600; letter-spacing:1px; margin:8px 0; }
    .divider { border:none; border-top:1px solid #f3f4f6; margin:24px 0; }
    .footer { background:#f9fafb; padding:24px 40px; text-align:center; border-top:1px solid #f3f4f6; }
    .footer p { font-size:12px; color:#9ca3af; margin:0 0 4px; }
    .footer a { color:#6b7280; text-decoration:none; }
    @media (max-width:600px) {
      .content { padding:28px 20px; }
      .header { padding:24px 20px; }
      .footer { padding:20px; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>LuxeStay</h1>
    <p>Luxury Hotel &amp; Resort</p>
  </div>
  <div class="content">
    @yield('content')
  </div>
  <div class="footer">
    <p>© {{ date('Y') }} LuxeStay — Luxury Hotel &amp; Resort</p>
    <p><a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
  </div>
</div>
</body>
</html>
```

- [ ] **Step 2: Create contact-received template (to admin)**

```blade
{{-- resources/views/emails/contact-received.blade.php --}}
@extends('emails.layouts.base')
@section('subject', 'Tin nhắn liên hệ mới từ ' . $senderName)
@section('content')
<h2>📩 Tin nhắn liên hệ mới</h2>
<p>Bạn vừa nhận được một tin nhắn liên hệ từ website LuxeStay.</p>
<div class="detail-box">
  <table>
    <tr><td>Họ tên</td><td>{{ $senderName }}</td></tr>
    <tr><td>Email</td><td>{{ $senderEmail }}</td></tr>
    <tr><td>Nguồn</td><td>{{ $source }}</td></tr>
    <tr><td>Nội dung</td><td>{{ $messageText }}</td></tr>
  </table>
</div>
<p>Hãy phản hồi sớm để duy trì trải nghiệm khách hàng tốt nhất.</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Email này được gửi tự động từ hệ thống LuxeStay.</p>
@endsection
```

- [ ] **Step 3: Create contact-auto-reply template (to form sender)**

```blade
{{-- resources/views/emails/contact-auto-reply.blade.php --}}
@extends('emails.layouts.base')
@section('subject', 'Cảm ơn bạn đã liên hệ với LuxeStay')
@section('content')
<h2>Cảm ơn bạn, {{ $senderName }}!</h2>
<p>Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong vòng <strong>24 giờ làm việc</strong>.</p>
<div class="detail-box">
  <table>
    <tr><td>Nội dung đã gửi</td><td>{{ $messageText }}</td></tr>
  </table>
</div>
<p>Trong thời gian chờ đợi, bạn có thể khám phá các phòng và dịch vụ của chúng tôi:</p>
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/rooms" class="btn">Khám phá phòng nghỉ</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu bạn cần hỗ trợ khẩn cấp, hãy liên hệ trực tiếp qua điện thoại.</p>
@endsection
```

- [ ] **Step 4: Create booking-confirmation template**

```blade
{{-- resources/views/emails/booking-confirmation.blade.php --}}
@extends('emails.layouts.base')
@section('subject', 'Xác nhận đặt phòng #' . $booking->id . ' — LuxeStay')
@section('content')
<h2>🏨 Đặt phòng đã được xác nhận!</h2>
<p>Xin chào <strong>{{ $guestName }}</strong>,</p>
<p>Chúng tôi đã nhận được yêu cầu đặt phòng của bạn. Chi tiết như sau:</p>
<div class="detail-box">
  <table>
    <tr><td>Mã đặt phòng</td><td>#{{ $booking->id }}</td></tr>
    <tr><td>Phòng</td><td>{{ $booking->room->name ?? 'N/A' }}</td></tr>
    <tr><td>Nhận phòng</td><td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</td></tr>
    <tr><td>Trả phòng</td><td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</td></tr>
    <tr><td>Số khách</td><td>{{ $booking->guests }}</td></tr>
    <tr><td>Tổng tiền</td><td>{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</td></tr>
    <tr><td>Trạng thái</td><td>{{ $booking->payment_method === 'vnpay' ? 'Đã thanh toán online' : 'Thanh toán khi đến' }}</td></tr>
  </table>
</div>
@if($booking->special_requests)
<p><strong>Yêu cầu đặc biệt:</strong> {{ $booking->special_requests }}</p>
@endif
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/bookings/{{ $booking->id }}/confirmation" class="btn">Xem chi tiết đặt phòng</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu có thắc mắc, vui lòng liên hệ với chúng tôi qua email hoặc điện thoại.</p>
@endsection
```

- [ ] **Step 5: Create subscriber-welcome template**

```blade
{{-- resources/views/emails/subscriber-welcome.blade.php --}}
@extends('emails.layouts.base')
@section('subject', 'Chào mừng bạn đến với LuxeStay Newsletter!')
@section('content')
<h2>🎉 Bạn đã đăng ký thành công!</h2>
<p>Cảm ơn bạn đã đăng ký nhận bản tin của <strong>LuxeStay</strong>.</p>
<p>Bạn sẽ là người đầu tiên nhận được:</p>
<ul style="line-height:2;color:#374151;font-size:15px;padding-left:20px;">
  <li>Ưu đãi độc quyền dành riêng cho thành viên</li>
  <li>Thông tin về phòng và gói dịch vụ mới</li>
  <li>Tin tức sự kiện và hoạt động tại resort</li>
</ul>
<p style="text-align:center;margin-top:28px;">
  <a href="{{ config('app.url') }}/vi" class="btn">Khám phá LuxeStay</a>
</p>
<hr class="divider">
<p style="font-size:12px;color:#9ca3af;text-align:center;">
  Để hủy đăng ký, bạn có thể liên hệ với chúng tôi bất kỳ lúc nào.
</p>
@endsection
```

- [ ] **Step 6: Create welcome (registration) template**

```blade
{{-- resources/views/emails/welcome.blade.php --}}
@extends('emails.layouts.base')
@section('subject', 'Chào mừng bạn đến với LuxeStay, ' . $user->name . '!')
@section('content')
<h2>Chào mừng, {{ $user->name }}! 👋</h2>
<p>Tài khoản LuxeStay của bạn đã được tạo thành công. Bây giờ bạn có thể:</p>
<ul style="line-height:2;color:#374151;font-size:15px;padding-left:20px;">
  <li>Đặt phòng và theo dõi lịch sử đặt phòng</li>
  <li>Mua sản phẩm từ cửa hàng resort</li>
  <li>Quản lý thông tin cá nhân</li>
</ul>
<div class="detail-box">
  <table>
    <tr><td>Email đăng nhập</td><td>{{ $user->email }}</td></tr>
    @if($user->phone)
    <tr><td>Số điện thoại</td><td>{{ $user->phone }}</td></tr>
    @endif
  </table>
</div>
<p style="text-align:center;margin-top:24px;">
  <a href="{{ config('app.url') }}/vi/rooms" class="btn">Khám phá phòng nghỉ</a>
</p>
<hr class="divider">
<p style="font-size:13px;color:#9ca3af;">Nếu bạn không tạo tài khoản này, hãy bỏ qua email này.</p>
@endsection
```

- [ ] **Step 7: Commit templates**

```bash
git add resources/views/emails/
git commit -m "feat: email Blade templates — base layout + 5 transactional templates"
```

---

## Task 6: Five Mailable Classes

**Files:**
- Create: `app/Mail/ContactReceived.php`
- Create: `app/Mail/ContactAutoReply.php`
- Create: `app/Mail/BookingConfirmation.php`
- Create: `app/Mail/SubscriberWelcome.php`
- Create: `app/Mail/WelcomeEmail.php`

- [ ] **Step 1: ContactReceived**

```php
<?php
// app/Mail/ContactReceived.php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactReceived extends Mailable
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly string $messageText,
        public readonly string $source = 'contact_page',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tin nhắn liên hệ mới từ ' . $this->senderName);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-received');
    }
}
```

- [ ] **Step 2: ContactAutoReply**

```php
<?php
// app/Mail/ContactAutoReply.php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactAutoReply extends Mailable
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $messageText,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Cảm ơn bạn đã liên hệ với LuxeStay');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-auto-reply');
    }
}
```

- [ ] **Step 3: BookingConfirmation**

```php
<?php
// app/Mail/BookingConfirmation.php
namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingConfirmation extends Mailable
{
    public function __construct(
        public readonly Booking $booking,
        public readonly string  $guestName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Xác nhận đặt phòng #' . $this->booking->id . ' — LuxeStay');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-confirmation');
    }
}
```

- [ ] **Step 4: SubscriberWelcome**

```php
<?php
// app/Mail/SubscriberWelcome.php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriberWelcome extends Mailable
{
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Chào mừng bạn đến với LuxeStay Newsletter!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscriber-welcome');
    }
}
```

- [ ] **Step 5: WelcomeEmail**

```php
<?php
// app/Mail/WelcomeEmail.php
namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeEmail extends Mailable
{
    public function __construct(
        public readonly User $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Chào mừng bạn đến với LuxeStay, ' . $this->user->name . '!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welcome');
    }
}
```

- [ ] **Step 6: Commit**

```bash
git add app/Mail/
git commit -m "feat: Mailable classes — ContactReceived, ContactAutoReply, BookingConfirmation, SubscriberWelcome, WelcomeEmail"
```

---

## Task 7: Wire Mailables into Controllers

**Files:**
- Modify: `app/Http/Controllers/Web/PageController.php`
- Modify: `app/Http/Controllers/Web/BookingController.php`
- Modify: `app/Http/Controllers/Auth/RegisteredUserController.php`
- Modify: `resources/views/auth/register.blade.php`

- [ ] **Step 1: Update PageController — contactStore + subscribe**

Replace the entire `app/Http/Controllers/Web/PageController.php`:

```php
<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AboutPageController;
use App\Mail\ContactAutoReply;
use App\Mail\ContactReceived;
use App\Mail\SubscriberWelcome;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\Subscriber;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about', ['aboutPage' => AboutPageController::getAboutPage()]);
    }
    public function contact(): View { return view('pages.contact'); }
    public function offers(): View  { return view('pages.offers'); }
    public function landing(): View { return view('pages.landing'); }
    public function privacyPolicy(): View { return view('pages.privacy-policy'); }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|max:255']);

        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'subscribe')) {
            return response()->json(['message' => 'Xác minh bảo mật thất bại. Vui lòng thử lại.'], 422);
        }

        if (Subscriber::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email này đã được đăng ký.'], 409);
        }

        Subscriber::create(['email' => $request->email]);

        try {
            Mail::to($request->email)->send(new SubscriberWelcome());
        } catch (\Exception) {
            // Mail failure must not break the subscription
        }

        return response()->json(['message' => 'Đăng ký thành công! Cảm ơn bạn.']);
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email|max:255',
            'msg'    => 'required|string|max:2000',
            'source' => 'nullable|string|in:contact_page,home_extra_service',
        ]);

        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'contact')) {
            return back()->withInput()->with('error', 'Xác minh bảo mật thất bại. Vui lòng thử lại.');
        }

        ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->msg,
            'source'  => $request->input('source', 'contact_page'),
        ]);

        try {
            $adminEmail = SiteSetting::get('email', config('mail.from.address'));
            Mail::to($adminEmail)->send(new ContactReceived(
                senderName:  $request->name,
                senderEmail: $request->email,
                messageText: $request->msg,
                source:      $request->input('source', 'contact_page'),
            ));
            Mail::to($request->email)->send(new ContactAutoReply(
                senderName:  $request->name,
                messageText: $request->msg,
            ));
        } catch (\Exception) {
            // Mail failure must not break contact submission
        }

        return back()
            ->withInput($request->only('source'))
            ->with('success', 'Cảm ơn bạn! Thông tin đã được gửi, chúng tôi sẽ liên hệ lại sớm.');
    }
}
```

- [ ] **Step 2: Update BookingController::store — send confirmation email**

In `app/Http/Controllers/Web/BookingController.php`, add at the top of the file (use imports):

```php
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
```

After `$booking = Booking::create([...])` and before the `if (!Auth::check())` session line, add the email dispatch. Find the block that reads:

```php
        $booking = Booking::create([
            'user_id'          => Auth::id(),
            'room_id'          => $room->id,
            'check_in'         => $data['check_in'],
            'check_out'        => $data['check_out'],
            'guests'           => $data['guests'],
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'total_price'      => $total,
            'special_requests' => $this->buildSpecialRequests($data),
        ]);

        if (!Auth::check()) {
```

Replace with:

```php
        $booking = Booking::create([
            'user_id'          => Auth::id(),
            'room_id'          => $room->id,
            'check_in'         => $data['check_in'],
            'check_out'        => $data['check_out'],
            'guests'           => $data['guests'],
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'total_price'      => $total,
            'special_requests' => $this->buildSpecialRequests($data),
        ]);

        $guestEmail = Auth::check() ? Auth::user()->email : ($data['guest_email'] ?? null);
        $guestName  = Auth::check() ? Auth::user()->name  : ($data['guest_name']  ?? 'Quý khách');
        if ($guestEmail) {
            try {
                $booking->load('room');
                Mail::to($guestEmail)->send(new BookingConfirmation($booking, $guestName));
            } catch (\Exception) {
                // Mail failure must not break booking
            }
        }

        if (!Auth::check()) {
```

- [ ] **Step 3: Update RegisteredUserController — add phone + send welcome email**

Replace `app/Http/Controllers/Auth/RegisteredUserController.php`:

```php
<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Services\RecaptchaService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!RecaptchaService::verify($request->input('recaptcha_token', ''), 'register')) {
            return back()->withInput($request->only('name', 'email'))
                ->withErrors(['email' => 'Xác minh bảo mật thất bại. Vui lòng thử lại.']);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception) {
            // Mail failure must not break registration
        }

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
```

- [ ] **Step 4: Update register.blade.php — add phone field**

In `resources/views/auth/register.blade.php`, after the Email block (before Password block), add:

```blade
        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('auth.phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
```

- [ ] **Step 5: Add `auth.phone` translation key to all 3 lang files**

In `lang/vi/auth.php`, inside the return array, add:
```php
'phone' => 'Số điện thoại',
```

In `lang/en/auth.php`, inside the return array, add:
```php
'phone' => 'Phone Number',
```

In `lang/zh/auth.php`, inside the return array, add:
```php
'phone' => '电话号码',
```

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/Web/PageController.php \
        app/Http/Controllers/Web/BookingController.php \
        app/Http/Controllers/Auth/RegisteredUserController.php \
        resources/views/auth/register.blade.php \
        lang/
git commit -m "feat: wire emails — contact, booking, subscribe, registration; add phone to register"
```

---

## Task 8: Admin Settings — Mail Config Tab

**Files:**
- Modify: `resources/js/admin/views/Settings/SettingsView.vue`

- [ ] **Step 1: Replace SettingsView.vue with tabbed version**

```vue
<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Cài đặt trang web</h1>

    <!-- Tab Nav -->
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-xl w-fit">
      <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
        :class="['px-4 py-2 rounded-lg text-sm font-medium transition', activeTab === tab.key ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-700']">
        {{ tab.label }}
      </button>
    </div>

    <div v-if="loading" class="text-gray-400">Đang tải…</div>
    <template v-else>

      <!-- ===== TAB: GENERAL ===== -->
      <template v-if="activeTab === 'general'">
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-4">Thông tin chung</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Tên trang web</label>
              <input v-model="form.site_name" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Điện thoại</label>
              <input v-model="form.phone" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Email liên hệ</label>
              <input v-model="form.email" type="email" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-4">Logo</h2>
          <ImageUpload v-model="form.logo" label="Ảnh logo" />
          <p class="text-xs text-gray-400 mt-1">Hiện tại: {{ form.logo }}</p>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">Favicon</h2>
          <p class="text-xs text-gray-400 mb-4">Biểu tượng hiển thị trên tab trình duyệt. Khuyến nghị: file .ico hoặc .png kích thước 32×32 hoặc 64×64.</p>
          <ImageUpload v-model="form.favicon" label="Ảnh favicon" />
          <div v-if="form.favicon" class="mt-3 flex items-center gap-3">
            <img :src="'/' + form.favicon" class="w-8 h-8 rounded border object-contain bg-gray-50" alt="favicon preview" />
            <span class="text-xs text-gray-500">{{ form.favicon }}</span>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-4">Mạng xã hội</h2>
          <div class="space-y-4">
            <div v-for="social in socials" :key="social.key">
              <label class="block text-sm font-medium mb-1">
                <i :class="social.icon" class="mr-1"></i> {{ social.label }}
              </label>
              <input v-model="form[social.key]" type="url" placeholder="https://..."
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">reCAPTCHA v3</h2>
          <p class="text-xs text-gray-400 mb-4">
            Bảo vệ tất cả form. Lấy key tại
            <a href="https://www.google.com/recaptcha/admin" target="_blank" class="underline">Google reCAPTCHA Admin</a>.
          </p>
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="form.recaptcha_enabled" class="sr-only peer" true-value="1" false-value="0" />
                <div class="w-10 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-black after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
              </label>
              <span class="text-sm font-medium">Bật reCAPTCHA</span>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Site Key <span class="text-gray-400 font-normal">(public)</span></label>
              <input v-model="form.recaptcha_site_key" placeholder="6Le..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">
                Secret Key <span class="text-gray-400 font-normal">(private)</span>
                <span v-if="form.recaptcha_has_secret_key" class="ml-2 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Đã lưu</span>
              </label>
              <input v-model="form.recaptcha_secret_key" type="password" placeholder="Nhập key mới để thay đổi…"
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" autocomplete="new-password" />
              <p class="text-xs text-gray-400 mt-1">Để trống nếu không muốn thay đổi secret key đã lưu.</p>
            </div>
          </div>
        </section>

        <SaveBar :saving="saving" :saved="saved" :error="error" @save="saveGeneral" />
      </template>

      <!-- ===== TAB: EMAIL ===== -->
      <template v-if="activeTab === 'email'">
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">Cấu hình SMTP</h2>
          <p class="text-xs text-slate-500 mb-5">Thiết lập máy chủ gửi email. Cài đặt sẽ có hiệu lực ngay sau khi lưu.</p>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Mailer</label>
              <select v-model="mail.mail_mailer" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <option value="smtp">SMTP</option>
                <option value="log">Log (không gửi thật, ghi vào log)</option>
                <option value="sendmail">Sendmail</option>
              </select>
            </div>
            <template v-if="mail.mail_mailer === 'smtp'">
              <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                  <label class="block text-sm font-medium mb-1">SMTP Host</label>
                  <input v-model="mail.mail_host" placeholder="smtp.gmail.com" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Port</label>
                  <input v-model.number="mail.mail_port" type="number" placeholder="587" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Encryption</label>
                <select v-model="mail.mail_encryption" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                  <option value="tls">TLS (khuyến nghị, port 587)</option>
                  <option value="ssl">SSL (port 465)</option>
                  <option value="starttls">STARTTLS</option>
                  <option value="">Không mã hóa</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input v-model="mail.mail_username" placeholder="your@email.com" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">
                  Password
                  <span v-if="mail.mail_has_password" class="ml-2 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Đã lưu</span>
                </label>
                <input v-model="mail.mail_password" type="password" placeholder="Nhập mật khẩu mới để thay đổi…"
                  class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" autocomplete="new-password" />
                <p class="text-xs text-gray-400 mt-1">Để trống nếu không muốn thay đổi mật khẩu đã lưu.</p>
              </div>
            </template>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium mb-1">From Address</label>
                <input v-model="mail.mail_from_address" type="email" placeholder="noreply@luxestay.com" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">From Name</label>
                <input v-model="mail.mail_from_name" placeholder="LuxeStay" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
              </div>
            </div>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">Gửi email test</h2>
          <p class="text-xs text-slate-500 mb-4">Kiểm tra kết nối SMTP bằng cách gửi email thử nghiệm.</p>
          <div class="flex gap-3">
            <input v-model="testEmail" type="email" placeholder="Địa chỉ nhận email test"
              class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            <button @click="sendTestEmail" :disabled="testSending"
              class="px-5 py-2 bg-slate-800 text-white text-sm rounded-lg disabled:opacity-50 hover:bg-slate-900 transition whitespace-nowrap">
              {{ testSending ? 'Đang gửi…' : 'Gửi test' }}
            </button>
          </div>
          <p v-if="testResult" :class="['mt-2 text-sm', testResult.ok ? 'text-emerald-600' : 'text-red-500']">
            {{ testResult.msg }}
          </p>
        </section>

        <SaveBar :saving="mailSaving" :saved="mailSaved" :error="mailError" @save="saveMail" />
      </template>

    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import SaveBar from '../../components/SaveBar.vue'
import api from '../../stores/api'

const loading   = ref(true)
const activeTab = ref('general')

const tabs = [
  { key: 'general', label: '⚙️ Chung' },
  { key: 'email',   label: '📧 Email' },
]

// --- General ---
const saving = ref(false)
const saved  = ref(false)
const error  = ref('')

const form = ref({
  site_name: '', phone: '', email: '', logo: '', favicon: '',
  facebook_url: '', instagram_url: '', linkedin_url: '', twitter_url: '',
  recaptcha_enabled: '0', recaptcha_site_key: '', recaptcha_secret_key: '',
  recaptcha_has_secret_key: false,
})

const socials = [
  { key: 'facebook_url',  label: 'Facebook',    icon: 'fab fa-facebook' },
  { key: 'instagram_url', label: 'Instagram',   icon: 'fab fa-instagram' },
  { key: 'linkedin_url',  label: 'LinkedIn',    icon: 'fab fa-linkedin' },
  { key: 'twitter_url',   label: 'Twitter / X', icon: 'fab fa-x-twitter' },
]

// --- Mail ---
const mailSaving = ref(false)
const mailSaved  = ref(false)
const mailError  = ref('')
const testEmail  = ref('')
const testSending = ref(false)
const testResult  = ref(null)

const mail = ref({
  mail_mailer: 'log', mail_host: '', mail_port: 587,
  mail_username: '', mail_password: '', mail_has_password: false,
  mail_encryption: 'tls', mail_from_address: '', mail_from_name: '',
})

onMounted(async () => {
  const [settingsRes, mailRes] = await Promise.all([
    api.get('/settings'),
    api.get('/mail-settings'),
  ])
  Object.assign(form.value, settingsRes.data.data)
  form.value.recaptcha_secret_key = ''
  Object.assign(mail.value, mailRes.data.data)
  mail.value.mail_password = ''
  loading.value = false
})

async function saveGeneral() {
  saving.value = true; saved.value = false; error.value = ''
  try {
    const payload = { ...form.value }
    delete payload.recaptcha_has_secret_key
    const { data } = await api.put('/settings', payload)
    form.value.recaptcha_has_secret_key = data.data.recaptcha_has_secret_key ?? false
    form.value.recaptcha_secret_key = ''
    saved.value = true; setTimeout(() => saved.value = false, 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally { saving.value = false }
}

async function saveMail() {
  mailSaving.value = true; mailSaved.value = false; mailError.value = ''
  try {
    const payload = { ...mail.value }
    delete payload.mail_has_password
    await api.put('/mail-settings', payload)
    mail.value.mail_password = ''
    mailSaved.value = true; setTimeout(() => mailSaved.value = false, 3000)
  } catch (e) {
    mailError.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally { mailSaving.value = false }
}

async function sendTestEmail() {
  if (!testEmail.value) return
  testSending.value = true; testResult.value = null
  try {
    const { data } = await api.post('/mail-settings/test', { to: testEmail.value })
    testResult.value = { ok: true, msg: data.message }
  } catch (e) {
    testResult.value = { ok: false, msg: e.response?.data?.message || 'Gửi thất bại.' }
  } finally { testSending.value = false }
}
</script>
```

- [ ] **Step 2: Create `SaveBar` reusable component** (used twice in SettingsView)

```vue
<!-- resources/js/admin/components/SaveBar.vue -->
<template>
  <div class="max-w-2xl flex items-center gap-4 mt-2">
    <button @click="$emit('save')" :disabled="saving"
      class="bg-slate-900 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-60 hover:bg-black transition">
      {{ saving ? 'Đang lưu…' : 'Lưu cài đặt' }}
    </button>
    <span v-if="saved" class="text-emerald-600 text-sm font-medium">✓ Đã lưu thành công</span>
    <span v-if="error" class="text-red-500 text-sm">{{ error }}</span>
  </div>
</template>

<script setup>
defineProps({ saving: Boolean, saved: Boolean, error: String })
defineEmits(['save'])
</script>
```

- [ ] **Step 3: Commit**

```bash
git add resources/js/admin/views/Settings/SettingsView.vue \
        resources/js/admin/components/SaveBar.vue
git commit -m "feat: Settings UI — add Email tab with SMTP config and test email"
```

---

## Task 9: UsersView.vue + Router + Nav

**Files:**
- Create: `resources/js/admin/views/Users/UsersView.vue`
- Modify: `resources/js/admin/router/index.js`
- Modify: `resources/js/admin/components/AppLayout.vue`
- Modify: `resources/js/admin/locales/vi.json`
- Modify: `resources/js/admin/locales/en.json`
- Modify: `resources/js/admin/locales/zh.json`

- [ ] **Step 1: Create UsersView.vue**

```vue
<!-- resources/js/admin/views/Users/UsersView.vue -->
<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Người dùng</h1>
        <p class="text-sm text-slate-500 mt-1">Danh sách tài khoản đã đăng ký</p>
      </div>
      <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full font-medium">
        {{ pagination?.total ?? 0 }} tài khoản
      </span>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-5">
      <input v-model="search" @input="onSearch" type="text" placeholder="Tìm theo tên hoặc email…"
        class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 max-w-xs" />
      <select v-model="roleFilter" @change="load(1)"
        class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
        <option value="">Tất cả vai trò</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <svg class="w-6 h-6 animate-spin text-purple-500" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
      <span class="ml-2 text-slate-500 text-sm">Đang tải...</span>
    </div>

    <!-- Empty -->
    <div v-else-if="!users.length" class="bg-white rounded-2xl border border-slate-200 p-16 text-center">
      <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <p class="text-slate-500 text-sm">Không tìm thấy người dùng nào</p>
    </div>

    <!-- Table -->
    <div v-else class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50">
            <th class="text-left px-6 py-3 font-semibold text-slate-600">#</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Người dùng</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Điện thoại</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Vai trò</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Ngày đăng ký</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, i) in users" :key="user.id"
              class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
            <td class="px-6 py-3.5 text-slate-400 text-xs">{{ (pagination.current_page - 1) * pagination.per_page + i + 1 }}</td>
            <td class="px-6 py-3.5">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                  :style="{ background: avatarColor(user.name) }">
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="font-medium text-slate-800">{{ user.name }}</p>
                  <p class="text-xs text-slate-400">{{ user.email }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-3.5 text-slate-500 text-xs">{{ user.phone || '—' }}</td>
            <td class="px-6 py-3.5">
              <span :class="user.role === 'admin'
                ? 'bg-purple-100 text-purple-700 border border-purple-200'
                : 'bg-slate-100 text-slate-600 border border-slate-200'"
                class="text-xs px-2.5 py-1 rounded-full font-medium">
                {{ user.role === 'admin' ? 'Admin' : 'User' }}
              </span>
            </td>
            <td class="px-6 py-3.5 text-slate-400 text-xs">{{ formatDate(user.created_at) }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1"
           class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        <span class="text-xs text-slate-500">
          Trang {{ pagination.current_page }} / {{ pagination.last_page }}
        </span>
        <div class="flex gap-1">
          <button v-for="page in visiblePages" :key="page"
            @click="load(page)" :disabled="page === pagination.current_page"
            :class="['px-3 py-1.5 rounded-lg text-xs font-medium transition',
              page === pagination.current_page
                ? 'bg-purple-600 text-white'
                : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50']">
            {{ page }}
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const users      = ref([])
const pagination = ref(null)
const loading    = ref(true)
const search     = ref('')
const roleFilter = ref('')
let   searchTimeout = null

onMounted(() => load(1))

async function load(page = 1) {
  loading.value = true
  try {
    const params = { page }
    if (search.value)     params.search = search.value
    if (roleFilter.value) params.role   = roleFilter.value
    const { data } = await api.get('/users', { params })
    users.value      = data.data
    pagination.value = { current_page: data.current_page, last_page: data.last_page, per_page: data.per_page, total: data.total }
  } finally { loading.value = false }
}

function onSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => load(1), 350)
}

const visiblePages = computed(() => {
  if (!pagination.value) return []
  const cur = pagination.value.current_page
  const last = pagination.value.last_page
  const pages = []
  for (let p = Math.max(1, cur - 2); p <= Math.min(last, cur + 2); p++) pages.push(p)
  return pages
})

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

function avatarColor(name) {
  const colors = ['#8b5cf6','#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#ec4899']
  let hash = 0
  for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash)
  return colors[Math.abs(hash) % colors.length]
}
</script>
```

- [ ] **Step 2: Add route to router/index.js**

At the top of `resources/js/admin/router/index.js`, add:

```js
import UsersView from '../views/Users/UsersView.vue'
```

Inside the `routes` array, after the `subscribers` route, add:

```js
    { path: '/admin/users', component: UsersView },
```

- [ ] **Step 3: Add Users nav link to AppLayout.vue**

In `resources/js/admin/components/AppLayout.vue`, find the `customers` nav group and add the users link. Find:

```js
  { key: 'customers', label: t('nav.group_customers'), links: [
    { to: '/admin/comments',    label: t('nav.comments'),    icon: icons.chat },
    { to: '/admin/messages',    label: t('nav.messages'),    icon: icons.mail },
    { to: '/admin/subscribers', label: t('nav.subscribers'), icon: icons.bell },
  ] },
```

Replace with:

```js
  { key: 'customers', label: t('nav.group_customers'), links: [
    { to: '/admin/users',       label: t('nav.users'),       icon: icons.users },
    { to: '/admin/comments',    label: t('nav.comments'),    icon: icons.chat },
    { to: '/admin/messages',    label: t('nav.messages'),    icon: icons.mail },
    { to: '/admin/subscribers', label: t('nav.subscribers'), icon: icons.bell },
  ] },
```

Also add the `users` icon in the `icons` object. Find the `icons` object definition and add:

```js
  users: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
```

- [ ] **Step 4: Add i18n keys to all 3 locale files**

In `resources/js/admin/locales/vi.json`, inside the `"nav"` object, add:
```json
"users": "Người dùng"
```

In `resources/js/admin/locales/en.json`, inside the `"nav"` object, add:
```json
"users": "Users"
```

In `resources/js/admin/locales/zh.json`, inside the `"nav"` object, add:
```json
"users": "用户管理"
```

- [ ] **Step 5: Commit**

```bash
git add resources/js/admin/views/Users/ \
        resources/js/admin/router/index.js \
        resources/js/admin/components/AppLayout.vue \
        resources/js/admin/locales/
git commit -m "feat: Users admin — list with search, role filter, pagination"
```

---

## Task 10: Build Frontend & Smoke Test

- [ ] **Step 1: Build Vue + assets**

```bash
ddev exec npm run build 2>&1 | tail -15
```

Expected: no errors, output shows `public/build/manifest.json` written.

- [ ] **Step 2: Verify website responds**

```bash
curl -sk https://luxestay.ddev.site/vi | grep -o "<title>.*</title>"
```

Expected: `<title>LuxeStay – Luxury Hotel &amp; Resort Booking</title>`

- [ ] **Step 3: Verify admin routes compile**

```bash
ddev artisan route:list --path=api/v1/mail 2>&1
ddev artisan route:list --path=api/v1/users 2>&1
```

Expected: rows showing GET/PUT `/api/v1/mail-settings` and GET `/api/v1/users`.

- [ ] **Step 4: Verify no PHP errors**

```bash
ddev artisan config:clear && ddev artisan optimize:clear 2>&1
```

Expected: all DONE, no exceptions.

- [ ] **Step 5: Final commit**

```bash
git add .
git commit -m "feat: complete mail system + user management — SMTP admin config, 5 transactional emails, users list, phone on register"
```
