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

    <Head title="Spent Tracker - Master Your Finances">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=fraunces:400,600,700,900|space-grotesk:300,400,500,600,700"
            rel="stylesheet" />
    </Head>

    <div class="landing-theme min-h-screen">
        <!-- Background Elements -->
        <div class="bg-scene fixed inset-0 -z-10">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="noise"></div>
        </div>

        <!-- Navigation -->
        <header class="fixed top-0 z-50 w-full transition-all duration-300"
            :class="isScrolled ? 'bg-[#030712]/80 py-3 backdrop-blur-xl border-b border-white/5' : 'bg-transparent py-6'">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 sm:px-10">
                <Link :href="route('home')" class="group flex items-center gap-2 text-white">
                    <div
                        class="flex aspect-square size-9 items-center justify-center rounded-lg bg-teal-500/20 shadow-inner group-hover:bg-teal-500/30 transition-colors">
                        <AppLogoIcon class="size-6 fill-current text-teal-400" />
                    </div>
                    <span class="text-sm font-bold uppercase tracking-[0.2em] sm:text-base">SpenTracker</span>
                </Link>

                <nav class="hidden items-center gap-8 md:flex">
                    <a href="#features"
                        class="text-[10px] font-bold uppercase tracking-widest text-white/40 transition hover:text-white">Features</a>
                    <a href="#mobile"
                        class="text-[10px] font-bold uppercase tracking-widest text-white/40 transition hover:text-white">Mobile</a>
                </nav>

                <div class="flex items-center gap-3 sm:gap-6">
                    <template v-if="!$page.props.auth.user">
                        <Link :href="route('login')"
                            class="text-xs font-bold uppercase tracking-widest text-white/40 transition hover:text-white">
                            Log in
                        </Link>
                        <Link :href="route('register')"
                            class="rounded-full bg-white px-4 py-2 text-[10px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400 sm:px-6 sm:py-2.5 sm:text-xs">
                            Join Free
                        </Link>
                    </template>
                    <Link v-else :href="route('dashboard')"
                        class="text-xs font-bold uppercase tracking-widest text-teal-400 transition hover:text-white">
                        Enter App
                    </Link>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section
                class="relative flex min-h-screen flex-col items-center justify-center pt-32 pb-20 px-6 overflow-hidden">
                <div class="hero-reveal text-center">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-teal-500/20 bg-teal-500/5 px-4 py-1.5 text-[9px] font-bold uppercase tracking-[0.2em] text-teal-400 sm:text-[10px]">
                        <span class="flex h-1.5 w-1.5 animate-pulse rounded-full bg-teal-400"></span>
                        Completely Free at Spentracker.live
                    </div>

                    <h1
                        class="font-fraunces mt-8 text-5xl font-black leading-[1] text-white sm:text-8xl lg:text-[9rem]">
                        Spend<br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-white to-orange-400">Wisely.</span>
                    </h1>

                    <p
                        class="mx-auto mt-8 max-w-2xl text-base leading-relaxed text-white/40 sm:text-xl sm:leading-relaxed">
                        A beautiful, privacy-first expense tracker for your modern lifestyle. Master your wealth across
                        web and
                        mobile without ever paying a dime.
                    </p>

                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Link :href="route('register')"
                            class="group relative flex w-full items-center justify-center gap-3 rounded-full bg-teal-500/10 border border-teal-500/20 px-8 py-4 text-sm font-bold text-teal-400 transition hover:bg-teal-500 hover:text-black sm:w-auto">
                            Get Started
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </Link>
                        <a href="#mobile"
                            class="flex w-full items-center justify-center gap-3 rounded-full border border-white/10 bg-white/5 px-8 py-4 text-sm font-bold text-white transition hover:bg-white/10 sm:w-auto">
                            Android App Teaser
                        </a>
                    </div>
                </div>

                <!-- Showcase Image -->
                <div class="hero-image-wrap mt-16 relative w-full max-w-5xl group">
                    <div
                        class="absolute -inset-10 rounded-[3rem] bg-teal-500/10 blur-[80px] opacity-20 transition-opacity group-hover:opacity-40">
                    </div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-white/5 bg-black shadow-2xl">
                        <img src="/images/hero-mockup.png" alt="Spent Tracker Mockup"
                            class="w-full transform transition duration-1000 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    </div>
                </div>
            </section>

            <!-- Features Grid -->
            <section id="features" class="py-24 px-6 sm:px-10 max-w-7xl mx-auto">
                <div class="flex flex-col gap-12">
                    <div class="max-w-xl">
                        <h2 class="font-fraunces text-4xl font-bold text-white leading-tight sm:text-5xl">Everything you
                            need,
                            <br /><span class="text-teal-400">perfectly balanced.</span>
                        </h2>
                        <p class="mt-5 text-white/40 text-base leading-relaxed sm:text-lg">Financial tracking doesn't
                            have to be
                            clinical. Spent Tracker blends raw power with a cinematic experience.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <!-- Private Bento -->
                        <div
                            class="md:col-span-8 group relative overflow-hidden rounded-[2rem] bg-indigo-500/5 border border-white/5 p-8 transition hover:bg-indigo-500/10 sm:p-12">
                            <div class="relative z-10 flex flex-col justify-between h-full min-h-[250px]">
                                <div>
                                    <div
                                        class="h-12 w-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 mb-6 shadow-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-fraunces text-3xl font-bold text-white mb-3 sm:text-4xl">Total
                                        Privacy</h3>
                                    <p class="text-white/30 max-w-sm text-base leading-relaxed sm:text-lg">Your data
                                        stays local
                                        and encrypted. We don't sell your data to third parties. You remain in control.
                                    </p>
                                </div>
                                <div class="mt-8 flex items-center gap-2">
                                    <span
                                        class="px-3 py-1 rounded-full bg-white/5 border border-white/5 text-[9px] uppercase font-bold tracking-widest text-white/30">Zero
                                        Tracking</span>
                                    <span
                                        class="px-3 py-1 rounded-full bg-white/5 border border-white/5 text-[9px] uppercase font-bold tracking-widest text-white/30">Encryption
                                        First</span>
                                </div>
                            </div>
                            <div
                                class="absolute -right-20 -bottom-20 h-48 w-48 rounded-full bg-indigo-500/10 blur-[60px]">
                            </div>
                        </div>

                        <!-- Real-time Bento -->
                        <div
                            class="md:col-span-4 group relative overflow-hidden rounded-[2rem] bg-teal-500/5 border border-white/5 p-8 transition hover:bg-teal-500/10 sm:p-10">
                            <div class="relative z-10">
                                <div
                                    class="h-12 w-12 rounded-xl bg-teal-500/20 flex items-center justify-center text-teal-400 mb-6 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 class="font-fraunces text-2xl font-bold text-white mb-3 sm:text-3xl">Real-time
                                    Insights</h3>
                                <p class="text-white/30 text-base leading-relaxed">Beautiful charts that help you
                                    visualize
                                    where your money goes. Instant feedback.</p>
                            </div>
                        </div>

                        <!-- Multi-device Bento -->
                        <div
                            class="md:col-span-4 group relative overflow-hidden rounded-[2rem] bg-orange-500/5 border border-white/5 p-8 transition hover:bg-orange-500/10 sm:p-10">
                            <div class="relative z-10">
                                <div
                                    class="h-12 w-12 rounded-xl bg-orange-500/20 flex items-center justify-center text-orange-400 mb-6 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="font-fraunces text-2xl font-bold text-white mb-3 sm:text-3xl">Sync Seamlessly
                                </h3>
                                <p class="text-white/30 text-base leading-relaxed">Across Web and Android. Start on your
                                    computer, finish on your phone.</p>
                            </div>
                        </div>

                        <!-- Completely Free Bento -->
                        <div
                            class="md:col-span-8 group relative overflow-hidden rounded-[2rem] bg-white/[0.02] border border-white/5 p-8 transition hover:bg-white/[0.04] sm:p-12">
                            <div class="relative z-10 flex flex-col justify-between h-full">
                                <div>
                                    <h3 class="font-fraunces text-3xl font-bold text-white mb-3 sm:text-4xl">Forever
                                        Free.</h3>
                                    <p class="text-white/30 max-w-xl text-base leading-relaxed sm:text-lg">No premium
                                        tiers, no
                                        limits, and no annoying advertisements. Built by Dev Centric Studios for simple
                                        financial mastery.</p>
                                </div>
                                <div class="mt-8 flex items-center gap-4">
                                    <div class="flex -space-x-3">
                                        <div v-for="i in 4" :key="i"
                                            class="h-10 w-10 rounded-full border-2 border-[#030712] bg-gradient-to-br from-white/10 to-white/5 flex items-center justify-center text-white/50 text-[10px] font-bold">
                                            U{{ i }}</div>
                                        <div
                                            class="h-10 w-10 rounded-full border-2 border-[#030712] bg-teal-500 flex items-center justify-center text-black text-[10px] font-black">
                                            +1k</div>
                                    </div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-white/20">Growing
                                        daily</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mobile App Section -->
            <section id="mobile" class="relative py-24 px-6 overflow-hidden">
                <div
                    class="mx-auto max-w-7xl rounded-[2.5rem] bg-gradient-to-br from-teal-500/10 via-indigo-500/5 to-transparent p-10 sm:p-20 border border-white/5 relative">
                    <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12 sm:gap-16">
                        <div class="flex-1 text-center lg:text-left">
                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.2em] text-black mb-8">
                                Android Play Store Q1 2025
                            </div>
                            <h2 class="font-fraunces text-4xl sm:text-7xl font-black text-white leading-none">Your
                                wallet,
                                <br />in your pocket.
                            </h2>
                            <p class="mt-6 text-white/40 text-base sm:text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
                                The Spent Tracker Android app is currently in final testing. Track every penny on the go
                                with
                                our ultra-smooth native experience.
                            </p>
                            <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row lg:justify-start">
                                <div class="opacity-20 grayscale pointer-events-none">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                        alt="Play Store" class="h-12" />
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-teal-400">Notifying
                                    soon...</span>
                            </div>
                        </div>
                        <div class="flex-1 relative group w-full max-w-sm">
                            <div class="absolute inset-0 bg-teal-400/10 blur-[100px] rounded-full opacity-30"></div>
                            <img src="/images/hero-mockup.png" alt="Android App Preview"
                                class="relative w-full transform -rotate-3 transition duration-1000 group-hover:rotate-0" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-24 text-center px-6">
                <div class="max-w-2xl mx-auto">
                    <h2 class="font-fraunces text-4xl font-bold text-white mb-6 sm:text-5xl">Ready to take control?</h2>
                    <p class="text-white/30 text-base mb-10 leading-relaxed sm:text-lg">Join others who are mastering
                        their
                        finances with Spent Tracker. No credit card required.</p>
                    <Link :href="route('register')"
                        class="inline-flex rounded-full bg-white px-10 py-4 text-xs font-black uppercase tracking-[0.2em] text-black transition hover:bg-teal-400 hover:scale-105 active:scale-95 sm:px-12 sm:py-5 sm:text-sm">
                        Create Your Free Account
                    </Link>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/5 py-16">
            <div class="mx-auto max-w-7xl px-6 sm:px-10">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="flex flex-col items-center md:items-start gap-3">
                        <Link :href="route('home')" class="flex items-center gap-2 text-white/90">
                            <div class="flex aspect-square size-6 items-center justify-center rounded bg-teal-500/20">
                                <AppLogoIcon class="size-4 fill-current text-teal-400" />
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest">SpenTracker</span>
                        </Link>
                        <p class="text-[9px] text-white/20 tracking-[0.3em] font-bold">© 2025 DEV CENTRIC STUDIOS.</p>
                    </div>

                    <div class="flex gap-8">
                        <Link :href="route('privacy-policy')"
                            class="text-[10px] font-bold uppercase tracking-widest text-white/30 transition hover:text-white">
                            Privacy Policy</Link>
                        <a href="mailto:info@devcentricstudios.com"
                            class="text-[10px] font-bold uppercase tracking-widest text-white/30 transition hover:text-white">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.landing-theme {
    background-color: #030712;
    color: #f9fafb;
    font-family: 'Space Grotesk', system-ui, sans-serif;
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
}

