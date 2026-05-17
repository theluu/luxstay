<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Chỉnh sửa phòng' : 'Thêm phòng mới' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-lg">
      <div>
        <label class="block text-sm font-medium mb-1">Loại phòng <span class="text-red-500">*</span></label>
        <select v-model="form.room_type_id" required class="w-full border rounded px-3 py-2 text-sm">
          <option value="" disabled>Chọn loại phòng…</option>
          <option v-for="rt in roomTypes" :key="rt.id" :value="rt.id">{{ rt.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Tên <span class="text-red-500">*</span></label>
        <input v-model="form.name" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug <span class="text-red-500">*</span></label>
        <input v-model="form.slug" required class="w-full border rounded px-3 py-2 text-sm" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Giá / Đêm ($) <span class="text-red-500">*</span></label>
          <input v-model="form.price_per_night" type="number" min="0" step="0.01" required class="w-full border rounded px-3 py-2 text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Số khách tối đa <span class="text-red-500">*</span></label>
          <input v-model="form.max_guests" type="number" min="1" required class="w-full border rounded px-3 py-2 text-sm" />
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Diện tích (m²)</label>
          <input v-model="form.size_sqm" type="number" min="0" class="w-full border rounded px-3 py-2 text-sm" />
        </div>
        <div class="flex items-end pb-2">
          <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" v-model="form.is_available" class="rounded" />
            Còn phòng
          </label>
        </div>
      </div>
      <ImageUpload v-model="form.thumbnail" label="Thumbnail" />
      <div>
        <label class="block text-sm font-medium mb-1">Mô tả</label>
        <textarea v-model="form.description" rows="4" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <div class="flex gap-3">
        <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm disabled:opacity-60">
          {{ saving ? 'Đang lưu…' : 'Lưu' }}
        </button>
        <RouterLink to="/admin/rooms" class="px-6 py-2 rounded text-sm border hover:bg-gray-50">Hủy</RouterLink>
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

const saving    = ref(false)
const error     = ref('')
const roomTypes = ref([])
const form = ref({
  room_type_id:    '',
  name:            '',
  slug:            '',
  price_per_night: '',
  max_guests:      2,
  size_sqm:        '',
  is_available:    true,
  thumbnail:       '',
  description:     '',
})

onMounted(async () => {
  const { data } = await api.get('/room-types')
  roomTypes.value = data.data ?? data

  if (isEdit.value) {
    const res = await api.get(`/rooms/${route.params.id}`)
    const room = res.data.data
    form.value = {
      room_type_id:    room.room_type?.id ?? room.room_type_id ?? '',
      name:            room.name,
      slug:            room.slug,
      price_per_night: room.price_per_night,
      max_guests:      room.max_guests,
      size_sqm:        room.size_sqm ?? '',
      is_available:    room.is_available,
      thumbnail:       room.thumbnail ?? '',
      description:     room.description ?? '',
    }
  }
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/rooms/${route.params.id}`, form.value)
    } else {
      await api.post('/rooms', form.value)
    }
    router.push('/admin/rooms')
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
