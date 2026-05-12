<template>
  <div class="flex h-screen bg-gray-100">
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="p-5 border-b">
        <h2 class="font-bold text-lg tracking-tight">LuxeStay Admin</h2>
      </div>
      <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <RouterLink
          v-for="link in navLinks"
          :key="link.to"
          :to="link.to"
          class="flex items-center gap-2 px-3 py-2 rounded text-sm text-gray-700 hover:bg-gray-100 transition"
          active-class="bg-gray-900 text-white hover:bg-gray-800"
        >
          {{ link.label }}
        </RouterLink>
      </nav>
      <div class="p-4 border-t">
        <p class="text-xs text-gray-500 mb-2 truncate">{{ auth.user?.name }}</p>
        <button @click="handleLogout" class="text-sm text-red-500 hover:underline">Logout</button>
      </div>
    </aside>
    <main class="flex-1 overflow-auto p-8">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth   = useAuthStore()
const router = useRouter()

const navLinks = [
  { to: '/admin',            label: 'Dashboard'  },
  { to: '/admin/rooms',      label: 'Rooms'      },
  { to: '/admin/bookings',   label: 'Bookings'   },
  { to: '/admin/posts',      label: 'Blog Posts' },
  { to: '/admin/products',   label: 'Products'   },
  { to: '/admin/orders',     label: 'Orders'     },
  { to: '/admin/activities', label: 'Activities' },
]

async function handleLogout() {
  await auth.logout()
  router.push('/admin/login')
}
</script>
