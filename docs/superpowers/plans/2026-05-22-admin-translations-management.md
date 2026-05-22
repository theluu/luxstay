# Admin Translations Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a dedicated `/admin/translations` page where admins can select a model, view all records with locale completion badges, and open a fullscreen modal to edit or clear translations per locale.

**Architecture:** A dedicated `TranslationController` (new) provides a unified API for all translatable models. The Vue layer is split into `useTranslations.js` (API calls), `TranslationModal.vue` (fullscreen editor), and `TranslationsView.vue` (table + model tabs). No existing CRUD controllers are modified.

**Tech Stack:** Laravel 11, Vue 3 Composition API, vue-i18n@9, spatie/laravel-translatable, Tailwind CSS, DDEV.

---

## File Map

| File | Action | Purpose |
|---|---|---|
| `app/Http/Controllers/Api/TranslationController.php` | Create | Unified index/update/clearLocale for all translatable models |
| `app/Http/Controllers/Api/Concerns/SavesTranslations.php` | Modify | Add `translationStatus()` method |
| `routes/api.php` | Modify | Register `/translations/{model}` routes |
| `resources/js/admin/composables/useTranslations.js` | Create | Fetch, save, clear API logic + MODELS config |
| `resources/js/admin/views/Translations/TranslationsView.vue` | Create | Model tabs, records table, search, pagination |
| `resources/js/admin/views/Translations/TranslationModal.vue` | Create | Fullscreen modal with VI/EN/ZH tabs |
| `resources/js/admin/router/index.js` | Modify | Add `/admin/translations` route |
| `resources/js/admin/components/AppLayout.vue` | Modify | Add "Bản dịch" nav item to content group |
| `resources/js/admin/locales/vi.json` | Modify | Add `nav.translations` key |
| `resources/js/admin/locales/en.json` | Modify | Add `nav.translations` key |
| `resources/js/admin/locales/zh.json` | Modify | Add `nav.translations` key |

---

## Task 1: Backend — TranslationController + API routes

**Files:**
- Create: `app/Http/Controllers/Api/TranslationController.php`
- Modify: `app/Http/Controllers/Api/Concerns/SavesTranslations.php`
- Modify: `routes/api.php`

- [ ] **Step 1: Add `translationStatus()` to SavesTranslations trait**

Read `app/Http/Controllers/Api/Concerns/SavesTranslations.php` then add this method inside the trait (after `allTranslations()`):

```php
protected function translationStatus($model): array
{
    $locales = config('app.supported_locales', ['vi', 'en', 'zh']);
    $primaryField = ($model->translatable ?? [])[0] ?? null;
    if (!$primaryField) {
        return array_fill_keys($locales, false);
    }
    $status = [];
    foreach ($locales as $locale) {
        $val = $model->getTranslation($primaryField, $locale, false);
        $status[$locale] = !empty($val);
    }
    return $status;
}
```

- [ ] **Step 2: Create TranslationController**

Create `app/Http/Controllers/Api/TranslationController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Room;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    use SavesTranslations;

    private const MODELS = [
        'rooms'               => [Room::class,            'name'],
        'posts'               => [Post::class,            'title'],
        'activities'          => [Activity::class,        'title'],
        'products'            => [Product::class,         'name'],
        'sliders'             => [Slider::class,          'title'],
        'post-categories'     => [PostCategory::class,    'name'],
        'product-categories'  => [ProductCategory::class, 'name'],
    ];

    public function index(string $model, Request $request): JsonResponse
    {
        [$modelClass, $primaryField] = self::MODELS[$model] ?? abort(404);

        $query = $modelClass::query();

        if ($search = $request->query('search')) {
            $query->where($primaryField, 'like', "%{$search}%");
        }

        $items = $query->orderBy('id', 'desc')->paginate(20);

        $data = $items->getCollection()->map(function ($item) use ($primaryField) {
            $raw = $item->getRawOriginal($primaryField);
            $decoded = is_string($raw) ? json_decode($raw, true) : null;
            $label = (is_array($decoded) ? ($decoded['vi'] ?? $decoded[array_key_first($decoded)] ?? '') : $raw) ?: '—';

            return [
                'id'                 => $item->id,
                'label'              => $label,
                'translation_status' => $this->translationStatus($item),
                'all_translations'   => $this->allTranslations($item),
            ];
        });

        return response()->json([
            'data'       => $data,
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    public function update(Request $request, string $model, int $id): JsonResponse
    {
        [$modelClass] = self::MODELS[$model] ?? abort(404);
        $item = $modelClass::findOrFail($id);

        if ($request->has('translations')) {
            $this->applyTranslations($item, $request->input('translations', []));
            $item->save();
        }

        return response()->json([
            'data' => [
                'id'                 => $item->id,
                'translation_status' => $this->translationStatus($item),
                'all_translations'   => $this->allTranslations($item),
            ],
        ]);
    }

    public function clearLocale(string $model, int $id, string $locale): JsonResponse
    {
        if ($locale === 'vi') {
            abort(422, 'Cannot clear the default locale (vi).');
        }

        [$modelClass] = self::MODELS[$model] ?? abort(404);
        $item = $modelClass::findOrFail($id);

        foreach ($item->translatable as $field) {
            $item->setTranslation($field, $locale, '');
        }
        $item->save();

        return response()->json([
            'data' => [
                'id'                 => $item->id,
                'translation_status' => $this->translationStatus($item),
                'all_translations'   => $this->allTranslations($item),
            ],
        ]);
    }
}
```

