import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            // PWA Configuration
            registerType: 'autoUpdate', // Auto-update service worker
            devOptions: {
                enabled: true // Enable PWA in development for testing
            },
            includeAssets: ['favicon.ico', 'apple-touch-icon.png'],
            manifest: {
                name: 'SpendTracker - Personal Expense Manager',
                short_name: 'SpendTracker',
                description: 'Track your expenses with offline support and beautiful UI',
                theme_color: '#0f172a', // matches your dark theme
                background_color: '#ffffff',
                display: 'standalone', // Makes it feel like a native app
                orientation: 'portrait',
                scope: '/',
                start_url: '/',
                icons: [
                    {
                        src: 'favicon.ico',
                        sizes: '64x64 32x32 24x24 16x16',
                        type: 'image/x-icon'
                    },
                    {
                        src: 'apple-touch-icon.png',
                        sizes: '180x180',
                        type: 'image/png'
                    }
                ]
            },
            workbox: {
                // Cache all static assets
                globPatterns: ['**/*.{js,css,html,ico,png,svg}'],
                // Cache API routes for offline access
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/localhost:8092\/api\/.*/i, // Your API routes
                        handler: 'NetworkFirst', // Try network first, fall back to cache
                        options: {
                            cacheName: 'api-cache',
                            cacheableResponse: {
                                statuses: [0, 200]
                            }
                        }
                    }
                ]
            }
        }),
    ],
});
