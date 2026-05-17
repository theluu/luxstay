<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Bài viết Blog</h1>
        <p class="text-sm text-slate-500 mt-0.5">Tạo và quản lý nội dung blog</p>
      </div>
      <RouterLink to="/admin/posts/create"
        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition hover:opacity-90"
        style="background: linear-gradient(135deg, #10b981, #0891b2)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Bài viết mới
      </RouterLink>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-12 bg-slate-200 rounded-xl animate-pulse"></div>
    </div>

    <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tiêu đề</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Danh mục</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Trạng thái</th>
            <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Đã đăng</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts" :key="post.id" class="border-t border-slate-100 hover:bg-slate-50/60 transition">
            <td class="px-5 py-3.5 font-medium text-slate-900">{{ post.title }}</td>
            <td class="px-5 py-3.5 text-slate-600">{{ post.category?.name ?? '—' }}</td>
            <td class="px-5 py-3.5">
              <span :class="post.status === 'published'
                ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                : 'bg-amber-100 text-amber-700 border border-amber-200'"
                class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize">{{ post.status }}</span>
            </td>
            <td class="px-5 py-3.5 text-slate-500">{{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '—' }}</td>
            <td class="px-5 py-3.5 flex gap-2">
              <RouterLink :to="`/admin/posts/${post.id}/edit`"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition">Chỉnh sửa</RouterLink>
              <button @click="deletePost(post.id)"
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
const posts = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/posts')
  posts.value = data.data
  loading.value = false
})
async function deletePost(id) {
  if (!confirm('Xóa bài viết này?')) return
  await api.delete(`/posts/${id}`)
  posts.value = posts.value.filter(p => p.id !== id)
}
</script>