- [ ] **Step 3: Register routes in api.php**

Read `routes/api.php`. Inside the `Route::middleware(['auth:sanctum', 'admin'])->group()` block, add:

```php
// Translations management
Route::get('/translations/{model}',              [TranslationController::class, 'index']);
Route::put('/translations/{model}/{id}',         [TranslationController::class, 'update']);
Route::delete('/translations/{model}/{id}/{locale}', [TranslationController::class, 'clearLocale']);
```

Also add the import at the top of the file:
```php
use App\Http\Controllers\Api\TranslationController;
```

- [ ] **Step 4: Verify routes registered**

```bash
cd /home/theluu/Desktop/Projects/luxstay && ddev artisan route:list --path=api/v1/translations 2>&1 | head -10
```

Expected: 3 routes shown (GET, PUT, DELETE).

- [ ] **Step 5: Smoke-test index endpoint**

```bash
ddev artisan tinker --execute="
\$ctrl = new App\Http\Controllers\Api\TranslationController();
\$req = new Illuminate\Http\Request();
\$res = \$ctrl->index('rooms', \$req);
echo substr(\$res->getContent(), 0, 200);
"
```

Expected: JSON with `data` array and `pagination` object, no errors.

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/Api/TranslationController.php \
        app/Http/Controllers/Api/Concerns/SavesTranslations.php \
        routes/api.php
git commit -m "feat(i18n): add TranslationController with index/update/clearLocale endpoints"
```

---

## Task 2: Composable — useTranslations.js

**Files:**
- Create: `resources/js/admin/composables/useTranslations.js`

- [ ] **Step 1: Create the composable**

Create `resources/js/admin/composables/useTranslations.js`:

```js
import { ref } from 'vue'
import api from '../stores/api'

export const MODELS = [
  {
    key: 'rooms',
    label: 'Phòng',
    fields: ['name', 'description'],
    primaryField: 'name',
  },
  {
    key: 'posts',
    label: 'Bài viết',
    fields: ['title', 'excerpt', 'content'],
    primaryField: 'title',
  },
  {
    key: 'activities',
    label: 'Hoạt động',
    fields: ['title', 'content'],
    primaryField: 'title',
  },
  {
    key: 'products',
    label: 'Sản phẩm',
    fields: ['name', 'description'],
    primaryField: 'name',
  },
  {
    key: 'sliders',
    label: 'Slider',
    fields: ['title'],
    primaryField: 'title',
  },
  {
    key: 'post-categories',
    label: 'DM Blog',
    fields: ['name'],
    primaryField: 'name',
  },
  {
    key: 'product-categories',
    label: 'DM Sản phẩm',
    fields: ['name'],
    primaryField: 'name',
  },
]

export const FIELD_LABELS = {
  name:        'Tên',
  title:       'Tiêu đề',
  description: 'Mô tả',
  content:     'Nội dung (HTML)',
  excerpt:     'Tóm tắt',
}

export const LONG_FIELDS = new Set(['content', 'description', 'excerpt'])

