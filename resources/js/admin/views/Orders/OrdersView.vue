<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Orders</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else class="overflow-x-auto">
      <table class="w-full bg-white rounded shadow text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left p-3">#</th>
            <th class="text-left p-3">Customer</th>
            <th class="text-left p-3">Total</th>
            <th class="text-left p-3">Status</th>
            <th class="text-left p-3">Payment</th>
            <th class="text-left p-3">Date</th>
            <th class="p-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="o in orders" :key="o.id" class="border-t">
            <td class="p-3">{{ o.id }}</td>
            <td class="p-3">{{ o.user?.name }}</td>
            <td class="p-3">${{ o.total }}</td>
            <td class="p-3">
              <select :value="o.status" @change="updateStatus(o.id, $event.target.value)" class="border rounded px-2 py-1 text-xs">
                <option v-for="s in statuses" :key="s">{{ s }}</option>
              </select>
            </td>
            <td class="p-3 text-xs">{{ o.payment_status }}</td>
            <td class="p-3">{{ new Date(o.created_at).toLocaleDateString() }}</td>
            <td class="p-3">
              <button @click="deleteOrder(o.id)" class="text-red-500 text-xs hover:underline">Delete</button>
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
const orders = ref([])
const loading = ref(true)
const statuses = ['pending', 'processing', 'completed', 'cancelled']
onMounted(async () => {
  const { data } = await api.get('/orders')
  orders.value = data.data
  loading.value = false
})
async function updateStatus(id, status) {
  await api.put(`/orders/${id}`, { status })
}
async function deleteOrder(id) {
  if (!confirm('Delete order?')) return
  await api.delete(`/orders/${id}`)
  orders.value = orders.value.filter(o => o.id !== id)
}
</script>
