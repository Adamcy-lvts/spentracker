<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

const updatedOn = 'December 31, 2025';
</script>

<template>
    <Head title="Privacy Policy">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=fraunces:400,600,700|space-grotesk:400,500,600"
            rel="stylesheet"
        />
    </Head>

    <div class="privacy-theme relative min-h-screen overflow-hidden px-6 pb-16 pt-10 sm:px-10">
        <div
            class="pointer-events-none absolute -left-28 top-[-10rem] h-[28rem] w-[28rem] rounded-full bg-[radial-gradient(circle_at_center,_rgba(15,118,110,0.4),_rgba(15,118,110,0))] blur-3xl"
        ></div>
        <div
            class="pointer-events-none absolute -right-20 top-[8rem] h-[22rem] w-[22rem] rounded-full bg-[radial-gradient(circle_at_center,_rgba(251,146,60,0.35),_rgba(251,146,60,0))] blur-3xl"
        ></div>

        <header class="fade-up mx-auto flex w-full max-w-6xl items-center justify-between gap-4">
            <Link
                :href="route('home')"
                class="flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.2em]"
            >
                <span class="h-2 w-2 rounded-full bg-[var(--privacy-accent)]"></span>
                {{ $page.props.name || 'Spent Tracker' }}
            </Link>
            <div class="flex items-center gap-3 text-sm">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded-full border border-[var(--privacy-border)] px-4 py-2 transition hover:border-[var(--privacy-accent)] hover:text-[var(--privacy-accent)]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded-full border border-[var(--privacy-border)] px-4 py-2 transition hover:border-[var(--privacy-accent)] hover:text-[var(--privacy-accent)]"
                    >
                        Sign in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="rounded-full bg-[var(--privacy-accent)] px-4 py-2 font-semibold text-white shadow-[0_18px_40px_rgba(15,118,110,0.35)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_45px_rgba(15,118,110,0.45)]"
                    >
                        Create account
                    </Link>
                </template>
            </div>
        </header>

        <main class="mx-auto mt-14 flex w-full max-w-6xl flex-col gap-10">
            <section class="fade-up delay-1">
                <div class="inline-flex items-center gap-3 rounded-full border border-[var(--privacy-border)] bg-white/70 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[var(--privacy-muted)]">
                    Effective date
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--privacy-accent)]"></span>
                    {{ updatedOn }}
                </div>
                <h1 class="privacy-title mt-6 text-4xl sm:text-5xl lg:text-6xl">
                    Privacy Policy for SpentTracker
                </h1>
                <p class="mt-4 max-w-2xl text-base text-[var(--privacy-muted)] sm:text-lg">
                    SpentTracker (the App, we, our, or us) is a personal expense tracking application available on Android and the web.
                    This Privacy Policy explains how we collect, use, store, and protect your information when you use SpentTracker.
                </p>
                <p class="mt-4 max-w-2xl text-base text-[var(--privacy-muted)] sm:text-lg">
                    By using SpentTracker, you agree to the practices described in this Privacy Policy.
                </p>
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="privacy-card fade-up delay-2">
                    <h2 class="privacy-title text-2xl">1. Information We Collect</h2>
                    <p class="mt-2 text-sm text-[var(--privacy-muted)]">a. Account Information</p>
                    <ul class="mt-6 space-y-4 text-sm">
                        <li>Full name</li>
                        <li>Email address</li>
                        <li>Phone number</li>
                        <li>Secure authentication credentials</li>
                    </ul>
                    <p class="mt-6 text-sm text-[var(--privacy-muted)]">
                        This information is required to create and manage your account.
                    </p>
                </div>
                <aside class="privacy-card fade-up delay-3">
                    <h2 class="privacy-title text-2xl">b. Financial and Expense Data</h2>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">
                        Users may voluntarily enter:
                    </p>
                    <ul class="mt-6 space-y-3 text-sm">
                        <li>Expense amounts</li>
                        <li>Categories</li>
                        <li>Notes or descriptions</li>
                        <li>Dates of transactions</li>
                        <li>Budget and financial summaries</li>
                    </ul>
                    <p class="mt-6 text-sm text-[var(--privacy-muted)]">
                        We do not collect bank account numbers, card details, or PINs.
                    </p>
                </aside>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="privacy-card fade-up delay-2">
                    <h3 class="privacy-title text-xl">c. Location Information</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">
                        With your permission, SpentTracker may collect location data to support app features such as tagging expenses
                        by location, providing location-based spending insights, and improving accuracy of expense records.
                    </p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Location data is collected only when permission is granted.</li>
                        <li>Location access is optional.</li>
                        <li>Location data is not collected in the background unless required by an enabled feature.</li>
                        <li>Location data is not shared or sold.</li>
                    </ul>
                    <p class="mt-4 text-sm text-[var(--privacy-muted)]">
                        Depending on your device settings, this may include approximate or precise location.
                    </p>
                </div>
                <div class="privacy-card fade-up delay-3">
                    <h3 class="privacy-title text-xl">d. Device and Technical Information</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">We may collect limited technical data such as:</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Device model</li>
                        <li>Operating system version</li>
                        <li>App version</li>
                        <li>IP address</li>
                        <li>Crash and performance logs</li>
                    </ul>
                    <p class="mt-4 text-sm text-[var(--privacy-muted)]">
                        This data is used solely to improve reliability, security, and performance.
                    </p>
                </div>
                <div class="privacy-card fade-up delay-4">
                    <h3 class="privacy-title text-xl">2. How We Use Your Information</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Create and manage user accounts</li>
                        <li>Synchronize expense data across devices</li>
                        <li>Provide mobile and web access</li>
                        <li>Enable location-based expense features (if enabled)</li>
                        <li>Improve functionality and user experience</li>
                        <li>Prevent fraud and unauthorized access</li>
                        <li>Respond to support requests</li>
                    </ul>
                    <p class="mt-4 text-sm text-[var(--privacy-muted)]">We do not sell or rent user data.</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="privacy-card fade-up delay-3">
                    <h3 class="privacy-title text-xl">3. Data Storage and Hosting</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>User data is securely stored on cloud servers hosted by DigitalOcean.</li>
                        <li>Servers may be located outside your country of residence.</li>
                        <li>All data transmission uses secure encryption (HTTPS).</li>
                        <li>DigitalOcean acts solely as a data processor.</li>
                    </ul>
                </div>
                <div class="privacy-card fade-up delay-4">
                    <h3 class="privacy-title text-xl">4. Data Sharing and Disclosure</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">
                        We do not share user data with third parties, except:
                    </p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>When required by law</li>
                        <li>To comply with legal obligations</li>
                        <li>To protect the safety, rights, or security of users or the service</li>
                    </ul>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="privacy-card fade-up delay-2">
                    <h3 class="privacy-title text-xl">5. Permissions Used</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">SpentTracker may request:</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Internet access for login and cloud synchronization</li>
                        <li>Location access for optional location-based expense features</li>
                        <li>Storage access for exporting reports or backups</li>
                    </ul>
                    <p class="mt-4 text-sm text-[var(--privacy-muted)]">
                        Permissions are used only for their stated purpose.
                    </p>
                </div>
                <div class="privacy-card fade-up delay-3">
                    <h3 class="privacy-title text-xl">6. Third-Party Services</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">We may use:</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Google Play Services (distribution)</li>
                        <li>Crash reporting or analytics tools (if enabled)</li>
                    </ul>
                    <p class="mt-4 text-sm text-[var(--privacy-muted)]">
                        These services process data according to their own privacy policies.
                    </p>
                </div>
                <div class="privacy-card fade-up delay-4">
                    <h3 class="privacy-title text-xl">7. Data Retention</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Data is retained while your account remains active.</li>
                        <li>Users may request account deletion at any time.</li>
                        <li>
                            Upon deletion, personal, financial, and location data are permanently removed within a reasonable
                            timeframe.
                        </li>
                    </ul>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="privacy-card fade-up delay-3">
                    <h3 class="privacy-title text-xl">8. User Rights</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>Access and update personal data</li>
                        <li>Disable location permission at any time via device settings</li>
                        <li>Request account and data deletion</li>
                        <li>Stop all data collection by uninstalling the App</li>
                    </ul>
                </div>
                <div class="privacy-card fade-up delay-4">
                    <h3 class="privacy-title text-xl">9. Children&apos;s Privacy</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">
                        SpentTracker is not intended for children under 13. We do not knowingly collect data from children.
                    </p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="privacy-card fade-up delay-3">
                    <h3 class="privacy-title text-xl">10. Security</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">
                        We use reasonable technical and organizational safeguards, including encrypted communication, secure
                        authentication, and restricted server access. No system is completely secure; users should protect their
                        login credentials.
                    </p>
                </div>
                <div class="privacy-card fade-up delay-4">
                    <h3 class="privacy-title text-xl">11. Changes to This Privacy Policy</h3>
                    <p class="mt-3 text-sm text-[var(--privacy-muted)]">
                        We may update this Privacy Policy periodically. Changes will be posted here with a revised date.
                    </p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="privacy-card fade-up delay-3">
                    <h3 class="privacy-title text-xl">12. Contact Us</h3>
                    <div class="mt-6 space-y-4 text-sm">
                        <div>
                            <p class="font-semibold">Email</p>
                            <a class="text-[var(--privacy-accent)] underline underline-offset-4" href="mailto:support@spenttracker.app">
                                support@spenttracker.app
                            </a>
                        </div>
                        <div>
                            <p class="font-semibold">Website</p>
                            <a
                                class="text-[var(--privacy-accent)] underline underline-offset-4"
                                href="https://spenttracker.app"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                https://spenttracker.app
                            </a>
                        </div>
                    </div>
                </div>
                <div class="privacy-card fade-up delay-4">
                    <h3 class="privacy-title text-xl">Summary</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>We do not sell or rent user data.</li>
                        <li>Location access is optional and controlled by you.</li>
                        <li>Data is retained while your account is active.</li>
                        <li>Requests for deletion are honored within a reasonable timeframe.</li>
                    </ul>
                </div>
            </section>
        </main>
    </div>