export function useTranslations() {
  const records    = ref([])
  const loading    = ref(false)
  const saving     = ref(false)
  const pagination = ref({ current_page: 1, last_page: 1, total: 0 })

  async function fetchRecords(modelKey, page = 1, search = '') {
    loading.value = true
    try {
      const params = new URLSearchParams({ page })
      if (search) params.set('search', search)
      const { data } = await api.get(`/translations/${modelKey}?${params}`)
      records.value    = data.data
      pagination.value = data.pagination
    } finally {
      loading.value = false
    }
  }

  async function saveTranslations(modelKey, id, translations) {
    saving.value = true
    try {
      const { data } = await api.put(`/translations/${modelKey}/${id}`, { translations })
      _updateRecord(id, data.data)
      return data.data
    } finally {
      saving.value = false
    }
  }

  async function clearLocale(modelKey, id, locale) {
    const { data } = await api.delete(`/translations/${modelKey}/${id}/${locale}`)
    _updateRecord(id, data.data)
    return data.data
  }

  function _updateRecord(id, patch) {
    const idx = records.value.findIndex(r => r.id === id)
    if (idx !== -1) records.value[idx] = { ...records.value[idx], ...patch }
  }

  return { records, loading, saving, pagination, fetchRecords, saveTranslations, clearLocale }
}
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/admin/composables/useTranslations.js
git commit -m "feat(i18n): add useTranslations composable"
```

---

## Task 3: TranslationModal.vue

**Files:**
- Create: `resources/js/admin/views/Translations/TranslationModal.vue`

- [ ] **Step 1: Create the modal component**

Create `resources/js/admin/views/Translations/TranslationModal.vue`:

```vue
<template>
  <!-- Backdrop -->
  <Teleport to="body">
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
          <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ modelMeta.label }}</p>
            <h2 class="text-lg font-bold text-slate-900 truncate">{{ record.label }}</h2>
          </div>
          <button @click="$emit('close')" class="p-2 rounded-xl hover:bg-slate-100 text-slate-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Locale tabs -->
        <div class="flex border-b border-slate-200 px-6">
          <button
            v-for="tab in localeTabs"
            :key="tab.code"
            type="button"
            @click="activeLocale = tab.code"
            class="px-4 py-3 text-sm font-semibold border-b-2 transition-colors -mb-px"
            :class="activeLocale === tab.code
              ? 'border-indigo-500 text-indigo-600'
              : 'border-transparent text-slate-500 hover:text-slate-700'"
          >
            {{ tab.flag }} {{ tab.label }}
          </button>
        </div>

        <!-- Fields -->
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5">
          <template v-for="loc in ['vi', 'en', 'zh']" :key="loc">
            <div v-show="activeLocale === loc" class="space-y-4">
              <template v-for="field in modelMeta.fields" :key="field">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    {{ FIELD_LABELS[field] || field }}
                  </label>
                  <textarea
                    v-if="LONG_FIELDS.has(field)"
                    v-model="draft[field][loc]"
                    :rows="field === 'content' ? 12 : 3"
                    :placeholder="fieldPlaceholder(field, loc)"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm resize-y focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    :class="field === 'content' ? 'font-mono text-xs' : ''"
                  ></textarea>
                  <input
                    v-else
                    v-model="draft[field][loc]"
                    type="text"
                    :placeholder="fieldPlaceholder(field, loc)"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                  />
                </div>
              </template>
            </div>
          </template>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
          <button
            v-if="activeLocale !== 'vi'"
            type="button"
            @click="confirmClear"
            :disabled="saving"
            class="text-sm font-medium text-rose-600 hover:text-rose-800 disabled:opacity-50 transition"
          >
            Xóa bản dịch {{ activeLocale.toUpperCase() }}
          </button>
          <div v-else></div>

          <div class="flex gap-3">
            <button @click="$emit('close')" type="button"
              class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
              Hủy
            </button>
            <button @click="handleSave" type="button" :disabled="saving"
              class="px-5 py-2 rounded-xl text-sm font-semibold text-white transition disabled:opacity-50"
              style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">
              {{ saving ? 'Đang lưu...' : 'Lưu tất cả' }}
            </button>
          </div>
        </div>

      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import { FIELD_LABELS, LONG_FIELDS } from '../../composables/useTranslations'

