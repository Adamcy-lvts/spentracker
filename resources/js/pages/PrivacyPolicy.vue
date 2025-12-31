<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const updatedOn = '31 December 2025';

const sections = [
    { id: 'collection', label: '1. Collection' },
    { id: 'usage', label: '2. Usage' },
    { id: 'storage', label: '3. Storage' },
    { id: 'sharing', label: '4. Sharing' },
    { id: 'permissions', label: '5. Permissions' },
    { id: 'third-party', label: '6. Services' },
    { id: 'retention', label: '7. Retention' },
    { id: 'security', label: '10. Security' },
];

const activeSection = ref('');

const handleScroll = () => {
    for (const section of sections) {
        const element = document.getElementById(section.id);
        if (element) {
            const rect = element.getBoundingClientRect();
            if (rect.top <= 150 && rect.bottom >= 150) {
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
            top: element.offsetTop - 100,
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

    <div class="compact-dark min-h-screen bg-[#030712] text-[#94a3b8]">
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
                <p class="mb-2 text-[9px] font-black uppercase tracking-[0.3em] text-white/20">Legal Interface</p>
                <h1 class="text-3xl font-black tracking-tighter text-white sm:text-5xl">Privacy Policy</h1>
                <p class="mt-4 text-sm font-medium leading-relaxed max-w-2xl">
                    Last updated: <span class="text-white/60">{{ updatedOn }}</span><br />
                    SpentTracker (“the App”, “we”, “our”, or “us”) is a personal expense tracking application available
                    on Android and the web. This Privacy Policy explains how we collect, use, store, and protect your
                    information.
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Sidebar Nav (Compact) -->
                <aside class="hidden lg:block w-48 shrink-0">
                    <nav class="sticky top-32 flex flex-col gap-3">
                        <button v-for="section in sections" :key="section.id" @click="scrollTo(section.id)"
                            class="text-left text-[10px] font-bold uppercase tracking-widest transition-all"
                            :class="activeSection === section.id ? 'text-teal-400 translate-x-1' : 'text-white/20 hover:text-white/40'">
                            {{ section.label }}
                        </button>
                    </nav>
                </aside>

                <!-- Policy Content -->
                <div class="flex-1 space-y-12 text-xs sm:text-[13px] leading-relaxed">

                    <section id="collection" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">1. Information We Collect
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-bold text-white/90 mb-2">a. Account Information</h3>
                                <p>When you create an account, we collect: Full name, Email address, Phone number, and
                                    Secure authentication credentials. Required for account management.</p>
                            </div>

                            <div>
                                <h3 class="font-bold text-white/90 mb-2">b. Financial & Expense Data</h3>
                                <p>Users may voluntarily enter amounts, categories, notes, dates, and budget summaries.
                                    <br /> <span class="text-orange-400 font-bold">⚠️ We do not collect bank account
                                        numbers, card details, or PINs.</span></p>
                            </div>

                            <div>
                                <h3 class="font-bold text-white/90 mb-2">c. Location Information</h3>
                                <p>With permission, we may collect location data for tagging expenses and providing
                                    insights. Location access is optional and data is not shared or sold.</p>
                            </div>

                            <div>
                                <h3 class="font-bold text-white/90 mb-2">d. Device & Technical Information</h3>
                                <p>We collect device model, OS version, app version, IP, and crash logs solely to
                                    improve reliability and security.</p>
                            </div>
                        </div>
                    </section>

                    <section id="usage" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">2. How We Use Information
                        </h2>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 list-none p-0">
                            <li class="flex items-center gap-2">
                                <div class="h-1 w-1 rounded-full bg-teal-500"></div> Account sync across devices
                            </li>
                            <li class="flex items-center gap-2">
                                <div class="h-1 w-1 rounded-full bg-teal-500"></div> Location-based features
                            </li>
                            <li class="flex items-center gap-2">
                                <div class="h-1 w-1 rounded-full bg-teal-500"></div> User experience refinement
                            </li>
                            <li class="flex items-center gap-2">
                                <div class="h-1 w-1 rounded-full bg-teal-500"></div> Fraud prevention
                            </li>
                        </ul>
                        <p class="font-bold text-white/60 mt-4">We do not sell or rent user data.</p>
                    </section>

                    <section id="storage" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">3. Data Storage & Hosting
                        </h2>
                        <p>User data is securely stored on cloud servers hosted by <strong
                                class="text-white">DigitalOcean</strong>. All data transmission uses enterprise-grade
                            HTTPS encryption.</p>
                    </section>

                    <section id="sharing" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">4. Data Sharing & Disclosure
                        </h2>
                        <p>We do not share user data with third parties, except when required by law or to protect user
                            safety and service security.</p>
                    </section>

                    <section id="permissions" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">5. Permissions Used</h2>
                        <p><strong>Internet</strong> (login & sync), <strong>Location</strong> (optional tagging), and
                            <strong>Storage</strong> (reports & backups). Used only for stated purposes.</p>
                    </section>

                    <section id="third-party" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">6. Third-Party Services</h2>
                        <p>We use Google Play Services and crash analytics. These process data according to their own
                            privacy policies.</p>
                    </section>

                    <section id="retention" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">7. Data Retention</h2>
                        <p>Data is retained while active. Users may request deletion at any time, which permanently
                            removes data within a reasonable timeframe.</p>
                    </section>

                    <section id="security" class="space-y-4">
                        <h2 class="text-lg font-black text-white uppercase tracking-wider">10. Security</h2>
                        <p>We use encrypted communication and secure authentication. Users are responsible for
                            protecting their login credentials.</p>
                    </section>

                    <section id="contact" class="pt-8 border-t border-white/5">
                        <div class="rounded-3xl bg-white p-8 text-black sm:p-12">
                            <h2 class="text-2xl font-black tracking-tighter mb-2">Support</h2>
                            <p class="text-sm font-medium opacity-60 mb-6">Questions about your data? Reach out
                                directly.</p>
                            <div class="flex flex-wrap gap-4">
                                <a href="mailto:info@devcentricstudios.com"
                                    class="bg-black text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest">info@devcentricstudios.com</a>
                                <a href="https://spentracker.live" target="_blank"
                                    class="border border-black/10 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest text-black/40">spentracker.live</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <footer class="border-t border-white/5 py-12 text-center">
            <p class="text-[9px] font-bold uppercase tracking-[0.4em] text-white/10">© 2025 DEV CENTRIC STUDIOS.</p>
        </footer>
    </div>
</template>

<style scoped>
.compact-dark {
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
