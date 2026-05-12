<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Room' : 'New Room' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-lg">
      <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input v-model="form.name" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Price / Night ($)</label>
        <input v-model="form.price_per_night" type="number" min="0" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Max Guests</label>
        <input v-model="form.max_guests" type="number" min="1" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" v-model="form.is_available" id="avail" />
        <label for="avail" class="text-sm">Available</label>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea v-model="form.description" rows="3" class="w-full border rounded px-3 py-2 text-sm"></textarea>
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
const form = ref({ name: '', slug: '', price_per_night: '', max_guests: 2, is_available: true, description: '', room_type_id: null })
onMounted(async () => {
  if (isEdit.value) {
    const { data } = await api.get(`/rooms/${route.params.id}`)
    Object.assign(form.value, data.data)
  }
})
async function save() {
  saving.value = true
  error.value = ''
  try {
    if (isEdit.value) {
      await api.put(`/rooms/${route.params.id}`, form.value)
    } else {
      await api.post('/rooms', form.value)
    }
    router.push('/admin/rooms')
  } catch (e) {
    error.value = e.response?.data?.message || 'Save failed.'
  } finally {
    saving.value = false
  }
}
</script>
