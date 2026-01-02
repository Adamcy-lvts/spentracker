<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Folder, Tag, Wallet, Target } from 'lucide-vue-next';
import { computed } from 'vue';
import { type User } from '@/types';

const page = usePage();
const user = computed(() => page.props.auth.user as User);
const currentUrl = computed(() => page.url);

const navItems = computed(() => {
    return [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
            active: currentUrl.value.startsWith('/dashboard')
        },
        {
            title: 'Expenses',
            href: '/expense',
            icon: Folder,
            active: currentUrl.value.startsWith('/expense')
        },
        {
            title: 'Income',
            href: '/income',
            icon: Wallet,
            active: currentUrl.value.startsWith('/income')
        },
        {
            title: 'Budget',
            href: '/budget',
            icon: Target,
            active: currentUrl.value.startsWith('/budget')
        },
        {
            title: 'Categories',
            href: '/categories',
            icon: Tag,
            active: currentUrl.value.startsWith('/categories')
        }
    ];
});
</script>

<template>
    <div
        class="fixed bottom-0 left-0 right-0 z-40 bg-background/80 backdrop-blur-xl border-t border-border/50 md:hidden pb-safe">
        <div class="flex items-center justify-around h-16">
            <Link v-for="item in navItems" :key="item.href" :href="item.href"
                class="flex flex-col items-center justify-center w-full h-full gap-1 transition-colors relative"
                :class="item.active ? 'text-primary' : 'text-muted-foreground hover:text-foreground'">
                <!-- Active Indicator Pill -->
                <div v-if="item.active"
                    class="absolute -top-[1px] w-12 h-1 bg-primary rounded-b-full shadow-[0_2px_10px] shadow-primary/50">
                </div>

                <component :is="item.icon" class="h-5 w-5 transition-transform duration-300"
                    :class="item.active ? 'scale-110' : ''" />
                <span class="text-[10px] font-medium">{{ item.title }}</span>
            </Link>
        </div>
    </div>
</template>

<style scoped>
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
