<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa hoạt động</h1>
    <div v-if="loading" class="text-gray-500">Đang tải…</div>
    <form v-else @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Tiêu đề <span class="text-red-500">*</span></label>
          <input v-model="form.title" required class="w-full border rounded px-3 py-2 text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Slug <span class="text-red-500">*</span></label>
          <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Loại</label>
        <select v-model="form.type" class="w-full border rounded px-3 py-2 text-sm">
          <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
        </select>
      </div>
      <ImageUpload v-model="form.thumbnail" label="Thumbnail" />
      <ImageUpload v-model="form.hero_image" label="Hero Image" />
      <div>
        <label class="block text-sm font-medium mb-1">Nội dung (HTML)</label>
        <textarea v-model="form.content" rows="12" class="w-full border rounded px-3 py-2 font-mono text-xs"></textarea>
      </div>
      <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 text-sm cursor-pointer">
          <input type="checkbox" v-model="form.is_featured" class="rounded" />
          Nổi bật
        </label>
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium">Thứ tự</label>
          <input v-model="form.sort_order" type="number" min="0" class="w-20 border rounded px-2 py-1 text-sm" />
        </div>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <div class="flex gap-3">
        <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm disabled:opacity-60">
          {{ saving ? 'Đang lưu…' : 'Lưu' }}
        </button>
        <RouterLink to="/admin/activities" class="px-6 py-2 rounded text-sm border hover:bg-gray-50">Hủy</RouterLink>
      </div>
    </form>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import api from '../../stores/api'

const route  = useRoute()
const router = useRouter()

const saving  = ref(false)
const loading = ref(true)
const error   = ref('')
const types   = ['spa', 'golf', 'hiking', 'skiing', 'water_sports', 'fitness', 'nature', 'restaurant', 'event']
const form = ref({
  title:      '',
  slug:       '',
  type:       'spa',
  thumbnail:  '',
  hero_image: '',
  content:    '',
  is_featured: false,
  sort_order: 0,
})

onMounted(async () => {
  const { data } = await api.get(`/activities/${route.params.id}`)
  const a = data.data
  form.value = {
    title:      a.title,
    slug:       a.slug,
    type:       a.type,
    thumbnail:  a.thumbnail ?? '',
    hero_image: a.hero_image ?? '',
    content:    a.content ?? '',
    is_featured: a.is_featured,
    sort_order: a.sort_order ?? 0,
  }
  loading.value = false
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    await api.put(`/activities/${route.params.id}`, form.value)
    router.push('/admin/activities')
  } catch (e) {
    const errors = e.response?.data?.errors
    error.value = errors
      ? Object.values(errors).flat().join(' ')
      : e.response?.data?.message || 'Lưu thất bại.'
  } finally {
    saving.value = false
  }
}
</script>
