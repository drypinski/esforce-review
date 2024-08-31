const RECAPTCHA_SITE_KEY = process.env.RECAPTCHA_SITE_KEY
const API_BASE_URL = process.env.API_BASE_URL

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2024-04-03',
    devtools: { enabled: false },
    telemetry: false,
    ssr: false,
    runtimeConfig: {
        public: {
            RECAPTCHA_SITE_KEY,
            API_BASE_URL,
        },
    },
    css: [
        '~/assets/scss/app.scss'
    ],
    plugins: [
        '@/plugins/recaptcha.client',
    ],
    routeRules: {
        '/posts': { redirect: '/' },
        '/authors': { cache: { maxAge: 300, swr: true } },
    },
})
