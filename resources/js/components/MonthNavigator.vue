<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight, Calendar } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    selectedMonth: string; // Format: YYYY-MM
}>();

const emit = defineEmits(['previous', 'next', 'select']);

const formattedMonth = computed(() => {
    const [year, month] = props.selectedMonth.split('-');
    const date = new Date(parseInt(year), parseInt(month) - 1);
    return date.toLocaleDateString('default', { month: 'long', year: 'numeric' });
});
</script>

<template>
    <div class="flex items-center gap-2 bg-card/50 backdrop-blur-sm border border-border/50 rounded-xl p-1 shadow-sm">
        <Button variant="ghost" size="icon" @click="emit('previous')"
            class="h-8 w-8 text-muted-foreground hover:text-foreground">
            <ChevronLeft class="h-4 w-4" />
        </Button>

        <div class="flex items-center gap-2 px-3 py-1 text-sm font-semibold select-none min-w-[140px] justify-center">
            <Calendar class="h-3.5 w-3.5 text-primary" />
            <span>{{ formattedMonth }}</span>
        </div>

        <Button variant="ghost" size="icon" @click="emit('next')"
            class="h-8 w-8 text-muted-foreground hover:text-foreground">
            <ChevronRight class="h-4 w-4" />
        </Button>
    </div>
</template>
