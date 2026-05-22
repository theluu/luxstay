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

    <!-- Search + count -->
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
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600 w-14">#</th>
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
              <span
                class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                :class="record.translation_status?.vi ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-400'"
              >{{ record.translation_status?.vi ? '✓' : '✗' }}</span>
            </td>
            <td class="px-4 py-3.5 text-center">
              <span
                class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                :class="record.translation_status?.en ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-400'"
              >{{ record.translation_status?.en ? '✓' : '✗' }}</span>
            </td>
            <td class="px-4 py-3.5 text-center">
              <span
                class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                :class="record.translation_status?.zh ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-400'"
              >{{ record.translation_status?.zh ? '✓' : '✗' }}</span>
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
