import { defineStore } from 'pinia'
import api from './api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user:  JSON.parse(localStorage.getItem('admin_user') || 'null'),
        token: localStorage.getItem('admin_token') || null,
    }),

    getters: {
        isAuthenticated: state => !!state.token,
    },

    actions: {
        async login(email, password) {
            const { data } = await api.post('/auth/login', { email, password })
            this.token = data.token
            this.user  = data.user
            localStorage.setItem('admin_token', data.token)
            localStorage.setItem('admin_user', JSON.stringify(data.user))
        },

        async logout() {
            try { await api.post('/auth/logout') } catch {}
            this.token = null
            this.user  = null
            localStorage.removeItem('admin_token')
            localStorage.removeItem('admin_user')
        },
    },
})
