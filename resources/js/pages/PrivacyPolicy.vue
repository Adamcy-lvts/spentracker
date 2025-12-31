<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const updatedOn = 'December 31, 2025';

const sections = [
    { id: 'information', label: '1. Collection' },
    { id: 'usage', label: '2. Usage' },
    { id: 'storage', label: '3. Storage' },
    { id: 'sharing', label: '4. Sharing' },
    { id: 'permissions', label: '5. Permissions' },
    { id: 'third-party', label: '6. Partners' },
    { id: 'retention', label: '7. Retention' },
    { id: 'rights', label: '8. Rights' },
    { id: 'security', label: '10. Security' },
    { id: 'contact', label: '12. Contact' },
];

const activeSection = ref('');
const scrollY = ref(0);

const handleScroll = () => {
    scrollY.value = window.scrollY;

    const sections = ['information', 'usage', 'storage', 'sharing', 'permissions', 'third-party', 'retention', 'rights', 'children', 'security', 'changes', 'contact'];

    for (const section of sections) {
        const element = document.getElementById(section);
        if (element) {
            const rect = element.getBoundingClientRect();
            if (rect.top <= 100 && rect.bottom >= 100) {
                activeSection.value = section;
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

    <Head title="Privacy Policy">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=fraunces:400,600,700,900|space-grotesk:300,400,500,600,700"
            rel="stylesheet" />
    </Head>

    <div class="privacy-wrap min-h-screen">
        <!-- Animated Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden bg-[#030712]">
            <div class="aura-1 absolute -left-[10%] -top-[10%] h-[60%] w-[60%] rounded-full opacity-40 blur-[120px]">
            </div>
            <div class="aura-2 absolute -right-[10%] top-[20%] h-[50%] w-[50%] rounded-full opacity-30 blur-[120px]">
            </div>
            <div class="aura-3 absolute bottom-[-10%] left-[20%] h-[40%] w-[40%] rounded-full opacity-20 blur-[120px]">
            </div>
            <div class="noise-overlay absolute inset-0 opacity-[0.03]"></div>
        </div>

        <!-- Navigation Progress -->
        <div class="fixed left-0 top-0 h-1 bg-gradient-to-r from-teal-500 to-orange-400 transition-all duration-300 z-50"
            :style="{ width: `${(scrollY / (typeof document !== 'undefined' ? document.documentElement.scrollHeight - window.innerHeight : 1)) * 100}%` }">
        </div>

        <header class="sticky top-0 z-40 w-full border-b border-white/5 bg-[#030712]/80 backdrop-blur-xl">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 sm:px-10">
                <Link :href="route('home')"
                    class="group flex items-center gap-3 text-sm font-bold uppercase tracking-[0.3em] text-white/90 transition hover:text-white">
                    <div
                        class="relative flex h-8 w-8 items-center justify-center rounded-lg bg-teal-500/20 group-hover:bg-teal-500/30 transition-colors">
                        <div class="h-2 w-2 rounded-full bg-teal-400 shadow-[0_0_12px_rgba(45,212,191,0.8)]"></div>
                    </div>
                    {{ $page.props.name || 'Spent Tracker' }}
                </Link>

                <div class="flex items-center gap-4 sm:gap-6">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="hidden text-sm font-medium text-white/60 transition hover:text-white sm:block">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="text-sm font-medium text-white/60 transition hover:text-white">
                            Sign in
                        </Link>
                        <Link :href="route('register')"
                            class="rounded-full bg-white px-5 py-2.5 text-sm font-bold text-black transition hover:bg-teal-400 hover:text-black hover:shadow-[0_0_20px_rgba(45,212,191,0.4)]">
                            Join Now
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="mx-auto flex max-w-7xl flex-col gap-12 px-6 py-16 sm:px-10 lg:flex-row lg:py-24">
            <!-- Sidebar Navigation -->
            <aside class="lg:w-72">
                <div class="sticky top-32 hidden space-y-8 lg:block">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-teal-400/80">Navigation</h3>
                        <nav class="mt-6 flex flex-col gap-3">
                            <button v-for="link in sections" :key="link.id" @click="scrollTo(link.id)"
                                class="group flex items-center gap-3 text-left transition-all duration-300"
                                :class="activeSection === link.id ? 'text-white translate-x-1' : 'text-white/40 hover:text-white/70'">
                                <span class="h-px w-4 bg-current transition-all group-hover:w-8"
                                    :class="activeSection === link.id ? 'w-8 bg-teal-400' : ''"></span>
                                <span class="text-sm font-medium">{{ link.label }}</span>
                            </button>
                        </nav>
                    </div>

                    <div class="rounded-2xl border border-white/5 bg-white/[0.02] p-6 backdrop-blur-md">
                        <p class="text-xs font-medium text-white/40">Last Updated</p>
                        <p class="mt-1 text-sm font-semibold text-white/90">{{ updatedOn }}</p>
                    </div>
                </div>
            </aside>

            <!-- Content Area -->
            <div class="flex-1 space-y-16">
                <!-- Hero Section -->
                <section class="reveal-content max-w-3xl">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-teal-500/20 bg-teal-500/5 px-4 py-1.5 text-[10px] font-bold uppercase tracking-[0.2em] text-teal-400/90">
                        <span class="flex h-1.5 w-1.5 animate-pulse rounded-full bg-teal-400"></span>
                        Privacy & Transparency
                    </div>
                    <h1 class="font-fraunces mt-8 text-5xl font-black leading-[1.1] text-white sm:text-7xl lg:text-8xl">
                        Privacy <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-orange-400">Policy.</span>
                    </h1>
                    <div class="mt-10 flex flex-col gap-6 text-lg leading-relaxed text-white/60 sm:text-xl">
                        <p>
                            SpentTracker (the <strong
                                class="text-white/90 font-medium whitespace-nowrap">"App"</strong>,
                            <strong class="text-white/90 font-medium">"we"</strong>, <strong
                                class="text-white/90 font-medium">"our"</strong>, or <strong
                                class="text-white/90 font-medium">"us"</strong>) is a personal expense tracking
                            application
                            available on Android and the web.
                        </p>
                        <p>
                            This Privacy Policy explains how we collect, use, store, and protect your information when
                            you use
                            SpentTracker. By using SpentTracker, you agree to the practices described in this Privacy
                            Policy.
                        </p>
                    </div>
                </section>

                <div class="grid gap-10">
                    <!-- 1. Information -->
                    <section id="information" class="group relative">
                        <div
                            class="absolute -inset-px rounded-3xl bg-gradient-to-br from-white/10 to-transparent opacity-0 transition group-hover:opacity-100">
                        </div>
                        <div
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-12">
                            <div class="flex items-start justify-between gap-4">
                                <h2 class="font-fraunces text-3xl font-bold text-white">1. Information We Collect</h2>
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-500/10 text-teal-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="mt-10 grid gap-10 lg:grid-cols-2">
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-widest text-teal-400/80">a. Account
                                        Information</h3>
                                    <ul class="mt-6 space-y-4">
                                        <li v-for="item in ['Full name', 'Email address', 'Phone number', 'Secure authentication credentials']"
                                            :key="item" class="flex items-center gap-3 text-white/70">
                                            <span class="h-1 w-1 rounded-full bg-indigo-400"></span>
                                            {{ item }}
                                        </li>
                                    </ul>
                                    <p class="mt-6 text-sm italic text-white/40">This information is required to create
                                        and
                                        manage your account.</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-widest text-orange-400/80">b.
                                        Financial and
                                        Expense Data</h3>
                                    <p class="mt-4 text-sm text-white/60">Users may voluntarily enter:</p>
                                    <ul class="mt-6 space-y-4">
                                        <li v-for="item in ['Expense amounts', 'Categories', 'Notes or descriptions', 'Dates of transactions', 'Budget and financial summaries']"
                                            :key="item" class="flex items-center gap-3 text-white/70">
                                            <span class="h-1 w-1 rounded-full bg-orange-400"></span>
                                            {{ item }}
                                        </li>
                                    </ul>
                                    <p class="mt-6 text-xs text-orange-400/60 font-medium">We do not collect bank
                                        account
                                        numbers, card details, or PINs.</p>
                                </div>
                            </div>

                            <div class="mt-16 grid gap-10 lg:grid-cols-2">
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-widest text-teal-400/80">c. Location
                                        Information</h3>
                                    <div class="mt-6 space-y-4 text-white/70">
                                        <p>With your permission, SpentTracker may collect location data to support app
                                            features
                                            such as tagging expenses by location, providing location-based spending
                                            insights,
                                            and improving accuracy of expense records.</p>
                                        <ul class="space-y-3 pl-4">
                                            <li class="list-disc text-sm text-white/60">Location data is collected only
                                                when
                                                permission is granted.</li>
                                            <li class="list-disc text-sm text-white/60">Location access is optional.
                                            </li>
                                            <li class="list-disc text-sm text-white/60">Location data is not collected
                                                in the
                                                background unless required by an enabled feature.</li>
                                            <li class="list-disc text-sm text-white/60">Location data is not shared or
                                                sold.
                                            </li>
                                        </ul>
                                        <p class="text-sm italic text-white/40">Depending on your device settings, this
                                            may
                                            include approximate or precise location.</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-widest text-teal-400/80">d. Device
                                        and
                                        Technical Information</h3>
                                    <p class="mt-6 text-sm text-white/60">We may collect limited technical data such as:
                                    </p>
                                    <ul class="mt-6 space-y-4">
                                        <li v-for="item in ['Device model', 'Operating system version', 'App version', 'IP address', 'Crash and performance logs']"
                                            :key="item" class="flex items-center gap-3 text-white/70">
                                            <span class="h-1 w-1 rounded-full bg-indigo-400"></span>
                                            {{ item }}
                                        </li>
                                    </ul>
                                    <p class="mt-6 text-sm italic text-white/40">This data is used solely to improve
                                        reliability, security, and performance.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 2. Use -->
                    <section id="usage" class="group relative">
                        <div
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-12">
                            <h2 class="font-fraunces text-3xl font-bold text-white">2. How We Use Your Information</h2>
                            <div class="mt-10 grid gap-8 sm:grid-cols-2">
                                <ul class="space-y-4">
                                    <li v-for="item in ['Create and manage user accounts', 'Synchronize expense data across devices', 'Provide mobile and web access', 'Enable location-based features']"
                                        :key="item" class="flex items-start gap-4 text-white/70">
                                        <div class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-teal-400"></div>
                                        <span>{{ item }}</span>
                                    </li>
                                </ul>
                                <ul class="space-y-4">
                                    <li v-for="item in ['Improve functionality and experience', 'Prevent fraud and unauthorized access', 'Respond to support requests']"
                                        :key="item" class="flex items-start gap-4 text-white/70">
                                        <div class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-teal-400"></div>
                                        <span>{{ item }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div
                                class="mt-10 inline-flex items-center gap-2 rounded-xl bg-orange-400/10 px-4 py-2 text-sm font-semibold text-orange-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                We do not sell or rent user data.
                            </div>
                        </div>
                    </section>

                    <!-- Row: Storage & Sharing -->
                    <div class="grid gap-10 lg:grid-cols-2">
                        <section id="storage"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">3. Data Storage and Hosting</h2>
                            <ul class="mt-8 space-y-4 text-sm text-white/60">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    User data is securely stored on cloud servers hosted by DigitalOcean.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    Servers may be located outside your country of residence.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    All data transmission uses secure encryption (HTTPS).
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    DigitalOcean acts solely as a data processor.
                                </li>
                            </ul>
                        </section>

                        <section id="sharing"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">4. Data Sharing and Disclosure</h2>
                            <p class="mt-6 text-sm text-white/60">We do not share user data with third parties, except:
                            </p>
                            <ul class="mt-6 space-y-4 text-sm text-white/60">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-orange-400"></span>
                                    When required by law
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-orange-400"></span>
                                    To comply with legal obligations
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-orange-400"></span>
                                    To protect the safety, rights, or security of users or the service
                                </li>
                            </ul>
                        </section>
                    </div>

                    <!-- Row: Permissions & Services -->
                    <div class="grid gap-10 lg:grid-cols-2">
                        <section id="permissions"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">5. Permissions Used</h2>
                            <p class="mt-6 text-sm text-white/60">SpentTracker may request:</p>
                            <ul class="mt-6 space-y-4 text-sm text-white/60">
                                <li class="flex items-start gap-4">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 text-xs font-bold text-white/40">
                                        1</div>
                                    Internet access for login and cloud synchronization
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 text-xs font-bold text-white/40">
                                        2</div>
                                    Location access for optional location-based expense features
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 text-xs font-bold text-white/40">
                                        3</div>
                                    Storage access for exporting reports or backups
                                </li>
                            </ul>
                            <p class="mt-6 text-xs italic text-white/30">Permissions are used only for their stated
                                purpose.</p>
                        </section>

                        <section id="third-party"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">6. Third-Party Services</h2>
                            <p class="mt-6 text-sm text-white/60">We may use:</p>
                            <ul class="mt-6 space-y-4 text-sm text-white/60 text-white/70">
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-indigo-400"></div>
                                    Google Play Services (distribution)
                                </li>
                                <li class="flex items-center gap-3">
                                    <div class="h-1.5 w-1.5 rounded-full bg-indigo-400"></div>
                                    Crash reporting or analytics tools (if enabled)
                                </li>
                            </ul>
                            <p class="mt-8 text-xs leading-relaxed text-white/40">
                                These services process data according to their own privacy policies.
                            </p>
                        </section>
                    </div>

                    <!-- Row: Retention & Rights -->
                    <div class="grid gap-10 lg:grid-cols-2">
                        <section id="retention"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">7. Data Retention</h2>
                            <ul class="mt-8 space-y-4 text-sm text-white/60">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    Data is retained while your account remains active.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    Users may request account deletion at any time.
                                </li>
                                <li class="flex items-start gap-3 font-medium text-white/80">
                                    <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-teal-400"></span>
                                    Upon deletion, personal, financial, and location data are permanently removed within
                                    a
                                    reasonable timeframe.
                                </li>
                            </ul>
                        </section>

                        <section id="rights"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">8. User Rights</h2>
                            <div class="mt-8 grid grid-cols-1 gap-4">
                                <div v-for="right in ['Access and update personal data', 'Disable location permission via settings', 'Request account and data deletion', 'Stop collection by uninstalling']"
                                    :key="right"
                                    class="rounded-xl bg-white/[0.02] border border-white/5 p-4 text-sm text-white/60 transition hover:bg-white/[0.04]">
                                    {{ right }}
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Row: Children & Security -->
                    <div class="grid gap-10 lg:grid-cols-2">
                        <section id="children"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">9. Children's Privacy</h2>
                            <p class="mt-6 text-sm leading-relaxed text-white/60">
                                SpentTracker is not intended for children under 13. We do not knowingly collect data
                                from
                                children.
                            </p>
                        </section>

                        <section id="security"
                            class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-10">
                            <h2 class="font-fraunces text-2xl font-bold text-white">10. Security</h2>
                            <p class="mt-6 text-sm leading-relaxed text-white/60">
                                We use reasonable technical and organizational safeguards, including encrypted
                                communication,
                                secure authentication, and restricted server access. No system is completely secure;
                                users
                                should protect their login credentials.
                            </p>
                        </section>
                    </div>

                    <!-- Bottom Section -->
                    <section id="changes"
                        class="relative rounded-3xl border border-white/5 bg-white/[0.03] p-8 transition-all hover:bg-white/[0.05] sm:p-12">
                        <div class="max-w-3xl">
                            <h2 class="font-fraunces text-3xl font-bold text-white">11. Changes to This Privacy Policy
                            </h2>
                            <p class="mt-6 text-lg text-white/60">
                                We may update this Privacy Policy periodically. Changes will be posted here with a
                                revised date.
                            </p>
                        </div>
                    </section>

                    <section id="contact"
                        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-500/20 to-indigo-500/20 p-8 sm:p-12 lg:p-16">
                        <div
                            class="relative z-10 flex flex-col items-center gap-8 text-center lg:flex-row lg:text-left">
                            <div class="flex-1">
                                <h2 class="font-fraunces text-4xl font-black text-white">12. Contact Us</h2>
                                <p class="mt-4 text-lg text-white/70">Have questions about your privacy or data?</p>
                            </div>
                            <div class="flex flex-col gap-4 sm:flex-row">
                                <a href="mailto:support@spenttracker.app"
                                    class="flex items-center gap-3 rounded-2xl bg-white px-8 py-4 font-bold text-black transition hover:scale-105 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    support@spenttracker.app
                                </a>
                                <a href="https://spenttracker.app" target="_blank"
                                    class="flex items-center gap-3 rounded-2xl border border-white/20 bg-white/5 px-8 py-4 font-bold text-white backdrop-blur-md transition hover:bg-white/10 active:scale-95">
                                    Visit Website
                                </a>
                            </div>
                        </div>
                    </section>

                    <!-- Summary -->
                    <section class="rounded-3xl border border-white/5 bg-white/[0.01] p-8 text-center sm:p-12">
                        <h3 class="font-fraunces text-2xl font-bold text-white/80">Simple Summary</h3>
                        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <div v-for="s in ['No selling data', 'Optional location', 'Active retention', 'Honored deletion']"
                                :key="s" class="flex flex-col items-center gap-3">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-500/10 text-teal-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-white/40">{{ s }}</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <footer class="border-t border-white/5 py-12 text-center">
            <p class="text-sm font-medium text-white/30 uppercase tracking-[0.2em]">
                &copy; {{ new Date().getFullYear() }} {{ $page.props.name || 'Spent Tracker' }}. All rights reserved.
            </p>
        </footer>
    </div>
</template>

<style scoped>
.privacy-wrap {
    color: #f9fafb;
    font-family: 'Space Grotesk', system-ui, sans-serif;
    selection-background-color: rgba(45, 212, 191, 0.3);
}

.font-fraunces {
    font-family: 'Fraunces', serif;
    letter-spacing: -0.01em;
}

.aura-1 {
    background: radial-gradient(circle at center, #0f766e 0%, transparent 70%);
}

.aura-2 {
    background: radial-gradient(circle at center, #ea580c 0%, transparent 70%);
}

.aura-3 {
    background: radial-gradient(circle at center, #4f46e5 0%, transparent 70%);
}

.noise-overlay {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3Base-filter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/feTurbulence%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}

.reveal-content {
    animation: reveal 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #030712;
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(45, 212, 191, 0.3);
}

@media (max-width: 1024px) {
    .font-fraunces {
        letter-spacing: -0.03em;
    }
}
</style>
