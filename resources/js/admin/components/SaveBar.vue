<template>
  <div class="max-w-2xl flex items-center gap-4 mt-2">
    <button @click="$emit('save')" :disabled="saving"
      class="bg-slate-900 text-white px-6 py-2 rounded-lg text-sm font-medium disabled:opacity-60 hover:bg-black transition">
      {{ saving ? 'Đang lưu…' : 'Lưu cài đặt' }}
    </button>
    <span v-if="saved" class="text-emerald-600 text-sm font-medium">✓ Đã lưu thành công</span>
    <span v-if="error" class="text-red-500 text-sm">{{ error }}</span>
  </div>
</template>

<script setup>
defineProps({ saving: Boolean, saved: Boolean, error: String })
defineEmits(['save'])
</script>
