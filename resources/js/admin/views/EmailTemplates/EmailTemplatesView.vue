<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Email Templates</h1>
        <p class="text-sm text-slate-500 mt-1">Quản lý nội dung các email gửi tự động</p>
      </div>
    </div>

    <div v-if="loading" class="flex items-center justify-center py-20">
      <svg class="w-6 h-6 animate-spin text-purple-500" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
      <span class="ml-2 text-slate-500 text-sm">Đang tải...</span>
    </div>

    <div v-else class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50">
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Tên template</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Subject mặc định</th>
            <th class="text-left px-6 py-3 font-semibold text-slate-600">Cập nhật</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tpl in templates" :key="tpl.key"
              class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
            <td class="px-6 py-4">
              <div class="font-medium text-slate-800">{{ tpl.name }}</div>
              <div class="text-xs text-slate-400 mt-0.5 font-mono">{{ tpl.key }}</div>
            </td>
            <td class="px-6 py-4 text-slate-500 text-sm max-w-xs truncate">{{ tpl.subject }}</td>
            <td class="px-6 py-4 text-slate-400 text-xs">{{ formatDate(tpl.updated_at) }}</td>
            <td class="px-6 py-4 text-right">
              <RouterLink :to="'/admin/email-templates/' + tpl.key + '/edit'"
                class="text-purple-600 hover:text-purple-800 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-purple-50 transition">
                Chỉnh sửa
              </RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const templates = ref([])
const loading   = ref(true)

onMounted(async () => {
  const { data } = await api.get('/email-templates')
  templates.value = data.data
  loading.value = false
})

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
}
</script>