.font-fraunces {
    font-family: 'Fraunces', serif;
    letter-spacing: -0.02em;
}

.bg-scene {
    overflow: hidden;
}

.blob {
    position: absolute;
    width: 60vw;
    height: 60vw;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.12;
}

.blob-1 {
    top: -10vw;
    left: -10vw;
    background: radial-gradient(circle at center, #0f766e, transparent);
    animation: pulse-blob 20s infinite alternate ease-in-out;
}

.blob-2 {
    bottom: -10vw;
    right: -10vw;
    background: radial-gradient(circle at center, #4f46e5, transparent);
    animation: pulse-blob 25s infinite alternate-reverse ease-in-out;
}

@keyframes pulse-blob {
    0% {
        transform: scale(1) translate(0, 0);
    }

    100% {
        transform: scale(1.1) translate(30px, 20px);
    }
}

.noise {
    position: absolute;
    inset: 0;
    opacity: 0.015;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3Base-filter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/feTurbulence%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}

.hero-reveal {
    animation: hero-reveal 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes hero-reveal {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-image-wrap {
    animation: hero-image-reveal 1.5s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
    opacity: 0;
}

@keyframes hero-image-reveal {
    from {
        opacity: 0;
        transform: translateY(60px) rotateX(8deg);
    }

    to {
        opacity: 1;
        transform: translateY(0) rotateX(0deg);
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
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

@media (max-width: 640px) {
    .font-fraunces {
        letter-spacing: -0.04em;
    }
}
</style>
