<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Cài đặt trang web</h1>

    <!-- Tab Nav -->
    <div class="flex gap-1 mb-6 bg-slate-100 p-1 rounded-xl w-fit">
      <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
        :class="['px-4 py-2 rounded-lg text-sm font-medium transition', activeTab === tab.key ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-700']">
        {{ tab.label }}
      </button>
    </div>

    <div v-if="loading" class="text-gray-400">Đang tải…</div>
    <template v-else>

      <!-- ===== TAB: GENERAL ===== -->
      <template v-if="activeTab === 'general'">
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-4">Thông tin chung</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Tên trang web</label>
              <input v-model="form.site_name" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Điện thoại</label>
              <input v-model="form.phone" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Email liên hệ</label>
              <input v-model="form.email" type="email" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-4">Logo</h2>
          <ImageUpload v-model="form.logo" label="Ảnh logo" />
          <p class="text-xs text-gray-400 mt-1">Hiện tại: {{ form.logo }}</p>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">Favicon</h2>
          <p class="text-xs text-gray-400 mb-4">Biểu tượng hiển thị trên tab trình duyệt. Khuyến nghị: file .ico hoặc .png kích thước 32×32 hoặc 64×64.</p>
          <ImageUpload v-model="form.favicon" label="Ảnh favicon" />
          <div v-if="form.favicon" class="mt-3 flex items-center gap-3">
            <img :src="'/' + form.favicon" class="w-8 h-8 rounded border object-contain bg-gray-50" alt="favicon preview" />
            <span class="text-xs text-gray-500">{{ form.favicon }}</span>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-4">Mạng xã hội</h2>
          <div class="space-y-4">
            <div v-for="social in socials" :key="social.key">
              <label class="block text-sm font-medium mb-1">
                <i :class="social.icon" class="mr-1"></i> {{ social.label }}
              </label>
              <input v-model="form[social.key]" type="url" placeholder="https://..."
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm" />
            </div>
          </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 max-w-2xl">
          <h2 class="font-semibold text-lg mb-1">reCAPTCHA v3</h2>
          <p class="text-xs text-gray-400 mb-4">
            Bảo vệ tất cả form. Lấy key tại
            <a href="https://www.google.com/recaptcha/admin" target="_blank" class="underline">Google reCAPTCHA Admin</a>.
          </p>
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="form.recaptcha_enabled" class="sr-only peer" true-value="1" false-value="0" />
                <div class="w-10 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-black after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
              </label>
              <span class="text-sm font-medium">Bật reCAPTCHA</span>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Site Key <span class="text-gray-400 font-normal">(public)</span></label>
              <input v-model="form.recaptcha_site_key" placeholder="6Le..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">
                Secret Key <span class="text-gray-400 font-normal">(private)</span>
                <span v-if="form.recaptcha_has_secret_key" class="ml-2 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Đã lưu</span>
              </label>
              <input v-model="form.recaptcha_secret_key" type="password" placeholder="Nhập key mới để thay đổi…"
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono" autocomplete="new-password" />
              <p class="text-xs text-gray-400 mt-1">Để trống nếu không muốn thay đổi secret key đã lưu.</p>
            </div>
          </div>
        </section>

        <SaveBar :saving="saving" :saved="saved" :error="error" @save="saveGeneral" />
      </template>

      <!-- ===== TAB: EMAIL ===== -->
      <template v-if="activeTab === 'email'">
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
        </section>

        <SaveBar :saving="mailSaving" :saved="mailSaved" :error="mailError" @save="saveMail" />
      </template>

    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import SaveBar from '../../components/SaveBar.vue'
import api from '../../stores/api'

const loading   = ref(true)
const activeTab = ref('general')

const tabs = [
  { key: 'general', label: '⚙️ Chung' },
  { key: 'email',   label: '📧 Email' },
]

// --- General ---
const saving = ref(false)
const saved  = ref(false)
const error  = ref('')

const form = ref({
  site_name: '', phone: '', email: '', logo: '', favicon: '',
  facebook_url: '', instagram_url: '', linkedin_url: '', twitter_url: '',
  recaptcha_enabled: '0', recaptcha_site_key: '', recaptcha_secret_key: '',
  recaptcha_has_secret_key: false,
})

const socials = [
  { key: 'facebook_url',  label: 'Facebook',    icon: 'fab fa-facebook' },
  { key: 'instagram_url', label: 'Instagram',   icon: 'fab fa-instagram' },
  { key: 'linkedin_url',  label: 'LinkedIn',    icon: 'fab fa-linkedin' },
  { key: 'twitter_url',   label: 'Twitter / X', icon: 'fab fa-x-twitter' },
]

// --- Mail ---
const mailSaving  = ref(false)
const mailSaved   = ref(false)
const mailError   = ref('')
const testEmailAddr = ref('')
const testSending   = ref(false)
const testResult    = ref(null)

const mail = ref({
  mail_mailer: 'log', mail_host: '', mail_port: 587,
  mail_username: '', mail_password: '', mail_has_password: false,
  mail_encryption: 'tls', mail_from_address: '', mail_from_name: '',
})

onMounted(async () => {
  const [settingsRes, mailRes] = await Promise.all([
    api.get('/settings'),
    api.get('/mail-settings'),
  ])
  Object.assign(form.value, settingsRes.data.data)
  form.value.recaptcha_secret_key = ''
  Object.assign(mail.value, mailRes.data.data)
  mail.value.mail_password = ''
  loading.value = false
})

async function saveGeneral() {
  saving.value = true; saved.value = false; error.value = ''
  try {
    const payload = { ...form.value }
    delete payload.recaptcha_has_secret_key
    const { data } = await api.put('/settings', payload)
    form.value.recaptcha_has_secret_key = data.data.recaptcha_has_secret_key ?? false
    form.value.recaptcha_secret_key = ''
    saved.value = true; setTimeout(() => saved.value = false, 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally { saving.value = false }
}

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
</script>
