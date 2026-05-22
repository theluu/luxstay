# Footer Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Tách phần quản lý footer (gallery + menu có hỗ trợ children) ra khỏi trang About, tạo trang admin riêng `/admin/footer`, và hiển thị dữ liệu từ DB lên frontend.

**Architecture:** Tạo `FooterController` mới quản lý 2 keys trong bảng `site_settings`: `footer_gallery` (mảng path ảnh) và `footer_menu` (mảng `{label, url, children[]}`). Gallery được chuyển hoàn toàn từ `AboutPageController`. Frontend `footer.blade.php` đọc `footer_menu` từ `$siteSettings` thay vì hardcode.

**Tech Stack:** Laravel 13 (PHP), Vue 3 (Composition API), Blade, `SiteSetting` model (key/value store)

---

## File Map

| Action | File | Mô tả |
|--------|------|--------|
| Create | `app/Http/Controllers/Api/FooterController.php` | GET/PUT /v1/footer |
| Modify | `app/Http/Controllers/Api/AboutPageController.php` | Xóa footer_gallery |
| Modify | `routes/api.php` | Thêm footer routes |
| Create | `resources/js/admin/views/Footer/FooterView.vue` | Trang admin footer |
| Modify | `resources/js/admin/router/index.js` | Thêm route /admin/footer |
| Modify | `resources/js/admin/components/AppLayout.vue` | Thêm link sidebar |
| Modify | `resources/views/components/footer.blade.php` | Đọc footer_menu từ DB |

---

### Task 1: Tạo FooterController

**Files:**
- Create: `app/Http/Controllers/Api/FooterController.php`

- [ ] **Tạo file controller**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'footer_gallery' => self::getFooterGallery(),
                'footer_menu'    => self::getFooterMenu(),
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'footer_gallery'            => 'nullable|array|max:10',
            'footer_gallery.*'          => 'nullable|string',
            'footer_menu'               => 'nullable|array|max:20',
            'footer_menu.*.label'       => 'required|string|max:100',
            'footer_menu.*.url'         => 'required|string|max:255',
            'footer_menu.*.children'    => 'nullable|array|max:10',
            'footer_menu.*.children.*.label' => 'required|string|max:100',
            'footer_menu.*.children.*.url'   => 'required|string|max:255',
        ]);

        SiteSetting::set('footer_gallery', json_encode(array_values($data['footer_gallery'] ?? [])));
        SiteSetting::set('footer_menu',    json_encode(array_values($data['footer_menu']    ?? [])));

        return $this->index();
    }

    public static function getFooterGallery(): array
    {
        $stored = json_decode(SiteSetting::get('footer_gallery', '[]'), true);

        return is_array($stored) && $stored ? array_values($stored) : self::defaultFooterGallery();
    }

    public static function getFooterMenu(): array
    {
        $stored = json_decode(SiteSetting::get('footer_menu', '[]'), true);

        return is_array($stored) && $stored ? array_values($stored) : self::defaultFooterMenu();
    }

    public static function defaultFooterGallery(): array
    {
        return [
            'images/footer_img1.png',
            'images/footer_img2.png',
            'images/footer_img3.png',
            'images/footer_img4.png',
            'images/footer_img5.png',
        ];
    }

    public static function defaultFooterMenu(): array
    {
        return [
            ['label' => 'Trang chủ',    'url' => '/',        'children' => []],
            ['label' => 'Về chúng tôi', 'url' => '/about',   'children' => []],
            ['label' => 'Phòng',        'url' => '/rooms',   'children' => []],
            ['label' => 'Cửa hàng',     'url' => '/shop',    'children' => []],
            ['label' => 'Blog',         'url' => '/blog',    'children' => []],
            ['label' => 'Liên hệ',      'url' => '/contact', 'children' => []],
        ];
    }
}
```

- [ ] **Commit**

```bash
git add app/Http/Controllers/Api/FooterController.php
git commit -m "feat: add FooterController for footer_gallery and footer_menu"
```

---

### Task 2: Đăng ký API routes

**Files:**
- Modify: `routes/api.php`

- [ ] **Thêm import và routes vào `routes/api.php`**

Thêm dòng use ở đầu file (sau dòng `use App\Http\Controllers\Api\AboutPageController;`):
```php
use App\Http\Controllers\Api\FooterController;
```

Thêm 2 routes vào bên trong `Route::middleware(['auth:sanctum', 'admin'])->group(...)`, sau dòng `Route::put('/about-page', ...)`:
```php
Route::get('/footer', [FooterController::class, 'index']);
Route::put('/footer', [FooterController::class, 'update']);
```

- [ ] **Kiểm tra route list**

```bash
cd /Users/mac/Desktop/Project/luxestay
php artisan route:list --path=api/v1/footer
```

Expected output: 2 dòng với GET và PUT `/api/v1/footer`.

- [ ] **Commit**

```bash
git add routes/api.php
git commit -m "feat: register GET/PUT /v1/footer routes"
```

---

### Task 3: Cập nhật AboutPageController — xóa footer_gallery

**Files:**
- Modify: `app/Http/Controllers/Api/AboutPageController.php`

- [ ] **Sửa `index()` — xóa `footer_gallery` khỏi response**

```php
public function index(): JsonResponse
{
    return response()->json([
        'data' => [
            'about_page' => $this->aboutPage(),
        ],
    ]);
}
```

- [ ] **Sửa `update()` — xóa footer_gallery validation và set**

```php
public function update(Request $request): JsonResponse
{
    $data = $request->validate([
        'about_page' => 'required|array',
    ]);

    SiteSetting::set('about_page', json_encode($data['about_page']));

    return $this->index();
}
```

- [ ] **Xóa các method liên quan đến footer_gallery**

Xóa hoàn toàn:
- `public static function getFooterGallery(): array` (cả method)
- `public static function defaultFooterGallery(): array` (cả method)
- `private function footerGallery(): array` (cả method)

- [ ] **Commit**

```bash
git add app/Http/Controllers/Api/AboutPageController.php
git commit -m "refactor: remove footer_gallery from AboutPageController"
```

---

### Task 4: Cập nhật AboutPageView.vue — xóa phần Gallery footer

**Files:**
- Modify: `resources/js/admin/views/About/AboutPageView.vue`

- [ ] **Xóa section Gallery footer khỏi template**

Xóa toàn bộ block:
```html
<section class="bg-white border border-slate-200 rounded-lg p-6">
  <h2 class="font-semibold text-lg mb-4">Gallery footer</h2>
  <div class="grid md:grid-cols-5 gap-4">
    <div v-for="(_, i) in footerGallery" :key="i">
      <ImageUpload v-model="footerGallery[i]" :label="`Ảnh ${i + 1}`" />
      <button @click="footerGallery.splice(i, 1)" class="text-xs text-rose-500 mt-2">Xóa ảnh</button>
    </div>
  </div>
  <button @click="footerGallery.push('')" class="text-sm underline mt-4">+ Thêm ảnh footer</button>
