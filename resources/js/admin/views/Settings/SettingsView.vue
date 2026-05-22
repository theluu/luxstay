<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Cài đặt trang web</h1>

    <div v-if="loading" class="text-gray-400">Đang tải…</div>
    <template v-else>

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

      <SaveBar :saving="saving" :saved="saved" :error="error" @save="save" />
    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import SaveBar from '../../components/SaveBar.vue'
import api from '../../stores/api'

const loading = ref(true)
const saving  = ref(false)
const saved   = ref(false)
const error   = ref('')

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

onMounted(async () => {
  const { data } = await api.get('/settings')
  Object.assign(form.value, data.data)
  form.value.recaptcha_secret_key = ''
  loading.value = false
})

async function save() {
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
</script>
