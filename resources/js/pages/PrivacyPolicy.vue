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
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800|inter:300,400,500,600,700"
            rel="stylesheet" />
    </Head>

    <div class="landing-page min-h-screen bg-[#000000] text-slate-400">
        <!-- Subtle Ambient Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-teal-500/5 blur-[120px] rounded-full"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] bg-indigo-500/5 blur-[100px] rounded-full"></div>
        </div>

        <!-- Premium Navigation -->
        <header class="fixed top-0 z-50 w-full border-b border-white/5 bg-black/80 backdrop-blur-xl py-4 sm:py-5">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 lg:px-12">
                <Link :href="route('home')" class="group flex items-center gap-3">
                    <div
                        class="relative flex size-8 items-center justify-center rounded-lg bg-zinc-900 border border-white/10 group-hover:border-teal-500/50 transition-colors">
                        <AppLogoIcon class="size-4 fill-current text-teal-400" />
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-white">SpenTracker</span>
                </Link>

                <div class="flex items-center gap-6">
                    <Link :href="route('register')"
                        class="px-5 py-2 rounded-full bg-white text-black text-[10px] font-bold uppercase tracking-widest transition hover:bg-teal-400">
                        JoinNow
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 pt-32 pb-32 lg:px-12 relative z-10">
            <div class="max-w-4xl">
                <!-- Header -->
                <div class="mb-20">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[9px] font-bold uppercase tracking-widest text-slate-500 mb-6">
                        Governance Portal
                    </div>
                    <h1 class="text-4xl sm:text-6xl font-bold tracking-tight text-white mb-8">Privacy Policy</h1>
                    <p class="text-base sm:text-lg font-medium leading-relaxed max-w-3xl text-slate-500">
                        Last updated: <span class="text-teal-400">{{ updatedOn }}</span><br />
                        SpentTracker (“the App”, “we”, “our”, or “us”) is a personal expense tracking application
                        available
                        on Android and the web. This Privacy Policy explains how we collect, use, store, and protect
                        your
                        information.
                    </p>
                </div>

                <div class="flex flex-col lg:flex-row gap-16 lg:gap-24">
                    <!-- Sidebar Nav -->
                    <aside class="hidden lg:block w-56 shrink-0">
                        <nav class="sticky top-40 flex flex-col gap-4">
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white/20 mb-2">Sections</p>
                            <button v-for="section in sections" :key="section.id" @click="scrollTo(section.id)"
                                class="text-left text-[11px] font-bold uppercase tracking-widest transition-all hover:translate-x-1"
                                :class="activeSection === section.id ? 'text-teal-400' : 'text-slate-600 hover:text-slate-400'">
                                {{ section.label }}
                            </button>
                        </nav>
                    </aside>

                    <!-- Policy Content -->
                    <div class="flex-1 space-y-20 text-sm sm:text-base leading-relaxed font-medium">

                        <section id="collection" class="group">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">01</span>
                                Information We Collect
                            </h2>

                            <div class="space-y-10 border-l border-white/5 pl-8">
                                <div>
                                    <h3 class="font-bold text-white mb-3">a. Account Information</h3>
                                    <p class="text-slate-500">When you create an account, we collect: Full name, Email
                                        address, Phone number, and
                                        Secure authentication credentials. Required for account management.</p>
                                </div>

                                <div>
                                    <h3 class="font-bold text-white mb-3">b. Financial & Expense Data</h3>
                                    <p class="text-slate-500">Users may voluntarily enter amounts, categories, notes,
                                        dates, and budget summaries.
                                        <br /> <span
                                            class="text-orange-400/80 font-bold block mt-3 px-4 py-2 bg-orange-500/5 border border-orange-500/10 rounded-xl">⚠️
                                            We do not collect bank account
                                            numbers, card details, or PINs.</span>
                                    </p>
                                </div>

                                <div>
                                    <h3 class="font-bold text-white mb-3">c. Location Information</h3>
                                    <p class="mb-4 text-slate-500">SpentTracker may request access to your device’s
                                        location while the app
                                        is in use to enable optional features such as tagging expenses by location and
                                        providing location-based insights.</p>
                                    <p class="mb-4 text-white/40 uppercase text-[10px] font-bold tracking-widest">Data
                                        Stewardship:</p>
                                    <ul
                                        class="space-y-2 text-slate-500 bg-white/[0.02] p-6 rounded-2xl border border-white/5">
                                        <li class="flex items-center gap-3">
                                            <div class="size-1 rounded-full bg-teal-500"></div> Collected only when the
                                            app is in the foreground
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <div class="size-1 rounded-full bg-teal-500"></div> Optional and can be
                                            denied or revoked at any time
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <div class="size-1 rounded-full bg-teal-500"></div> Not collected in the
                                            background
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <div class="size-1 rounded-full bg-teal-500"></div> Not used for advertising
                                            or tracking
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <div class="size-1 rounded-full bg-teal-500"></div> Not shared or sold
                                        </li>
                                    </ul>
                                    <p class="italic text-slate-600 mt-4 text-xs">The app continues to function without
                                        location access, with
                                        reduced location-based features.</p>
                                </div>

                                <div>
                                    <h3 class="font-bold text-white mb-3">d. Device & Technical Information</h3>
                                    <p class="text-slate-500">We collect device model, OS version, app version, IP, and
                                        crash logs solely to
                                        improve reliability and security.</p>
                                </div>
                            </div>
                        </section>

                        <section id="usage">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">02</span>
                                How We Use Information
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-l border-white/5 pl-8 mb-8">
                                <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 text-slate-400">Account
                                    sync across devices</div>
                                <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 text-slate-400">
                                    Location-based features</div>
                                <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 text-slate-400">User
                                    experience refinement</div>
                                <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 text-slate-400">Fraud
                                    prevention</div>
                            </div>
                            <p class="font-bold text-teal-400/80 pl-8">We do not sell or rent user data.</p>
                        </section>

                        <section id="storage">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">03</span>
                                Data Storage & Hosting
                            </h2>
                            <p class="text-slate-500 border-l border-white/5 pl-8">User data is securely stored on cloud
                                server. All data
                                transmission uses enterprise-grade
                                HTTPS encryption.</p>
                        </section>

                        <section id="sharing">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">04</span>
                                Data Sharing & Disclosure
                            </h2>
                            <p class="text-slate-500 border-l border-white/5 pl-8">We do not share user data with third
                                parties, except when required by law or to protect user
                                safety and service security.</p>
                        </section>

                        <section id="permissions">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">05</span>
                                Permissions Used
                            </h2>
                            <div class="space-y-4 border-l border-white/5 pl-8">
                                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/5">
                                    <ul class="space-y-3 text-slate-500">
                                        <li><strong class="text-white">Internet access</strong> – account login and
                                            cloud synchronization</li>
                                        <li><strong class="text-white">Location access</strong> – optional
                                            location-based expense features</li>
                                        <li><strong class="text-white">Storage access</strong> – exporting reports or
                                            backups</li>
                                        <li><strong class="text-white">Notifications</strong> – solely for expense
                                            reminders and budget alerts</li>
                                    </ul>
                                </div>
                                <p class="italic text-slate-600 text-xs">Permissions are requested only when necessary
                                    and used only
                                    for their stated purpose.</p>
                            </div>
                        </section>

                        <section id="third-party">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">06</span>
                                Third-Party Services
                            </h2>
                            <p class="text-slate-500 border-l border-white/5 pl-8">We use Google Play Services and crash
                                analytics. These process data according to their own
                                privacy policies.</p>
                        </section>

                        <section id="retention">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">07</span>
                                Data Retention
                            </h2>
                            <p class="text-slate-500 border-l border-white/5 pl-8">Data is retained while active. Users
                                may request deletion at any time, which permanently
                                removes data within a reasonable timeframe.</p>
                        </section>

                        <section id="security">
                            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                                <span class="text-xs font-black text-teal-500/50 uppercase tracking-widest">08</span>
                                Security
                            </h2>
                            <p class="text-slate-500 border-l border-white/5 pl-8">We use encrypted communication and
                                secure authentication. Users are responsible for
                                protecting their login credentials.</p>
                        </section>

                        <section id="contact" class="pt-20">
                            <div class="rounded-[2.5rem] bg-white p-10 text-black sm:p-16 shadow-2xl shadow-white/5">
                                <h2 class="text-3xl font-bold tracking-tight mb-4">Support & Transparency</h2>
                                <p class="text-lg font-medium text-black/50 mb-10 max-w-xl">If you have any questions
                                    regarding your data privacy or account security, our team is ready to assist.</p>
                                <div class="flex flex-wrap gap-4">
                                    <a href="mailto:info@devcentricstudios.com"
                                        class="bg-black text-white px-8 py-4 rounded-2xl text-[11px] font-bold uppercase tracking-widest hover:scale-[1.02] transition-transform">info@devcentricstudios.com</a>
                                    <a href="https://spentracker.live" target="_blank"
                                        class="border border-black/10 px-8 py-4 rounded-2xl text-[11px] font-bold uppercase tracking-widest text-black/60 hover:bg-black/5 transition-colors">spentracker.live</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>

        <footer class="border-t border-white/5 py-16 text-center bg-black">
            <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-zinc-800">© 2025 ALL RIGHTS RESERVED. DEV
                CENTRIC STUDIOS.</p>
        </footer>
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
h4 {
    font-family: 'Poppins', sans-serif;
}

::selection {
    background: #2dd4bf;
    color: #000;
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
