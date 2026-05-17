<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Sản phẩm</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý danh mục cửa hàng</p>
      </div>
      <RouterLink to="/admin/products/create"
        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:opacity-90"
        style="background: linear-gradient(135deg, #f59e0b, #f97316)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Sản phẩm mới
      </RouterLink>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-12 bg-slate-200 rounded-xl animate-pulse"></div>
    </div>

    <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tên</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Giá ($)</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tồn kho</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Trạng thái</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in products" :key="p.id" class="border-t border-slate-100 hover:bg-slate-50/60 transition">
            <td class="px-5 py-3.5 font-medium text-slate-900">{{ p.name }}</td>
            <td class="px-5 py-3.5 text-slate-700 font-medium">${{ p.price }}</td>
            <td class="px-5 py-3.5 text-slate-600">{{ p.stock }}</td>
            <td class="px-5 py-3.5">
              <span :class="p.is_active
                ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                : 'bg-slate-100 text-slate-600 border border-slate-200'"
                class="text-xs font-semibold px-2.5 py-1 rounded-full">
                {{ p.is_active ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
            <td class="px-5 py-3.5 flex gap-2">
              <RouterLink :to="`/admin/products/${p.id}/edit`"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition">Chỉnh sửa</RouterLink>
              <button @click="deleteProduct(p.id)"
                class="text-xs font-medium text-rose-500 hover:text-rose-700 hover:underline transition">Xóa</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'
const products = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/products')
  products.value = data.data
  loading.value = false
})
async function deleteProduct(id) {
  if (!confirm('Xóa sản phẩm này?')) return
  await api.delete(`/products/${id}`)
  products.value = products.value.filter(p => p.id !== id)
}
</script>
