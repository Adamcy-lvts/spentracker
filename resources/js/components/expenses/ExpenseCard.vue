<script setup lang="ts">
import { computed } from 'vue';

interface Expense {
    id: number | null;
    localId?: string;
    description: string;
    amount: string;
    date: string;
    category?: {
        name: string;
        color: string;
        icon: string;
    };
    isOffline?: boolean;
}

const props = defineProps<{
    expense: Expense;
}>();

const emit = defineEmits<{
    edit: [expense: Expense];
    delete: [expense: Expense];
}>();

const formattedDate = computed(() => {
    return new Date(props.expense.date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
});

const formattedAmount = computed(() => {
    const amount = parseFloat(props.expense.amount);
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(amount);
});
</script>


<template>
    <div class="flex flex-col border-b border-border/10 last:border-0 hover:bg-muted/5 transition-colors">
        <div class="p-3 flex items-center justify-between gap-3" @click="$emit('edit', expense)">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <!-- Category Icon/Color (Removed as per user request to remove avatars) -->
                <!-- Replaced with small colored dot indicator -->
                <div class="w-2 h-2 rounded-full shrink-0"
                    :style="{ backgroundColor: expense.category?.color || '#6B7280' }"></div>

                <!-- Details -->
                <div class="truncate flex-1">
                    <h3 class="font-medium truncate text-sm leading-tight">{{ expense.description }}</h3>
                    <div class="flex items-center text-[10px] text-muted-foreground gap-1.5 mt-0.5">
                        <span>{{ formattedDate }}</span>
                        <span class="w-0.5 h-0.5 rounded-full bg-muted-foreground/40"></span>
                        <span v-if="expense.isOffline" class="text-amber-500 font-medium flex items-center gap-1">
                            Offline
                        </span>
                        <span v-else class="truncate">{{ expense.category?.name || 'Uncategorized' }}</span>
                    </div>
                </div>
            </div>

            <!-- Amount -->
            <div class="text-right shrink-0">
                <div class="font-bold text-sm">{{ formattedAmount }}</div>
            </div>

            <!-- Context Menu Trigger or similar would be good here, but for now simple tap to edit -->
        </div>
    </div>
</template>
