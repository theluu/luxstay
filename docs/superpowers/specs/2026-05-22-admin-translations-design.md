# Admin Translations Management Design

**Date:** 2026-05-22
**Scope:** Dedicated `/admin/translations` page for managing all translatable content

---

## Architecture

**New files:**
- `resources/js/admin/views/Translations/TranslationsView.vue` — model tabs, records table
- `resources/js/admin/views/Translations/TranslationModal.vue` — fullscreen edit modal
- `resources/js/admin/composables/useTranslations.js` — API logic

**Router:** `{ path: '/admin/translations', component: TranslationsView }`

**Sidebar:** Add under "Content" group with globe icon.

---

## Translatable Models

| Key | Label | Endpoint | Fields |
|---|---|---|---|
| rooms | Phòng | /api/admin/rooms | name, description |
| posts | Bài viết | /api/admin/posts | title, excerpt, content |
| activities | Hoạt động | /api/admin/activities | title, content |
| products | Sản phẩm | /api/admin/products | name, description |
| sliders | Slider | /api/admin/sliders | title |
| post-categories | DM Blog | /api/admin/post-categories | name |
| product-categories | DM Sản phẩm | /api/admin/product-categories | name |

---

## TranslationsView

- Tab bar (one tab per model), lazy-loads records on tab switch
- Table: primary label column + VI/EN/ZH badge columns + Dịch button
- Badge: ✓ green (has value) / ✗ red (empty) per locale
- Detect translation status from `all_translations.{field}.{locale}`
- Client-side search filtering on primary label
- Pagination: 20 records/page
- Clicking [Dịch] opens TranslationModal

---

## TranslationModal

- Fullscreen modal overlay
- Header: record primary label + model name
- Tab bar: 🇻🇳 VI | 🇬🇧 EN | 🇨🇳 ZH
- Per tab: all translatable fields as labeled inputs/textareas
- Footer:
  - "Lưu tất cả" — saves all locales in one PUT request
  - "Xóa bản dịch [locale]" — clears current tab's locale (disabled for VI, confirm dialog)
- On save success: update badge in parent table without full reload

---

## useTranslations.js

```js
fetchRecords(model)           // GET /api/admin/{model}
fetchOne(model, id)           // GET /api/admin/{model}/{id} → returns all_translations
saveTranslations(model, id, translations)  // PUT with { translations }
clearLocale(model, id, locale, fields)     // PUT with fields set to "" for that locale
```

---

## Locale Switcher Nav Addition

Add to locale JSON files under nav:
- `translations`: "Bản dịch" / "Translations" / "翻译"
