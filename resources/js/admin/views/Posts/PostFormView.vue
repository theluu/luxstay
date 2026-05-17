<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Chỉnh sửa bài viết' : 'Bài viết mới' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
      <div>
        <label class="block text-sm font-medium mb-1">Danh mục <span class="text-red-500">*</span></label>
        <select v-model="form.post_category_id" required class="w-full border rounded px-3 py-2 text-sm">
          <option value="" disabled>Chọn danh mục…</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tiêu đề <span class="text-red-500">*</span></label>
        <input v-model="form.title" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug <span class="text-red-500">*</span></label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tóm tắt</label>
        <textarea v-model="form.excerpt" rows="2" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <ImageUpload v-model="form.thumbnail" label="Thumbnail" />
      <div>
        <label class="block text-sm font-medium mb-1">Nội dung (HTML) <span class="text-red-500">*</span></label>
        <textarea v-model="form.content" rows="12" required class="w-full border rounded px-3 py-2 font-mono text-xs"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Trạng thái</label>
          <select v-model="form.status" class="w-full border rounded px-3 py-2 text-sm">
            <option value="draft">Bản nháp</option>
            <option value="published">Đã đăng</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Loại</label>
          <select v-model="form.type" class="w-full border rounded px-3 py-2 text-sm">
            <option value="standard">Tiêu chuẩn</option>
            <option value="video">Video</option>
            <option value="quote">Trích dẫn</option>
          </select>
        </div>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <div class="flex gap-3">
        <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm disabled:opacity-60">
          {{ saving ? 'Đang lưu…' : 'Lưu' }}
        </button>
        <RouterLink to="/admin/posts" class="px-6 py-2 rounded text-sm border hover:bg-gray-50">Hủy</RouterLink>
      </div>
    </form>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import ImageUpload from '../../components/ImageUpload.vue'
import api from '../../stores/api'

const route  = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)

const saving     = ref(false)
const error      = ref('')
const categories = ref([])
const form = ref({
  post_category_id: '',
  title:     '',
  slug:      '',
  excerpt:   '',
  thumbnail: '',
  content:   '',
  status:    'draft',
  type:      'standard',
})

onMounted(async () => {
  const { data } = await api.get('/post-categories')
  categories.value = data.data ?? data

  if (isEdit.value) {
    const res = await api.get(`/posts/${route.params.id}`)
    const post = res.data.data
    form.value = {
      post_category_id: post.post_category_id ?? post.category?.id ?? '',
      title:     post.title,
      slug:      post.slug,
      excerpt:   post.excerpt ?? '',
      thumbnail: post.thumbnail ?? '',
      content:   post.content ?? '',
      status:    post.status,
      type:      post.type,
    }
  }
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/posts/${route.params.id}`, form.value)
    } else {
      await api.post('/posts', form.value)
    }
    router.push('/admin/posts')
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
