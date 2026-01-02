<script setup lang="ts">
import { computed } from 'vue';
import type { BudgetStatus } from '@/types/budget';
interface Props {
    percentageUsed: number;
    status: BudgetStatus;
    showLabel?: boolean;
}
const props = withDefaults(defineProps<Props>(), {
    showLabel: true,
});
const progressWidth = computed(() => Math.min(props.percentageUsed, 100));
const progressColor = computed(() => {
    const colors = {
        safe: 'bg-green-500',
        warning: 'bg-yellow-500',
        critical: 'bg-orange-500',
        over_budget: 'bg-red-500',
    };
    return colors[props.status] || 'bg-gray-500';
});
</script>
<template>
    <div class="w-full">
        <div class="flex justify-between mb-1" v-if="showLabel">
            <span class="text-sm text-gray-400">{{ percentageUsed }}% used</span>
            <span v-if="status === 'over_budget'" class="text-sm text-red-400">Over Budget!</span>
        </div>
        <div class="w-full h-2 bg-gray-700 rounded-full overflow-hidden">
            <div :class="progressColor" :style="{ width: `${progressWidth}%` }"
                class="h-full transition-all duration-300"></div>
        </div>
    </div>
</template>
