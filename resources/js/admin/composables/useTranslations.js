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

// Fields that are manual-entry only — no auto-translate button
export const MANUAL_ONLY_FIELDS = new Set(['content'])

export async function translateText(text, targetLocale) {
  if (!text?.trim()) return ''
  const { data } = await api.post('/translations/translate', { text, target: targetLocale })
  return data.translated || ''
}

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
