<script setup lang="ts">
import { computed } from 'vue';
import BudgetProgressBar from './BudgetProgressBar.vue';
import type { BudgetWithSpending } from '@/types/budget';
import { useBudget } from '@/composables/useBudget';
interface Props {
    budgetWithSpending: BudgetWithSpending;
}
const props = defineProps<Props>();
const emit = defineEmits(['edit', 'delete']);
const { getStatusColor, formatCurrency } = useBudget();
const categoryName = computed(() =>
    props.budgetWithSpending.budget.category?.name || 'Overall Budget'
);
const categoryColor = computed(() =>
    props.budgetWithSpending.budget.category?.color || '#6366f1'
);
</script>
<template>
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: categoryColor }"></div>
                <span class="font-medium text-white">{{ categoryName }}</span>
            </div>
            <div class="flex gap-2">
                <button @click="emit('edit')" class="text-gray-400 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>
                <button @click="emit('delete')" class="text-gray-400 hover:text-red-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex justify-between text-sm text-gray-400 mb-2">
            <span>₦{{ budgetWithSpending.spent.toLocaleString() }} spent</span>
            <span>of ₦{{ budgetWithSpending.budget.amount.toLocaleString() }}</span>
        </div>
        <BudgetProgressBar :percentage-used="budgetWithSpending.percentage_used" :status="budgetWithSpending.status" />
        <div class="flex justify-between mt-2 text-sm">
            <span :class="getStatusColor(budgetWithSpending.status)">
                ₦{{ Math.abs(budgetWithSpending.remaining).toLocaleString() }}
                {{ budgetWithSpending.remaining >= 0 ? 'remaining' : 'over' }}
            </span>
            <span class="text-gray-500">{{ budgetWithSpending.days_left }} days left</span>
        </div>
    </div>
</template>
