# LuxeStay — FE to Laravel Migration Design

**Date:** 2026-05-04  
**Project:** `/Users/mac/Desktop/Project/luxestay`  
**FE Source:** `/Users/mac/Desktop/Project/luxestay/FE/luxestay/` (~40 static HTML files)

---

## 1. Overview

Migrate a static HTML hotel/resort frontend into a full-stack Laravel 13 application with:

- **Blade frontend** — server-rendered, converted from static HTML
- **Laravel REST API** — JSON, protected by Sanctum, consumed by Admin SPA
- **Vue.js Admin SPA** — Vue 3 + Pinia + Vue Router, lives inside `resources/js/admin/`
- **MariaDB** — via DDEV (`db`/`db`/`db`)
- **Laravel Breeze** — user-facing authentication
- **VNPay** — payment gateway for shop orders and room bookings

---

## 2. Architecture

```
Browser (Frontend)                  Browser (Admin)
       │                                   │
       ▼                                   ▼
Laravel Web Routes              Laravel Web Route /admin/*
Blade Templates                 admin.blade.php (SPA shell)
       │                                   │
       ├── Web Controllers                 ▼
       │   (inject data to views)   Vue.js SPA (Vite)
       │                            Vue Router + Pinia
       │                                   │
       └──────── Laravel API ──────────────┘
                 routes/api.php v1/*
                 Sanctum auth:sanctum
                 API Resources (JSON)
                        │
                   MariaDB (DDEV)
```

- Admin SPA shares the same domain as the Laravel app → Sanctum CSRF works out of the box.
- Frontend JS (jQuery, GSAP, Swiper) is kept as-is, moved to `resources/js/app.js` and compiled by Vite.
- No REST API calls from the Blade frontend — data comes from Controllers directly.

---

## 3. Database Schema

### Users & Auth
```sql
users: id, name, email, password, role (enum: user|admin), remember_token, timestamps
```

### Rooms
```sql
room_types:   id, name, slug, description, timestamps
rooms:        id, room_type_id, name, slug, description, price_per_night, max_guests,
              size_sqm, thumbnail, gallery (JSON), is_available, timestamps
amenities:    id, name, icon, timestamps
room_amenity: room_id, amenity_id
```

### Bookings
```sql
bookings: id, user_id, room_id, check_in, check_out, guests,
          status (enum: pending|confirmed|cancelled|completed),
          payment_status (enum: unpaid|paid|refunded),
          total_price, vnpay_txn_ref, special_requests, timestamps

booking_services: id, booking_id, service_name, quantity, unit_price, timestamps
```

### Blog
```sql
post_categories: id, name, slug, timestamps
posts:           id, post_category_id, author_id, title, slug, excerpt, content,
                 thumbnail, type (enum: standard|video|quote), status (enum: draft|published),
                 published_at, timestamps
```

### Shop
```sql
product_categories: id, name, slug, timestamps
products:           id, product_category_id, name, slug, description, price, stock,
                    thumbnail, gallery (JSON), is_active, timestamps
orders:             id, user_id, status (enum: pending|processing|completed|cancelled),
                    payment_status (enum: unpaid|paid|refunded),
                    subtotal, shipping_fee, total, vnpay_txn_ref,
                    shipping_address (JSON), timestamps
order_items:        id, order_id, product_id, quantity, unit_price, timestamps
```

### Activities
```sql
activities: id, type (enum: spa|golf|hiking|skiing|water_sports|fitness|nature|restaurant|event),
            title, slug, content, thumbnail, hero_image, is_featured, sort_order, timestamps
```

### Payments
```sql
payment_transactions: id, payable_type, payable_id, amount, gateway (vnpay),
                      status (enum: pending|success|failed), gateway_ref,
                      gateway_response (JSON), timestamps
```

---

## 4. Blade Frontend Conversion

### Strategy
Each static HTML page → a Blade view. Shared header/footer extracted into `layouts/app.blade.php`. CSS/JS assets moved to `public/` or compiled via Vite.

