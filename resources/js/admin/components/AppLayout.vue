<template>
  <div class="flex h-screen bg-slate-50 overflow-hidden">
    <aside
      class="flex-shrink-0 flex flex-col shadow-2xl transition-[width] duration-200"
      :class="collapsed ? 'w-20' : 'w-64'"
      style="background: linear-gradient(160deg, #1e1b4b 0%, #312e81 30%, #4c1d95 65%, #1e1b4b 100%)"
    >
      <div class="px-4 py-5 border-b border-white/10">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"
            style="background: linear-gradient(135deg, #f59e0b, #ef4444)">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
          </div>
          <div v-if="!collapsed" class="min-w-0">
            <p class="text-white font-bold text-sm leading-none truncate">LuxeStay</p>
            <p class="text-purple-300 text-xs mt-1 truncate">Bảng quản trị</p>
          </div>
        </div>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
        <template v-for="group in navGroups" :key="group.key">
          <button
            v-if="!collapsed"
            @click="toggleGroup(group.key)"
            class="w-full flex items-center justify-between px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-purple-200/50 hover:text-purple-100"
          >
            <span>{{ group.label }}</span>
            <svg class="w-3.5 h-3.5 transition-transform" :class="openGroups[group.key] ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>

          <div v-show="collapsed || openGroups[group.key]" class="space-y-0.5">
            <RouterLink
              v-for="link in group.links"
              :key="link.to"
              :to="link.to"
              :title="collapsed ? link.label : ''"
              class="flex items-center rounded-xl text-sm text-purple-200/70 hover:bg-white/10 hover:text-white transition-all duration-150 group"
              :class="collapsed ? 'justify-center px-0 py-3' : 'gap-3 px-3 py-2.5'"
              active-class="!bg-white/15 !text-white font-semibold shadow-inner"
            >
              <span class="w-5 h-5 flex-shrink-0 opacity-75 group-hover:opacity-100" v-html="link.icon"></span>
              <span v-if="!collapsed" class="truncate">{{ link.label }}</span>
            </RouterLink>
          </div>
        </template>
      </nav>

      <div class="px-4 py-4 border-t border-white/10">
        <div class="flex items-center gap-3 mb-3" :class="collapsed ? 'justify-center' : ''">
          <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
            style="background: linear-gradient(135deg, #a78bfa, #818cf8)">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() || 'A' }}
          </div>
          <div v-if="!collapsed" class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-white truncate">{{ auth.user?.name }}</p>
            <p class="text-xs text-purple-400 truncate">Quản trị viên</p>
          </div>
        </div>
        <button @click="handleLogout"
          class="flex items-center gap-2 text-xs text-rose-400 hover:text-rose-300 transition"
          :class="collapsed ? 'justify-center w-full' : ''"
          :title="collapsed ? 'Đăng xuất' : ''">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          <span v-if="!collapsed">Đăng xuất</span>
        </button>
      </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
      <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between flex-shrink-0 shadow-sm">
        <div class="flex items-center gap-3">
          <button @click="toggleSidebar"
            class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50"
            :title="collapsed ? 'Mở rộng menu' : 'Thu gọn menu'">
            <svg v-if="collapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M19 19l-7-7 7-7"/>
            </svg>
          </button>
          <p class="text-sm text-slate-500">
            Chào mừng trở lại, <span class="text-slate-900 font-semibold">{{ auth.user?.name }}</span>
          </p>
        </div>
        <div class="flex items-center gap-2">
          <a href="/" target="_blank"
            class="inline-flex items-center gap-1.5 text-xs border border-slate-200 text-slate-600 px-2.5 py-1 rounded-full font-medium hover:bg-slate-50">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7v7m0-7L10 14m-4-7H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-1"/>
            </svg>
            Xem website
          </a>
          <span class="inline-flex items-center gap-1.5 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full font-medium">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Trực tuyến
          </span>
        </div>
      </header>

      <main class="flex-1 overflow-auto p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()
const collapsed = ref(localStorage.getItem('admin_sidebar_collapsed') === '1')
const openGroups = reactive(JSON.parse(localStorage.getItem('admin_nav_groups') || '{"operations":true,"content":true,"customers":true,"system":true}'))

const icons = {
  dashboard: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
  rooms: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
  calendar: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`,
  document: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
  bag: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>`,
  clipboard: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
  bolt: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`,
  image: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-16 4h16a2 2 0 002-2V8a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>`,
  folder: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h7l2 2h5a2 2 0 012 2v10a2 2 0 01-2 2z"/></svg>`,
  chat: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>`,
  mail: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
  bell: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>`,
  card: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/></svg>`,
  menu: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>`,
  cog: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
}

const navGroups = [
  { key: 'operations', label: 'Vận hành', links: [
    { to: '/admin', label: 'Bảng điều khiển', icon: icons.dashboard },
    { to: '/admin/rooms', label: 'Phòng', icon: icons.rooms },
    { to: '/admin/bookings', label: 'Đặt phòng', icon: icons.calendar },
    { to: '/admin/orders', label: 'Đơn hàng', icon: icons.clipboard },
    { to: '/admin/payment-transactions', label: 'Thanh toán', icon: icons.card },
  ] },
  { key: 'content', label: 'Nội dung', links: [
    { to: '/admin/posts', label: 'Bài viết Blog', icon: icons.document },
    { to: '/admin/products', label: 'Sản phẩm', icon: icons.bag },
    { to: '/admin/activities', label: 'Hoạt động', icon: icons.bolt },
    { to: '/admin/sliders', label: 'Slide & Banner', icon: icons.image },
    { to: '/admin/about-page', label: 'Trang About', icon: icons.folder },
  ] },
  { key: 'customers', label: 'Khách hàng', links: [
    { to: '/admin/comments', label: 'Bình luận', icon: icons.chat },
    { to: '/admin/messages', label: 'Tin nhắn', icon: icons.mail },
    { to: '/admin/subscribers', label: 'Đăng ký tin', icon: icons.bell },
  ] },
  { key: 'system', label: 'Hệ thống', links: [
    { to: '/admin/menu', label: 'Điều hướng', icon: icons.menu },
    { to: '/admin/settings', label: 'Cài đặt', icon: icons.cog },
  ] },
]

watch(collapsed, value => localStorage.setItem('admin_sidebar_collapsed', value ? '1' : '0'))

function toggleSidebar() {
  collapsed.value = !collapsed.value
}

function toggleGroup(key) {
  openGroups[key] = !openGroups[key]
  localStorage.setItem('admin_nav_groups', JSON.stringify(openGroups))
}

async function handleLogout() {
  await auth.logout()
  router.push('/admin/login')
}
</script>
