<template>
  <div>
    <label v-if="label" class="block text-sm font-medium mb-1">{{ label }}</label>

    <!-- Preview -->
    <div v-if="previewUrl" class="mb-2">
      <img :src="previewUrl" alt="preview"
           class="h-40 w-full object-cover rounded border bg-gray-50" />
    </div>
    <div v-else class="mb-2 h-40 w-full rounded border border-dashed bg-gray-50 flex items-center justify-center text-gray-400 text-sm">
      No image
    </div>

    <!-- Drop zone / file picker -->
    <label
      class="relative flex items-center gap-2 cursor-pointer border rounded px-3 py-2 text-sm hover:bg-gray-50 transition"
      :class="uploading ? 'opacity-60 pointer-events-none' : ''"
    >
      <svg v-if="!uploading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 12V4m0 0l-3 3m3-3l3 3" />
      </svg>
      <svg v-else class="h-4 w-4 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
      <span>{{ uploading ? 'Uploading…' : (modelValue ? 'Change image' : 'Upload image') }}</span>
      <input type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
             :disabled="uploading" @change="handleFile" />
    </label>

    <!-- Current path (read-only hint) -->
    <p v-if="modelValue" class="mt-1 text-xs text-gray-400 truncate">{{ modelValue }}</p>
    <p v-if="uploadError" class="mt-1 text-xs text-red-500">{{ uploadError }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import api from '../stores/api'

const props = defineProps({
  modelValue: { type: String, default: '' },
  label:      { type: String, default: '' },
})
const emit = defineEmits(['update:modelValue'])

const uploading   = ref(false)
const uploadError = ref('')
const localPreview = ref('')   // blob URL for just-uploaded file

const previewUrl = computed(() => {
  if (localPreview.value) return localPreview.value
  if (!props.modelValue)  return ''
  // Already a full URL or storage path
  if (props.modelValue.startsWith('http') || props.modelValue.startsWith('/')) {
    return props.modelValue
  }
  return '/' + props.modelValue
})

async function handleFile(e) {
  const file = e.target.files?.[0]
  if (!file) return

  // Show local preview immediately
  localPreview.value = URL.createObjectURL(file)
  uploading.value    = true
  uploadError.value  = ''

  try {
    const fd = new FormData()
    fd.append('file', file)
    const { data } = await api.post('/upload', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    emit('update:modelValue', data.path)
  } catch (err) {
    uploadError.value  = err.response?.data?.message || 'Upload failed.'
    localPreview.value = ''
  } finally {
    uploading.value = false
    e.target.value  = ''
  }
}
</script>
