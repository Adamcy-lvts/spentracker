<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import PwaInstallPrompt from '@/components/PwaInstallPrompt.vue';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});
</script>

<template>

    <Head title="Spent Tracker — Smart Financial Clarity">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800|inter:300,400,500,600,700"
            rel="stylesheet" />
    </Head>

    <div class="landing-page min-h-screen bg-[#000000] text-slate-300 overflow-x-hidden">
        <!-- Ambient Background Effects -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-teal-500/5 blur-[120px] rounded-full"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] bg-indigo-500/5 blur-[100px] rounded-full"></div>
        </div>

        <!-- Progress Stripe -->
        <div
            class="fixed top-0 left-0 h-[2px] bg-gradient-to-r from-teal-500 via-indigo-500 to-teal-500 w-full z-[60] opacity-50">
        </div>

        <!-- Premium Navigation -->
        <header class="fixed top-0 z-50 w-full transition-all duration-500"
            :class="isScrolled ? 'bg-black/80 backdrop-blur-xl py-4 border-b border-white/5' : 'bg-transparent py-8 border-transparent'">
            <div class="container mx-auto max-w-7xl flex items-center justify-between px-6 lg:px-12">
                <Link :href="route('home')" class="group flex items-center gap-3">
                    <div class="relative">
                        <div
                            class="relative flex size-9 items-center justify-center rounded-xl bg-zinc-900 border border-white/10 group-hover:border-teal-500/50 transition-colors duration-500">
                            <AppLogoIcon
                                class="size-5 fill-current text-teal-400 group-hover:text-teal-300 transition-colors" />
                        </div>
                    </div>
                    <span
                        class="text-xs font-bold uppercase tracking-widest text-white hidden sm:block">SpenTracker</span>
                </Link>

                <nav class="hidden items-center gap-10 md:flex">
                    <a href="#features"
                        class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 hover:text-teal-400 transition-colors">Features</a>
                    <a href="#mobile"
                        class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 hover:text-teal-400 transition-colors">Mobile</a>
                </nav>

                <div class="flex items-center gap-8">
                    <template v-if="!$page.props.auth.user">
                        <Link :href="route('login')"
                            class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 hover:text-white transition-colors">
                            Log In</Link>
                        <Link :href="route('register')"
                            class="px-6 py-2.5 bg-white text-black rounded-full text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-teal-400 hover:text-black active:scale-95">
                            Join Now
                        </Link>
                    </template>
                    <Link v-else :href="route('dashboard')"
                        class="px-6 py-2.5 bg-teal-500 text-black rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-teal-400 transition-all active:scale-95">
                        Dashboard
                    </Link>
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <!-- Hero Section -->
            <section class="relative min-h-[90vh] flex flex-col items-center justify-center px-6 pt-20 pb-20">
                <div class="container mx-auto max-w-5xl text-center">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[10px] font-semibold uppercase tracking-widest text-teal-300 mb-10">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                        Precision Finance Dashboard 2.0
                    </div>

                    <h1 class="text-5xl font-bold text-white sm:text-7xl lg:text-[100px] leading-[1.1] mb-8">
                        Wealth is <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-indigo-500">organized
                            freedom.</span>
                    </h1>

                    <p
                        class="mx-auto max-w-2xl text-base sm:text-lg leading-relaxed text-slate-400 mb-12 px-4 font-medium">
                        Stop guessing where your money goes. Experience a minimalist, privacy-focused workspace designed
                        for
                        tracking expenses with absolute clarity.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link :href="route('register')"
                            class="w-full sm:w-auto px-10 py-4 bg-white text-black text-[11px] font-bold uppercase tracking-widest rounded-xl hover:bg-teal-400 transition-all duration-300 active:scale-95">
                            Get Started Free
                        </Link>
                        <a href="#features"
                            class="w-full sm:w-auto px-10 py-4 bg-white/5 border border-white/10 text-white text-[11px] font-bold uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all duration-300">
                            Explore Features
                        </a>
                    </div>
                </div>
            </section>

            <!-- Bento Features -->
            <section id="features" class="py-32 px-6 lg:px-12 border-t border-white/5 bg-zinc-950/20">
                <div class="container mx-auto max-w-7xl">
                    <div class="mb-20 text-center">
                        <h2 class="text-[10px] font-bold uppercase tracking-[0.3em] text-teal-400 mb-4">Functional
                            Mastery</h2>
                        <h3 class="text-3xl sm:text-5xl font-bold text-white leading-tight">Every tool you need, none of
                            the
                            clutter.</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Privacy -->
                        <div
                            class="md:col-span-7 relative group overflow-hidden rounded-[2.5rem] border border-white/5 bg-zinc-900/40 p-10 hover:border-teal-500/30 transition-all duration-500">
                            <div class="relative z-20">
                                <div
                                    class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-teal-500/10 text-teal-400 border border-teal-500/10">
                                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-white mb-3">Privacy First, Always.</h4>
                                <p class="text-slate-400 text-sm leading-relaxed max-w-md font-medium">Your financial
                                    data stays
                                    yours. No bank tracking, no ads, just encrypted local records on your own terms.</p>
                            </div>
                        </div>

                        <!-- Sync -->
                        <div
                            class="md:col-span-5 relative group overflow-hidden rounded-[2.5rem] border border-white/5 bg-zinc-900/40 p-10 hover:border-indigo-500/30 transition-all duration-500">
                            <div class="relative z-20">
                                <div
                                    class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/10">
                                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-white mb-3">Universal Sync</h4>
                                <p class="text-slate-400 text-sm leading-relaxed font-medium">Cloud-native architecture
                                    ensures
                                    your budget is updated in real-time across Android and the web.</p>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div
                            class="md:col-span-12 relative group overflow-hidden rounded-[2.5rem] border border-white/5 bg-zinc-900/40 p-10 hover:border-white/10 transition-all duration-500">
                            <div class="flex flex-col md:flex-row items-center gap-12">
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-white mb-4">Deep Visual Intelligence</h4>
                                    <p class="text-slate-400 text-base leading-relaxed max-w-lg font-medium">Beautiful,
                                        minimal
                                        charts help you spot spending leaks instantly. Data visualization reimagined for
                                        personal clarity.</p>
                                </div>
                                <div class="flex-1 w-full flex items-end justify-center gap-3 h-40">
                                    <div
                                        class="w-12 bg-teal-500/20 rounded-t-2xl h-[40%] group-hover:h-[60%] transition-all duration-1000">
                                    </div>
                                    <div
                                        class="w-12 bg-indigo-500/30 rounded-t-2xl h-[70%] group-hover:h-[90%] transition-all duration-1000 delay-75">
                                    </div>
                                    <div
                                        class="w-12 bg-teal-500/50 rounded-t-2xl h-[50%] group-hover:h-[80%] transition-all duration-1000 delay-150">
                                    </div>
                                    <div
                                        class="w-12 bg-white/10 rounded-t-2xl h-[90%] group-hover:h-[100%] transition-all duration-1000 delay-225">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mobile Section (Revised) -->
            <section id="mobile" class="py-32 px-6 lg:px-12 bg-zinc-950/50 overflow-hidden relative">
                <div class="container mx-auto max-w-7xl relative z-10">
                    <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-32">
                        <div class="flex-1 text-center lg:text-left">
                            <div
                                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/20 text-[10px] font-bold uppercase tracking-widest text-teal-400 mb-8">
                                <span class="flex h-1.5 w-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                                Mobile Engine Live
                            </div>
                            <h2 class="text-4xl sm:text-5xl font-bold text-white mb-8 leading-tight">Pocket-sized <br
                                    class="hidden sm:block" /> Precision.</h2>
                            <p
                                class="text-slate-400 text-base sm:text-lg mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0 font-medium">
                                We've brought SpenTracker to the palm of your hand. The native Android application is
                                already in
                                active pilot, featuring biometric security, offline tracking, and lightning-fast
                                entries.
                            </p>
                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                                <div
                                    class="px-7 py-3.5 rounded-xl border border-white/10 text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-white/5">
                                    Google Play</div>
                                <div
                                    class="px-7 py-3.5 rounded-xl bg-teal-500 text-black text-[10px] font-heavy font-extrabold uppercase tracking-widest shadow-lg shadow-teal-500/20">
                                    Active Testing</div>
                            </div>
                        </div>

                        <div class="flex-1 relative flex justify-center py-10">
                            <!-- High-Fidelity Android Mockup -->
                            <div
                                class="relative w-[280px] h-[580px] sm:w-[310px] sm:h-[640px] bg-[#050505] rounded-[3.5rem] border-[12px] border-zinc-900 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.8)] overflow-hidden ring-1 ring-white/10">
                                <!-- Notch -->
                                <div
                                    class="absolute top-1 left-1/2 -translate-x-1/2 w-20 h-5 bg-zinc-900 rounded-full z-20">
                                </div>

                                <!-- Screen Content -->
                                <div class="absolute inset-2 bg-[#000000] rounded-[2.5rem] overflow-hidden">
                                    <div class="w-full h-full flex flex-col items-center justify-center relative p-8">
                                        <!-- Replaceable Image Placeholder -->
                                        <div
                                            class="absolute inset-0 z-0 bg-gradient-to-br from-zinc-900 to-black overflow-hidden flex items-center justify-center">
                                            <AppLogoIcon class="size-20 fill-current text-white/[0.03]" />
                                            <!-- To use an image: uncomment below and remove the logo/gradient -->
                                            <!-- <img src="/path/to/android-ui.png" class="w-full h-full object-cover" /> -->
                                        </div>

                                        <!-- Interactive UI Sim - Non Obtrusive -->
                                        <div class="relative z-10 w-full mt-auto space-y-4">
                                            <div
                                                class="w-full h-14 rounded-2xl bg-white/5 border border-white/5 backdrop-blur-sm">
                                            </div>
                                            <div
                                                class="w-4/5 h-14 rounded-2xl bg-white/5 border border-white/5 backdrop-blur-sm">
                                            </div>
                                            <div
                                                class="w-full h-28 rounded-3xl bg-teal-500/5 border border-teal-500/10 p-5 flex flex-col justify-between">
                                                <div class="w-12 h-1 bg-teal-500/40 rounded-full"></div>
                                                <div class="w-24 h-4 bg-teal-500/20 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="absolute bottom-4 left-1/2 -translate-x-1/2 w-28 h-1.5 bg-white/10 rounded-full z-20">
                                </div>
                            </div>

                            <!-- Decorative Elements -->
                            <div
                                class="absolute -right-6 top-1/3 p-6 bg-zinc-900 border border-white/10 rounded-3xl shadow-2xl hidden md:block animate-pulse-slow">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="size-10 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 20a10.003 10.003 0 006.203-2.126l.054.09m-9.712-4.838A3.5 3.5 0 1115 11.705M15 11.705a3.5 3.5 0 11-3.243 4.732" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                            Biometric</p>
                                        <p class="text-lg font-bold text-white">Encrypted</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-32 px-6 border-t border-white/5 text-center bg-black">
                <div class="container mx-auto max-w-4xl">
                    <h2 class="text-4xl sm:text-6xl font-bold text-white mb-8 leading-tight">Start your journey today.
                    </h2>
                    <p class="text-slate-500 text-lg mb-12 max-w-xl mx-auto font-medium">Join the growing community of
                        users
                        tracking wealth with absolute clarity.</p>
                    <Link :href="route('register')"
                        class="inline-block px-14 py-6 bg-teal-500 hover:bg-teal-400 text-black text-[12px] font-extrabold uppercase tracking-[0.2em] rounded-2xl transition-all active:scale-95 shadow-xl shadow-teal-500/10">
                        Create Free Account
                    </Link>
                </div>
            </section>
        </main>

        <footer class="bg-black py-24 px-6 border-t border-white/5">
            <div class="container mx-auto max-w-7xl">
                <div class="flex flex-col md:flex-row justify-between gap-16">
                    <div class="max-w-xs text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-4 mb-8">
                            <AppLogoIcon class="size-7 fill-current text-white" />
                            <span class="text-sm font-bold uppercase tracking-widest text-white">SpenTracker</span>
                        </div>
                        <p
                            class="text-slate-600 text-[10px] leading-relaxed uppercase tracking-widest font-semibold font-sans italic">
                            Minimum clutter. Maximum clarity.
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-12">
                        <Link :href="route('privacy-policy')"
                            class="text-[11px] text-slate-500 hover:text-teal-400 transition-colors uppercase tracking-widest font-bold">
                            Privacy</Link>
                        <a href="mailto:info@devcentricstudios.com"
                            class="text-[11px] text-slate-500 hover:text-teal-400 transition-colors uppercase tracking-widest font-bold">Support</a>
                    </div>
                </div>
                <div class="mt-20 pt-8 border-t border-white/5 text-center">
                    <p class="text-[9px] font-bold text-zinc-800 uppercase tracking-[0.3em]">© 2025 ALL RIGHTS RESERVED.
                        DEV
                        CENTRIC STUDIOS</p>
                </div>
            </div>
        </footer>

        <PwaInstallPrompt />
    </div>
</template>

<style scoped>
.landing-page {
    font-family: 'Inter', sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
}

h1,
h2,
h3,
h4,
h5 {
    font-family: 'Poppins', sans-serif;
}

::selection {
    background: #2dd4bf;
    color: #000000;
}

@keyframes pulse-slow {

    0%,
    100% {
        opacity: 1;
        transform: translateY(0);
    }

    50% {
        opacity: 0.8;
        transform: translateY(-5px);
    }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite ease-in-out;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #000000;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
