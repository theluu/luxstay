# Multilanguage (i18n) Design — LuxeStay

**Date:** 2026-05-22
**Scope:** Full i18n — UI strings (Blade), DB content, Admin Vue SPA
**Locales:** Vietnamese (vi, default), English (en), Chinese Simplified (zh)
**Extensible:** yes — add locale to `supported_locales` config + create lang files

---

## 1. Architecture Overview

Three independent i18n layers:

| Layer | Technology | Example |
|---|---|---|
| UI strings in Blade | Laravel `lang/` + `__()` | `__('common.book_now')` → "Đặt ngay" |
| Dynamic DB content | `spatie/laravel-translatable` JSON column | `$room->title` → auto current locale |
| Admin Vue SPA | vue-i18n v9 | `$t('rooms.title')` |

URL structure:
- `/` → redirect to `/vi` (default locale)
- `/{locale}/...` — all public routes prefixed with locale
- `/admin` — no locale prefix, admin locale stored in localStorage

---

## 2. Package

- **`spatie/laravel-translatable`** — adds `HasTranslations` trait for Eloquent models
- **`vue-i18n@9`** — standard Vue 3 i18n library for admin SPA

No other packages needed.

---

## 3. Backend: Middleware & Routes

### LocaleMiddleware
File: `app/Http/Middleware/LocaleMiddleware.php`

- Reads `{locale}` from route parameter
- Validates against `config('app.supported_locales')`
- Aborts 404 for unknown locales
- Calls `app()->setLocale($locale)`

### Routes (web.php)
```php
// Redirect root to default locale
Route::get('/', fn() => redirect('/vi'));

// All public routes inside locale prefix group
Route::prefix('{locale}')
    ->middleware('locale')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/rooms', ...)->name('rooms.index');
        // ... all existing routes
    });

// Admin — unchanged, no locale prefix
Route::get('/admin/{any?}', fn() => view('layouts.admin'))
    ->where('any', '.*')
    ->name('admin');
```

### Route Helper
File: `app/Helpers/locale_helpers.php` (autoloaded via composer)

```php
function localeRoute(string $locale, string $named, array $params = []): string {
    return route($named, array_merge(['locale' => $locale], $params));
}

function currentLocale(): string {
    return app()->getLocale();
}
```

### config/app.php additions
```php
'supported_locales' => ['vi', 'en', 'zh'],
'locale' => env('APP_LOCALE', 'vi'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'vi'),
```

---

## 4. Lang Files Structure

```
lang/
  vi/
    common.php     # buttons, labels, flash messages
    nav.php        # navigation items (hardcoded ones)
    auth.php       # login, register, forgot password
    rooms.php      # room detail page strings
    booking.php    # booking form strings
    blog.php       # blog page strings
    shop.php       # shop/cart/checkout strings
    contact.php    # contact form strings
    footer.php     # footer strings
  en/
    (same files)
  zh/
    (same files)
```

All Blade text replaced with `__('file.key')`. Example:
- `"Đặt ngay"` → `__('common.book_now')`
- `"Gọi cho chúng tôi:"` → `__('common.call_us')`
- `"Hoạt động"` → `__('nav.activities')`
- `"Đặt phòng"` → `__('booking.title')`

---

## 5. Database Translations

### Migrations — one per affected table
Each adds a nullable JSON `translations` column:

```php
$table->json('translations')->nullable();
```

**Affected tables & fields:**

| Table | Translatable fields |
|---|---|
| `rooms` | title, description, excerpt |
| `posts` | title, content, excerpt |
| `activities` | title, content |
| `products` | title, description |
| `sliders` | title, description |
| `post_categories` | name |
| `product_categories` | name |
| `menus` (nav items) | label |

### Model Trait
Add `HasTranslations` to each affected model:

```php
use Spatie\Translatable\HasTranslations;

class Room extends Model {
    use HasTranslations;
    public $translatable = ['title', 'description', 'excerpt'];
}
```

**Behavior:**
- `$room->title` → returns string for current app locale
- `$room->getTranslation('title', 'en')` → explicit locale
- `$room->setTranslation('title', 'en', 'Deluxe Room')` → set
- Fallback: if translation missing, returns `vi` value (fallback_locale)
- Backward compat: existing plain string data is preserved as-is until admin provides translations

