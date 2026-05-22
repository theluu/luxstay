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
