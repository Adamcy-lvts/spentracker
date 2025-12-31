<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const updatedOn = 'December 31, 2025';

const sections = [
    { id: 'information', label: 'Collection' },
    { id: 'usage', label: 'Usage' },
    { id: 'storage', label: 'Storage' },
    { id: 'sharing', label: 'Sharing' },
    { id: 'permissions', label: 'Permissions' },
    { id: 'retention', label: 'Retention' },
    { id: 'security', label: 'Security' },
    { id: 'contact', label: 'Contact' },
];

const activeSection = ref('');
const scrollY = ref(0);

const handleScroll = () => {
    scrollY.value = window.scrollY;

    for (const section of sections) {
        const element = document.getElementById(section.id);
        if (element) {
            const rect = element.getBoundingClientRect();
            if (rect.top <= 120 && rect.bottom >= 120) {
                activeSection.value = section.id;
                break;
            }
        }
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const scrollTo = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
        window.scrollTo({
            top: element.offsetTop - 110,
            behavior: 'smooth'
        });
    }
};
</script>

<template>

    <Head title="Privacy Policy - SpenTracker">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900" rel="stylesheet" />
    </Head>

    <div class="sleek-dark-policy min-h-screen bg-[#030712] text-white">
        <!-- Minimal Bordered Navbar -->
        <header class="fixed top-0 z-50 w-full border-b border-white/5 bg-[#030712]/80 backdrop-blur-xl py-4 sm:py-5">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6">
                <Link :href="route('home')" class="group flex items-center gap-2.5">
                    <div
                        class="flex aspect-square size-8 items-center justify-center rounded-xl bg-white/5 border border-white/10 group-hover:bg-white/10 transition-transform group-hover:rotate-6">
                        <AppLogoIcon class="size-5 fill-current text-teal-400" />
                    </div>
                    <span class="text-sm font-black uppercase tracking-[0.25em] text-white">SpenTracker</span>
                </Link>

                <div class="flex items-center gap-6">
                    <Link v-if="!$page.props.auth.user" :href="route('login')"
                        class="text-[11px] font-bold uppercase tracking-widest text-white/40 hover:text-white transition">
                        Log in</Link>
                    <Link :href="route('register')"
                        class="rounded-full bg-white px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400">
                        Join Free
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 pt-40 pb-32">
            <!-- Sleek Header -->
            <div class="mb-24">
                <p class="mb-4 text-[10px] font-extrabold uppercase tracking-[0.3em] text-white/20">Legal Interface</p>
                <h1 class="text-5xl font-black tracking-tighter text-white sm:text-7xl leading-[0.95]">
                    Privacy Policy
                </h1>
                <p class="mt-8 text-xl leading-relaxed text-white/40 max-w-2xl font-medium">
                    Simplified transparency. We protect your financial data with the same precision we apply to our
                    design.
                </p>
                <div
                    class="mt-10 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-white/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Updated {{ updatedOn }}
                    </span>
                    <span class="h-1 w-1 rounded-full bg-white/10"></span>
                    <span>Spentracker.live</span>
                </div>
            </div>

            <!-- Bento Nav Overlay -->
            <div class="mb-20 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <button v-for="section in sections" :key="section.id" @click="scrollTo(section.id)"
                    class="rounded-[1.5rem] border border-white/5 bg-white/[0.02] p-5 text-left transition hover:bg-white/[0.04] hover:border-white/10"
                    :class="activeSection === section.id ? 'border-teal-400/30 bg-teal-400/5' : ''">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white/20">Section</p>
                    <p class="mt-1 text-xs font-black uppercase tracking-widest text-white transition-colors"
                        :class="activeSection === section.id ? 'text-teal-400' : ''">{{ section.label }}</p>
                </button>
            </div>

            <div class="space-y-32">
                <!-- Intro Section -->
                <section class="max-w-3xl border-l-2 border-white/5 pl-8">
                    <p class="text-lg leading-relaxed text-white/40 font-medium">
                        SpentTracker (the <strong class="text-white">"App"</strong>, <strong
                            class="text-white">"we"</strong>, or <strong class="text-white">"us"</strong>) is a
                        high-precision expense tracking application. This document outlines our standard of data safety
                        across <strong>spentracker.live</strong>.
                    </p>
                </section>

                <!-- 1. Collection -->
                <section id="information" class="group">
                    <div class="flex items-center gap-4 mb-10">
                        <span class="text-[10px] font-black text-teal-400/50 tracking-widest uppercase">01 /
                            Collection</span>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div
                            class="rounded-[2rem] border border-white/5 bg-white/[0.02] p-10 transition-colors group-hover:bg-white/[0.03]">
                            <p class="text-[10px] font-black uppercase tracking-widest text-white/20 mb-6">User Identity
                            </p>
                            <ul class="space-y-4 text-xs font-bold tracking-widest text-white/40 list-none p-0">
                                <li class="flex items-center gap-3">
                                    <div class="h-1 w-1 rounded-full bg-teal-400"></div>
                                    Full Name & Email
                                </li>
                                <li class="flex items-center gap-3">
                                    <div class="h-1 w-1 rounded-full bg-teal-400"></div>
                                    Authentication data
                                </li>
                            </ul>
                        </div>
                        <div
                            class="rounded-[2rem] border border-white/5 bg-white/[0.02] p-10 transition-colors group-hover:bg-white/[0.03]">
                            <p class="text-[10px] font-black uppercase tracking-widest text-white/20 mb-6">Financial
                                data</p>
                            <ul class="space-y-4 text-xs font-bold tracking-widest text-white/40 list-none p-0">
                                <li class="flex items-center gap-3">
                                    <div class="h-1 w-1 rounded-full bg-teal-400"></div>
                                    Transaction records
                                </li>
                                <li class="flex items-center gap-3">
                                    <div class="h-1 w-1 rounded-full bg-teal-400"></div>
                                    Expense Categories
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Usage -->
                <section id="usage" class="group">
                    <div class="flex items-center gap-4 mb-10">
                        <span class="text-[10px] font-black text-teal-400/50 tracking-widest uppercase">02 /
                            Purpose</span>
                    </div>
                    <div class="rounded-[2.5rem] border border-white/5 bg-white/[0.02] p-12">
                        <p class="text-white/40 leading-relaxed mb-10 text-lg font-medium">
                            We use your information exclusively to synchronize your financial dashboard across devices
                            and respond to your technical requests.
                        </p>
                        <div class="grid gap-8 sm:grid-cols-2">
                            <div class="space-y-5">
                                <p
                                    class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-white/80">
                                    <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Real-time Sync
                                </p>
                                <p
                                    class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-white/80">
                                    <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Fraud Prevention
                                </p>
                            </div>
                            <div class="space-y-5">
                                <p
                                    class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-white/80">
                                    <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    System Metrics
                                </p>
                                <p
                                    class="flex items-center gap-3 text-xs font-black uppercase tracking-widest text-white/80">
                                    <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Support Office
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sharing & Security -->
                <div class="grid gap-6 sm:grid-cols-2">
                    <section id="sharing" class="rounded-[2rem] border border-white/5 bg-white/[0.02] p-10">
                        <h3 class="text-xl font-bold text-white mb-6 uppercase tracking-wider">Zero Sharing</h3>
                        <p class="text-xs font-bold leading-relaxed text-white/30 tracking-widest uppercase mb-10">
                            Absolute Privacy Policy</p>
                        <p class="text-sm text-white/40 leading-relaxed font-medium">Spent Tracker will never sell,
                            lease, or distribute your identity or financial records to third-party marketing entities.
                        </p>
                    </section>
                    <section id="storage" class="rounded-[2rem] border border-white/5 bg-white/[0.02] p-10">
                        <h3 class="text-xl font-bold text-white mb-6 uppercase tracking-wider">Fortress Storage</h3>
                        <p class="text-xs font-bold leading-relaxed text-white/30 tracking-widest uppercase mb-10">
                            Digital Ocean Cloud</p>
                        <p class="text-sm text-white/40 leading-relaxed font-medium">Servers located in tier-1 data
                            centers. All transmission is secured via enterprise-grade HTTPS encryption.</p>
                    </section>
                </div>

                <!-- Footer style sections -->
                <section id="contact"
                    class="rounded-[3rem] bg-white p-12 text-black sm:p-24 relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-12">
                        <div class="flex-1 text-center lg:text-left">
                            <h2 class="text-5xl font-black tracking-tighter mb-4">Privacy Support.</h2>
                            <p class="text-black/50 text-lg leading-relaxed font-medium">Have queries about our
                                architecture? <br /> Connect with our legal & tech team.</p>
                        </div>
                        <div class="flex flex-col gap-4 w-full lg:w-auto">
                            <a href="mailto:info@devcentricstudios.com"
                                class="flex items-center justify-center gap-3 rounded-2xl bg-black px-10 py-5 text-xs font-black uppercase tracking-widest text-white transition hover:scale-[1.03]">
                                info@devcentricstudios.com
                            </a>
                            <a href="https://spentracker.live" target="_blank"
                                class="flex items-center justify-center gap-3 rounded-2xl border border-black/10 px-10 py-5 text-xs font-black uppercase tracking-widest text-black/40 transition hover:bg-black/5">
                                Spentracker.live
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <footer class="border-t border-white/5 bg-black py-24">
            <div class="mx-auto max-w-5xl px-6 flex flex-col sm:flex-row justify-between items-center gap-8">
                <div class="text-center sm:text-left">
                    <p class="text-[10px] font-black uppercase tracking-[0.5em] text-white">SpenTracker</p>
                    <p class="mt-3 text-[9px] text-white/20 uppercase font-black tracking-widest">© 2025 Dev Centric
                        Studios.</p>
                </div>
                <div class="flex gap-10">
                    <Link :href="route('home')"
                        class="text-[10px] font-black uppercase tracking-widest text-white/30 hover:text-white transition">
                        Terms</Link>
                    <Link :href="route('privacy-policy')"
                        class="text-[10px] font-black uppercase tracking-widest text-white transition">Privacy</Link>
                    <a href="mailto:info@devcentricstudios.com"
                        class="text-[10px] font-black uppercase tracking-widest text-white/30 hover:text-white transition">Admin</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.sleek-dark-policy {
    font-family: 'Outfit', sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
    letter-spacing: -0.01em;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: #000;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2dd4bf;
}

::selection {
    background: #2dd4bf;
    color: #000;
}
</style>
