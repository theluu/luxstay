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
        <label class="block text-sm font-medium mb-1">Tiêu đề (mặc định) <span class="text-red-500">*</span></label>
        <input v-model="form.title" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug <span class="text-red-500">*</span></label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>

      <!-- Translation tabs -->
      <div class="mb-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Nội dung đa ngôn ngữ
        </label>
        <div class="flex gap-2 mb-4 border-b border-gray-200">
          <button
            v-for="tab in [
              { code: 'vi', label: '🇻🇳 VI' },
              { code: 'en', label: '🇬🇧 EN' },
              { code: 'zh', label: '🇨🇳 ZH' }
            ]"
            :key="tab.code"
            type="button"
            @click="activeTranslationTab = tab.code"
            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors -mb-px"
            :class="activeTranslationTab === tab.code
              ? 'border-indigo-500 text-indigo-600'
              : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            {{ tab.label }}
          </button>
        </div>

        <template v-for="loc in ['vi', 'en', 'zh']" :key="loc">
          <div v-show="activeTranslationTab === loc">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề</label>
              <input
                v-model="form.translations.title[loc]"
                type="text"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                :placeholder="loc === 'vi' ? 'Tiêu đề tiếng Việt' : loc === 'en' ? 'Post title in English' : '中文标题'"
              />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Tóm tắt</label>
              <textarea
                v-model="form.translations.excerpt[loc]"
                rows="2"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                :placeholder="loc === 'vi' ? 'Tóm tắt tiếng Việt...' : loc === 'en' ? 'Post excerpt in English...' : '中文摘要...'"
              ></textarea>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung (HTML)</label>
              <textarea
                v-model="form.translations.content[loc]"
                rows="12"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                :placeholder="loc === 'vi' ? 'Nội dung HTML tiếng Việt...' : loc === 'en' ? 'HTML content in English...' : '中文HTML内容...'"
              ></textarea>
            </div>
          </div>
        </template>
      </div>

      <ImageUpload v-model="form.thumbnail" label="Thumbnail" />
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
const activeTranslationTab = ref('vi')
const form = ref({
  post_category_id: '',
  title:     '',
  slug:      '',
  excerpt:   '',
  thumbnail: '',
  content:   '',
  status:    'draft',
  type:      'standard',
  translations: {
    title:   { vi: '', en: '', zh: '' },
    excerpt: { vi: '', en: '', zh: '' },
    content: { vi: '', en: '', zh: '' },
  },
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
      translations: {
        title:   { vi: '', en: '', zh: '' },
        excerpt: { vi: '', en: '', zh: '' },
        content: { vi: '', en: '', zh: '' },
      },
    }
    if (post.all_translations) {
      form.value.translations.title   = post.all_translations.title   || { vi: '', en: '', zh: '' }
      form.value.translations.excerpt = post.all_translations.excerpt || { vi: '', en: '', zh: '' }
      form.value.translations.content = post.all_translations.content || { vi: '', en: '', zh: '' }
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
