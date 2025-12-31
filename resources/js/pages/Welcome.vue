<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});
</script>

<template>

    <Head title="Spent Tracker - Smart Financial Mastery">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900" rel="stylesheet" />
    </Head>

    <div class="compact-landing min-h-screen bg-[#030712] text-white">
        <!-- Compact Navbar -->
        <header class="fixed top-0 z-50 w-full transition-all duration-300 border-b"
            :class="isScrolled ? 'bg-[#030712]/95 backdrop-blur-md py-3 border-white/5' : 'bg-transparent py-6 border-transparent'">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-5">
                <Link :href="route('home')" class="group flex items-center gap-2">
                    <div
                        class="flex size-7 items-center justify-center rounded-lg bg-white/5 border border-white/10 group-hover:rotate-6 transition-transform">
                        <AppLogoIcon class="size-4 fill-current text-teal-400" />
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.25em] text-white">SpenTracker</span>
                </Link>

                <nav class="hidden items-center gap-8 md:flex">
                    <a href="#features"
                        class="text-[10px] font-bold uppercase tracking-widest text-white/30 hover:text-white transition">Features</a>
                    <a href="#mobile"
                        class="text-[10px] font-bold uppercase tracking-widest text-white/30 hover:text-white transition">Mobile</a>
                </nav>

                <div class="flex items-center gap-4 sm:gap-6">
                    <template v-if="!$page.props.auth.user">
                        <Link :href="route('login')"
                            class="text-[10px] font-black uppercase tracking-widest text-white/30 hover:text-white transition">
                            Log in</Link>
                        <Link :href="route('register')"
                            class="rounded-full bg-white px-5 py-2 text-[9px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400">
                            Join
                        </Link>
                    </template>
                    <Link v-else :href="route('dashboard')"
                        class="text-[10px] font-black uppercase tracking-widest text-teal-400 hover:text-white">
                        Dashboard</Link>
                </div>
            </div>
        </header>

        <main>
            <!-- Sleek Compact Hero -->
            <section class="relative pt-40 pb-20 px-5 overflow-hidden">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[300px] pointer-events-none opacity-20">
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-teal-500/20 blur-[100px] rounded-full">
                    </div>
                </div>

                <div class="mx-auto max-w-4xl text-center relative z-10 transition-all">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/5 bg-white/[0.03] px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.2em] text-white/30 mb-8 sm:mb-10">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                        Spentracker.live
                    </div>

                    <h1
                        class="text-4xl font-black tracking-tighter text-white sm:text-7xl leading-[0.95] mb-8 lg:text-8xl">
                        Master your <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-indigo-400">financial
                            world.</span>
                    </h1>

                    <p
                        class="mx-auto mt-8 max-w-xl text-sm leading-relaxed text-white/30 sm:text-base font-medium px-4">
                        A polished, privacy-first expense tracker. Beautifully simple, completely free, and always in
                        sync
                        across Android and web.
                    </p>

                    <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <Link :href="route('register')"
                            class="w-full sm:w-auto rounded-full bg-white px-8 py-3.5 text-[10px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400 hover:scale-[1.02]">
                            Start Tracking Free
                        </Link>
                        <a href="#mobile"
                            class="w-full sm:w-auto rounded-full border border-white/10 bg-white/5 px-8 py-3.5 text-[10px] font-black uppercase tracking-widest text-white transition hover:bg-white/10">
                            Mobile App Teaser
                        </a>
                    </div>
                </div>
            </section>

            <!-- Compact Features -->
            <section id="features" class="py-20 px-5 max-w-5xl mx-auto border-t border-white/5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="p-8 rounded-[1.5rem] border border-white/5 bg-white/[0.01] transition-colors hover:bg-white/[0.02]">
                        <div
                            class="h-8 w-8 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-400 mb-6 border border-teal-500/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-white mb-3">Total Privacy</h3>
                        <p class="text-white/30 text-[11px] font-bold leading-relaxed tracking-wide">Encrypted financial
                            records. We never collect or sell bank data.</p>
                    </div>
                    <div
                        class="p-8 rounded-[1.5rem] border border-white/5 bg-white/[0.01] transition-colors hover:bg-white/[0.02]">
                        <div
                            class="h-8 w-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-6 border border-indigo-500/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-white mb-3">Live Insights</h3>
                        <p class="text-white/30 text-[11px] font-bold leading-relaxed tracking-wide">Visualize patterns
                            instantly with geometric charts and real-time category updates.</p>
                    </div>
                    <div
                        class="p-8 rounded-[1.5rem] border border-white/5 bg-white/[0.01] transition-colors hover:bg-white/[0.02]">
                        <div
                            class="h-8 w-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-400 mb-6 border border-orange-500/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-white mb-3">Mobile Sync</h3>
                        <p class="text-white/30 text-[11px] font-bold leading-relaxed tracking-wide">Transition between
                            web and
                            Android seamlessly. Zero friction tracking.</p>
                    </div>
                </div>
            </section>

            <!-- Compact Mobile Section (No Images) -->
            <section id="mobile" class="py-20 px-5">
                <div
                    class="mx-auto max-w-5xl rounded-[2rem] bg-white p-10 sm:p-20 text-black text-center relative overflow-hidden group">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-black/20 mb-6">Mobile Status</p>
                    <h2 class="text-4xl sm:text-6xl font-black tracking-tighter leading-[1] mb-6">Wallet in pockets.
                    </h2>
                    <p
                        class="text-black/50 text-xs font-bold uppercase tracking-widest leading-relaxed max-w-md mx-auto mb-10">
                        The native Android app is almost here. Fast, secure, and built specifically for the minimalist
                        tracking
                        experience.
                    </p>
                    <div class="flex items-center gap-4 justify-center">
                        <span
                            class="px-5 py-2 rounded-full bg-black/5 border border-black/10 text-[9px] font-black uppercase tracking-widest">Store
                            Launch Q1 2025</span>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/5 py-16 bg-black">
            <div class="mx-auto max-w-5xl px-5">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-10">
                    <div class="text-center sm:text-left">
                        <p class="text-[11px] font-black uppercase tracking-[0.4em] text-white">SpenTracker</p>
                        <p class="mt-2 text-[9px] text-white/20 uppercase font-black tracking-widest">Built by Dev
                            Centric
                            Studios.</p>
                    </div>

                    <div class="flex gap-10">
                        <Link :href="route('privacy-policy')"
                            class="text-[9px] font-black uppercase tracking-widest text-white/30 hover:text-white transition">
                            Privacy</Link>
                        <a href="mailto:info@devcentricstudios.com"
                            class="text-[9px] font-black uppercase tracking-widest text-white/30 hover:text-white transition">Support</a>
                    </div>
                </div>
                <div class="mt-12 pt-6 border-t border-white/5 text-center">
                    <p class="text-[8px] font-bold uppercase tracking-[0.5em] text-white/10">© 2025 ALL RIGHTS RESERVED.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.compact-landing {
    font-family: 'Outfit', sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
    letter-spacing: -0.01em;
}

::selection {
    background: #2dd4bf;
    color: #030712;
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 3px;
}

::-webkit-scrollbar-track {
    background: #030712;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
}
</style>
