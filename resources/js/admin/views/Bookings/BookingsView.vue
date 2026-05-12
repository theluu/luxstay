<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Bookings</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else class="overflow-x-auto">
      <table class="w-full bg-white rounded shadow text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left p-3">#</th>
            <th class="text-left p-3">Guest</th>
            <th class="text-left p-3">Room</th>
            <th class="text-left p-3">Check-in</th>
            <th class="text-left p-3">Check-out</th>
            <th class="text-left p-3">Status</th>
            <th class="text-left p-3">Payment</th>
            <th class="text-left p-3">Total</th>
            <th class="p-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="b in bookings" :key="b.id" class="border-t">
            <td class="p-3">{{ b.id }}</td>
            <td class="p-3">{{ b.user?.name }}</td>
            <td class="p-3">{{ b.room?.name }}</td>
            <td class="p-3">{{ b.check_in }}</td>
            <td class="p-3">{{ b.check_out }}</td>
            <td class="p-3">
              <select :value="b.status" @change="updateStatus(b.id, $event.target.value)" class="border rounded px-2 py-1 text-xs">
                <option v-for="s in statuses" :key="s">{{ s }}</option>
              </select>
            </td>
            <td class="p-3 text-xs">{{ b.payment_status }}</td>
            <td class="p-3">${{ b.total_price }}</td>
            <td class="p-3">
              <button @click="deleteBooking(b.id)" class="text-red-500 text-xs hover:underline">Delete</button>
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
const bookings = ref([])
const loading = ref(true)
const statuses = ['pending', 'confirmed', 'cancelled', 'completed']
onMounted(async () => {
  const { data } = await api.get('/bookings')
  bookings.value = data.data
  loading.value = false
})
async function updateStatus(id, status) {
  await api.put(`/bookings/${id}`, { status })
}
async function deleteBooking(id) {
  if (!confirm('Delete booking?')) return
  await api.delete(`/bookings/${id}`)
  bookings.value = bookings.value.filter(b => b.id !== id)
}
</script>
