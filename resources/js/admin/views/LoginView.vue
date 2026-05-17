<template>
  <div class="min-h-screen flex items-center justify-center relative overflow-hidden"
    style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 70%, #1e1b4b 100%)">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full opacity-20"
        style="background: radial-gradient(circle, #a78bfa, transparent)"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full opacity-20"
        style="background: radial-gradient(circle, #818cf8, transparent)"></div>
      <div class="absolute top-1/3 left-1/4 w-48 h-48 rounded-full opacity-10"
        style="background: radial-gradient(circle, #f59e0b, transparent)"></div>
    </div>

    <div class="relative w-full max-w-md px-4">
      <!-- Logo -->
      <div class="flex justify-center mb-8">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-2xl shadow-lg flex items-center justify-center"
            style="background: linear-gradient(135deg, #f59e0b, #ef4444)">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
          </div>
          <div>
            <p class="text-white font-bold text-xl leading-none">LuxeStay</p>
            <p class="text-purple-300 text-xs mt-0.5">Cổng quản trị</p>
          </div>
        </div>
      </div>

      <!-- Card -->
      <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
        <h2 class="text-xl font-bold text-white mb-1 text-center">Chào mừng trở lại</h2>
        <p class="text-purple-300 text-sm text-center mb-7">Đăng nhập để quản lý khách sạn</p>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-purple-200 mb-1.5">Địa chỉ email</label>
            <input v-model="email" type="email" required autocomplete="email"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white placeholder-purple-400 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition"
              placeholder="admin@luxestay.com" />
          </div>
          <div>
            <label class="block text-sm font-medium text-purple-200 mb-1.5">Mật khẩu</label>
            <input v-model="password" type="password" required autocomplete="current-password"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white placeholder-purple-400 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition"
              placeholder="••••••••" />
          </div>

          <p v-if="error"
            class="text-rose-300 text-sm bg-rose-500/20 border border-rose-500/30 rounded-xl px-4 py-2.5">
            {{ error }}
          </p>

          <button type="submit" :disabled="loading"
            class="w-full py-3 rounded-xl font-semibold text-sm transition-all duration-200 disabled:opacity-60 shadow-lg mt-2"
            style="background: linear-gradient(135deg, #f59e0b, #ef4444); color: white;">
            <span v-if="loading" class="flex items-center justify-center gap-2">
              <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              Đang đăng nhập…
            </span>
            <span v-else>Đăng nhập</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth     = useAuthStore()
const router   = useRouter()
const email    = ref('')
const password = ref('')
const error    = ref('')
const loading  = ref(false)

async function handleLogin() {
  error.value   = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/admin')
  } catch (e) {
    error.value = e.response?.data?.errors?.email?.[0]
                || e.response?.data?.message
                || 'Login failed.'
  } finally {
    loading.value = false
  }
}
</script>
