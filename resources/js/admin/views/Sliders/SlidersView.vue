<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Banner / Slideshow</h1>
        <p class="text-sm text-slate-500 mt-1">Quản lý các slide hiển thị trên trang chủ</p>
      </div>
      <RouterLink to="/admin/sliders/create"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Thêm slide
      </RouterLink>
    </div>

    <div v-if="loading" class="flex items-center justify-center py-20">
      <svg class="w-6 h-6 animate-spin text-purple-500" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
      <span class="ml-2 text-slate-500 text-sm">Đang tải...</span>
    </div>

    <div v-else-if="!sliders.length" class="bg-white rounded-2xl border border-slate-200 p-16 text-center">
      <p class="text-slate-400 text-sm">Chưa có slide nào. Thêm slide đầu tiên!</p>
    </div>

    <div v-else class="space-y-3">
      <div v-for="slide in sliders" :key="slide.id"
           class="bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 px-5 py-4 hover:shadow-md transition">

        <!-- Preview -->
        <div class="w-24 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-slate-100 flex items-center justify-center">
          <template v-if="slide.type === 'video'">
            <video v-if="isDirectVideo(slide.media_url)" :src="mediaPreview(slide.media_url)" muted
              class="w-full h-full object-cover"></video>
            <div v-else class="flex flex-col items-center text-slate-400">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
              </svg>
              <span class="text-xs mt-0.5">Video</span>
            </div>
          </template>
          <template v-else>
            <img :src="mediaPreview(slide.media_url)" :alt="slide.title || 'Slide'"
                 class="w-full h-full object-cover" @error="onImgError($event)">
          </template>
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                  :class="slide.type === 'video' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'">
              {{ slide.type === 'video' ? 'Video' : 'Ảnh' }}
            </span>
            <span v-if="!slide.is_active" class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">Ẩn</span>
          </div>
          <p class="text-sm font-semibold text-slate-800 truncate">{{ slide.title || '(Không có tiêu đề)' }}</p>
          <p class="text-xs text-slate-400 truncate mt-0.5">{{ slide.media_url }}</p>
        </div>

        <!-- Sort order -->
        <div class="text-center flex-shrink-0 w-12">
          <p class="text-xs text-slate-400">Thứ tự</p>
          <p class="text-lg font-bold text-slate-600">{{ slide.sort_order }}</p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2 flex-shrink-0">
          <RouterLink :to="`/admin/sliders/${slide.id}/edit`"
            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
            Sửa
          </RouterLink>
          <button @click="confirmDelete(slide)"
            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
            Xóa
          </button>
        </div>
      </div>
    </div>

    <!-- Services Video Setting -->
    <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
      <h2 class="text-base font-bold text-slate-800 mb-1">Video dịch vụ</h2>
      <p class="text-xs text-slate-400 mb-4">URL video hiển thị trong section dịch vụ ở trang chủ</p>
      <div class="flex gap-3">
        <input v-model="servicesVideoUrl" type="text" placeholder="storage/uploads/service.mp4 hoặc https://..."
          class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
        <label class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
          Upload
          <input type="file" accept="video/mp4,video/webm,video/ogg" class="sr-only" @change="uploadServicesVideo">
        </label>
        <button @click="saveServicesVideo" :disabled="savingVideo"
          class="px-5 py-2.5 rounded-xl bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition disabled:opacity-50">
          {{ savingVideo ? 'Đang lưu...' : 'Lưu' }}
        </button>
      </div>
      <p v-if="videoSaved" class="text-xs text-emerald-600 mt-2">Đã lưu thành công!</p>
    </div>

    <!-- Delete Modal -->
    <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl p-7 w-full max-w-sm mx-4">
        <h3 class="font-bold text-slate-900 text-lg mb-2">Xác nhận xóa</h3>
        <p class="text-slate-500 text-sm mb-6">Bạn có chắc muốn xóa slide này?</p>
        <div class="flex gap-3">
          <button @click="deleteTarget = null" class="flex-1 px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-sm hover:bg-slate-50 transition">Hủy</button>
          <button @click="doDelete" class="flex-1 px-4 py-2 rounded-xl bg-rose-500 text-white text-sm font-semibold hover:bg-rose-600 transition">Xóa</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const sliders         = ref([])
const loading         = ref(true)
const deleteTarget    = ref(null)
const servicesVideoUrl = ref('')
const savingVideo     = ref(false)
const videoSaved      = ref(false)

async function fetchSliders() {
  loading.value = true
  try {
    const { data } = await api.get('/sliders')
    sliders.value = data.data
  } finally {
    loading.value = false
  }
}

async function fetchSettings() {
  const { data } = await api.get('/settings')
  servicesVideoUrl.value = data.data?.services_video_url ?? ''
}

async function saveServicesVideo() {
  savingVideo.value = true
  videoSaved.value  = false
  try {
    await api.put('/settings', { services_video_url: servicesVideoUrl.value })
    videoSaved.value = true
    setTimeout(() => (videoSaved.value = false), 3000)
  } finally {
    savingVideo.value = false
  }
}

async function uploadServicesVideo(e) {
  const file = e.target.files?.[0]
  if (!file) return
  const fd = new FormData()
  fd.append('file', file)
  savingVideo.value = true
  try {
    const { data } = await api.post('/upload', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    servicesVideoUrl.value = data.path || data.url || ''
    await saveServicesVideo()
  } finally {
    savingVideo.value = false
    e.target.value = ''
  }
}

function mediaPreview(url) {
  if (!url) return ''
  if (url.startsWith('http')) return url
  return '/' + url
}

function isDirectVideo(url) {
  return /\.(mp4|webm|ogg)(\?.*)?$/i.test(url || '')
}

function onImgError(e) {
  e.target.style.display = 'none'
  e.target.parentElement.innerHTML = '<span class="text-xs text-slate-400">No img</span>'
}

function confirmDelete(slide) { deleteTarget.value = slide }

async function doDelete() {
  await api.delete(`/sliders/${deleteTarget.value.id}`)
  sliders.value   = sliders.value.filter(s => s.id !== deleteTarget.value.id)
  deleteTarget.value = null
}

onMounted(() => { fetchSliders(); fetchSettings() })
</script>
