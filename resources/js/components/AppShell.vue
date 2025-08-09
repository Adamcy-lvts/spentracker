<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';
import { Toaster } from '@/components/ui/sonner';
import 'vue-sonner/style.css'; // Required for vue-sonner v2

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;
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
</template>
