<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Cài đặt trang web</h1>

    <div v-if="loading" class="text-gray-400">Đang tải…</div>
    <template v-else>

      <!-- General -->
      <section class="bg-white rounded shadow p-6 mb-6 max-w-2xl">
        <h2 class="font-semibold text-lg mb-4">Thông tin chung</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Tên trang web</label>
            <input v-model="form.site_name" class="w-full border rounded px-3 py-2 text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Điện thoại</label>
            <input v-model="form.phone" class="w-full border rounded px-3 py-2 text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2 text-sm" />
          </div>
        </div>
      </section>

      <!-- Logo -->
      <section class="bg-white rounded shadow p-6 mb-6 max-w-2xl">
        <h2 class="font-semibold text-lg mb-4">Logo</h2>
        <ImageUpload v-model="form.logo" label="Ảnh logo" />
        <p class="text-xs text-gray-400 mt-1">Hiện tại: {{ form.logo }}</p>
      </section>

      <!-- Favicon -->
      <section class="bg-white rounded shadow p-6 mb-6 max-w-2xl">
        <h2 class="font-semibold text-lg mb-1">Favicon</h2>
        <p class="text-xs text-gray-400 mb-4">Biểu tượng hiển thị trên tab trình duyệt. Khuyến nghị: file .ico hoặc .png kích thước 32×32 hoặc 64×64.</p>
        <ImageUpload v-model="form.favicon" label="Ảnh favicon" />
        <div v-if="form.favicon" class="mt-3 flex items-center gap-3">
          <img :src="'/' + form.favicon" class="w-8 h-8 rounded border object-contain bg-gray-50" alt="favicon preview" />
          <span class="text-xs text-gray-500">{{ form.favicon }}</span>
        </div>
      </section>

      <!-- Social Media -->
      <section class="bg-white rounded shadow p-6 mb-6 max-w-2xl">
        <h2 class="font-semibold text-lg mb-4">Mạng xã hội</h2>
        <div class="space-y-4">
          <div v-for="social in socials" :key="social.key">
            <label class="block text-sm font-medium mb-1">
              <i :class="social.icon" class="mr-1"></i> {{ social.label }}
            </label>
            <input v-model="form[social.key]" type="url" placeholder="https://..."
              class="w-full border rounded px-3 py-2 text-sm" />
          </div>
        </div>
      </section>

      <p v-if="saved" class="text-green-600 text-sm mb-3">✓ Đã lưu cài đặt.</p>
      <p v-if="error" class="text-red-500 text-sm mb-3">{{ error }}</p>

      <button @click="save" :disabled="saving"
        class="bg-black text-white px-6 py-2 rounded text-sm disabled:opacity-60">
        {{ saving ? 'Đang lưu…' : 'Lưu cài đặt' }}
      </button>
    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import api from '../../stores/api'

const loading = ref(true)
const saving  = ref(false)
const saved   = ref(false)
const error   = ref('')

const form = ref({
  site_name: '', phone: '', email: '', logo: '', favicon: '',
  facebook_url: '', instagram_url: '', linkedin_url: '', twitter_url: '',
})

const socials = [
  { key: 'facebook_url',  label: 'Facebook',  icon: 'fab fa-facebook' },
  { key: 'instagram_url', label: 'Instagram',  icon: 'fab fa-instagram' },
  { key: 'linkedin_url',  label: 'LinkedIn',   icon: 'fab fa-linkedin' },
  { key: 'twitter_url',   label: 'Twitter / X', icon: 'fab fa-x-twitter' },
]

onMounted(async () => {
  const { data } = await api.get('/settings')
  Object.assign(form.value, data.data)
  loading.value = false
})

async function save() {
  saving.value = true
  saved.value  = false
  error.value  = ''
  try {
    await api.put('/settings', form.value)
    saved.value = true
    setTimeout(() => saved.value = false, 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}
</script>
