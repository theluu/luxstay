<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Trình đơn điều hướng</h1>
      <button @click="addItem" class="text-sm bg-black text-white px-4 py-2 rounded">+ Thêm mục</button>
    </div>

    <div v-if="loading" class="text-gray-400">Đang tải…</div>
    <template v-else>
      <div class="space-y-3 mb-6">
        <div v-for="(item, i) in items" :key="i" class="bg-white rounded shadow">
          <!-- Top-level item -->
          <div class="flex items-center gap-3 p-4">
            <span class="text-gray-400 cursor-move text-lg">⠿</span>
            <div class="flex-1 grid grid-cols-2 gap-3">
              <input v-model="item.label" placeholder="Nhãn" class="border rounded px-3 py-1.5 text-sm" />
              <input v-model="item.url"   placeholder="URL (vd. /rooms)" class="border rounded px-3 py-1.5 text-sm" />
            </div>
            <button @click="toggleChildren(i)"
              class="text-xs border px-2 py-1.5 rounded hover:bg-gray-50 whitespace-nowrap">
              Mục con ({{ item.children?.length ?? 0 }})
            </button>
            <button @click="removeItem(i)" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
          </div>

          <!-- Children -->
          <div v-if="openIndex === i" class="border-t px-4 pb-4 pt-3 bg-gray-50 rounded-b space-y-2">
            <div v-for="(child, j) in item.children" :key="j" class="flex items-center gap-3">
              <span class="w-4 text-gray-300">↳</span>
              <input v-model="child.label" placeholder="Nhãn" class="flex-1 border rounded px-3 py-1.5 text-sm" />
              <input v-model="child.url"   placeholder="URL"  class="flex-1 border rounded px-3 py-1.5 text-sm" />
              <button @click="removeChild(i, j)" class="text-red-400 hover:text-red-600">&times;</button>
            </div>
            <button @click="addChild(i)" class="text-xs text-gray-500 hover:text-black underline mt-1">+ Thêm mục con</button>
          </div>
        </div>
      </div>

      <p class="text-xs text-gray-400 mb-4">
        Ghi chú: Tiểu mục <strong>Hoạt động</strong> luôn được lấy động từ cơ sở dữ liệu và hiển thị riêng trong thanh điều hướng bên phải.
      </p>

      <p v-if="saved" class="text-green-600 text-sm mb-3">✓ Đã lưu trình đơn.</p>
      <p v-if="error" class="text-red-500 text-sm mb-3">{{ error }}</p>

      <button @click="save" :disabled="saving"
        class="bg-black text-white px-6 py-2 rounded text-sm disabled:opacity-60">
        {{ saving ? 'Đang lưu…' : 'Lưu trình đơn' }}
      </button>
    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const loading  = ref(true)
const saving   = ref(false)
const saved    = ref(false)
const error    = ref('')
const openIndex = ref(null)
const items    = ref([])

onMounted(async () => {
  const { data } = await api.get('/settings')
  items.value = JSON.parse(data.data.nav_items ?? '[]')
  loading.value = false
})

function addItem() {
  items.value.push({ label: '', url: '/', children: [] })
}

function removeItem(i) {
  items.value.splice(i, 1)
  if (openIndex.value === i) openIndex.value = null
}

function toggleChildren(i) {
  openIndex.value = openIndex.value === i ? null : i
  if (!items.value[i].children) items.value[i].children = []
}

function addChild(i) {
  if (!items.value[i].children) items.value[i].children = []
  items.value[i].children.push({ label: '', url: '/' })
}

function removeChild(i, j) {
  items.value[i].children.splice(j, 1)
}

async function save() {
  saving.value = true
  saved.value  = false
  error.value  = ''
  try {
    await api.put('/settings', { nav_items: items.value })
    saved.value = true
    setTimeout(() => saved.value = false, 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}
</script>
