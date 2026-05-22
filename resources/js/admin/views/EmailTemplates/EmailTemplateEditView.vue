<template>
  <AppLayout>
    <div class="flex items-center gap-3 mb-6">
      <RouterLink to="/admin/email-templates"
        class="text-sm text-slate-500 hover:text-slate-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Danh sách templates
      </RouterLink>
      <span class="text-slate-300">/</span>
      <h1 class="text-xl font-bold text-slate-900">{{ template?.name }}</h1>
    </div>

    <div v-if="loading" class="flex items-center justify-center py-20">
      <svg class="w-6 h-6 animate-spin text-purple-500" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
    </div>

    <div v-else class="grid grid-cols-3 gap-6">
      <!-- Editor -->
      <div class="col-span-2 space-y-5">
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
          <h2 class="font-semibold text-slate-800 mb-4">Nội dung email</h2>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Tiêu đề (Subject)</label>
            <input v-model="form.subject"
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300" />
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Nội dung HTML</label>
            <p class="text-xs text-slate-400 mb-2">
              Dùng <code class="bg-slate-100 px-1 rounded">{tên_biến}</code> để chèn dữ liệu động.
              Nội dung này được nhúng vào layout email LuxeStay.
            </p>
            <textarea v-model="form.body" rows="20"
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-purple-300 resize-y"
              placeholder="<h2>Tiêu đề</h2><p>Nội dung...</p>"></textarea>
          </div>
        </section>

        <!-- Save bar -->
        <div class="flex items-center gap-4">
          <button @click="save" :disabled="saving"
            class="bg-slate-900 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-60 hover:bg-black transition">
            {{ saving ? 'Đang lưu…' : 'Lưu template' }}
          </button>
          <span v-if="saved" class="text-emerald-600 text-sm font-medium">✓ Đã lưu thành công</span>
          <span v-if="saveError" class="text-red-500 text-sm">{{ saveError }}</span>
        </div>
      </div>

      <!-- Variables reference -->
      <div class="col-span-1">
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-6">
          <h2 class="font-semibold text-slate-800 mb-3">Biến có sẵn</h2>
          <div v-if="template?.variables?.length" class="space-y-2">
            <div v-for="v in template.variables" :key="v"
              class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-2 cursor-pointer hover:bg-purple-50 transition group"
              @click="insertVar(v)">
              <code class="text-xs font-mono text-purple-700">{{ '{' + v + '}' }}</code>
              <span class="text-xs text-slate-400 group-hover:text-purple-500">click để chèn</span>
            </div>
          </div>
          <div v-else class="text-xs text-slate-400">Template này không có biến động.</div>
          <hr class="my-4 border-slate-100">
          <p class="text-xs text-slate-400">
            <strong class="text-slate-600">Biến đặc biệt:</strong><br>
            <code class="text-purple-700">{app_url}</code> — URL website
          </p>

          <hr class="my-4 border-slate-100">
          <h3 class="font-medium text-slate-700 text-sm mb-2">Hỗ trợ HTML</h3>
          <div class="space-y-1 text-xs text-slate-500">
            <div><code class="bg-slate-100 px-1 rounded">&lt;strong&gt;</code> — <strong>in đậm</strong></div>
            <div><code class="bg-slate-100 px-1 rounded">&lt;em&gt;</code> — <em>in nghiêng</em></div>
            <div><code class="bg-slate-100 px-1 rounded">.btn</code> — nút CTA vàng</div>
            <div><code class="bg-slate-100 px-1 rounded">.detail-box</code> — bảng thông tin</div>
            <div><code class="bg-slate-100 px-1 rounded">.divider</code> — đường kẻ ngang</div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const route    = useRoute()
const key      = route.params.key
const loading  = ref(true)
const saving   = ref(false)
const saved    = ref(false)
const saveError = ref('')
const template = ref(null)

const form = ref({ subject: '', body: '' })

onMounted(async () => {
  const { data } = await api.get('/email-templates/' + key)
  template.value = data.data
  form.value.subject = data.data.subject
  form.value.body    = data.data.body
  loading.value = false
})

async function save() {
  saving.value = true; saved.value = false; saveError.value = ''
  try {
    const { data } = await api.put('/email-templates/' + key, form.value)
    template.value = data.data
    saved.value = true
    setTimeout(() => saved.value = false, 3000)
  } catch (e) {
    saveError.value = e.response?.data?.message || 'Lưu thất bại.'
  } finally { saving.value = false }
}

function insertVar(v) {
  form.value.body += '{' + v + '}'
}
</script>
