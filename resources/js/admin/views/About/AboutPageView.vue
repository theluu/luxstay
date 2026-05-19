<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Trang About</h1>
      <p class="text-sm text-slate-500 mt-1">Quản lý nội dung và hình ảnh hiển thị tại /about</p>
    </div>

    <div v-if="loading" class="text-slate-400">Đang tải...</div>
    <div v-else class="space-y-6 max-w-5xl">
      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Banner</h2>
        <div class="grid md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-medium mb-1">Tiêu đề</label>
            <input v-model="about.hero.title" class="w-full border rounded px-3 py-2 text-sm" />
          </div>
          <ImageUpload v-model="about.hero.image" label="Ảnh banner" />
        </div>
      </section>

      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Khối giới thiệu</h2>
        <label class="block text-sm font-medium mb-1">Tiêu đề</label>
        <textarea v-model="about.intro.title" rows="2" class="w-full border rounded px-3 py-2 text-sm mb-4"></textarea>

        <div class="space-y-3 mb-5">
          <div v-for="(_, i) in about.intro.paragraphs" :key="i" class="flex gap-2">
            <textarea v-model="about.intro.paragraphs[i]" rows="3" class="flex-1 border rounded px-3 py-2 text-sm"></textarea>
            <button @click="about.intro.paragraphs.splice(i, 1)" class="text-rose-500 text-sm">Xóa</button>
          </div>
          <button @click="about.intro.paragraphs.push('')" class="text-sm underline">+ Thêm đoạn mô tả</button>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
          <div v-for="(card, i) in about.intro.cards" :key="i" class="border border-slate-200 rounded-lg p-4">
            <h3 class="font-semibold mb-3">Card {{ i + 1 }}</h3>
            <div class="space-y-3">
              <input v-model="card.title" placeholder="Tiêu đề" class="w-full border rounded px-3 py-2 text-sm" />
              <textarea v-model="card.description" rows="2" placeholder="Mô tả" class="w-full border rounded px-3 py-2 text-sm"></textarea>
              <input v-model="card.button_text" placeholder="Text nút" class="w-full border rounded px-3 py-2 text-sm" />
              <input v-model="card.url" placeholder="/about" class="w-full border rounded px-3 py-2 text-sm" />
              <ImageUpload v-model="card.image" label="Ảnh" />
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Comfort</h2>
        <label class="block text-sm font-medium mb-1">Tiêu đề nền</label>
        <textarea v-model="about.band.title" rows="2" class="w-full border rounded px-3 py-2 text-sm mb-4"></textarea>
        <label class="block text-sm font-medium mb-1">Tiêu đề comfort</label>
        <textarea v-model="about.comfort.title" rows="2" class="w-full border rounded px-3 py-2 text-sm mb-4"></textarea>

        <div class="grid md:grid-cols-2 gap-5 mb-5">
          <ImageUpload v-model="about.comfort.left_image" label="Ảnh trái" />
          <ImageUpload v-model="about.comfort.right_image" label="Ảnh phải" />
        </div>

        <div class="grid md:grid-cols-4 gap-3">
          <div v-for="(counter, i) in about.comfort.counters" :key="i" class="border border-slate-200 rounded-lg p-3 space-y-2">
            <input v-model="counter.number" placeholder="Số" class="w-full border rounded px-3 py-2 text-sm" />
            <input v-model="counter.suffix" placeholder="Ký hiệu" class="w-full border rounded px-3 py-2 text-sm" />
            <input v-model="counter.label" placeholder="Nhãn" class="w-full border rounded px-3 py-2 text-sm" />
          </div>
        </div>
      </section>

      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Đối tác</h2>
        <textarea v-model="about.partners.title" rows="2" class="w-full border rounded px-3 py-2 text-sm mb-4"></textarea>
        <div class="grid md:grid-cols-5 gap-4">
          <div v-for="(_, i) in about.partners.logos" :key="i">
            <ImageUpload v-model="about.partners.logos[i]" :label="`Logo ${i + 1}`" />
            <button @click="about.partners.logos.splice(i, 1)" class="text-xs text-rose-500 mt-2">Xóa logo</button>
          </div>
        </div>
        <button @click="about.partners.logos.push('')" class="text-sm underline mt-4">+ Thêm logo</button>
      </section>

      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Gallery footer</h2>
        <div class="grid md:grid-cols-5 gap-4">
          <div v-for="(_, i) in footerGallery" :key="i">
            <ImageUpload v-model="footerGallery[i]" :label="`Ảnh ${i + 1}`" />
            <button @click="footerGallery.splice(i, 1)" class="text-xs text-rose-500 mt-2">Xóa ảnh</button>
          </div>
        </div>
        <button @click="footerGallery.push('')" class="text-sm underline mt-4">+ Thêm ảnh footer</button>
      </section>

      <p v-if="saved" class="text-emerald-600 text-sm">Đã lưu nội dung.</p>
      <p v-if="error" class="text-rose-600 text-sm">{{ error }}</p>

      <button @click="save" :disabled="saving"
        class="bg-black text-white px-6 py-2.5 rounded text-sm font-semibold disabled:opacity-60">
        {{ saving ? 'Đang lưu...' : 'Lưu trang About' }}
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import api from '../../stores/api'

const loading = ref(true)
const saving = ref(false)
const saved = ref(false)
const error = ref('')
const about = ref({})
const footerGallery = ref([])

onMounted(load)

async function load() {
  const { data } = await api.get('/about-page')
  about.value = data.data.about_page
  footerGallery.value = data.data.footer_gallery
  loading.value = false
}

async function save() {
  saving.value = true
  saved.value = false
  error.value = ''
  try {
    await api.put('/about-page', {
      about_page: about.value,
      footer_gallery: footerGallery.value.filter(Boolean),
    })
    saved.value = true
    setTimeout(() => (saved.value = false), 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}
</script>
