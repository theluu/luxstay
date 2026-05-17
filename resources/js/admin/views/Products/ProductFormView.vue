<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-6">{{ isEdit ? 'Chỉnh sửa sản phẩm' : 'Sản phẩm mới' }}</h1>
    <form @submit.prevent="save" class="bg-white rounded shadow p-6 space-y-4 max-w-lg">
      <div>
        <label class="block text-sm font-medium mb-1">Danh mục <span class="text-red-500">*</span></label>
        <select v-model="form.product_category_id" required class="w-full border rounded px-3 py-2 text-sm">
          <option value="" disabled>Chọn danh mục…</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
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
          <label class="block text-sm font-medium mb-1">Giá ($) <span class="text-red-500">*</span></label>
          <input v-model="form.price" type="number" min="0" step="0.01" required class="w-full border rounded px-3 py-2 text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Tồn kho <span class="text-red-500">*</span></label>
          <input v-model="form.stock" type="number" min="0" required class="w-full border rounded px-3 py-2 text-sm" />
        </div>
      </div>
      <ImageUpload v-model="form.thumbnail" label="Thumbnail" />
      <div>
        <label class="block text-sm font-medium mb-1">Mô tả</label>
        <textarea v-model="form.description" rows="4" class="w-full border rounded px-3 py-2 text-sm"></textarea>
      </div>
      <div>
        <label class="flex items-center gap-2 text-sm cursor-pointer">
          <input type="checkbox" v-model="form.is_active" class="rounded" />
          Kích hoạt (hiển thị trong cửa hàng)
        </label>
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <div class="flex gap-3">
        <button type="submit" :disabled="saving" class="bg-black text-white px-6 py-2 rounded text-sm disabled:opacity-60">
          {{ saving ? 'Đang lưu…' : 'Lưu' }}
        </button>
        <RouterLink to="/admin/products" class="px-6 py-2 rounded text-sm border hover:bg-gray-50">Hủy</RouterLink>
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
  product_category_id: '',
  name:        '',
  slug:        '',
  price:       '',
  stock:       0,
  thumbnail:   '',
  description: '',
  is_active:   true,
})

onMounted(async () => {
  const { data } = await api.get('/product-categories')
  categories.value = data.data ?? data

  if (isEdit.value) {
    const res = await api.get(`/products/${route.params.id}`)
    const p   = res.data.data
    form.value = {
      product_category_id: p.product_category_id ?? p.category?.id ?? '',
      name:        p.name,
      slug:        p.slug,
      price:       p.price,
      stock:       p.stock,
      thumbnail:   p.thumbnail ?? '',
      description: p.description ?? '',
      is_active:   p.is_active,
    }
  }
})

async function save() {
  saving.value = true
  error.value  = ''
  try {
    if (isEdit.value) {
      await api.put(`/products/${route.params.id}`, form.value)
    } else {
      await api.post('/products', form.value)
    }
    router.push('/admin/products')
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
