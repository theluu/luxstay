<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Thanh toán</h1>
      <p class="text-sm text-slate-500 mt-1">Cấu hình VNPAY, theo dõi giao dịch và trạng thái xử lý</p>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-12 bg-slate-200 rounded-xl animate-pulse"></div>
    </div>

    <section v-else class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
      <div class="flex items-start justify-between gap-4 mb-5">
        <div>
          <h2 class="font-bold text-slate-900">Setup VNPAY</h2>
          <p class="text-xs text-slate-500 mt-1">Lưu cấu hình sandbox/production. Hash secret không hiển thị lại sau khi lưu.</p>
        </div>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
          <input v-model="settings.vnpay_enabled" type="checkbox" class="w-4 h-4">
          Bật VNPAY
        </label>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Môi trường</label>
          <select v-model="settings.vnpay_environment" @change="applyEnvironmentUrl"
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="sandbox">Sandbox</option>
            <option value="production">Production</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Payment URL</label>
          <input v-model="settings.vnpay_payment_url" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">TMN Code</label>
          <input v-model="settings.vnpay_tmn_code" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">
            Hash Secret
            <span v-if="settings.vnpay_has_hash_secret" class="text-xs text-emerald-600 font-normal">(đã lưu, nhập mới để thay đổi)</span>
          </label>
          <input v-model="settings.vnpay_hash_secret" type="password" autocomplete="new-password"
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Return URL</label>
          <input v-model="settings.vnpay_return_url" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">IPN URL</label>
          <input v-model="settings.vnpay_ipn_url" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Locale</label>
          <select v-model="settings.vnpay_locale" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <option value="vn">Tiếng Việt</option>
            <option value="en">English</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Tỷ giá USD sang VND</label>
          <input v-model.number="settings.vnpay_usd_to_vnd" type="number" min="1"
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
      </div>

      <div class="flex items-center gap-3 mt-5">
        <button @click="saveSettings" :disabled="savingSettings"
          class="bg-black text-white px-5 py-2 rounded-lg text-sm font-semibold disabled:opacity-60">
          {{ savingSettings ? 'Đang lưu...' : 'Lưu setup VNPAY' }}
        </button>
        <p v-if="settingsSaved" class="text-sm text-emerald-600">Đã lưu cấu hình.</p>
        <p v-if="settingsError" class="text-sm text-rose-600">{{ settingsError }}</p>
      </div>
    </section>

    <div v-if="!loading" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
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
const settings = ref({})
const savingSettings = ref(false)
const settingsSaved = ref(false)
const settingsError = ref('')

onMounted(load)

async function load() {
  const [txRes, settingsRes] = await Promise.all([
    api.get('/payment-transactions'),
    api.get('/payment-settings'),
  ])
  transactions.value = txRes.data.data
  settings.value = settingsRes.data.data
  loading.value = false
}

async function saveSettings() {
  savingSettings.value = true
  settingsSaved.value = false
  settingsError.value = ''
  try {
    const { data } = await api.put('/payment-settings', settings.value)
    settings.value = data.data
    settingsSaved.value = true
    setTimeout(() => (settingsSaved.value = false), 3000)
  } catch (e) {
    settingsError.value = e.response?.data?.message || 'Lưu cấu hình thất bại.'
  } finally {
    savingSettings.value = false
  }
}

function applyEnvironmentUrl() {
  if (settings.value.vnpay_environment === 'sandbox') {
    settings.value.vnpay_payment_url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'
  } else {
    settings.value.vnpay_payment_url = 'https://pay.vnpay.vn/vpcpay.html'
  }
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
