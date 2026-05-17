<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Đơn hàng</h1>
      <p class="text-sm text-slate-500 mt-0.5">Theo dõi và quản lý đơn hàng</p>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-12 bg-slate-200 rounded-xl animate-pulse"></div>
    </div>

    <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">#</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Khách hàng</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tổng</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Trạng thái</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Thanh toán</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Gateway</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Ngày</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="o in orders" :key="o.id" class="border-t border-slate-100 hover:bg-slate-50/60 transition">
            <td class="px-5 py-3.5 font-mono text-xs text-slate-500">#{{ o.id }}</td>
            <td class="px-5 py-3.5 font-medium text-slate-900">{{ o.user?.name ?? 'Khách' }}</td>
            <td class="px-5 py-3.5 font-semibold text-slate-800">${{ o.total }}</td>
            <td class="px-5 py-3.5">
              <select :value="o.status" @change="updateStatus(o.id, $event.target.value)"
                class="border border-slate-200 rounded-lg px-2.5 py-1 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 capitalize">
                <option v-for="s in statuses" :key="s">{{ s }}</option>
              </select>
            </td>
            <td class="px-5 py-3.5">
              <span :class="{
                'bg-emerald-100 text-emerald-700 border border-emerald-200': o.payment_status === 'paid',
                'bg-amber-100 text-amber-700 border border-amber-200': o.payment_status === 'pending',
                'bg-rose-100 text-rose-700 border border-rose-200': o.payment_status === 'failed',
                'bg-slate-100 text-slate-600 border border-slate-200': !['paid','pending','failed'].includes(o.payment_status),
              }" class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize">{{ o.payment_status }}</span>
            </td>
            <td class="px-5 py-3.5 text-slate-600">
              <div v-if="o.latest_transaction">
                <p class="text-xs font-semibold uppercase">{{ o.latest_transaction.gateway }}</p>
                <p class="text-[11px] text-slate-400 font-mono">{{ o.latest_transaction.gateway_ref }}</p>
              </div>
              <span v-else class="text-xs text-slate-400">COD</span>
            </td>
            <td class="px-5 py-3.5 text-slate-500">{{ new Date(o.created_at).toLocaleDateString() }}</td>
            <td class="px-5 py-3.5">
              <button @click="deleteOrder(o.id)"
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
const orders = ref([])
const loading = ref(true)
const statuses = ['pending', 'processing', 'completed', 'cancelled']
onMounted(async () => {
  const { data } = await api.get('/orders')
  orders.value = data.data
  loading.value = false
})
async function updateStatus(id, status) {
  await api.put(`/orders/${id}`, { status })
}
async function deleteOrder(id) {
  if (!confirm('Xóa đơn hàng này?')) return
  await api.delete(`/orders/${id}`)
  orders.value = orders.value.filter(o => o.id !== id)
}
</script>
