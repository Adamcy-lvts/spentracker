import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        watch: {
            usePolling: true,
        },
        hmr: {
            port: 5173,
        },
    },
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
            filename: 'sw.js',
            manifestFilename: 'manifest.webmanifest',
            devOptions: {
                enabled: true, // Enable for testing
                type: 'module'
            },
            includeAssets: ['favicon.ico', 'apple-touch-icon-180x180.png', 'pwa-192x192.png', 'pwa-512x512.png', 'maskable-icon-512x512.png'],
            manifest: {
                name: 'SpendTracker - Personal Expense Manager',
                short_name: 'SpendTracker',
                description: 'Track your expenses with offline support and beautiful UI',
                theme_color: '#0f172a',
                background_color: '#0f172a',
                display: 'standalone',
                orientation: 'portrait',
                scope: '/',
                start_url: '/',
                id: '/',
                icons: [
                    {
                        src: 'favicon.ico?v=2',
                        sizes: '16x16 32x32 48x48 64x64',
                        type: 'image/x-icon'
                    },
                    {
                        src: 'pwa-192x192.png?v=2',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any'
                    },
                    {
                        src: 'pwa-512x512.png?v=2',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any'
                    },
                    {
                        src: 'maskable-icon-512x512.png?v=2',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable'
                    }
                ]
            },
            workbox: {
                // Vue Learning Point #56: Enhanced Asset Caching
                // Cache all static assets including Vue components and pages
                globPatterns: ['**/*.{js,css,html,ico,png,svg,woff,woff2}'],
                
                // Vue Learning Point #57: Offline Navigation Setup
                // This makes ALL navigation requests fall back to your main app
                navigateFallback: '/',
                navigateFallbackAllowlist: [/^(?!\/__).*/], // Allow all except dev routes
                
                // Vue Learning Point #58: Advanced Runtime Caching
                runtimeCaching: [
                    {
                        // Cache ALL page navigation requests
                        urlPattern: ({ request }) => request.mode === 'navigate',
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'navigation-cache',
                            networkTimeoutSeconds: 3,
                            cacheableResponse: {
                                statuses: [200]
                            },
                            broadcastUpdate: {
                                channelName: 'sw-messages',
                                options: {
                                    headersToCheck: ['content-type', 'etag', 'last-modified']
                                }
                            },
                        }
                    },
                    {
                        // Cache specific Laravel routes
                        urlPattern: /^https?:\/\/localhost:8092\/(expense|dashboard|profile).*$/,
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'pages-cache',
                            cacheableResponse: {
                                statuses: [200]
                            }
                        }
                    },
                    {
                        // Cache API endpoints for data
                        urlPattern: /^https?:\/\/localhost:8092\/api\/.*/,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-cache',
                            networkTimeoutSeconds: 5,
                            cacheableResponse: {
                                statuses: [0, 200]
                            }
                        }
                    },
                    {
                        // Cache Laravel CSRF endpoints
                        urlPattern: /^https?:\/\/localhost:8092\/sanctum\/csrf-cookie$/,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'auth-cache',
                            cacheableResponse: {
                                statuses: [200, 204]
                            }
                        }
                    },
                    {
                        // Cache images and assets
                        urlPattern: /\.(?:png|jpg|jpeg|svg|gif|webp)$/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'images-cache',
                            expiration: {
                                maxEntries: 100,
                                maxAgeSeconds: 60 * 60 * 24 * 30, // 30 days
                            },
                        }
                    }
                ]
            }
        }),
    ],
});
