<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">Edit Activity</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <form v-else @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-2xl">
      <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input v-model="form.title" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Type</label>
        <select v-model="form.type" class="w-full border rounded px-3 py-2 text-sm">
          <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Content (HTML)</label>
        <textarea v-model="form.content" rows="10" class="w-full border rounded px-3 py-2 font-mono text-xs"></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" v-model="form.is_featured" id="feat" />
        <label for="feat" class="text-sm">Featured</label>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <div class="flex gap-3">
        <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm">
          {{ saving ? 'Saving...' : 'Save' }}
        </button>
        <RouterLink to="/admin/activities" class="text-sm text-gray-600 hover:underline self-center">Cancel</RouterLink>
      </div>
    </form>
  </AppLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'
const route = useRoute()
const router = useRouter()
const saving = ref(false)
const loading = ref(true)
const error = ref('')
const types = ['spa','golf','hiking','skiing','water_sports','fitness','nature','restaurant','event']
const form = ref({ title: '', slug: '', type: 'spa', content: '', is_featured: false })
onMounted(async () => {
  const { data } = await api.get(`/activities/${route.params.id}`)
  Object.assign(form.value, data.data)
  loading.value = false
})
async function save() {
  saving.value = true
  error.value = ''
  try {
    await api.put(`/activities/${route.params.id}`, form.value)
    router.push('/admin/activities')
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}
</script>
