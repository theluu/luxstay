<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Phòng</h1>
        <p class="text-sm text-slate-500 mt-0.5">Quản lý phòng và tình trạng còn phòng</p>
      </div>
      <RouterLink to="/admin/rooms/create"
        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:opacity-90"
        style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Thêm phòng mới
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
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Loại</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Giá / Đêm ($)</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Trạng thái</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="room in rooms" :key="room.id" class="border-t border-slate-100 hover:bg-slate-50/60 transition">
            <td class="px-5 py-3.5 font-medium text-slate-900">{{ room.name }}</td>
            <td class="px-5 py-3.5 text-slate-600">{{ room.room_type?.name }}</td>
            <td class="px-5 py-3.5 text-slate-700 font-medium">${{ room.price_per_night }}</td>
            <td class="px-5 py-3.5">
              <span :class="room.is_available
                ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                : 'bg-rose-100 text-rose-700 border border-rose-200'"
                class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full"
                  :class="room.is_available ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                {{ room.is_available ? 'Còn phòng' : 'Hết phòng' }}
              </span>
            </td>
            <td class="px-5 py-3.5 flex gap-2">
              <RouterLink :to="`/admin/rooms/${room.id}/edit`"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition">Chỉnh sửa</RouterLink>
              <button @click="deleteRoom(room.id)"
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
const rooms = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/rooms')
  rooms.value = data.data
  loading.value = false
})
async function deleteRoom(id) {
  if (!confirm('Xóa phòng này?')) return
  await api.delete(`/rooms/${id}`)
  rooms.value = rooms.value.filter(r => r.id !== id)
}
</script>