</section>
```

- [ ] **Xóa state và logic footerGallery khỏi `<script setup>`**

Xóa:
```js
const footerGallery = ref([])
```

Trong `load()`, sửa:
```js
async function load() {
  const { data } = await api.get('/about-page')
  about.value = data.data.about_page
  loading.value = false
}
```

Trong `save()`, sửa payload:
```js
await api.put('/about-page', {
  about_page: about.value,
})
```

- [ ] **Commit**

```bash
git add resources/js/admin/views/About/AboutPageView.vue
git commit -m "refactor: remove footer gallery section from AboutPageView"
```

---

### Task 5: Tạo FooterView.vue

**Files:**
- Create: `resources/js/admin/views/Footer/FooterView.vue`

- [ ] **Tạo file**

```vue
<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Footer</h1>
      <p class="text-sm text-slate-500 mt-1">Quản lý gallery ảnh và menu điều hướng footer</p>
    </div>

    <div v-if="loading" class="text-slate-400">Đang tải...</div>
    <div v-else class="space-y-6 max-w-5xl">

      <!-- Gallery -->
      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Gallery ảnh</h2>
        <div class="grid md:grid-cols-5 gap-4">
          <div v-for="(_, i) in gallery" :key="i">
            <ImageUpload v-model="gallery[i]" :label="`Ảnh ${i + 1}`" />
            <button @click="gallery.splice(i, 1)" class="text-xs text-rose-500 mt-2">Xóa ảnh</button>
          </div>
        </div>
        <button @click="gallery.push('')" class="text-sm underline mt-4">+ Thêm ảnh</button>
      </section>

      <!-- Menu footer -->
      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-semibold text-lg">Menu footer</h2>
          <button @click="addItem" class="text-sm bg-black text-white px-4 py-2 rounded">+ Thêm mục</button>
        </div>

        <div class="space-y-3">
          <div v-for="(item, i) in menu" :key="i" class="bg-white rounded border border-slate-200">
            <!-- Top-level item -->
            <div class="flex items-center gap-3 p-4">
              <span class="text-slate-400 cursor-move text-lg">⠿</span>
              <div class="flex-1 grid grid-cols-2 gap-3">
                <input v-model="item.label" placeholder="Nhãn" class="border rounded px-3 py-1.5 text-sm" />
                <input v-model="item.url" placeholder="URL (vd. /rooms)" class="border rounded px-3 py-1.5 text-sm" />
              </div>
              <button @click="toggleChildren(i)"
                class="text-xs border px-2 py-1.5 rounded hover:bg-slate-50 whitespace-nowrap">
                Mục con ({{ item.children?.length ?? 0 }})
              </button>
              <button @click="menu.splice(i, 1)" class="text-rose-400 hover:text-rose-600 text-lg leading-none">&times;</button>
            </div>

            <!-- Children -->
            <div v-if="openIndex === i" class="border-t px-4 pb-4 pt-3 bg-slate-50 rounded-b space-y-2">
              <div v-for="(child, j) in item.children" :key="j" class="flex items-center gap-3">
                <span class="w-4 text-slate-300">↳</span>
                <input v-model="child.label" placeholder="Nhãn" class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <input v-model="child.url" placeholder="URL" class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <button @click="item.children.splice(j, 1)" class="text-rose-400 hover:text-rose-600">&times;</button>
              </div>
              <button @click="addChild(i)" class="text-xs text-slate-500 hover:text-black underline mt-1">+ Thêm mục con</button>
            </div>
          </div>
        </div>

        <p class="text-xs text-slate-400 mt-4">Menu với mục con sẽ hiển thị dropdown trên frontend.</p>
      </section>

      <p v-if="saved" class="text-emerald-600 text-sm">✓ Đã lưu footer.</p>
      <p v-if="error" class="text-rose-600 text-sm">{{ error }}</p>

      <button @click="save" :disabled="saving"
        class="bg-black text-white px-6 py-2.5 rounded text-sm font-semibold disabled:opacity-60">
        {{ saving ? 'Đang lưu...' : 'Lưu footer' }}
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import api from '../../stores/api'

