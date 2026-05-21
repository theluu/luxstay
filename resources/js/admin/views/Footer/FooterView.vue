<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Footer</h1>
      <p class="text-sm text-slate-500 mt-1">Quản lý gallery ảnh và menu điều hướng footer</p>
    </div>

    <div v-if="loading" class="text-slate-400">Đang tải...</div>
    <div v-else class="space-y-6 max-w-5xl">

      <!-- Gallery -->
      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <h2 class="font-semibold text-lg mb-4">Gallery ảnh</h2>
        <div class="grid md:grid-cols-5 gap-4">
          <div v-for="(_, i) in gallery" :key="i">
            <ImageUpload v-model="gallery[i]" :label="`Ảnh ${i + 1}`" />
            <button @click="gallery.splice(i, 1)" class="text-xs text-rose-500 mt-2">Xóa ảnh</button>
          </div>
        </div>
        <button @click="gallery.push('')" class="text-sm underline mt-4">+ Thêm ảnh</button>
      </section>

      <!-- Menu footer -->
      <section class="bg-white border border-slate-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-semibold text-lg">Menu footer</h2>
          <button @click="addItem" class="text-sm bg-black text-white px-4 py-2 rounded">+ Thêm mục</button>
        </div>

        <div class="space-y-3">
          <div v-for="(item, i) in menu" :key="i" class="bg-white rounded border border-slate-200">
            <!-- Top-level item -->
            <div class="flex items-center gap-3 p-4">
              <span class="text-slate-400 cursor-move text-lg">⠿</span>
              <div class="flex-1 grid grid-cols-2 gap-3">
                <input v-model="item.label" placeholder="Nhãn" class="border rounded px-3 py-1.5 text-sm" />
                <input v-model="item.url" placeholder="URL (vd. /rooms)" class="border rounded px-3 py-1.5 text-sm" />
              </div>
              <button @click="toggleChildren(i)"
                class="text-xs border px-2 py-1.5 rounded hover:bg-slate-50 whitespace-nowrap">
                Mục con ({{ item.children?.length ?? 0 }})
              </button>
              <button @click="menu.splice(i, 1)" class="text-rose-400 hover:text-rose-600 text-lg leading-none">&times;</button>
            </div>

            <!-- Children -->
            <div v-if="openIndex === i" class="border-t px-4 pb-4 pt-3 bg-slate-50 rounded-b space-y-2">
              <div v-for="(child, j) in item.children" :key="j" class="flex items-center gap-3">
                <span class="w-4 text-slate-300">↳</span>
                <input v-model="child.label" placeholder="Nhãn" class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <input v-model="child.url" placeholder="URL" class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <button @click="item.children.splice(j, 1)" class="text-rose-400 hover:text-rose-600">&times;</button>
              </div>
              <button @click="addChild(i)" class="text-xs text-slate-500 hover:text-black underline mt-1">+ Thêm mục con</button>
            </div>
          </div>
        </div>

        <p class="text-xs text-slate-400 mt-4">Menu với mục con sẽ hiển thị dropdown trên frontend.</p>
      </section>

      <p v-if="saved" class="text-emerald-600 text-sm">✓ Đã lưu footer.</p>
      <p v-if="error" class="text-rose-600 text-sm">{{ error }}</p>

      <button @click="save" :disabled="saving"
        class="bg-black text-white px-6 py-2.5 rounded text-sm font-semibold disabled:opacity-60">
        {{ saving ? 'Đang lưu...' : 'Lưu footer' }}
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import api from '../../stores/api'

const loading   = ref(true)
const saving    = ref(false)
const saved     = ref(false)
const error     = ref('')
const gallery   = ref([])
const menu      = ref([])
const openIndex = ref(null)

async function load() {
  const { data } = await api.get('/footer')
  gallery.value = data.data.footer_gallery
  menu.value    = data.data.footer_menu
  loading.value = false
}

function addItem() {
  menu.value.push({ label: '', url: '/', children: [] })
}

function toggleChildren(i) {
  openIndex.value = openIndex.value === i ? null : i
  if (!menu.value[i].children) menu.value[i].children = []
}

function addChild(i) {
  if (!menu.value[i].children) menu.value[i].children = []
  menu.value[i].children.push({ label: '', url: '/' })
}

async function save() {
  saving.value = true
  saved.value  = false
  error.value  = ''
  try {
    await api.put('/footer', {
      footer_gallery: gallery.value.filter(Boolean),
      footer_menu:    menu.value,
    })
    saved.value = true
    setTimeout(() => (saved.value = false), 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}

load()
</script>
