<template>
  <AppLayout>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Bình luận Blog</h1>
      <div class="flex gap-2">
        <button v-for="tab in tabs" :key="tab.value" @click="switchTab(tab.value)"
          :class="['px-4 py-1.5 rounded-full text-sm font-medium transition',
            activeTab === tab.value ? 'bg-black text-white' : 'bg-white border text-gray-600 hover:bg-gray-50']">
          {{ tab.label }}
          <span v-if="tab.value === 'pending' && pendingCount" class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5">{{ pendingCount }}</span>
        </button>
      </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
      <div v-if="loading" class="px-6 py-10 text-center text-gray-400">Đang tải…</div>
      <div v-else-if="!comments.length" class="px-6 py-10 text-center text-gray-400">Không có bình luận.</div>
      <div v-else class="divide-y">
        <div v-for="c in comments" :key="c.id" class="p-5">
          <!-- Comment header -->
          <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3 flex-1 min-w-0">
              <img :src="`https://www.gravatar.com/avatar/${c.gravatar}?s=48&d=mp`"
                class="w-10 h-10 rounded-full shrink-0 bg-gray-100" alt="">
              <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                  <span class="font-medium text-sm">{{ c.author_name }}</span>
                  <a :href="`mailto:${c.author_email}`" class="text-xs text-gray-400 hover:underline">{{ c.author_email }}</a>
                  <span class="text-xs text-gray-400">·</span>
                  <span class="text-xs text-gray-400">{{ formatDate(c.created_at) }}</span>
                  <span v-if="c.post" class="text-xs text-blue-600">
                    on <a :href="`/blog/${c.post.slug}`" target="_blank" class="hover:underline">{{ c.post.title }}</a>
                  </span>
                </div>
                <p class="text-sm text-gray-700 mt-1">{{ c.body }}</p>
              </div>
            </div>
            <!-- Actions -->
            <div class="flex gap-2 shrink-0">
              <button v-if="!c.is_approved" @click="approve(c)"
                class="text-xs bg-green-600 text-white px-3 py-1.5 rounded hover:bg-green-700">
                Duyệt
              </button>
              <button @click="openReply(c)"
                class="text-xs bg-gray-800 text-white px-3 py-1.5 rounded hover:bg-gray-900">
                Trả lời
              </button>
              <button @click="reject(c)"
                class="text-xs border border-red-300 text-red-500 px-3 py-1.5 rounded hover:bg-red-50">
                Xóa
              </button>
            </div>
          </div>

          <!-- Existing replies -->
          <div v-if="c.replies?.length" class="mt-3 ml-13 space-y-2 pl-4 border-l-2 border-amber-300">
            <div v-for="r in c.replies" :key="r.id" class="flex gap-2 items-start">
              <span class="text-xs font-semibold text-amber-700 shrink-0 mt-0.5">LuxeStay:</span>
              <p class="text-sm text-gray-700">{{ r.body }}</p>
              <span class="text-xs text-gray-400 shrink-0 ml-auto">{{ formatDate(r.created_at) }}</span>
            </div>
          </div>

          <!-- Inline reply box -->
          <div v-if="replyingTo === c.id" class="mt-3 ml-13">
            <textarea v-model="replyBody" rows="3" placeholder="Viết phản hồi với tư cách nhóm LuxeStay…"
              class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900" />
            <div class="flex gap-2 mt-2">
              <button @click="submitReply(c)" :disabled="!replyBody.trim() || submitting"
                class="text-sm bg-black text-white px-4 py-1.5 rounded disabled:opacity-50">
                {{ submitting ? 'Đang gửi…' : 'Gửi phản hồi' }}
              </button>
              <button @click="replyingTo = null; replyBody = ''"
                class="text-sm border px-4 py-1.5 rounded hover:bg-gray-50">Hủy</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="lastPage > 1" class="flex gap-2 mt-4 justify-end">
      <button v-for="p in lastPage" :key="p" @click="loadPage(p)"
        :class="['w-8 h-8 rounded text-sm', page === p ? 'bg-black text-white' : 'border hover:bg-gray-50']">
        {{ p }}
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'

const tabs = [
  { label: 'Tất cả', value: 'all' },
  { label: 'Chờ duyệt', value: 'pending' },
  { label: 'Đã duyệt', value: 'approved' },
]

const comments    = ref([])
const loading     = ref(true)
const activeTab   = ref('all')
const page        = ref(1)
const lastPage    = ref(1)
const pendingCount = ref(0)
const replyingTo  = ref(null)
const replyBody   = ref('')
const submitting  = ref(false)

onMounted(() => {
  load()
  loadPendingCount()
})

async function load() {
  loading.value = true
  const { data } = await api.get('/comments', { params: { status: activeTab.value, page: page.value } })
  comments.value = data.data.map(c => ({ ...c, replies: c.replies ?? [] }))
  lastPage.value = data.last_page
  loading.value  = false
}

async function loadPendingCount() {
  const { data } = await api.get('/comments', { params: { status: 'pending', page: 1 } })
  pendingCount.value = data.total
}

function switchTab(tab) {
  activeTab.value = tab
  page.value = 1
  load()
}

function loadPage(p) {
  page.value = p
  load()
}

async function approve(c) {
  await api.patch(`/comments/${c.id}/approve`)
  c.is_approved = true
  if (activeTab.value === 'pending') comments.value = comments.value.filter(x => x.id !== c.id)
  pendingCount.value = Math.max(0, pendingCount.value - 1)
}

async function reject(c) {
  if (!confirm('Xóa bình luận này?')) return
  await api.delete(`/comments/${c.id}`)
  comments.value = comments.value.filter(x => x.id !== c.id)
  if (!c.is_approved) pendingCount.value = Math.max(0, pendingCount.value - 1)
}

function openReply(c) {
  replyingTo.value = replyingTo.value === c.id ? null : c.id
  replyBody.value  = ''
}

async function submitReply(c) {
  if (!replyBody.value.trim()) return
  submitting.value = true
  const { data } = await api.post(`/comments/${c.id}/reply`, { body: replyBody.value })
  if (!c.replies) c.replies = []
  c.replies.push(data)
  replyingTo.value = null
  replyBody.value  = ''
  submitting.value = false
}

function formatDate(d) {
  return new Date(d).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}
</script>