### Layout hierarchy
```
layouts/
  app.blade.php          ← header + footer + asset links
  auth.blade.php         ← minimal layout for login/register
  admin.blade.php        ← bare shell: <div id="admin-app">, loads admin JS bundle

components/
  header.blade.php
  footer.blade.php
  room-card.blade.php
  blog-card.blade.php
  product-card.blade.php
  booking-form.blade.php
```

### Page → Route → Controller mapping
| HTML File | Route | Controller@method |
|---|---|---|
| index.html | `GET /` | `HomeController@index` |
| rooms.html | `GET /rooms` | `RoomController@index` |
| rooms-suits.html | `GET /rooms/suites` | `RoomController@suites` |
| room-single.html | `GET /rooms/{slug}` | `RoomController@show` |
| blog*.html | `GET /blog` | `BlogController@index` |
| blog-single.html | `GET /blog/{slug}` | `BlogController@show` |
| shop.html | `GET /shop` | `ShopController@index` |
| shop-single.html | `GET /shop/{slug}` | `ShopController@show` |
| cart.html | `GET /cart` | `CartController@index` |
| checkout.html | `GET /checkout` | `CheckoutController@index` |
| contact.html | `GET /contact` | `ContactController@index` |
| about-us.html | `GET /about` | `PageController@about` |
| offers-promotions.html | `GET /offers` | `PageController@offers` |
| landing.html | `GET /landing` | `PageController@landing` |
| home-page-2.html | _(design variant — not used, kept as reference)_ | — |
| home-page-3.html | _(design variant — not used, kept as reference)_ | — |
| spa-wellness.html | `GET /activities/spa` | `ActivityController@show` |
| golf-courses.html | `GET /activities/golf` | `ActivityController@show` |
| restaurant.html | `GET /activities/restaurant` | `ActivityController@show` |
| event-wedding.html | `GET /activities/events` | `ActivityController@show` |
| (other activities) | `GET /activities/{slug}` | `ActivityController@show` |
| my-account.html | `GET /account` | `AccountController@index` |
| edit-account.html | `GET /account/edit` | `AccountController@edit` |
| edit-address.html | `GET /account/address` | `AccountController@address` |
| orders.html | `GET /account/orders` | `OrderController@index` |
| downloads.html | `GET /account/downloads` | `AccountController@downloads` |

### Assets
- `css/`, `js/`, `images/`, `webfonts/` from FE → `public/` (static, not Vite-compiled)
- Only `resources/js/app.js` (custom JS wrappers) and `resources/js/admin/` go through Vite

---

## 5. Laravel REST API

### Auth
```
POST   /api/v1/auth/login          → issue Sanctum token
POST   /api/v1/auth/logout
GET    /api/v1/auth/me
```

### Resources (all under `auth:sanctum` + `role:admin` middleware)
```
GET|POST          /api/v1/rooms
GET|PUT|DELETE    /api/v1/rooms/{id}
GET|POST          /api/v1/bookings
GET|PUT|DELETE    /api/v1/bookings/{id}
GET|POST          /api/v1/posts
GET|PUT|DELETE    /api/v1/posts/{id}
GET|POST          /api/v1/products
GET|PUT|DELETE    /api/v1/products/{id}
GET|POST          /api/v1/orders
GET|PUT|DELETE    /api/v1/orders/{id}
GET|POST          /api/v1/activities
GET|PUT|DELETE    /api/v1/activities/{id}
GET               /api/v1/dashboard/stats
```

All responses use **Laravel API Resources** for consistent JSON shape.

---

## 6. Vue.js Admin SPA

**Stack:** Vue 3 (Composition API) + Vue Router 4 + Pinia + Axios + TailwindCSS (admin only)

**Location:** `resources/js/admin/`

**Build:** Separate Vite entry point `admin.js`, output to `public/build/admin/`

### SPA Shell
```php
// web.php
Route::get('/admin/{any?}', fn() => view('layouts.admin'))->where('any', '.*');
```

