<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Dashboard</h1>
    </div>
    <div v-if="loading" class="text-gray-500">Loading…</div>
    <div v-else class="grid grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="stat in stats" :key="stat.label"
        class="bg-white rounded-xl shadow-sm border p-5">
        <p class="text-sm text-gray-500">{{ stat.label }}</p>
        <p class="text-3xl font-bold mt-1">{{ stat.value }}</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../components/AppLayout.vue'
import api from '../stores/api'

const loading = ref(true)
const stats   = ref([])

onMounted(async () => {
  try {
    const { data } = await api.get('/dashboard/stats')
    const d = data.data
    stats.value = [
      { label: 'Total Bookings',    value: d.total_bookings },
      { label: 'Pending Bookings',  value: d.pending_bookings },
      { label: 'Total Orders',      value: d.total_orders },
      { label: 'Pending Orders',    value: d.pending_orders },
      { label: 'Total Rooms',       value: d.total_rooms },
      { label: 'Revenue (USD)',      value: '$' + Number(d.total_revenue).toLocaleString() },
    ]
  } finally {
    loading.value = false
  }
})
</script>
