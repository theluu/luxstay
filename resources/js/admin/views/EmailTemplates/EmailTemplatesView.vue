<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Email Templates</h1>
        <p class="text-sm text-slate-500 mt-1">Quản lý nội dung email tự động và cấu hình SMTP</p>
      </div>
    </div>

    <!-- Tab Nav -->
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-xl w-fit">
      <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
        :class="['px-4 py-2 rounded-lg text-sm font-medium transition', activeTab === tab.key ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-700']">
        {{ tab.label }}
      </button>
    </div>

    <!-- ===== TAB: TEMPLATES ===== -->
    <template v-if="activeTab === 'templates'">
      <div v-if="loadingTemplates" class="flex items-center justify-center py-20">
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
              <th class="text-left px-6 py-3 font-semibold text-slate-600">Subject</th>
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
    </template>

    <!-- ===== TAB: SMTP ===== -->
    <template v-if="activeTab === 'smtp'">
      <div v-if="loadingMail" class="flex items-center justify-center py-20">
        <svg class="w-6 h-6 animate-spin text-purple-500" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
      </div>
      <template v-else>
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">Cấu hình SMTP</h2>
          <p class="text-xs text-slate-500 mb-5">Thiết lập máy chủ gửi email. Cài đặt sẽ có hiệu lực ngay sau khi lưu.</p>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Mailer</label>
              <select v-model="mail.mail_mailer" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <option value="smtp">SMTP</option>
                <option value="log">Log (không gửi thật, ghi vào log)</option>
                <option value="sendmail">Sendmail</option>
              </select>
            </div>
            <template v-if="mail.mail_mailer === 'smtp'">
              <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                  <label class="block text-sm font-medium mb-1">SMTP Host</label>
                  <input v-model="mail.mail_host" placeholder="smtp.gmail.com" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Port</label>
                  <input v-model.number="mail.mail_port" type="number" placeholder="587" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Encryption</label>
                <select v-model="mail.mail_encryption" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                  <option value="tls">TLS (khuyến nghị, port 587)</option>
                  <option value="ssl">SSL (port 465)</option>
                  <option value="starttls">STARTTLS</option>
                  <option value="">Không mã hóa</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input v-model="mail.mail_username" placeholder="your@email.com" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">
                  Password
                  <span v-if="mail.mail_has_password" class="ml-2 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Đã lưu</span>
                </label>
                <input v-model="mail.mail_password" type="password" placeholder="Nhập mật khẩu mới để thay đổi…"
                  class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" autocomplete="new-password" />
                <p class="text-xs text-gray-400 mt-1">Để trống nếu không muốn thay đổi mật khẩu đã lưu.</p>
              </div>
            </template>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium mb-1">From Address</label>
                <input v-model="mail.mail_from_address" type="email" placeholder="noreply@luxestay.com" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">From Name</label>
                <input v-model="mail.mail_from_name" placeholder="LuxeStay" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
              </div>
            </div>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">Gửi email test</h2>
          <p class="text-xs text-slate-500 mb-4">Kiểm tra kết nối SMTP bằng cách gửi email thử nghiệm.</p>
          <div class="flex gap-3">
            <input v-model="testEmailAddr" type="email" placeholder="Địa chỉ nhận email test"
              class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            <button @click="sendTestEmail" :disabled="testSending"
              class="px-5 py-2 bg-slate-800 text-white text-sm rounded-lg disabled:opacity-50 hover:bg-slate-900 transition whitespace-nowrap">
              {{ testSending ? 'Đang gửi…' : 'Gửi test' }}
            </button>
          </div>
          <p v-if="testResult" :class="['mt-2 text-sm', testResult.ok ? 'text-emerald-600' : 'text-red-500']">
            {{ testResult.msg }}
          </p>
          <p class="text-xs text-slate-400 mt-3">
            💡 Môi trường local dùng Mailpit:
            <a href="https://luxestay.ddev.site:8026" target="_blank" class="underline text-purple-600">https://luxestay.ddev.site:8026</a>
          </p>
        </section>

        <SaveBar :saving="mailSaving" :saved="mailSaved" :error="mailError" @save="saveMail" />
      </template>
    </template>

  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import SaveBar from '../../components/SaveBar.vue'
import api from '../../stores/api'

const activeTab = ref('templates')
const tabs = [
  { key: 'templates', label: '✉️ Templates' },
  { key: 'smtp',      label: '⚙️ SMTP' },
]

// --- Templates ---
const templates       = ref([])
const loadingTemplates = ref(true)

// --- Mail / SMTP ---
const loadingMail  = ref(true)
const mailSaving   = ref(false)
const mailSaved    = ref(false)
const mailError    = ref('')
const testEmailAddr = ref('')
const testSending   = ref(false)
const testResult    = ref(null)

const mail = ref({
  mail_mailer: 'smtp', mail_host: '', mail_port: 587,
  mail_username: '', mail_password: '', mail_has_password: false,
  mail_encryption: 'tls', mail_from_address: '', mail_from_name: '',
})

onMounted(async () => {
  const [tplRes, mailRes] = await Promise.all([
    api.get('/email-templates'),
    api.get('/mail-settings'),
  ])
  templates.value = tplRes.data.data
  loadingTemplates.value = false
  Object.assign(mail.value, mailRes.data.data)
  mail.value.mail_password = ''
  loadingMail.value = false
})

async function saveMail() {
  mailSaving.value = true; mailSaved.value = false; mailError.value = ''
  try {
    const payload = { ...mail.value }
    delete payload.mail_has_password
    await api.put('/mail-settings', payload)
    mail.value.mail_password = ''
    mailSaved.value = true; setTimeout(() => mailSaved.value = false, 3000)
  } catch (e) {
    mailError.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally { mailSaving.value = false }
}

async function sendTestEmail() {
  if (!testEmailAddr.value) return
  testSending.value = true; testResult.value = null
  try {
    const { data } = await api.post('/mail-settings/test', { to: testEmailAddr.value })
    testResult.value = { ok: true, msg: data.message }
  } catch (e) {
    testResult.value = { ok: false, msg: e.response?.data?.message || 'Gửi thất bại.' }
  } finally { testSending.value = false }
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
}
</script>