const props = defineProps({
  modelMeta: { type: Object, required: true },
  record:    { type: Object, required: true },
  saving:    { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'save', 'clear'])

const activeLocale = ref('vi')

const localeTabs = [
  { code: 'vi', flag: '🇻🇳', label: 'Tiếng Việt' },
  { code: 'en', flag: '🇬🇧', label: 'English' },
  { code: 'zh', flag: '🇨🇳', label: '中文' },
]

// Build draft from record.all_translations
const draft = reactive({})
watch(() => props.record, (rec) => {
  props.modelMeta.fields.forEach(field => {
    const existing = rec.all_translations?.[field] || {}
    draft[field] = { vi: existing.vi || '', en: existing.en || '', zh: existing.zh || '' }
  })
}, { immediate: true, deep: true })

function fieldPlaceholder(field, loc) {
  const map = {
    name:        { vi: 'Tên tiếng Việt...', en: 'Name in English...', zh: '中文名称...' },
    title:       { vi: 'Tiêu đề tiếng Việt...', en: 'Title in English...', zh: '中文标题...' },
    description: { vi: 'Mô tả tiếng Việt...', en: 'Description in English...', zh: '中文描述...' },
    content:     { vi: 'Nội dung HTML...', en: 'HTML content...', zh: 'HTML内容...' },
    excerpt:     { vi: 'Tóm tắt tiếng Việt...', en: 'Excerpt in English...', zh: '中文摘要...' },
  }
  return map[field]?.[loc] || ''
}

function handleSave() {
  // Build translations object: { fieldName: { vi, en, zh } }
  const translations = {}
  props.modelMeta.fields.forEach(field => {
    translations[field] = { ...draft[field] }
  })
  emit('save', translations)
}

function confirmClear() {
  if (confirm(`Xóa toàn bộ bản dịch ${activeLocale.value.toUpperCase()} của record này?`)) {
    emit('clear', activeLocale.value)
  }
}
</script>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/admin/views/Translations/TranslationModal.vue
git commit -m "feat(i18n): add TranslationModal component"
```

---

## Task 4: TranslationsView.vue

**Files:**
- Create: `resources/js/admin/views/Translations/TranslationsView.vue`

- [ ] **Step 1: Create the view**

Create `resources/js/admin/views/Translations/TranslationsView.vue`:

```vue
<template>
  <AppLayout>
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">🌐 Quản lý bản dịch</h1>
      <p class="text-sm text-slate-500 mt-0.5">Dịch nội dung sang tiếng Anh và tiếng Trung</p>
    </div>

    <!-- Model tab bar -->
    <div class="flex gap-1 flex-wrap mb-5 bg-slate-100 rounded-xl p-1.5">
      <button
        v-for="m in MODELS"
        :key="m.key"
        @click="selectModel(m.key)"
        class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
        :class="activeModelKey === m.key
          ? 'bg-white text-slate-900 shadow-sm font-semibold'
          : 'text-slate-500 hover:text-slate-700'"
      >
        {{ m.label }}
      </button>
    </div>

    <!-- Search -->
    <div class="flex items-center gap-3 mb-4">
      <div class="relative flex-1 max-w-xs">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
          v-model="search"
          @input="onSearch"
          type="text"
          placeholder="Tìm kiếm..."
          class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
        />
      </div>
      <span class="text-sm text-slate-400">{{ pagination.total }} records</span>
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-14 bg-slate-100 rounded-xl animate-pulse"></div>
    </div>

    <!-- Table -->
    <div v-else class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600 w-8">#</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tên / Tiêu đề</th>
            <th class="px-4 py-3.5 font-semibold text-slate-600 text-center w-16">VI</th>
            <th class="px-4 py-3.5 font-semibold text-slate-600 text-center w-16">EN</th>
            <th class="px-4 py-3.5 font-semibold text-slate-600 text-center w-16">ZH</th>
            <th class="px-5 py-3.5 w-24"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="records.length === 0">
            <td colspan="6" class="text-center py-12 text-slate-400 text-sm">Không có dữ liệu</td>
          </tr>
          <tr
            v-for="record in records"
            :key="record.id"
            class="border-t border-slate-100 hover:bg-slate-50/60 transition"
          >
            <td class="px-5 py-3.5 text-slate-400 text-xs">{{ record.id }}</td>
            <td class="px-5 py-3.5 font-medium text-slate-900 max-w-xs truncate">{{ record.label }}</td>
            <td class="px-4 py-3.5 text-center">
              <LocaleBadge :has="record.translation_status?.vi" />
            </td>
            <td class="px-4 py-3.5 text-center">
              <LocaleBadge :has="record.translation_status?.en" />
            </td>
            <td class="px-4 py-3.5 text-center">
              <LocaleBadge :has="record.translation_status?.zh" />
            </td>
            <td class="px-5 py-3.5 text-right">
              <button
                @click="openModal(record)"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition"
              >
                Dịch
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 mt-5">
      <button
        v-for="page in visiblePages"
        :key="page"
        @click="goToPage(page)"
        class="w-9 h-9 rounded-lg text-sm font-medium transition"
        :class="page === pagination.current_page
          ? 'bg-indigo-600 text-white'
          : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
      >
        {{ page }}
      </button>
    </div>

    <!-- Modal -->
    <TranslationModal
      v-if="modalRecord"
      :model-meta="activeModel"
      :record="modalRecord"
      :saving="saving"
      @close="modalRecord = null"
      @save="handleSave"
      @clear="handleClear"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import TranslationModal from './TranslationModal.vue'
import { MODELS, useTranslations } from '../../composables/useTranslations'

// Inline badge component (tiny — no separate file needed)
const LocaleBadge = {
  props: { has: Boolean },
  template: `
    <span
      class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
      :class="has ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-400'"
    >{{ has ? '✓' : '✗' }}</span>
  `,
}

const { records, loading, saving, pagination, fetchRecords, saveTranslations, clearLocale } = useTranslations()

const activeModelKey = ref(MODELS[0].key)
const search         = ref('')
const modalRecord    = ref(null)

const activeModel = computed(() => MODELS.find(m => m.key === activeModelKey.value))

const visiblePages = computed(() => {
  const { current_page, last_page } = pagination.value
  const pages = []
  for (let p = Math.max(1, current_page - 2); p <= Math.min(last_page, current_page + 2); p++) {
    pages.push(p)
  }
  return pages
})

function selectModel(key) {
  activeModelKey.value = key
  search.value = ''
  fetchRecords(key, 1, '')
}

let searchTimer = null
function onSearch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchRecords(activeModelKey.value, 1, search.value), 350)
}

