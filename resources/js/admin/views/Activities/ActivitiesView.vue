<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Hoạt động</h1>
      <p class="text-sm text-slate-500 mt-0.5">Quản lý trải nghiệm và hoạt động</p>
    </div>

    <div v-if="loading" class="grid gap-4">
      <div v-for="i in 4" :key="i" class="h-20 bg-slate-200 rounded-2xl animate-pulse"></div>
    </div>

    <div v-else class="grid gap-3">
      <div v-for="a in activities" :key="a.id"
        class="bg-white rounded-2xl border border-slate-200 p-5 flex justify-between items-start shadow-sm hover:shadow-md transition">
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
            style="background: linear-gradient(135deg, #f59e0b, #ef4444)">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-slate-900">{{ a.title }}</h3>
            <p class="text-sm text-slate-500 mt-0.5">
              <span class="inline-flex items-center bg-purple-100 text-purple-700 text-xs font-medium px-2 py-0.5 rounded-full capitalize">{{ a.type }}</span>
              <span class="text-slate-400 ml-2">{{ a.slug }}</span>
            </p>
          </div>
        </div>
        <RouterLink :to="`/admin/activities/${a.id}/edit`"
          class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 border border-indigo-200 hover:border-indigo-400 px-3 py-1.5 rounded-lg transition">
          Chỉnh sửa
        </RouterLink>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'
const activities = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/activities')
  activities.value = data.data
  loading.value = false
})
</script>
