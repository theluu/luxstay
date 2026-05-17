<template>
  <AppLayout>
    <div class="max-w-2xl">
      <div class="flex items-center gap-3 mb-6">
        <RouterLink to="/admin/sliders"
          class="p-2 rounded-xl hover:bg-slate-100 text-slate-500 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </RouterLink>
        <div>
          <h1 class="text-2xl font-bold text-slate-900">{{ isEdit ? 'Sửa slide' : 'Thêm slide mới' }}</h1>
          <p class="text-sm text-slate-500 mt-0.5">{{ isEdit ? `Slide #${route.params.id}` : 'Tạo slide mới cho hero slideshow' }}</p>
        </div>
      </div>

      <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">

        <!-- Type -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Loại slide</label>
          <div class="flex gap-3">
            <label v-for="opt in [{v:'image',l:'Ảnh banner'},{v:'video',l:'Video'}]" :key="opt.v"
              class="flex-1 flex items-center gap-3 border-2 rounded-xl px-4 py-3 cursor-pointer transition"
              :class="form.type === opt.v ? 'border-purple-500 bg-purple-50' : 'border-slate-200 hover:border-slate-300'">
              <input type="radio" :value="opt.v" v-model="form.type" class="sr-only">
              <span class="text-sm font-semibold" :class="form.type === opt.v ? 'text-purple-700' : 'text-slate-600'">{{ opt.l }}</span>
            </label>
          </div>
        </div>

        <!-- Media URL -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">
            {{ form.type === 'video' ? 'URL Video (mp4)' : 'Đường dẫn ảnh' }}
          </label>
          <div class="flex gap-2">
            <input v-model="form.media_url" type="text" required
              :placeholder="form.type === 'video' ? 'https://example.com/video.mp4' : 'images/hero.png hoặc https://...'"
              class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
            <label
              class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 cursor-pointer transition">
              Upload
              <input type="file" :accept="form.type === 'video' ? 'video/mp4,video/webm,video/ogg' : 'image/*'" class="sr-only" @change="uploadMedia">
            </label>
          </div>
          <div v-if="form.type === 'image' && form.media_url" class="mt-3">
            <img :src="previewUrl" alt="preview"
              class="h-32 rounded-xl object-cover border border-slate-200"
              @error="e => e.target.style.display='none'">
          </div>
          <div v-if="form.type === 'video' && form.media_url && isDirectVideo(form.media_url)" class="mt-3">
            <video :src="previewUrl" controls muted
              class="h-40 rounded-xl object-cover border border-slate-200 bg-slate-950"></video>
          </div>
          <p class="text-xs text-slate-400 mt-2">
            Hỗ trợ ảnh JPG, PNG, WebP, GIF, SVG hoặc video MP4, WebM, OGG. Có thể nhập URL ngoài hoặc upload file.
          </p>
        </div>

        <!-- Title -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Tiêu đề <span class="text-slate-400 font-normal">(tuỳ chọn)</span></label>
          <textarea v-model="form.title" rows="3" placeholder="Tiêu đề hiển thị trên slide&#10;Dùng Enter để xuống dòng"
            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 resize-none"></textarea>
        </div>

        <!-- Sort order + Active -->
        <div class="flex gap-4">
          <div class="flex-1">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Thứ tự hiển thị</label>
            <input v-model.number="form.sort_order" type="number" min="0"
              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
          </div>
          <div class="flex-1 flex flex-col">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Trạng thái</label>
            <label class="inline-flex items-center gap-3 cursor-pointer mt-1">
              <div @click="form.is_active = !form.is_active"
                class="relative w-11 h-6 rounded-full transition-colors"
                :class="form.is_active ? 'bg-emerald-500' : 'bg-slate-200'">
                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform"
                      :class="form.is_active ? 'translate-x-5' : 'translate-x-0'"></span>
              </div>
              <span class="text-sm" :class="form.is_active ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                {{ form.is_active ? 'Hiển thị' : 'Ẩn' }}
              </span>
            </label>
          </div>
        </div>

        <!-- Error -->
        <p v-if="error" class="text-rose-600 text-sm bg-rose-50 px-4 py-3 rounded-xl">{{ error }}</p>

        <!-- Submit -->
        <div class="flex gap-3 pt-2">
          <RouterLink to="/admin/sliders"
            class="flex-1 text-center px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm hover:bg-slate-50 transition">
            Hủy
          </RouterLink>
          <button type="submit" :disabled="saving"
            class="flex-1 px-4 py-2.5 rounded-xl bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition disabled:opacity-50">
            {{ saving ? 'Đang lưu...' : (isEdit ? 'Cập nhật' : 'Tạo slide') }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const route  = useRoute()
const router = useRouter()

const isEdit = computed(() => !!route.params.id)
const saving = ref(false)
const error  = ref('')

const form = ref({
  type: 'image',
  title: '',
  media_url: '',
  sort_order: 0,
  is_active: true,
})

const previewUrl = computed(() => {
  const url = form.value.media_url
  if (!url) return ''
  return url.startsWith('http') ? url : '/' + url
})

function isDirectVideo(url) {
  return /\.(mp4|webm|ogg)(\?.*)?$/i.test(url || '')
}

async function uploadMedia(e) {
  const file = e.target.files[0]
  if (!file) return
  const fd = new FormData()
  fd.append('file', file)
  try {
    const { data } = await api.post('/upload', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    form.value.media_url = data.path || data.url || ''
    if (data.type) form.value.type = data.type
  } catch (err) {
    error.value = err.response?.data?.message || 'Upload thất bại'
  } finally {
    e.target.value = ''
  }
}

async function submit() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/sliders/${route.params.id}`, form.value)
    } else {
      await api.post('/sliders', form.value)
    }
    router.push('/admin/sliders')
  } catch (e) {
    error.value = e.response?.data?.message || e.message || 'Lỗi khi lưu'
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  if (!isEdit.value) return
  const { data } = await api.get(`/sliders/${route.params.id}`)
  Object.assign(form.value, data.data)
})
</script>
