<template>
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

              <!-- Content fields: manual note banner -->
              <div v-if="hasContentField && loc !== 'vi'"
                class="flex items-center gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Trường <strong>Nội dung (HTML)</strong> cần nhập thủ công — không hỗ trợ dịch tự động.
              </div>

              <template v-for="field in modelMeta.fields" :key="field">
                <div>
                  <!-- Field label + auto-translate button -->
                  <div class="flex items-center justify-between mb-1.5">
                    <label class="text-sm font-medium text-slate-700">
                      {{ FIELD_LABELS[field] || field }}
                    </label>
                    <!-- Auto-translate button: only for non-content fields, non-VI tabs -->
                    <button
                      v-if="!MANUAL_ONLY_FIELDS.has(field) && loc !== 'vi'"
                      type="button"
                      @click="autoTranslate(field, loc)"
                      :disabled="translatingField === field || !draft[field]?.vi?.trim()"
                      class="inline-flex items-center gap-1 text-xs font-medium text-indigo-500 hover:text-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                      <svg v-if="translatingField === field"
                        class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                      </svg>
                      {{ translatingField === field ? 'Đang dịch...' : 'Dịch từ VI' }}
                    </button>
                  </div>

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
import { ref, reactive, watch, computed } from 'vue'
import { FIELD_LABELS, LONG_FIELDS, MANUAL_ONLY_FIELDS, translateText } from '../../composables/useTranslations'

const props = defineProps({
  modelMeta: { type: Object, required: true },
  record:    { type: Object, required: true },
  saving:    { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'save', 'clear'])

const activeLocale     = ref('vi')
const translatingField = ref(null)

const hasContentField = computed(() =>
  props.modelMeta.fields.some(f => MANUAL_ONLY_FIELDS.has(f))
)

async function autoTranslate(field, locale) {
  const sourceText = draft[field]?.vi
  if (!sourceText?.trim()) return
  translatingField.value = field
  try {
    const result = await translateText(sourceText, locale)
    if (result) draft[field][locale] = result
  } finally {
    translatingField.value = null
  }
}

const localeTabs = [
  { code: 'vi', flag: '🇻🇳', label: 'Tiếng Việt' },
  { code: 'en', flag: '🇬🇧', label: 'English' },
  { code: 'zh', flag: '🇨🇳', label: '中文' },
]

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
