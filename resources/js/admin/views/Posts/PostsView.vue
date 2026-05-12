<template>
  <AppLayout>
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Blog Posts</h1>
      <RouterLink to="/admin/posts/create" class="bg-black text-white px-4 py-2 rounded text-sm">+ New Post</RouterLink>
    </div>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <table v-else class="w-full bg-white rounded shadow text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Title</th>
          <th class="text-left p-3">Category</th>
          <th class="text-left p-3">Status</th>
          <th class="text-left p-3">Published</th>
          <th class="p-3"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="post in posts" :key="post.id" class="border-t">
          <td class="p-3">{{ post.title }}</td>
          <td class="p-3">{{ post.category?.name ?? '—' }}</td>
          <td class="p-3">{{ post.status }}</td>
          <td class="p-3">{{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '—' }}</td>
          <td class="p-3 flex gap-2">
            <RouterLink :to="`/admin/posts/${post.id}/edit`" class="text-blue-600 hover:underline text-xs">Edit</RouterLink>
            <button @click="deletePost(post.id)" class="text-red-500 hover:underline text-xs">Delete</button>
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
const posts = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/posts')
  posts.value = data.data
  loading.value = false
})
async function deletePost(id) {
  if (!confirm('Delete post?')) return
  await api.delete(`/posts/${id}`)
  posts.value = posts.value.filter(p => p.id !== id)
}
</script>
