<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Đăng ký nhận tin</h1>
        <p class="text-sm text-slate-500 mt-1">Danh sách email đã đăng ký bản tin từ footer</p>
      </div>
      <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full font-medium">
        {{ subscribers.length }} người đăng ký
      </span>
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
    <div v-else-if="!subscribers.length" class="bg-white rounded-2xl border border-slate-200 p-16 text-center">
      <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      <p class="text-slate-500 text-sm">Chưa có email nào đăng ký</p>
    </div>

    <!-- Table -->
    <div v-else class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50">
            <th class="text-left px-6 py-3 font-semibold text-slate-600">#</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Email</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Ngày đăng ký</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(sub, i) in subscribers" :key="sub.id"
              class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
            <td class="px-6 py-3.5 text-slate-400 text-xs">{{ i + 1 }}</td>
            <td class="px-6 py-3.5 font-medium text-slate-800">{{ sub.email }}</td>
            <td class="px-6 py-3.5 text-slate-400 text-xs">{{ formatDate(sub.created_at) }}</td>
            <td class="px-6 py-3.5 text-right">
              <button @click="confirmDelete(sub)"
                class="text-rose-400 hover:text-rose-600 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-rose-50 transition">
                Xóa
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Delete confirm modal -->
    <div v-if="deleteTarget"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl p-7 w-full max-w-sm mx-4">
        <h3 class="font-bold text-slate-900 text-lg mb-2">Xác nhận xóa</h3>
        <p class="text-slate-500 text-sm mb-6">
          Bạn có chắc muốn xóa <span class="font-semibold text-slate-700">{{ deleteTarget.email }}</span> khỏi danh sách?
        </p>
        <div class="flex gap-3">
          <button @click="deleteTarget = null"
            class="flex-1 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm hover:bg-slate-50 transition">
            Hủy
          </button>
          <button @click="doDelete"
            class="flex-1 px-4 py-2 rounded-xl bg-rose-500 text-white text-sm font-semibold hover:bg-rose-600 transition">
            Xóa
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import { useAuthStore } from '../../stores/auth'

const auth        = useAuthStore()
const subscribers = ref([])
const loading     = ref(true)
const deleteTarget = ref(null)

async function fetchSubscribers() {
  loading.value = true
  try {
    const res = await fetch('/api/v1/subscribers', {
      headers: { Authorization: `Bearer ${auth.token}` }
    })
    const data = await res.json()
    subscribers.value = data.data
  } finally {
    loading.value = false
  }
}

function confirmDelete(sub) {
  deleteTarget.value = sub
}

async function doDelete() {
  if (!deleteTarget.value) return
  await fetch(`/api/v1/subscribers/${deleteTarget.value.id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${auth.token}` }
  })
  subscribers.value = subscribers.value.filter(s => s.id !== deleteTarget.value.id)
  deleteTarget.value = null
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

onMounted(fetchSubscribers)
</script>