</template>

<style scoped>
.privacy-theme {
    --privacy-ink: #1f1d1a;
    --privacy-muted: #5d594f;
    --privacy-accent: #0f766e;
    --privacy-border: rgba(31, 29, 26, 0.15);
    --privacy-surface: rgba(255, 255, 255, 0.75);
    background: linear-gradient(135deg, #f7f2ea 0%, #fdf9f4 45%, #f7f7f2 100%);
    color: var(--privacy-ink);
    font-family: 'Space Grotesk', 'Instrument Sans', system-ui, sans-serif;
}

.privacy-title {
    font-family: 'Fraunces', 'Times New Roman', serif;
    letter-spacing: -0.02em;
}

.privacy-card {
    border-radius: 1.75rem;
    border: 1px solid var(--privacy-border);
    background: var(--privacy-surface);
    padding: 2rem;
    box-shadow: 0 25px 60px rgba(15, 23, 42, 0.08);
    backdrop-filter: blur(14px);
}

.fade-up {
    animation: fade-up 0.7s ease both;
}

.delay-1 {
    animation-delay: 120ms;
}

.delay-2 {
    animation-delay: 220ms;
}

.delay-3 {
    animation-delay: 320ms;
}

.delay-4 {
    animation-delay: 420ms;
}

@keyframes fade-up {
    from {
        opacity: 0;
        transform: translateY(14px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (prefers-reduced-motion: reduce) {
    .fade-up {
        animation: none;
    }
}
</style>
