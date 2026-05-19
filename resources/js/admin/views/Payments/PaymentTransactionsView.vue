<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Thanh toán</h1>
      <p class="text-sm text-slate-500 mt-1">Theo dõi giao dịch VNPAY và trạng thái xử lý</p>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-12 bg-slate-200 rounded-xl animate-pulse"></div>
    </div>

    <div v-else class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">#</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Loại</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Gateway</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Mã giao dịch</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Số tiền</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Trạng thái</th>
            <th class="text-left px-5 py-3 font-semibold text-slate-600">Ngày tạo</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!transactions.length">
            <td colspan="8" class="px-5 py-10 text-center text-slate-400">Chưa có giao dịch.</td>
          </tr>
          <tr v-for="tx in transactions" :key="tx.id" class="border-t border-slate-100 hover:bg-slate-50/70">
            <td class="px-5 py-3 font-mono text-xs text-slate-500">#{{ tx.id }}</td>
            <td class="px-5 py-3">{{ tx.payable_type }} #{{ tx.payable_id }}</td>
            <td class="px-5 py-3 uppercase font-semibold text-slate-700">{{ tx.gateway }}</td>
            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ tx.gateway_ref || '-' }}</td>
            <td class="px-5 py-3 font-semibold">${{ tx.amount }}</td>
            <td class="px-5 py-3">
              <span :class="statusClass(tx.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize">
                {{ tx.status }}
              </span>
            </td>
            <td class="px-5 py-3 text-slate-500 whitespace-nowrap">{{ formatDate(tx.created_at) }}</td>
            <td class="px-5 py-3 text-right">
              <button @click="selected = tx" class="text-xs text-indigo-600 hover:underline">Payload</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="selected" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="selected = null">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="text-lg font-bold">Giao dịch #{{ selected.id }}</h2>
            <p class="text-xs text-slate-500 font-mono">{{ selected.gateway_ref }}</p>
          </div>
          <button @click="selected = null" class="text-slate-400 hover:text-slate-800 text-xl leading-none">&times;</button>
        </div>
        <pre class="bg-slate-950 text-slate-100 text-xs rounded-lg p-4 overflow-auto max-h-[60vh]">{{ JSON.stringify(selected.gateway_response || {}, null, 2) }}</pre>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const transactions = ref([])
const loading = ref(true)
const selected = ref(null)

onMounted(load)

async function load() {
  const { data } = await api.get('/payment-transactions')
  transactions.value = data.data
  loading.value = false
}

function statusClass(status) {
  return {
    success: 'bg-emerald-100 text-emerald-700 border border-emerald-200',
    failed: 'bg-rose-100 text-rose-700 border border-rose-200',
    pending: 'bg-amber-100 text-amber-700 border border-amber-200',
  }[status] || 'bg-slate-100 text-slate-600 border border-slate-200'
}

function formatDate(value) {
  return value ? new Date(value).toLocaleString('vi-VN') : '-'
}
</script>
