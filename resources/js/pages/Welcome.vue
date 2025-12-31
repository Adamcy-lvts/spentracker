<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

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
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />
    </Head>

    <div class="minimal-base min-h-screen bg-white text-[#1a1a1a]">
        <!-- Polished Navigation -->
        <header class="fixed top-0 z-50 w-full transition-all duration-300 border-b"
            :class="isScrolled ? 'bg-white/90 backdrop-blur-md py-4 border-slate-100 shadow-sm' : 'bg-transparent py-7 border-transparent'">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6">
                <Link :href="route('home')"
                    class="flex items-center gap-2.5 text-xl font-extrabold tracking-tight text-slate-900 group">
                    <div
                        class="h-8 w-8 rounded-xl bg-slate-900 flex items-center justify-center transition group-hover:rotate-12">
                        <div class="h-2 w-2 rounded-full bg-white"></div>
                    </div>
                    Spent Tracker
                </Link>

                <nav class="hidden items-center gap-10 md:flex">
                    <a href="#features"
                        class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Features</a>
                    <a href="#mobile" class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Mobile
                        App</a>
                </nav>

                <div class="flex items-center gap-6">
                    <template v-if="!$page.props.auth.user">
                        <Link :href="route('login')"
                            class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Log in</Link>
                        <Link :href="route('register')"
                            class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 hover:scale-[1.02] active:scale-95">
                            Get Started
                        </Link>
                    </template>
                    <Link v-else :href="route('dashboard')" class="text-sm font-bold text-slate-900">Go to App</Link>
                </div>
            </div>
        </header>

        <main>
            <!-- Sleek Hero -->
            <section class="relative pt-44 pb-32 px-6 overflow-hidden">
                <div class="mx-auto max-w-4xl text-center">
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-1.5 text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-10">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-teal-500"></span>
                        Now live at SpentTracker.live
                    </div>

                    <h1 class="text-6xl font-[800] tracking-tight text-slate-900 sm:text-8xl leading-[1.05]">
                        Take back control <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">of your
                            money.</span>
                    </h1>

                    <p class="mx-auto mt-10 max-w-2xl text-xl leading-relaxed text-slate-500">
                        A polished, privacy-first expense tracker for modern lives. Beautifully simple, completely free,
                        and
                        always in sync.
                    </p>

                    <div class="mt-12 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Link :href="route('register')"
                            class="rounded-full bg-slate-900 px-10 py-4 font-bold text-white transition hover:bg-slate-800 hover:shadow-xl hover:scale-[1.03] active:scale-95">
                            Start Tracking Free
                        </Link>
                        <a href="#mobile"
                            class="rounded-full border border-slate-200 bg-white px-10 py-4 font-bold text-slate-600 transition hover:border-slate-400">
                            Android App Teaser
                        </a>
                    </div>
                </div>

                <!-- Polished Mockup Reveal -->
                <div
                    class="mt-24 mx-auto max-w-5xl rounded-[3rem] border border-slate-100 bg-white p-4 shadow-2xl relative group">
                    <div
                        class="absolute -inset-1 rounded-[3.2rem] bg-gradient-to-b from-slate-100 to-transparent opacity-50">
                    </div>
                    <img src="/images/hero-mockup.png" alt="Spent Tracker App"
                        class="relative w-full rounded-[2.5rem] grayscale-[0.2] transition duration-700 group-hover:grayscale-0" />
                </div>
            </section>

            <!-- Refined Features Grid -->
            <section id="features" class="py-32 px-6 max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="space-y-6">
                        <div
                            class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Privacy Forever</h3>
                        <p class="text-slate-500 leading-relaxed">Your data belongs to you. We use end-to-end encryption
                            and
                            will never sell your information to third parties.</p>
                    </div>
                    <div class="space-y-6">
                        <div
                            class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Real-time Insights</h3>
                        <p class="text-slate-500 leading-relaxed">Instant visualization of your spending habits with
                            clean,
                            minimalist charts that tell the story of your wealth.</p>
                    </div>
                    <div class="space-y-6">
                        <div
                            class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Multi-device Sync</h3>
                        <p class="text-slate-500 leading-relaxed">Seamlessly transition between our web platform and the
                            upcoming Android app. Your wallet, synced in real-time.</p>
                    </div>
                </div>
            </section>

            <!-- Mobile App Section -->
            <section id="mobile" class="py-32 px-6">
                <div
                    class="mx-auto max-w-6xl rounded-[3rem] bg-slate-900 p-12 sm:p-24 text-white relative overflow-hidden">
                    <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                        <div class="flex-1 text-center lg:text-left">
                            <div
                                class="inline-flex items-center rounded-full bg-white/10 border border-white/20 px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest text-white mb-10">
                                Native Android App
                            </div>
                            <h2 class="text-5xl sm:text-7xl font-extrabold tracking-tight leading-[1.1]">Track on <br />
                                the go.
                            </h2>
                            <p class="mt-8 text-white/60 text-lg leading-relaxed max-w-md mx-auto lg:mx-0">
                                The Spent Tracker Android experience is almost here. Built for speed, precision, and
                                complete
                                financial clarity wherever you are.
                            </p>
                            <div class="mt-12">
                                <span class="text-xs font-bold uppercase tracking-[0.2em] text-teal-400">Play Store
                                    Launch Q1
                                    2025</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <img src="/images/hero-mockup.png" alt="Mobile App"
                                class="w-full max-w-sm mx-auto transform rotate-6 scale-110 drop-shadow-2xl" />
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-100 py-24 bg-white">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex flex-col md:flex-row justify-between items-start gap-16">
                    <div class="space-y-6">
                        <p class="text-xl font-black text-slate-900 tracking-tight">Spent Tracker</p>
                        <p class="text-slate-400 max-w-xs text-sm leading-relaxed">The beautifully simple way to master
                            your
                            finances. Built with passion by Dev Centric Studios.</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-16">
                        <div class="space-y-6">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-900">Legal</p>
                            <div class="flex flex-col gap-4">
                                <Link :href="route('privacy-policy')"
                                    class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Privacy
                                    Policy
                                </Link>
                                <a href="https://spentracker.live" target="_blank"
                                    class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Terms</a>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-900">Support</p>
                            <div class="flex flex-col gap-4">
                                <a href="mailto:info@devcentricstudios.com"
                                    class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Contact
                                    Us</a>
                                <a href="mailto:info@devcentricstudios.com"
                                    class="text-sm font-bold text-slate-400 hover:text-slate-900 transition">Help
                                    Center</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-20 pt-8 border-t border-slate-50 text-center sm:text-left">
                    <p class="text-xs font-bold text-slate-400">© 2025 Dev Centric Studios. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.minimal-base {
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
}

::selection {
    background: #1a1a1a;
    color: white;
}

@media (max-width: 640px) {
    h1 {
        line-height: 1.1;
    }
}
</style>
