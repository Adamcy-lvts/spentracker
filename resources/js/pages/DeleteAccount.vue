<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>

    <Head title="Delete Account - SpentTracker">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900" rel="stylesheet" />
    </Head>

    <div class="delete-account-page min-h-screen bg-[#030712] text-[#94a3b8]">
        <!-- Compact Navbar -->
        <header class="fixed top-0 z-50 w-full border-b border-white/5 bg-[#030712]/90 backdrop-blur-md py-3 sm:py-4">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 sm:px-6">
                <Link :href="route('home')" class="group flex items-center gap-2">
                    <div
                        class="flex size-7 items-center justify-center rounded-lg bg-white/5 border border-white/10 group-hover:rotate-6 transition-transform">
                        <AppLogoIcon class="size-4 fill-current text-teal-400" />
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-white">SpenTracker</span>
                </Link>

                <div class="flex items-center gap-4">
                    <Link :href="route('register')"
                        class="rounded-full bg-white px-4 py-1.5 text-[9px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400">
                        Join
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-4 pt-24 pb-20 sm:pt-32 sm:px-6">
            <!-- Header -->
            <div class="mb-16">
                <p class="mb-2 text-[9px] font-black uppercase tracking-[0.3em] text-white/20">Account Management</p>
                <h1 class="text-3xl font-black tracking-tighter text-white sm:text-5xl">Delete Your SpentTracker Account
                    and Data</h1>
                <p class="mt-4 text-sm font-medium leading-relaxed max-w-2xl">
                    Users of SpentTracker have the right to request deletion of their personal data.
                </p>
            </div>

            <!-- Content -->
            <div class="space-y-12 text-xs sm:text-[13px] leading-relaxed">

                <!-- How to Request Section -->
                <section class="space-y-4">
                    <h2 class="text-lg font-black text-white uppercase tracking-wider flex items-center gap-3">
                        <span
                            class="flex size-8 items-center justify-center rounded-lg bg-teal-500/10 border border-teal-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-teal-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        How to Request Data Deletion
                    </h2>

                    <div class="ml-11">
                        <p class="mb-4">You may request deletion of your SpentTracker account and associated data by
                            sending an email to:</p>

                        <a href="mailto:support@spenttracker.live"
                            class="inline-flex items-center gap-3 rounded-2xl bg-gradient-to-r from-teal-500/10 to-cyan-500/10 border border-teal-500/20 px-6 py-4 transition-all hover:border-teal-500/40 hover:from-teal-500/20 hover:to-cyan-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-teal-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-bold text-white">support@spenttracker.live</span>
                        </a>

                        <p class="mt-4 text-white/60">
                            <span class="text-teal-400 font-semibold">Tip:</span> Please include the email address
                            associated with your SpentTracker account in your deletion request.
                        </p>
                    </div>
                </section>

                <!-- What Data Will Be Deleted -->
                <section class="space-y-4">
                    <h2 class="text-lg font-black text-white uppercase tracking-wider flex items-center gap-3">
                        <span
                            class="flex size-8 items-center justify-center rounded-lg bg-rose-500/10 border border-rose-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-rose-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </span>
                        What Data Will Be Deleted
                    </h2>

                    <div class="ml-11">
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Account Information -->
                            <div
                                class="rounded-2xl bg-white/[0.02] border border-white/5 p-5 transition-all hover:border-white/10 hover:bg-white/[0.04]">
                                <div
                                    class="mb-3 flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500/20 to-purple-500/20 border border-violet-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-violet-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-white mb-1">Account Information</h3>
                                <p class="text-[11px] opacity-70">Name, email, phone number</p>
                            </div>

                            <!-- Financial Records -->
                            <div
                                class="rounded-2xl bg-white/[0.02] border border-white/5 p-5 transition-all hover:border-white/10 hover:bg-white/[0.04]">
                                <div
                                    class="mb-3 flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500/20 to-green-500/20 border border-emerald-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-emerald-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-white mb-1">Financial Records</h3>
                                <p class="text-[11px] opacity-70">Expense and financial records</p>
                            </div>

                            <!-- Location Data -->
                            <div
                                class="rounded-2xl bg-white/[0.02] border border-white/5 p-5 transition-all hover:border-white/10 hover:bg-white/[0.04]">
                                <div
                                    class="mb-3 flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 border border-amber-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-amber-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-white mb-1">Location Data</h3>
                                <p class="text-[11px] opacity-70">Location data (if any)</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Retention Period -->
                <section class="space-y-4">
                    <h2 class="text-lg font-black text-white uppercase tracking-wider flex items-center gap-3">
                        <span
                            class="flex size-8 items-center justify-center rounded-lg bg-blue-500/10 border border-blue-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        Retention Period
                    </h2>

                    <div class="ml-11">
                        <div
                            class="rounded-2xl bg-gradient-to-br from-blue-500/5 to-indigo-500/5 border border-blue-500/10 p-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-blue-500/10 border border-blue-500/20">
                                    <span class="text-xl font-black text-blue-400">30</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-white mb-1">Days to Permanent Deletion</p>
                                    <p class="opacity-70">All requested data will be permanently deleted from our
                                        servers within 30 days, unless a longer retention period is required by law.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-4 flex items-center gap-3 rounded-xl bg-rose-500/5 border border-rose-500/10 px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-rose-400 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm">
                                <span class="font-bold text-rose-400">Important:</span>
                                <span class="text-white/70">After deletion, the data cannot be recovered.</span>
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Support Contact Card -->
                <section class="pt-8 border-t border-white/5">
                    <div class="rounded-3xl bg-white p-8 text-black sm:p-12">
                        <h2 class="text-2xl font-black tracking-tighter mb-2">Need Help?</h2>
                        <p class="text-sm font-medium opacity-60 mb-6">Questions about data deletion? We're here to
                            help.</p>
                        <div class="flex flex-wrap gap-4">
                            <a href="mailto:support@spenttracker.live"
                                class="bg-black text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition hover:bg-teal-600">support@spenttracker.live</a>
                            <a href="https://spentracker.live" target="_blank"
                                class="border border-black/10 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest text-black/40 transition hover:border-black/20 hover:text-black/60">spentracker.live</a>
                        </div>
                    </div>
                </section>

                <!-- Additional Links -->
                <section class="flex flex-wrap gap-4">
                    <Link :href="route('privacy-policy')"
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-white/60 transition hover:border-white/20 hover:text-white/80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Privacy Policy
                    </Link>
                    <Link :href="route('home')"
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-white/60 transition hover:border-white/20 hover:text-white/80">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Back to Home
                    </Link>
                </section>
            </div>
        </main>

        <footer class="border-t border-white/5 py-12 text-center">
            <p class="text-[9px] font-bold uppercase tracking-[0.4em] text-white/10">© 2025 DEV CENTRIC STUDIOS.</p>
        </footer>
    </div>
</template>

<style scoped>
.delete-account-page {
    font-family: 'Outfit', sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 3px;
}

::-webkit-scrollbar-track {
    background: #030712;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
}

::selection {
    background: #2dd4bf;
    color: #000;
}
</style>
