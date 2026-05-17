<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Đặt phòng</h1>
      <p class="text-sm text-slate-500 mt-0.5">Quản lý tất cả đặt phòng</p>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-12 bg-slate-200 rounded-xl animate-pulse"></div>
    </div>

    <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">#</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Khách</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Phòng</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Ngày nhận phòng</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Ngày trả phòng</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Trạng thái</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Thanh toán</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Gateway</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tổng</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="b in bookings" :key="b.id" class="border-t border-slate-100 hover:bg-slate-50/60 transition">
            <td class="px-5 py-3.5 font-mono text-xs text-slate-500">#{{ b.id }}</td>
            <td class="px-5 py-3.5 font-medium text-slate-900">{{ b.user?.name ?? 'Khách' }}</td>
            <td class="px-5 py-3.5 text-slate-600">{{ b.room?.name }}</td>
            <td class="px-5 py-3.5 text-slate-600">{{ b.check_in }}</td>
            <td class="px-5 py-3.5 text-slate-600">{{ b.check_out }}</td>
            <td class="px-5 py-3.5">
              <select :value="b.status" @change="updateStatus(b.id, $event.target.value)"
                class="border border-slate-200 rounded-lg px-2.5 py-1 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 capitalize">
                <option v-for="s in statuses" :key="s">{{ s }}</option>
              </select>
            </td>
            <td class="px-5 py-3.5">
              <span :class="{
                'bg-emerald-100 text-emerald-700 border border-emerald-200': b.payment_status === 'paid',
                'bg-amber-100 text-amber-700 border border-amber-200': b.payment_status === 'pending',
                'bg-rose-100 text-rose-700 border border-rose-200': b.payment_status === 'failed',
                'bg-slate-100 text-slate-600 border border-slate-200': !['paid','pending','failed'].includes(b.payment_status),
              }" class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize">{{ b.payment_status }}</span>
            </td>
            <td class="px-5 py-3.5 text-slate-600">
              <div v-if="b.latest_transaction">
                <p class="text-xs font-semibold uppercase">{{ b.latest_transaction.gateway }}</p>
                <p class="text-[11px] text-slate-400 font-mono">{{ b.latest_transaction.gateway_ref }}</p>
              </div>
              <span v-else class="text-xs text-slate-400">Thanh toán sau</span>
            </td>
            <td class="px-5 py-3.5 font-semibold text-slate-800">${{ b.total_price }}</td>
            <td class="px-5 py-3.5">
              <button @click="deleteBooking(b.id)"
                class="text-xs font-medium text-rose-500 hover:text-rose-700 hover:underline transition">Xóa</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'
const bookings = ref([])
const loading = ref(true)
const statuses = ['pending', 'confirmed', 'cancelled', 'completed']
onMounted(async () => {
  const { data } = await api.get('/bookings')
  bookings.value = data.data
  loading.value = false
})
async function updateStatus(id, status) {
  await api.put(`/bookings/${id}`, { status })
}
async function deleteBooking(id) {
  if (!confirm('Xóa đặt phòng này?')) return
  await api.delete(`/bookings/${id}`)
  bookings.value = bookings.value.filter(b => b.id !== id)
}
</script>
