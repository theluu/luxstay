<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Rooms</h1>
      <RouterLink to="/admin/rooms/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Room</RouterLink>
    </div>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Name</th>
          <th class="text-left p-3">Type</th>
          <th class="text-left p-3">Price/Night</th>
          <th class="text-left p-3">Available</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="room in rooms" :key="room.id" class="border-t">
          <td class="p-3">{{ room.name }}</td>
          <td class="p-3">{{ room.room_type?.name }}</td>
          <td class="p-3">${{ room.price_per_night }}</td>
          <td class="p-3">{{ room.is_available ? 'Yes' : 'No' }}</td>
          <td class="p-3 flex gap-2">
            <RouterLink :to="`/admin/rooms/${room.id}/edit`" class="text-blue-600 hover:underline text-xs">Edit</RouterLink>
            <button @click="deleteRoom(room.id)" class="text-red-500 hover:underline text-xs">Delete</button>
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
const rooms = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/rooms')
  rooms.value = data.data
  loading.value = false
})
async function deleteRoom(id) {
  if (!confirm('Delete this room?')) return
  await api.delete(`/rooms/${id}`)
  rooms.value = rooms.value.filter(r => r.id !== id)
}
</script>
