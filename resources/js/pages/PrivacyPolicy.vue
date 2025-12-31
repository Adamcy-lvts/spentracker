<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const updatedOn = 'December 31, 2025';
const isScrolled = ref(false);

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

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

const scrollTo = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
        window.scrollTo({
            top: element.offsetTop - 120,
            behavior: 'smooth'
        });
    }
};
</script>

<template>

    <Head title="Privacy Policy - Spent Tracker">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet" />
    </Head>

    <div class="elegant-base min-h-screen bg-[#fafafa] text-[#1a1a1a]">
        <!-- Minimal Header -->
        <header class="fixed top-0 z-50 w-full transition-all duration-300 border-b"
            :class="isScrolled ? 'bg-white/80 backdrop-blur-md py-4 border-slate-200 shadow-sm' : 'bg-transparent py-8 border-transparent'">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6">
                <Link :href="route('home')"
                    class="flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-slate-900">
                    <div class="h-6 w-6 rounded-md bg-slate-900 flex items-center justify-center">
                        <div class="h-1.5 w-1.5 rounded-full bg-white"></div>
                    </div>
                    Spent Tracker
                </Link>

                <div class="flex items-center gap-8">
                    <Link :href="route('home')"
                        class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition">Home</Link>
                    <Link v-if="!$page.props.auth.user" :href="route('login')"
                        class="rounded-full bg-slate-900 px-5 py-2 text-sm font-bold text-white transition hover:bg-slate-800">
                        Sign in
                    </Link>
                    <Link v-else :href="route('dashboard')"
                        class="text-sm font-semibold text-slate-500 hover:text-slate-900">Dashboard</Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 pt-40 pb-32">
            <!-- Hero -->
            <div class="mb-24 text-center sm:text-left">
                <p class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Legal Document</p>
                <h1 class="text-5xl font-extrabold tracking-tight text-slate-900 sm:text-7xl">
                    Privacy Policy
                </h1>
                <p class="mt-8 text-xl leading-relaxed text-slate-500 max-w-2xl">
                    We value your trust and are committed to protecting your personal information. This policy explains
                    our data practices in simple, transparent terms.
                </p>
                <div class="mt-10 flex items-center gap-4 text-sm text-slate-400">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Last updated {{ updatedOn }}
                    </span>
                    <span class="h-1 w-1 rounded-full bg-slate-200"></span>
                    <span>12 min read</span>
                </div>
            </div>

            <!-- Fast Nav -->
            <div class="mb-20 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-4">
                <button v-for="section in sections" :key="section.id" @click="scrollTo(section.id)"
                    class="rounded-xl border border-slate-200 bg-white p-4 text-left transition hover:border-slate-400 hover:shadow-md">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Section</p>
                    <p class="mt-1 text-xs font-bold text-slate-900">{{ section.label }}</p>
                </button>
            </div>

            <div class="prose prose-slate prose-lg max-w-none space-y-24">
                <!-- Intro -->
                <section class="border-l-4 border-slate-900 pl-8">
                    <p class="text-lg leading-relaxed text-slate-600">
                        SpentTracker (the <strong class="text-slate-900">"App"</strong>, <strong
                            class="text-slate-900">"we"</strong>, <strong class="text-slate-900">"our"</strong>, or
                        <strong class="text-slate-900">"us"</strong>) is a personal expense tracking application
                        available on Android and the web via <strong>spentracker.live</strong>.
                    </p>
                </section>

                <!-- 1. Collection -->
                <section id="information" class="group">
                    <h2
                        class="text-3xl font-bold tracking-tight text-slate-900 mb-8 flex items-center gap-4 transition group-hover:translate-x-2">
                        <span class="text-slate-200">01</span> Information We Collect
                    </h2>
                    <div class="grid gap-10 lg:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-6">Account Details
                            </h3>
                            <ul class="space-y-4 text-slate-600 list-none p-0">
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-900"></div>
                                    Full name and Email address
                                </li>
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-900"></div>
                                    Phone number (optional)
                                </li>
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-900"></div>
                                    Secure authentication credentials
                                </li>
                            </ul>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-6">Financial Data
                            </h3>
                            <p class="text-slate-600 text-sm mb-4">You voluntarily provide:</p>
                            <ul class="space-y-4 text-slate-600 list-none p-0">
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-900"></div>
                                    Expense amounts & categories
                                </li>
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-900"></div>
                                    Budget and financial summaries
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- 2. Usage -->
                <section id="usage" class="group">
                    <h2
                        class="text-3xl font-bold tracking-tight text-slate-900 mb-8 flex items-center gap-4 transition group-hover:translate-x-2">
                        <span class="text-slate-200">02</span> How We Use It
                    </h2>
                    <div class="rounded-2xl bg-slate-900 p-10 text-white">
                        <div class="grid gap-8 sm:grid-cols-2">
                            <ul class="space-y-4 list-none p-0">
                                <li class="flex items-start gap-3 opacity-90">
                                    <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Manage your account securely
                                </li>
                                <li class="flex items-start gap-3 opacity-90">
                                    <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Sync data across your devices
                                </li>
                            </ul>
                            <ul class="space-y-4 list-none p-0">
                                <li class="flex items-start gap-3 opacity-90">
                                    <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Improve app performance
                                </li>
                                <li class="flex items-start gap-3 opacity-90">
                                    <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Respond to support requests
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- 3. Storage -->
                <section id="storage" class="group">
                    <h2
                        class="text-3xl font-bold tracking-tight text-slate-900 mb-8 flex items-center gap-4 transition group-hover:translate-x-2">
                        <span class="text-slate-200">03</span> Data Storage
                    </h2>
                    <div class="flex flex-col sm:flex-row gap-10 items-center">
                        <div class="flex-1">
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Your data is securely hosted on high-performance cloud servers provided by
                                **DigitalOcean**. We use standard HTTPS encryption for all data in transit to ensure no
                                one can intercept your information.
                            </p>
                            <div
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                SSL/TLS Encrypted
                            </div>
                        </div>
                        <div
                            class="h-32 w-32 shrink-0 rounded-full border-4 border-slate-50 flex items-center justify-center bg-white shadow-xl">
                            <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                </section>

                <!-- 4. Sharing -->
                <section id="sharing" class="group">
                    <h2
                        class="text-3xl font-bold tracking-tight text-slate-900 mb-8 flex items-center gap-4 transition group-hover:translate-x-2">
                        <span class="text-slate-200">04</span> Zero Sales Policy
                    </h2>
                    <p class="text-slate-600 mb-8">
                        We respect your privacy. Spent Tracker does not sell, rent, or trade your personal data to third
                        parties for marketing purposes. Disclosure only occurs when legally required or to protect our
                        users.
                    </p>
                    <div class="p-6 rounded-2xl border border-slate-100 bg-slate-50 text-slate-500 text-sm italic">
                        "Your data is yours. We simply provide the tools to manage it."
                    </div>
                </section>

                <!-- Footer style sections -->
                <div class="grid gap-12 sm:grid-cols-2">
                    <section id="permissions" class="space-y-4">
                        <h3 class="text-xl font-bold text-slate-900">App Permissions</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            We request access to Internet (for sync), Location (optional for tagging), and Storage (for
                            exporting reports).
                        </p>
                    </section>
                    <section id="retention" class="space-y-4">
                        <h3 class="text-xl font-bold text-slate-900">Data Retention</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Data is kept while your account is active. You can request permanent deletion at any time
                            via settings.
                        </p>
                    </section>
                    <section id="security" class="space-y-4">
                        <h3 class="text-xl font-bold text-slate-900">Security Measures</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            We use industry-standard security protocols to prevent unauthorized access to our database.
                        </p>
                    </section>
                    <section id="contact" class="space-y-8">
                        <h3 class="text-xl font-bold text-slate-900">Contact Support</h3>
                        <div class="flex flex-col gap-3">
                            <a href="mailto:info@devcentricstudios.com"
                                class="flex items-center gap-3 text-sm font-bold text-slate-900 hover:underline">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                info@devcentricstudios.com
                            </a>
                            <a href="https://spentracker.live" target="_blank"
                                class="flex items-center gap-3 text-sm font-bold text-slate-900 hover:underline">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </div>
                                spentracker.live
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <footer class="border-t border-slate-100 py-20 bg-white">
            <div class="mx-auto max-w-5xl px-6 flex flex-col sm:flex-row justify-between items-center gap-8">
                <div class="text-center sm:text-left">
                    <p class="text-sm font-bold text-slate-900 uppercase tracking-widest">Spent Tracker</p>
                    <p class="mt-2 text-xs text-slate-400">© 2025 Dev Centric Studios. All rights reserved.</p>
                </div>
                <div class="flex gap-8">
                    <Link :href="route('home')"
                        class="text-xs font-bold text-slate-400 hover:text-slate-900 transition">Terms</Link>
                    <Link :href="route('privacy-policy')" class="text-xs font-bold text-slate-900 transition">Privacy
                    </Link>
                    <a href="mailto:info@devcentricstudios.com"
                        class="text-xs font-bold text-slate-400 hover:text-slate-900 transition">Help</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.elegant-base {
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    -webkit-font-smoothing: antialiased;
    scroll-behavior: smooth;
}

/* Custom selection */
::selection {
    background: #1a1a1a;
    color: white;
}

/* Responsive fixes */
@media (max-width: 640px) {
    h1 {
        line-height: 1.1;
    }
}

/* Transitions */
.group:hover h2 {
    color: #000;
}
</style>