### JSON storage format
```json
{
  "title": {"vi": "Phòng Deluxe", "en": "Deluxe Room", "zh": "豪华客房"},
  "description": {"vi": "...", "en": "...", "zh": "..."}
}
```

---

## 6. Admin API — Translation endpoints

Admin needs to read and write translations per locale. Existing CRUD API endpoints are extended:

- `GET /api/admin/rooms/{id}` — returns full `translations` object alongside existing fields
- `PUT /api/admin/rooms/{id}` — accepts `translations` in request body, saves via spatie

No new endpoints needed — just extend existing ones to include translation data.

### Admin API convention
Request payload for update:
```json
{
  "title": "Phòng Deluxe",
  "translations": {
    "title": {"vi": "Phòng Deluxe", "en": "Deluxe Room", "zh": "豪华客房"},
    "description": {"vi": "...", "en": "...", "zh": "..."}
  }
}
```

---

## 7. Admin Vue SPA — vue-i18n

### Setup
- Install `vue-i18n@9`
- Locale files: `resources/js/admin/locales/vi.json`, `en.json`, `zh.json`
- i18n instance created in `resources/js/admin/i18n.js`, injected into Vue app

### Usage
```js
// In Vue templates
$t('rooms.list_title')   // "Danh sách phòng"
$t('common.save')        // "Lưu"
```

### Locale switcher in admin header
- Small 3-button toggle: `VI | EN | ZH`
- Locale stored in `localStorage('admin_locale')`
- Independent from frontend locale
- Does not affect URL

### Translation forms in admin
For each translatable resource (rooms, posts, etc.), the edit form shows a **tab panel per locale**:
```
[ 🇻🇳 Tiếng Việt ] [ 🇬🇧 English ] [ 🇨🇳 中文 ]
  Title: [________]
  Content: [______]
```
Each tab edits that locale's translation. All tabs saved together on submit.

---

## 8. Language Switcher Component (Frontend)

File: `resources/views/components/locale-switcher.blade.php`

- Dropdown button showing current locale flag + code
- Options: 🇻🇳 Tiếng Việt, 🇬🇧 English, 🇨🇳 中文
- Each option links to same named route with locale swapped:
  ```php
  route(request()->route()->getName(), array_merge(
      request()->route()->parameters(),
      ['locale' => $targetLocale]
  ))
  ```
- Active locale is highlighted/disabled
- Placed in header, right side, adjacent to cart icon
- Registered in `AppServiceProvider::boot()` via `View::composer`

---

## 9. SEO — hreflang tags

In `layouts/app.blade.php` `<head>`, emit alternate links:
```html
<link rel="alternate" hreflang="vi" href="{{ localeRoute('vi', ...) }}">
<link rel="alternate" hreflang="en" href="{{ localeRoute('en', ...) }}">
<link rel="alternate" hreflang="zh" href="{{ localeRoute('zh', ...) }}">
<link rel="alternate" hreflang="x-default" href="{{ localeRoute('vi', ...) }}">
```

---

## 10. Adding a New Locale in the Future

1. Add locale code to `config('app.supported_locales')`
2. Create `lang/{code}/` folder, copy from `vi/`, translate
3. Create `resources/js/admin/locales/{code}.json`
4. Add flag+name to both switcher components
5. No migration needed — JSON column already stores any locale key

---

## Implementation Order

1. Install packages (`spatie/laravel-translatable`, `vue-i18n@9`)
2. `LocaleMiddleware` + register in `Kernel.php`
3. Refactor `routes/web.php` with locale prefix group
4. Add `config('app.supported_locales')`
5. Create `lang/vi/`, `lang/en/`, `lang/zh/` files
6. Replace hardcoded Blade text with `__()`
7. Migrations: add `translations` column to 7 tables
8. Add `HasTranslations` trait to 7 models
9. Extend admin API controllers to return/accept translations
10. `<x-locale-switcher>` Blade component
11. Add hreflang tags to layout
12. vue-i18n setup in admin SPA
13. Admin locale switcher in header
14. Admin translation tab panels in edit forms
