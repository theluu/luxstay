import { createI18n } from 'vue-i18n'
import vi from './locales/vi.json'
import en from './locales/en.json'
import zh from './locales/zh.json'

const savedLocale = localStorage.getItem('admin_locale') || 'vi'

export const i18n = createI18n({
    legacy: false,
    locale: savedLocale,
    fallbackLocale: 'vi',
    messages: { vi, en, zh },
})
