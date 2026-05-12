<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Post' : 'New Post' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
      <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input v-model="form.title" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Excerpt</label>
        <textarea v-model="form.excerpt" rows="2" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Content (HTML)</label>
        <textarea v-model="form.content" rows="10" required class="w-full border rounded px-3 py-2 font-mono text-xs"></textarea>
      </div>
      <div class="flex gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">Status</label>
          <select v-model="form.status" class="w-full border rounded px-3 py-2 text-sm">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">Type</label>
          <select v-model="form.type" class="w-full border rounded px-3 py-2 text-sm">
            <option value="standard">Standard</option>
            <option value="video">Video</option>
            <option value="quote">Quote</option>
          </select>
        </div>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm">
        {{ saving ? 'Saving...' : 'Save' }}
      </button>
    </form>
  </AppLayout>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'
const route = useRoute()
const router = useRouter()
const isEdit = computed(() => !!route.params.id)
const saving = ref(false)
const error = ref('')
const form = ref({ title: '', slug: '', excerpt: '', content: '', status: 'draft', type: 'standard' })
onMounted(async () => {
  if (isEdit.value) {
    const { data } = await api.get(`/posts/${route.params.id}`)
    Object.assign(form.value, data.data)
  }
})
async function save() {
  saving.value = true
  error.value = ''
  try {
    if (isEdit.value) {
      await api.put(`/posts/${route.params.id}`, form.value)
    } else {
      await api.post('/posts', form.value)
    }
    router.push('/admin/posts')
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}
</script>
