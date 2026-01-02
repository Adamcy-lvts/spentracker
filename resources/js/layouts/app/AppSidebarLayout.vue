<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import MobileBottomNav from '@/components/MobileBottomNav.vue';
import QuickAddExpenseDialog from '@/components/QuickAddExpenseDialog.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItemType } from '@/types';
import { Plus } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';
import { useOfflineStorage } from '@/composables/useOfflineStorage';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const isQuickAddOpen = ref(false);
const { initDB, initNetworkSync, isInitialized } = useOfflineStorage();

const handleKeydown = (e: KeyboardEvent) => {
    // Ctrl+N or Cmd+N
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        isQuickAddOpen.value = true;
    }
};

onMounted(async () => {
    window.addEventListener('keydown', handleKeydown);
    
    // Initialize offline storage globally if not already done
    if (!isInitialized.value) {
        await initDB();
        initNetworkSync();
    }
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden relative mb-16 md:mb-0">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
            
            <!-- Quick Add FAB -->
            <div class="fixed bottom-20 md:bottom-6 right-6 z-50">
                <Button 
                    class="md:h-14 md:w-14 h-12 px-5 md:px-0 rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.3)] bg-white text-black hover:bg-gray-100 md:bg-gradient-to-r md:from-emerald-500 md:to-teal-500 md:hover:from-emerald-400 md:hover:to-teal-400 md:text-white transition-all duration-300 hover:scale-105 active:scale-95 border border-white/10 md:border-transparent flex items-center gap-2"
                    @click="isQuickAddOpen = true"
                >
                    <Plus class="h-5 w-5 md:h-6 md:w-6" />
                    <span class="md:hidden font-semibold text-sm">Add Expense</span>
                </Button>
            </div>
            
            <QuickAddExpenseDialog v-model:open="isQuickAddOpen" />
            
            <!-- Mobile Bottom Navigation -->
            <MobileBottomNav />
        </AppContent>
    </AppShell>
</template>
