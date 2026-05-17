<template>
  <AppLayout>
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-slate-900">Bảng điều khiển</h1>
      <p class="text-sm text-slate-500 mt-1">Tổng quan hiệu suất khách sạn</p>
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <div v-for="i in 6" :key="i" class="h-32 bg-slate-200 rounded-2xl animate-pulse"></div>
    </div>

    <!-- Stat cards -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="rounded-2xl p-6 shadow-sm relative overflow-hidden"
        :style="{ background: stat.bg }"
      >
        <!-- Decorative circle -->
        <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-20"
          :style="{ background: stat.circle }"></div>
        <div class="relative">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 shadow"
            :style="{ background: stat.iconBg }">
            <span class="w-5 h-5 text-white" v-html="stat.icon"></span>
          </div>
          <p class="text-sm font-medium mb-1" :style="{ color: stat.labelColor }">{{ stat.label }}</p>
          <p class="text-3xl font-extrabold" :style="{ color: stat.valueColor }">{{ stat.value }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import api from '../stores/api'

const loading = ref(true)
const rawData = ref({})

const cardThemes = [
  {
    bg: 'linear-gradient(135deg, #eff6ff, #dbeafe)',
    circle: '#3b82f6',
    iconBg: 'linear-gradient(135deg, #3b82f6, #2563eb)',
    labelColor: '#1e40af',
    valueColor: '#1d4ed8',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`,
  },
  {
    bg: 'linear-gradient(135deg, #fffbeb, #fef3c7)',
    circle: '#f59e0b',
    iconBg: 'linear-gradient(135deg, #f59e0b, #d97706)',
    labelColor: '#92400e',
    valueColor: '#b45309',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
  },
  {
    bg: 'linear-gradient(135deg, #f0fdf4, #dcfce7)',
    circle: '#22c55e',
    iconBg: 'linear-gradient(135deg, #22c55e, #16a34a)',
    labelColor: '#14532d',
    valueColor: '#15803d',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
  },
  {
    bg: 'linear-gradient(135deg, #fff7ed, #ffedd5)',
    circle: '#f97316',
    iconBg: 'linear-gradient(135deg, #f97316, #ea580c)',
    labelColor: '#7c2d12',
    valueColor: '#c2410c',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`,
  },
  {
    bg: 'linear-gradient(135deg, #f5f3ff, #ede9fe)',
    circle: '#8b5cf6',
    iconBg: 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
    labelColor: '#4c1d95',
    valueColor: '#6d28d9',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
  },
  {
    bg: 'linear-gradient(135deg, #fff1f2, #ffe4e6)',
    circle: '#f43f5e',
    iconBg: 'linear-gradient(135deg, #f43f5e, #e11d48)',
    labelColor: '#881337',
    valueColor: '#be123c',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
  },
]

const stats = ref([])

onMounted(async () => {
  try {
    const { data } = await api.get('/dashboard/stats')
    const d = data.data
    const labels = [
      { label: 'Tổng đặt phòng',          value: d.total_bookings },
      { label: 'Đặt phòng chờ xử lý',    value: d.pending_bookings },
      { label: 'Tổng đơn hàng',           value: d.total_orders },
      { label: 'Đơn hàng chờ xử lý',     value: d.pending_orders },
      { label: 'Tổng số phòng',           value: d.total_rooms },
      { label: 'Doanh thu (USD)',          value: '$' + Number(d.total_revenue).toLocaleString() },
    ]
    stats.value = labels.map((s, i) => ({ ...s, ...cardThemes[i] }))
  } finally {
    loading.value = false
  }
})
</script>