### Admin Views
```
views/
  Dashboard/      ← stats cards: total bookings, revenue, rooms, orders
  Rooms/          ← list, create, edit (with image upload)
  Bookings/       ← list with status filter, detail, confirm/cancel
  Posts/          ← list, create/edit with rich text editor (Tiptap)
  Products/       ← list, create, edit
  Orders/         ← list, detail, update status
  Activities/     ← list, edit content per activity type
  Users/          ← list users, toggle admin role
```

### Auth flow (Admin)
1. `/admin/login` → POST `/api/v1/auth/login` → receive Sanctum token
2. Token stored in `localStorage`
3. Axios interceptor attaches `Authorization: Bearer <token>` to all requests
4. Pinia `useAuthStore` holds user state, router guard checks token before each navigation

---

## 7. VNPay Integration

**Service class:** `App\Services\VNPayService`

```php
// Methods:
createPaymentUrl(payable, amount, orderInfo, returnUrl): string
verifyReturn(queryParams): bool
verifyIPN(queryParams): bool
```

### Booking payment flow
1. User submits booking form → `BookingController@store` → creates `Booking` (status: pending, payment: unpaid)
2. Redirect to `/booking/{id}/pay` → `VNPayService::createPaymentUrl()` → redirect to VNPay
3. VNPay redirects to `/vnpay/booking/return` → verify signature → update `Booking.payment_status = paid`, create `PaymentTransaction`
4. VNPay calls `/vnpay/ipn` (server-to-server) → second verification layer

### Shop payment flow
Same pattern via `CheckoutController` → `Order` → VNPay → `/vnpay/order/return`

### Security
- All VNPay callbacks verify HMAC-SHA512 signature before any DB write
- `vnpay_hash_secret` stored in `.env`, never in code

---

## 8. Authentication & Roles

- **Laravel Breeze** (Blade stack) — login, register, password reset for user-facing auth
- **Admin auth** — separate flow via API token (not Breeze), admin users have `role = admin`
- **Middleware:**
  - `auth` — Breeze session auth for frontend protected routes (account, booking, orders)
  - `auth:sanctum` — token auth for all `/api/v1/*` routes
  - `EnsureUserIsAdmin` — custom middleware checks `$user->role === 'admin'`

---

## 9. Cart Implementation

Cart stored in **Laravel Session** (no DB table needed for guests).

- `CartService` — add/remove/update/clear items, calculate totals
- On checkout, if user is logged in, cart is persisted to order; guest is prompted to login/register

---

## 10. Key Technical Decisions

| Decision | Choice | Reason |
|---|---|---|
| Frontend rendering | Blade (SSR) | SEO matters for hotel site; simpler than SPA |
| Admin rendering | Vue SPA | Rich interactive CRUD UX |
| API auth | Sanctum tokens | Same-domain SPA, simple setup |
| Frontend JS | Keep jQuery/GSAP | Already working, no reason to rewrite |
| CSS pipeline | `public/css/` (static) | No rewriting needed, just move assets |
| Image storage | `storage/app/public` + symlink | Standard Laravel approach |
| Cart storage | Session | No need for cart DB table |
| Rich text (blog) | Tiptap (Vue) | Lightweight, no server dependency |

---

## 11. Module Build Order

1. **Foundation** — DB migrations, Models, Seeders, asset migration
2. **Auth** — Breeze install, User model with role, admin middleware
3. **Blade Layout** — `layouts/app.blade.php`, header/footer components
4. **Frontend Pages** — Convert all HTML → Blade views + Web Controllers
5. **Admin SPA Shell** — Vue setup, Vite config, Pinia, Router, login
6. **Rooms Module** — Model, API Controller, Resource, Admin views
7. **Bookings Module** — Model, Web + API Controller, booking form
8. **VNPay — Bookings** — VNPayService, booking payment flow
9. **Blog Module** — Model, Web + API Controller, Admin views
10. **Shop Module** — Model, Web + API Controller, CartService
11. **VNPay — Orders** — order payment flow
12. **Activities Module** — Model, Web + API Controller, Admin views
13. **Account Pages** — my-account, orders, address (auth-protected)
14. **Admin Dashboard** — stats API, Dashboard view
15. **Polish & Testing** — seeders with realistic data, edge cases
