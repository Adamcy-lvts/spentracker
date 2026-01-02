<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useExpenseActions } from '@/composables/useExpenseActions';
import { usePage } from '@inertiajs/vue3';
import { today, getLocalTimeZone } from '@internationalized/date';
import { LoaderCircle, Plus } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { DatePicker } from '@/components/ui/date-picker';

const props = defineProps<{
    open: boolean;
}>();

const emit = defineEmits(['update:open']);

// Fetch categories from global page props since this dialog is used globally
const page = usePage();
// We'll need to ensure categories are shared globally or fetch them. 
// For now, assuming they might not be on every page, but let's check.
// If categories are not in common props, we might need to rely on what's passed or handle it gracefully.
// However, looking at Dashboard/Expense pages, categories are passed as props.
// To make this truly global, we should probably share categories via HandleInertiaRequests middleware.
// For this iteration, let's assume valid categories are available if we are on pages that provide them, 
// or fallback to an empty list or try to inspect `page.props`.
const categories = computed(() => (page.props as any).categories || []);

const { submitExpense, formatCurrency, parseCurrency } = useExpenseActions();

const description = ref('');
const amount = ref('');
const displayAmount = ref('');
const categoryId = ref<string | null>(null);
const selectedDate = ref(today(getLocalTimeZone()));
const isProcessing = ref(false);

// Reset form when dialog opens
watch(() => props.open, (isOpen) => {
    if (isOpen) {
        description.value = '';
        amount.value = '';
        displayAmount.value = '';
        categoryId.value = null;
        selectedDate.value = today(getLocalTimeZone());
        isProcessing.value = false;
    }
});

const onAmountInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const value = target.value;
    
    // Clean and parse
    const numericPart = parseCurrency(value);
    amount.value = numericPart;
    
    // Format for display
    if (numericPart && !isNaN(parseFloat(numericPart))) {
        displayAmount.value = formatCurrency(numericPart);
    } else {
        displayAmount.value = numericPart ? '₦' + numericPart : '';
    }
};

const handleSubmit = async () => {
    if (!description.value || !amount.value) return;
    
    isProcessing.value = true;
    
    await submitExpense({
        description: description.value,
        amount: amount.value,
        date: selectedDate.value.toString(),
        category_id: categoryId.value
    }, {
        onSuccess: () => {
            isProcessing.value = false;
            emit('update:open', false);
        },
        onError: () => {
            isProcessing.value = false;
        }
    });
};
</script>

<template>
    <Dialog :open="open" @update:open="$emit('update:open', $event)">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Quick Add Expense</DialogTitle>
                <DialogDescription>
                    Add a new expense quickly.
                </DialogDescription>
            </DialogHeader>
            
            <form @submit.prevent="handleSubmit" class="space-y-4 py-4">
                <div class="space-y-2">
                    <Label for="quick-description">Description</Label>
                    <Input
                        id="quick-description"
                        v-model="description"
                        placeholder="What did you spend on?"
                        autoFocus
                        required
                    />
                </div>
                
                <div class="space-y-2">
                    <Label for="quick-amount">Amount</Label>
                    <Input
                        id="quick-amount"
                        v-model="displayAmount"
                        @input="onAmountInput"
                        placeholder="₦0"
                        required
                    />
                </div>
                
                <div class="space-y-2">
                    <Label for="quick-category">Category</Label>
                    <Select v-model="categoryId">
                        <SelectTrigger>
                            <SelectValue placeholder="Select category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="category in categories" :key="category.id" :value="category.id.toString()">
                                <div class="flex items-center gap-2">
                                    <div 
                                        v-if="category.color"
                                        class="w-3 h-3 rounded-full" 
                                        :style="{ backgroundColor: category.color }"
                                    ></div>
                                    {{ category.name }}
                                </div>
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                
                <div class="space-y-2">
                    <Label>Date</Label>
                    <DatePicker 
                        v-model="selectedDate"
                        placeholder="Select date"
                    />
                </div>
                
                <DialogFooter>
                    <Button type="submit" :disabled="isProcessing" class="w-full">
                        <LoaderCircle v-if="isProcessing" class="w-4 h-4 mr-2 animate-spin" />
                        {{ isProcessing ? 'Saving...' : 'Add Expense' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
