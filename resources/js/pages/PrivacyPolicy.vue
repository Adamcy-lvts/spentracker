<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const updatedOn = 'December 31, 2025';

const sections = [
    { id: 'information', label: '1. Collection' },
    { id: 'usage', label: '2. Usage' },
    { id: 'storage', label: '3. Storage' },
    { id: 'sharing', label: '4. Sharing' },
    { id: 'permissions', label: '5. Permissions' },
    { id: 'retention', label: '7. Retention' },
    { id: 'security', label: '10. Security' },
    { id: 'contact', label: '12. Contact' },
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
        <link href="https://fonts.bunny.net/css?family=fraunces:400,600,700,900|space-grotesk:300,400,500,600,700"
            rel="stylesheet" />
    </Head>

    <div class="privacy-dark-theme min-h-screen">
        <!-- Background -->
        <div class="fixed inset-0 -z-10 bg-[#030712]">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="noise"></div>
        </div>

        <!-- Progress Bar -->
        <div class="fixed left-0 top-0 h-0.5 bg-gradient-to-r from-teal-500 to-orange-400 z-50 transition-all duration-300"
            :style="{ width: `${(scrollY / (typeof document !== 'undefined' ? document.documentElement.scrollHeight - window.innerHeight : 1)) * 100}%` }">
        </div>

        <!-- Responsive Navbar -->
        <header class="sticky top-0 z-40 w-full border-b border-white/5 bg-[#030712]/70 backdrop-blur-xl py-4 sm:py-5">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 sm:px-10">
                <Link :href="route('home')" class="group flex items-center gap-2">
                    <div
                        class="flex aspect-square size-8 items-center justify-center rounded-lg bg-teal-500/20 shadow-inner group-hover:bg-teal-500/30 transition-colors">
                        <AppLogoIcon class="size-5 fill-current text-teal-400" />
                    </div>
                    <span
                        class="text-xs font-bold uppercase tracking-[0.2em] text-white/90 sm:text-sm">SpenTracker</span>
                </Link>

                <div class="flex items-center gap-3">
                    <Link v-if="!$page.props.auth.user" :href="route('login')"
                        class="text-[10px] font-bold uppercase tracking-widest text-white/40 hover:text-white transition">
                        Log in</Link>
                    <Link :href="route('register')"
                        class="rounded-full bg-white px-4 py-2 text-[9px] font-black uppercase tracking-widest text-black transition hover:bg-teal-400 sm:px-5 sm:py-2.5 sm:text-[10px]">
                        Join Free
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto flex max-w-7xl flex-col gap-10 px-5 py-12 lg:flex-row sm:px-10 sm:py-20 lg:py-24">
            <!-- Sticky Sidebar Nav (Desktop) -->
            <aside class="hidden lg:block lg:w-64">
                <div class="sticky top-32 space-y-10">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-teal-400/60 mb-6">Navigation
                        </p>
                        <nav class="flex flex-col gap-4">
                            <button v-for="section in sections" :key="section.id" @click="scrollTo(section.id)"
                                class="group flex items-center gap-3 text-left transition duration-300"
                                :class="activeSection === section.id ? 'text-white translate-x-1' : 'text-white/30 hover:text-white/60'">
                                <span class="h-px w-3 bg-current transition-all group-hover:w-6"
                                    :class="activeSection === section.id ? 'w-6 bg-teal-400' : ''"></span>
                                <span class="text-[11px] font-bold uppercase tracking-wider">{{ section.label }}</span>
                            </button>
                        </nav>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-white/[0.02] p-5">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-white/20">Last Updated</p>
                        <p class="mt-1 text-xs font-bold text-white/80">{{ updatedOn }}</p>
                    </div>
                </div>
            </aside>

            <!-- Content -->
            <div class="flex-1 space-y-16">
                <section class="reveal-fade max-w-3xl">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[9px] font-bold uppercase tracking-widest text-white/40 mb-8 sm:mb-10">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                        Privacy First Architecture
                    </div>
                    <h1 class="font-fraunces text-5xl font-black leading-[1.1] text-white sm:text-7xl lg:text-8xl">
                        Privacy <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-white to-orange-400">Policy.</span>
                    </h1>
                    <div class="mt-8 space-y-6 text-base leading-relaxed text-white/40 sm:text-lg">
                        <p>SpentTracker (the <strong>"App"</strong>, <strong>"we"</strong>, or <strong>"us"</strong>) is
                            a minimalist financial mastery tool live at <strong>spentracker.live</strong>.</p>
                        <p>This policy outlines our promise to protect your data with cinematic precision. By using
                            SpenTracker, you join our commitment to privacy.</p>
                    </div>
                </section>

                <!-- Cards Grid -->
                <div class="grid gap-6">
                    <section v-for="section in sections.slice(0, -1)" :id="section.id" :key="section.id"
                        class="group relative rounded-[2rem] border border-white/5 bg-white/[0.02] p-8 transition-all hover:bg-white/[0.04] sm:p-12">
                        <div class="flex items-center justify-between mb-8 sm:mb-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white sm:text-3xl">{{ section.label }}</h2>
                            <div
                                class="size-10 rounded-xl bg-white/5 flex items-center justify-center text-white/20 group-hover:text-teal-400 transition-colors">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Content Mock (Content preserved but styled for simplicity) -->
                        <div v-if="section.id === 'information'" class="space-y-8">
                            <div class="grid sm:grid-cols-2 gap-8">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-teal-400/40 mb-4">
                                        Account</p>
                                    <ul class="space-y-3 text-sm text-white/30 list-none p-0">
                                        <li>Email & Full Name</li>
                                        <li>Secure Credentials</li>
                                        <li class="italic opacity-50">Used for platform synchronization</li>
                                    </ul>
                                </div>
                                <div>
                                    <p
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400/40 mb-4">
                                        Financial</p>
                                    <ul class="space-y-3 text-sm text-white/30 list-none p-0">
                                        <li>Expense Data & Categories</li>
                                        <li>Budget Descriptions</li>
                                        <li class="italic opacity-50">Stored securely via Cloud</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-sm text-white/30 leading-relaxed max-w-2xl">
                            <p v-if="section.id === 'usage'">We use data to synchronize your experience, prevent
                                unauthorized access, and continuously refine our minimalist interface.</p>
                            <p v-if="section.id === 'storage'">Hosted via DigitalOcean. All transmissions are standard
                                HTTPS encrypted. Your dashboard is a fortress.</p>
                            <p v-if="section.id === 'sharing'">Third-parties get zero. Unless legally demanded, your
                                data is private. We strictly never sell your data.</p>
                            <p v-if="section.id === 'permissions'">Internet for cloud sync. Optional location for
                                geotagging. Minimal permissions for maximum power.</p>
                            <p v-if="section.id === 'retention'">Active data stays while you use the app. Request
                                deletion, and we scrub your digital footprint permanently.</p>
                            <p v-if="section.id === 'security'">Reasonable technical safeguards. Secure authentication
                                protocols. No fluff, just security.</p>
                        </div>
                    </section>

                    <!-- Final Contact Card -->
                    <section id="contact"
                        class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-teal-500/20 to-indigo-500/10 p-10 sm:p-16 text-center lg:text-left">
                        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-10">
                            <div class="flex-1">
                                <h2 class="font-fraunces text-4xl font-black text-white sm:text-5xl">Contact Us</h2>
                                <p class="mt-4 text-white/40 text-base sm:text-lg">Need support regarding your data or
                                    privacy? Reach out directly.</p>
                            </div>
                            <div class="flex flex-col gap-4 w-full sm:w-auto">
                                <a href="mailto:info@devcentricstudios.com"
                                    class="flex items-center justify-center gap-3 rounded-2xl bg-white px-8 py-4 text-xs font-black uppercase tracking-widest text-black transition hover:bg-teal-400 sm:text-sm">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Support Email
                                </a>
                                <a href="https://spentracker.live" target="_blank"
                                    class="flex items-center justify-center gap-3 rounded-2xl border border-white/20 bg-white/5 px-8 py-4 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-white/10 sm:text-sm">
                                    Our Website
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <footer class="border-t border-white/5 py-12 text-center">
            <p class="text-[9px] font-bold uppercase tracking-[0.4em] text-white/20">
                © 2025 DEV CENTRIC STUDIOS. ALL RIGHTS RESERVED.
            </p>
        </footer>
    </div>
</template>

<style scoped>
.privacy-dark-theme {
    background-color: #030712;
    color: #f9fafb;
    font-family: 'Space Grotesk', system-ui, sans-serif;
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
}

.font-fraunces {
    font-family: 'Fraunces', serif;
    letter-spacing: -0.01em;
}

.blob {
    position: absolute;
    width: 60vw;
    height: 60vw;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.1;
}

.blob-1 {
    top: -10vw;
    left: -10vw;
    background: radial-gradient(circle at center, #0f766e, transparent);
}

.blob-2 {
    bottom: -10vw;
    right: -10vw;
    background: radial-gradient(circle at center, #4f46e5, transparent);
}

.noise {
    position: absolute;
    inset: 0;
    opacity: 0.01;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3Base-filter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/feTurbulence%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}

.reveal-fade {
    animation: reveal 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes reveal {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
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

@media (max-width: 640px) {
    .font-fraunces {
        letter-spacing: -0.03em;
    }
}
</style>
