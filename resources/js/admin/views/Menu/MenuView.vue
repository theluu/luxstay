<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Trình đơn điều hướng</h1>
    </div>

    <div v-if="loading" class="text-gray-400">Đang tải…</div>
    <template v-else>

      <!-- Left Menu -->
      <section class="bg-white border border-slate-200 rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-semibold text-lg">Menu trái</h2>
            <p class="text-xs text-slate-400 mt-0.5">Hiển thị bên trái logo trên header</p>
          </div>
          <button @click="addLeft" class="text-sm bg-black text-white px-4 py-2 rounded">+ Thêm mục</button>
        </div>

        <div class="space-y-3">
          <div v-for="(item, i) in leftItems" :key="'l'+i" class="bg-white rounded border border-slate-200">
            <div class="flex items-center gap-3 p-4">
              <span class="text-gray-400 cursor-move text-lg">⠿</span>
              <div class="flex-1 grid grid-cols-2 gap-3">
                <input v-model="item.label" placeholder="Nhãn" class="border rounded px-3 py-1.5 text-sm" />
                <input v-model="item.url"   placeholder="URL (vd. /rooms)" class="border rounded px-3 py-1.5 text-sm" />
              </div>
              <button @click="toggleLeft(i)"
                class="text-xs border px-2 py-1.5 rounded hover:bg-gray-50 whitespace-nowrap">
                Mục con ({{ item.children?.length ?? 0 }})
              </button>
              <button @click="leftItems.splice(i, 1); if(openLeft===i) openLeft=null" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
            </div>
            <div v-if="openLeft === i" class="border-t px-4 pb-4 pt-3 bg-gray-50 rounded-b space-y-2">
              <div v-for="(child, j) in item.children" :key="j" class="flex items-center gap-3">
                <span class="w-4 text-gray-300">↳</span>
                <input v-model="child.label" placeholder="Nhãn" class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <input v-model="child.url"   placeholder="URL"  class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <button @click="item.children.splice(j, 1)" class="text-red-400 hover:text-red-600">&times;</button>
              </div>
              <button @click="addLeftChild(i)" class="text-xs text-gray-500 hover:text-black underline mt-1">+ Thêm mục con</button>
            </div>
          </div>
        </div>

        <div class="mt-4 flex gap-3">
          <button @click="saveLeft" :disabled="saving"
            class="bg-black text-white px-5 py-2 rounded text-sm disabled:opacity-60">
            {{ saving ? 'Đang lưu…' : 'Lưu menu trái' }}
          </button>
          <span v-if="savedLeft" class="text-green-600 text-sm self-center">✓ Đã lưu</span>
        </div>
      </section>

      <!-- Right Menu -->
      <section class="bg-white border border-slate-200 rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-semibold text-lg">Menu phải</h2>
            <p class="text-xs text-slate-400 mt-0.5">Hiển thị bên phải logo — <strong>Hoạt động</strong> tự động thêm cuối, không cần nhập thủ công</p>
          </div>
          <button @click="addRight" class="text-sm bg-black text-white px-4 py-2 rounded">+ Thêm mục</button>
        </div>

        <div class="space-y-3">
          <div v-for="(item, i) in rightItems" :key="'r'+i" class="bg-white rounded border border-slate-200">
            <div class="flex items-center gap-3 p-4">
              <span class="text-gray-400 cursor-move text-lg">⠿</span>
              <div class="flex-1 grid grid-cols-2 gap-3">
                <input v-model="item.label" placeholder="Nhãn" class="border rounded px-3 py-1.5 text-sm" />
                <input v-model="item.url"   placeholder="URL (vd. /blog)" class="border rounded px-3 py-1.5 text-sm" />
              </div>
              <button @click="toggleRight(i)"
                class="text-xs border px-2 py-1.5 rounded hover:bg-gray-50 whitespace-nowrap">
                Mục con ({{ item.children?.length ?? 0 }})
              </button>
              <button @click="rightItems.splice(i, 1); if(openRight===i) openRight=null" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
            </div>
            <div v-if="openRight === i" class="border-t px-4 pb-4 pt-3 bg-gray-50 rounded-b space-y-2">
              <div v-for="(child, j) in item.children" :key="j" class="flex items-center gap-3">
                <span class="w-4 text-gray-300">↳</span>
                <input v-model="child.label" placeholder="Nhãn" class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <input v-model="child.url"   placeholder="URL"  class="flex-1 border rounded px-3 py-1.5 text-sm" />
                <button @click="item.children.splice(j, 1)" class="text-red-400 hover:text-red-600">&times;</button>
              </div>
              <button @click="addRightChild(i)" class="text-xs text-gray-500 hover:text-black underline mt-1">+ Thêm mục con</button>
            </div>
          </div>

          <!-- Auto item: Hoạt động (read-only preview) -->
          <div class="rounded border border-dashed border-slate-300 bg-slate-50 p-4 flex items-center gap-3 text-slate-400 text-sm">
            <span class="text-slate-300 text-lg">⠿</span>
            <span class="flex-1">Hoạt động <span class="text-xs">(tự động — sub-menu lấy từ bảng Hoạt động)</span></span>
            <span class="text-xs border border-slate-300 px-2 py-1 rounded">Tự động</span>
          </div>
        </div>

        <div class="mt-4 flex gap-3">
          <button @click="saveRight" :disabled="saving"
            class="bg-black text-white px-5 py-2 rounded text-sm disabled:opacity-60">
            {{ saving ? 'Đang lưu…' : 'Lưu menu phải' }}
          </button>
          <span v-if="savedRight" class="text-green-600 text-sm self-center">✓ Đã lưu</span>
        </div>
      </section>

      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const loading    = ref(true)
const saving     = ref(false)
const savedLeft  = ref(false)
const savedRight = ref(false)
const error      = ref('')
const leftItems  = ref([])
const rightItems = ref([])
const openLeft   = ref(null)
const openRight  = ref(null)

onMounted(async () => {
  const { data } = await api.get('/menu')
  leftItems.value  = data.data.nav_items       ?? []
  rightItems.value = data.data.nav_items_right ?? []
  loading.value = false
})

// Left menu helpers
function addLeft() {
  leftItems.value.push({ label: '', url: '/', children: [] })
}
function toggleLeft(i) {
  openLeft.value = openLeft.value === i ? null : i
  if (!leftItems.value[i].children) leftItems.value[i].children = []
}
function addLeftChild(i) {
  if (!leftItems.value[i].children) leftItems.value[i].children = []
  leftItems.value[i].children.push({ label: '', url: '/' })
}

// Right menu helpers
function addRight() {
  rightItems.value.push({ label: '', url: '/', children: [] })
}
function toggleRight(i) {
  openRight.value = openRight.value === i ? null : i
  if (!rightItems.value[i].children) rightItems.value[i].children = []
}
function addRightChild(i) {
  if (!rightItems.value[i].children) rightItems.value[i].children = []
  rightItems.value[i].children.push({ label: '', url: '/' })
}

async function saveLeft() {
  saving.value = true; savedLeft.value = false; error.value = ''
  try {
    await api.put('/menu', { nav_items: leftItems.value })
    savedLeft.value = true
    setTimeout(() => (savedLeft.value = false), 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}

async function saveRight() {
  saving.value = true; savedRight.value = false; error.value = ''
  try {
    await api.put('/menu', { nav_items_right: rightItems.value })
    savedRight.value = true
    setTimeout(() => (savedRight.value = false), 3000)
  } catch (e) {
    error.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}
</script>
