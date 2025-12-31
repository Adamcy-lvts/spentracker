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

    <div class="sleek-dark-base min-h-screen bg-[#030712] text-white">
        <!-- Minimal Bordered Navbar -->
        <header class="fixed top-0 z-50 w-full transition-all duration-300 border-b"
            :class="isScrolled ? 'bg-[#030712]/90 backdrop-blur-md py-4 border-white/5 shadow-xl' : 'bg-transparent py-7 border-transparent'">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6">
                <Link :href="route('home')" class="group flex items-center gap-2.5">
                    <div
                        class="flex aspect-square size-8 items-center justify-center rounded-xl bg-white/5 border border-white/10 group-hover:bg-white/10 transition-transform group-hover:rotate-6">
                        <AppLogoIcon class="size-5 fill-current text-teal-400" />
                    </div>
                    <span class="text-sm font-black uppercase tracking-[0.25em] text-white">SpenTracker</span>
                </Link>

                <nav class="hidden items-center gap-10 md:flex">
                    <a href="#features"
                        class="text-[11px] font-bold uppercase tracking-widest text-white/40 hover:text-white transition">Features</a>
                    <a href="#mobile"
                        class="text-[11px] font-bold uppercase tracking-widest text-white/40 hover:text-white transition">Mobile</a>
                </nav>

                <div class="flex items-center gap-3 sm:gap-6">
                    <template v-if="!$page.props.auth.user">
                        <Link :href="route('login')"
                            class="text-[11px] font-bold uppercase tracking-widest text-white/40 hover:text-white transition">
                            Log in</Link>
                        <Link :href="route('register')"
                            class="rounded-full bg-white px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400 hover:scale-[1.02] active:scale-95">
                            Join Free
                        </Link>
                    </template>
                    <Link v-else :href="route('dashboard')"
                        class="text-[11px] font-bold uppercase tracking-widest text-teal-400 hover:text-white">Dashboard
                    </Link>
                </div>
            </div>
        </header>

        <main>
            <!-- Sleek Hero -->
            <section class="relative pt-44 pb-32 px-6 overflow-hidden">
                <!-- Background Glows -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none">
                    <div
                        class="absolute -top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[600px] bg-teal-500/10 blur-[150px] rounded-full">
                    </div>
                </div>

                <div class="mx-auto max-w-4xl text-center relative z-10">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-[10px] font-black uppercase tracking-[0.2em] text-white/40 mb-10">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                        Now live at Spentracker.live
                    </div>

                    <h1 class="text-5xl font-black tracking-tighter text-white sm:text-8xl leading-[0.95] mb-8">
                        Master your money <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-indigo-400">with
                            precision.</span>
                    </h1>

                    <p class="mx-auto mt-10 max-w-2xl text-lg leading-relaxed text-white/40 sm:text-xl font-medium">
                        A polished, privacy-first expense tracker for modern lifestyles. Simple enough for everyone,
                        powerful
                        enough for experts.
                    </p>

                    <div class="mt-12 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Link :href="route('register')"
                            class="rounded-full bg-white px-10 py-4 text-xs font-black uppercase tracking-widest text-black transition hover:bg-teal-400 hover:scale-[1.03] active:scale-95">
                            Start Tracking Free
                        </Link>
                        <a href="#mobile"
                            class="rounded-full border border-white/10 bg-white/5 px-10 py-4 text-xs font-black uppercase tracking-widest text-white transition hover:bg-white/10">
                            Android App Teaser
                        </a>
                    </div>
                </div>

                <!-- Sleek Mockup -->
                <div
                    class="mt-24 mx-auto max-w-5xl rounded-[2.5rem] border border-white/5 bg-white/5 p-3 shadow-2xl relative group">
                    <div
                        class="absolute inset-0 rounded-[2.5rem] bg-gradient-to-b from-teal-400/10 to-transparent opacity-30 group-hover:opacity-50 transition-opacity">
                    </div>
                    <img src="/images/hero-mockup.png" alt="Spent Tracker App"
                        class="relative w-full rounded-[2rem] shadow-2xl transition duration-1000 group-hover:scale-[1.01]" />
                </div>
            </section>

            <!-- Refined Features -->
            <section id="features" class="py-32 px-6 max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        class="group p-10 rounded-[2rem] border border-white/5 bg-white/[0.02] transition-colors hover:bg-white/[0.04] hover:border-white/10">
                        <div
                            class="h-10 w-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-teal-400 mb-8 border border-teal-500/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Total Privacy</h3>
                        <p class="text-white/40 text-sm leading-relaxed">No data collection. No selling. Your financial
                            records
                            are encrypted and remain strictly yours.</p>
                    </div>
                    <div
                        class="group p-10 rounded-[2rem] border border-white/5 bg-white/[0.02] transition-colors hover:bg-white/[0.04] hover:border-white/10">
                        <div
                            class="h-10 w-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Live Insights</h3>
                        <p class="text-white/40 text-sm leading-relaxed">Visualize your spending patterns instantly with
                            elegant, geometric charts and real-time category updates.</p>
                    </div>
                    <div
                        class="group p-10 rounded-[2rem] border border-white/5 bg-white/[0.02] transition-colors hover:bg-white/[0.04] hover:border-white/10">
                        <div
                            class="h-10 w-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-400 mb-8 border border-orange-500/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Native Android App</h3>
                        <p class="text-white/40 text-sm leading-relaxed">Seamless synchronization across web and mobile.
                            Build
                            your habits on the go with zero friction.</p>
                    </div>
                </div>
            </section>

            <!-- Mobile Section (Bento Style) -->
            <section id="mobile" class="py-24 px-6">
                <div
                    class="mx-auto max-w-6xl rounded-[3rem] bg-white p-12 sm:p-24 text-black relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                        <div class="flex-1 text-center lg:text-left">
                            <p class="text-xs font-black uppercase tracking-[0.3em] text-black/20 mb-10">Mobile
                                Advantage</p>
                            <h2 class="text-5xl sm:text-7xl font-black tracking-tighter leading-[1] mb-8">Wallet in
                                <br />
                                pockets.</h2>
                            <p class="text-black/50 text-lg leading-relaxed max-w-md mx-auto lg:mx-0 font-medium">
                                The native Android app is almost here. Fast, secure, and built specifically for the
                                minimalist
                                tracking experience.
                            </p>
                            <div class="mt-12 flex items-center gap-4 justify-center lg:justify-start">
                                <span
                                    class="px-5 py-2 rounded-full bg-black/5 border border-black/10 text-xs font-bold">Play
                                    Store Launch Q1 2025</span>
                            </div>
                        </div>
                        <div class="flex-1 relative">
                            <img src="/images/hero-mockup.png" alt="Mobile App"
                                class="w-full max-w-xs mx-auto drop-shadow-2xl transition duration-700 group-hover:rotate-3 group-hover:scale-110" />
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/5 py-24 bg-black">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex flex-col md:flex-row justify-between items-start gap-16">
                    <div class="space-y-6 text-center md:text-left">
                        <p class="text-xl font-black uppercase tracking-widest text-white">SpenTracker</p>
                        <p class="text-white/30 max-w-xs text-xs font-bold tracking-widest leading-loose">Built with
                            cinematic
                            precision by <br /> Dev Centric Studios.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-20">
                        <div class="space-y-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-teal-400">Legal</p>
                            <div class="flex flex-col gap-4">
                                <Link :href="route('privacy-policy')"
                                    class="text-xs font-bold text-white/40 hover:text-white transition">Privacy Policy
                                </Link>
                                <a href="https://spentracker.live" target="_blank"
                                    class="text-xs font-bold text-white/40 hover:text-white transition">Platform</a>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-teal-400">Support</p>
                            <div class="flex flex-col gap-4">
                                <a href="mailto:info@devcentricstudios.com"
                                    class="text-xs font-bold text-white/40 hover:text-white transition">Contact Us</a>
                                <a href="mailto:info@devcentricstudios.com"
                                    class="text-xs font-bold text-white/40 hover:text-white transition">Help Center</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-24 pt-10 border-t border-white/5 text-center sm:text-left">
                    <p class="text-[9px] font-bold uppercase tracking-[0.4em] text-white/20">© 2025 Dev Centric Studios.
                        All
                        rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.sleek-dark-base {
    font-family: 'Outfit', sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
    letter-spacing: -0.01em;
}

::selection {
    background: #2dd4bf;
    color: #030712;
}

@keyframes reveal {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

main {
    animation: reveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 5px;
}

::-webkit-scrollbar-track {
    background: #030712;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2dd4bf;
}
</style>