const loading  = ref(true)
const saving   = ref(false)
const saved    = ref(false)
const error    = ref('')
const gallery  = ref([])
const menu     = ref([])
const openIndex = ref(null)

async function load() {
  const { data } = await api.get('/footer')
  gallery.value = data.data.footer_gallery
  menu.value    = data.data.footer_menu
  loading.value = false
}

function addItem() {
  menu.value.push({ label: '', url: '/', children: [] })
}

function toggleChildren(i) {
  openIndex.value = openIndex.value === i ? null : i
  if (!menu.value[i].children) menu.value[i].children = []
}

function addChild(i) {
  if (!menu.value[i].children) menu.value[i].children = []
  menu.value[i].children.push({ label: '', url: '/' })
}

async function save() {
  saving.value = true
  saved.value  = false
  error.value  = ''
  try {
    await api.put('/footer', {
      footer_gallery: gallery.value.filter(Boolean),
      footer_menu:    menu.value,
    })
    saved.value = true
    setTimeout(() => (saved.value = false), 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}

load()
</script>
```

- [ ] **Commit**

```bash
git add resources/js/admin/views/Footer/FooterView.vue
git commit -m "feat: add FooterView admin page for gallery and menu management"
```

---

### Task 6: Đăng ký route và sidebar

**Files:**
- Modify: `resources/js/admin/router/index.js`
- Modify: `resources/js/admin/components/AppLayout.vue`

- [ ] **Thêm import và route vào `router/index.js`**

Thêm import (sau dòng `import AboutPageView`):
```js
import FooterView from '../views/Footer/FooterView.vue'
```

Thêm route vào mảng `routes` (sau dòng `{ path: '/admin/about-page', ... }`):
```js
{ path: '/admin/footer', component: FooterView },
```

- [ ] **Thêm link vào sidebar trong `AppLayout.vue`**

Trong group `content`, thêm sau dòng `{ to: '/admin/about-page', label: 'Trang About', icon: icons.folder }`:
```js
{ to: '/admin/footer', label: 'Footer', icon: icons.image },
```

- [ ] **Commit**

```bash
git add resources/js/admin/router/index.js resources/js/admin/components/AppLayout.vue
git commit -m "feat: register /admin/footer route and sidebar link"
```

---

### Task 7: Cập nhật footer.blade.php — đọc footer_menu từ DB

**Files:**
- Modify: `resources/views/components/footer.blade.php`

- [ ] **Thay thế hardcoded menu bằng dữ liệu từ DB**

Thay toàn bộ block:
```html
<div class="menu-footer-menu-container d-flex align-items-center justify-content-center wow fadeInUp">
   <ul class="menu list-unstyled p-0 mb-0 d-flex align-items-center">
      <li class="menu-item text-uppercase"><a href="{{ route('home') }}">Trang chủ</a></li>
      <li class="menu-item text-uppercase"><a href="{{ route('about') }}">Về chúng tôi</a></li>
      <li class="menu-item text-uppercase"><a href="{{ route('rooms.index') }}">Phòng</a></li>
      <li class="menu-item text-uppercase"><a href="{{ route('shop.index') }}">Cửa hàng</a></li>
      <li class="menu-item text-uppercase"><a href="{{ route('blog.index') }}">Blog</a></li>
      <li class="menu-item text-uppercase"><a href="{{ route('contact') }}">Liên hệ</a></li>
   </ul>
</div>
```

Bằng:
```blade
@php
    $footerMenuRaw = json_decode($siteSettings['footer_menu'] ?? '[]', true);
    $footerMenuItems = (is_array($footerMenuRaw) && $footerMenuRaw) ? $footerMenuRaw : [
        ['label' => 'Trang chủ',    'url' => '/',        'children' => []],
        ['label' => 'Về chúng tôi', 'url' => '/about',   'children' => []],
        ['label' => 'Phòng',        'url' => '/rooms',   'children' => []],
        ['label' => 'Cửa hàng',     'url' => '/shop',    'children' => []],
        ['label' => 'Blog',         'url' => '/blog',    'children' => []],
        ['label' => 'Liên hệ',      'url' => '/contact', 'children' => []],
    ];
@endphp
<div class="menu-footer-menu-container d-flex align-items-center justify-content-center wow fadeInUp">
   <ul class="menu list-unstyled p-0 mb-0 d-flex align-items-center">
      @foreach($footerMenuItems as $footerItem)
         @if(empty($footerItem['children']))
            <li class="menu-item text-uppercase">
               <a href="{{ $footerItem['url'] }}">{{ $footerItem['label'] }}</a>
            </li>
         @else
            <li class="menu-item text-uppercase submenu">
               <a href="{{ $footerItem['url'] }}">{{ $footerItem['label'] }} <i class="fas fa-chevron-down" style="font-size:10px"></i></a>
               <ul class="sub-menu">
                  @foreach($footerItem['children'] as $footerChild)
                     <li class="menu-item"><a href="{{ $footerChild['url'] }}">{{ $footerChild['label'] }}</a></li>
                  @endforeach
               </ul>
            </li>
         @endif
      @endforeach
   </ul>
</div>
```

- [ ] **Commit**

```bash
git add resources/views/components/footer.blade.php
git commit -m "feat: render footer menu from DB with dropdown support"
```

---

### Task 8: Build assets

- [ ] **Build Vue assets**

```bash
cd /Users/mac/Desktop/Project/luxestay
npm run build
```

Expected: Build thành công, không có lỗi.

- [ ] **Kiểm tra trang admin footer trên trình duyệt**

Mở `https://luxestay.ddev.site/admin/footer`
- Kiểm tra sidebar có link "Footer" không
- Kiểm tra gallery section load ảnh hiện tại
- Kiểm tra menu section load 6 mục mặc định
- Thêm 1 mục menu, lưu → reload kiểm tra persisted

- [ ] **Kiểm tra frontend footer**

Mở `https://luxestay.ddev.site`
- Menu footer hiển thị đúng dữ liệu vừa lưu
- Gallery footer hiển thị đúng

- [ ] **Kiểm tra trang About admin vẫn hoạt động**

Mở `https://luxestay.ddev.site/admin/about-page`
- Không còn section "Gallery footer"
- Lưu trang About vẫn thành công

- [ ] **Commit cuối nếu còn file chưa commit**

```bash
git status
```
