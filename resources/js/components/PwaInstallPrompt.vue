<script setup lang="ts">
import { usePwaInstall } from '@/composables/usePwaInstall';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const { showPrompt, showIosPrompt, requestInstall, hidePrompt } = usePwaInstall();

const handleInstall = async () => {
    const choice = await requestInstall();
    if (choice.outcome !== 'accepted') {
        hidePrompt();
    }
};
</script>

<template>
    <transition name="fade">
        <div
            v-if="showPrompt"
            class="fixed bottom-4 right-4 z-50 w-[320px] max-w-[calc(100vw-2rem)] rounded-2xl border border-border bg-background/95 p-4 shadow-2xl backdrop-blur sm:bottom-6 sm:right-6"
        >
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-border bg-muted/40">
                        <AppLogoIcon class="h-7 w-7 fill-current text-foreground" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-foreground">Install SpendTracker</p>
                        <p class="text-xs text-muted-foreground">Get the full app experience</p>
                    </div>
                </div>
                <button
                    type="button"
                    class="rounded-full border border-transparent p-1 text-muted-foreground transition hover:border-border hover:text-foreground"
                    @click="hidePrompt"
                    aria-label="Close"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-2 text-xs text-muted-foreground">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Launch from your home screen</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Faster loading and performance</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    </svg>
                    <span>Access your work offline</span>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between gap-2">
                <button
                    type="button"
                    class="rounded-lg border border-border px-3 py-2 text-xs font-semibold text-foreground transition hover:bg-muted"
                    @click="hidePrompt"
                >
                    Maybe later
                </button>
                <button
                    type="button"
                    class="flex items-center gap-2 rounded-lg bg-foreground px-4 py-2 text-xs font-semibold text-background transition hover:opacity-90"
                    @click="handleInstall"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l4-4m-4 4l-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                    </svg>
                    Install App
                </button>
            </div>
        </div>
    </transition>

    <transition name="fade">
        <div
            v-if="showIosPrompt"
            class="fixed bottom-4 right-4 z-50 w-[320px] max-w-[calc(100vw-2rem)] rounded-2xl border border-border bg-background/95 p-4 shadow-2xl backdrop-blur sm:bottom-6 sm:right-6"
        >
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl border border-border bg-muted/40">
                        <AppLogoIcon class="h-7 w-7 fill-current text-foreground" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-foreground">Install on iOS</p>
                        <p class="text-xs text-muted-foreground">Add to Home Screen</p>
                    </div>
                </div>
                <button
                    type="button"
                    class="rounded-full border border-transparent p-1 text-muted-foreground transition hover:border-border hover:text-foreground"
                    @click="hidePrompt"
                    aria-label="Close"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-2 text-xs text-muted-foreground">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-8-8v16" />
                    </svg>
                    <span>Tap Share, then “Add to Home Screen”</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Open quickly from your home screen</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-foreground/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    </svg>
                    <span>Use it even when offline</span>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <button
                    type="button"
                    class="rounded-lg border border-border px-3 py-2 text-xs font-semibold text-foreground transition hover:bg-muted"
                    @click="hidePrompt"
                >
                    Got it
                </button>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(8px);
}
</style>
