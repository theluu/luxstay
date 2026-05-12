<template>
  <AppLayout>
    <h1 class="text-2xl font-bold mb-4">Activities</h1>
    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else class="grid gap-4">
      <div v-for="a in activities" :key="a.id" class="bg-white rounded shadow p-4 flex justify-between items-start">
        <div>
          <h3 class="font-semibold">{{ a.title }}</h3>
          <p class="text-sm text-gray-500">{{ a.type }} · slug: {{ a.slug }}</p>
        </div>
        <RouterLink :to="`/admin/activities/${a.id}/edit`" class="text-blue-600 text-sm hover:underline">Edit</RouterLink>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import api from '../../stores/api'
const activities = ref([])
const loading = ref(true)
onMounted(async () => {
  const { data } = await api.get('/activities')
  activities.value = data.data
  loading.value = false
})
</script>
