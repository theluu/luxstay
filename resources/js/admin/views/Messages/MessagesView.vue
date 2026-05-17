<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Tin nhắn liên hệ</h1>
      <span class="text-sm text-gray-500">{{ total }} tổng</span>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Tên</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Nguồn</th>
            <th class="px-4 py-3 text-left">Tin nhắn</th>
            <th class="px-4 py-3 text-left">Ngày</th>
            <th class="px-4 py-3 text-left">Trạng thái</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="px-4 py-8 text-center text-gray-400">Đang tải…</td></tr>
          <tr v-else-if="!messages.length"><td colspan="7" class="px-4 py-8 text-center text-gray-400">Chưa có tin nhắn.</td></tr>
          <template v-else>
            <tr v-for="m in messages" :key="m.id"
                :class="['border-t', m.is_read ? 'bg-white' : 'bg-blue-50']">
              <td class="px-4 py-3 font-medium">{{ m.name }}</td>
              <td class="px-4 py-3">
                <a :href="`mailto:${m.email}`" class="text-blue-600 hover:underline">{{ m.email }}</a>
              </td>
              <td class="px-4 py-3">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                  {{ sourceLabel(m.source) }}
                </span>
              </td>
              <td class="px-4 py-3 max-w-xs">
                <p class="truncate text-gray-700">{{ m.message }}</p>
              </td>
              <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ formatDate(m.created_at) }}</td>
              <td class="px-4 py-3">
                <span :class="m.is_read
                  ? 'bg-gray-100 text-gray-500'
                  : 'bg-blue-100 text-blue-700'"
                  class="px-2 py-0.5 rounded-full text-xs font-medium">
                  {{ m.is_read ? 'Đã đọc' : 'Mới' }}
                </span>
              </td>
              <td class="px-4 py-3 text-right whitespace-nowrap">
                <button v-if="!m.is_read" @click="markRead(m)"
                  class="text-xs text-gray-500 hover:text-black underline mr-3">
                  Đánh dấu đã đọc
                </button>
                <button @click="expand(m)" class="text-xs text-gray-500 hover:text-black underline">
                  Xem
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="selected" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="selected = null">
      <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="font-bold text-lg">{{ selected.name }}</h2>
            <a :href="`mailto:${selected.email}`" class="text-sm text-blue-600">{{ selected.email }}</a>
            <p class="text-xs text-gray-500 mt-1">{{ sourceLabel(selected.source) }}</p>
          </div>
          <button @click="selected = null" class="text-gray-400 hover:text-black text-xl leading-none">&times;</button>
        </div>
        <p class="text-sm text-gray-500 mb-3">{{ formatDate(selected.created_at) }}</p>
        <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ selected.message }}</p>
        <div class="mt-5 flex justify-end gap-3">
          <a :href="`mailto:${selected.email}`" class="text-sm bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
            Trả lời qua Email
          </a>
          <button @click="selected = null" class="text-sm border px-4 py-2 rounded hover:bg-gray-50">Đóng</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const messages = ref([])
const total    = ref(0)
const loading  = ref(true)
const selected = ref(null)

onMounted(load)

async function load() {
  loading.value = true
  const { data } = await api.get('/contact-messages')
  messages.value = data.data
  total.value    = data.total ?? data.data.length
  loading.value  = false
}

async function markRead(msg) {
  await api.patch(`/contact-messages/${msg.id}/read`)
  msg.is_read = true
}

function expand(msg) {
  selected.value = msg
  if (!msg.is_read) markRead(msg)
}

function formatDate(d) {
  return new Date(d).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function sourceLabel(source) {
  return source === 'home_extra_service' ? 'Trang chủ - Extra Service' : 'Liên hệ'
}
</script>
