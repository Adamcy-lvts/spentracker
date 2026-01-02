<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';
import { Toaster } from '@/components/ui/sonner';
import OfflineIndicator from '@/components/OfflineIndicator.vue';
import PwaInstallPrompt from '@/components/PwaInstallPrompt.vue';
import 'vue-sonner/style.css'; // Required for vue-sonner v2

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;

// Handle sync requests from the offline indicator
const handleSyncRequest = () => {
    console.log('🔄 Manual sync requested by user')
    // We'll implement the actual sync logic in the next step
    window.dispatchEvent(new CustomEvent('app:sync-requested'))
}
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <slot />
    </div>
    <SidebarProvider v-else :default-open="isOpen">
        <slot />
    </SidebarProvider>
    
    <!-- Global Toast Container -->
    <Toaster class="pointer-events-auto" position="top-right" />
    
    <!-- Offline Status Indicator -->
    <OfflineIndicator @sync-requested="handleSyncRequest" />

    <!-- Custom PWA Install Prompt -->
    <PwaInstallPrompt />
</template>
