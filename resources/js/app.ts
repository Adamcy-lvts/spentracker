import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { registerSW } from 'virtual:pwa-register';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Vue Learning Point #46: Initialize Global Composables
// This will set light / dark mode on page load...
initializeTheme();


// Register service worker for PWA functionality
const updateSW = registerSW({
    onNeedRefresh() {
        // Show a prompt to user for updating the app
        if (confirm('New version available! Click OK to update.')) {
            updateSW(true)
        }
    },
    onOfflineReady() {
        console.log('🎯 App is ready for offline use!')
        // You could show a toast notification here
    },
    onRegistered() {
        console.log('✅ Service Worker registered successfully!')
        console.log('📱 PWA is ready for installation!')
    },
    onRegisterError(error: any) {
        console.error('❌ Service Worker registration failed:', error)
    }
});