function goToPage(page) {
  fetchRecords(activeModelKey.value, page, search.value)
}

function openModal(record) {
  modalRecord.value = record
}

async function handleSave(translations) {
  const updated = await saveTranslations(activeModelKey.value, modalRecord.value.id, translations)
  if (updated) modalRecord.value = { ...modalRecord.value, ...updated }
}

async function handleClear(locale) {
  const updated = await clearLocale(activeModelKey.value, modalRecord.value.id, locale)
  if (updated) modalRecord.value = { ...modalRecord.value, ...updated }
}

// Load first model on mount
fetchRecords(activeModelKey.value, 1, '')
</script>
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/admin/views/Translations/TranslationsView.vue
git commit -m "feat(i18n): add TranslationsView with model tabs and records table"
```

---

## Task 5: Router + Sidebar + Locale strings + Build

**Files:**
- Modify: `resources/js/admin/router/index.js`
- Modify: `resources/js/admin/components/AppLayout.vue`
- Modify: `resources/js/admin/locales/vi.json`
- Modify: `resources/js/admin/locales/en.json`
- Modify: `resources/js/admin/locales/zh.json`

- [ ] **Step 1: Add route to router/index.js**

Read `resources/js/admin/router/index.js`. Add the import near the other view imports:

```js
import TranslationsView from '../views/Translations/TranslationsView.vue'
```

Add the route inside the `routes` array:

```js
{ path: '/admin/translations', component: TranslationsView },
```

Place it after the `sliders` routes for logical grouping.

- [ ] **Step 2: Add nav item to AppLayout.vue**

Read `resources/js/admin/components/AppLayout.vue`. Find the `navGroups` computed. In the `content` group array, add a new entry after sliders:

```js
{ to: '/admin/translations', label: t('nav.translations'), icon: icons.globe },
```

Then add the globe icon SVG to the `icons` object (after the existing icons):

```js
globe: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>`,
```

- [ ] **Step 3: Add translation keys to locale files**

In `resources/js/admin/locales/vi.json`, add inside `"nav"`:
```json
"translations": "Bản dịch"
```

In `resources/js/admin/locales/en.json`, add inside `"nav"`:
```json
"translations": "Translations"
```

In `resources/js/admin/locales/zh.json`, add inside `"nav"`:
```json
"translations": "翻译"
```

- [ ] **Step 4: Build assets**

```bash
cd /home/theluu/Desktop/Projects/luxstay && ddev exec npm run build 2>&1
```

Expected: build succeeds, `main-*.js` generated without errors.

- [ ] **Step 5: Verify admin loads and route exists**

```bash
curl -sk -o /dev/null -w "%{http_code}" https://luxestay.ddev.site/admin
```

Expected: 200

```bash
curl -sk https://luxestay.ddev.site/admin | grep -o "main-[^\"]*\.js"
```

Expected: new bundle filename (e.g. `main-XXXXXX.js`)

- [ ] **Step 6: Verify API endpoint works**

```bash
# Get auth token first
TOKEN=$(curl -sk -X POST https://luxestay.ddev.site/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@luxestay.com","password":"password"}' \
  | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

curl -sk -H "Authorization: Bearer $TOKEN" \
  "https://luxestay.ddev.site/api/v1/translations/rooms" | head -c 300
```

Expected: JSON with `data` array of rooms including `translation_status` and `all_translations`.

- [ ] **Step 7: Commit**

```bash
git add resources/js/admin/router/index.js \
        resources/js/admin/components/AppLayout.vue \
        resources/js/admin/locales/vi.json \
        resources/js/admin/locales/en.json \
        resources/js/admin/locales/zh.json \
        public/build/
git commit -m "feat(i18n): wire translations page into router, sidebar, and build"
```
