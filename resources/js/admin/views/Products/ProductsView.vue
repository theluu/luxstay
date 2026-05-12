<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Products</h1>
      <RouterLink to="/admin/products/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Product</RouterLink>
    </div>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Name</th>
          <th class="text-left p-3">Price</th>
          <th class="text-left p-3">Stock</th>
          <th class="text-left p-3">Active</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products" :key="p.id" class="border-t">
          <td class="p-3">{{ p.name }}</td>
          <td class="p-3">${{ p.price }}</td>
          <td class="p-3">{{ p.stock }}</td>
          <td class="p-3">{{ p.is_active ? 'Yes' : 'No' }}</td>
          <td class="p-3 flex gap-2">
            <RouterLink :to="`/admin/products/${p.id}/edit`" class="text-blue-600 hover:underline text-xs">Edit</RouterLink>
            <button @click="deleteProduct(p.id)" class="text-red-500 hover:underline text-xs">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
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
  if (!confirm('Delete product?')) return
  await api.delete(`/products/${id}`)
  products.value = products.value.filter(p => p.id !== id)
}
</script>
