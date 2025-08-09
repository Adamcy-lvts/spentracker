<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { LoaderCircle, Plus } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { toast } from 'vue-sonner';
import EditExpenseDialog from '@/components/EditExpenseDialog.vue';
import DeleteExpenseDialog from '@/components/DeleteExpenseDialog.vue';
import { DatePicker } from '@/components/ui/date-picker';
import { today, getLocalTimeZone } from '@internationalized/date';
import ExpenseDataTable from '@/components/expenses/ExpenseDataTable.vue';
import { columns } from '@/components/expenses/columns';

defineProps<{
  expenses: Array<{
    id: number;
    description: string;
    amount: string; // Laravel returns decimals as strings
    date: string;
    user_id: number;
    created_at: string;
    updated_at: string;
  }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Expenses',
    href: '/expense',
  },
];

// Create the form with Inertia's useForm helper
const form = useForm({
    description: '',
    amount: '',
    date: new Date().toISOString().split('T')[0], // Today's date
});

// Date picker state
const selectedDate = ref(today(getLocalTimeZone()))

// Watch for date changes and update form
watch(selectedDate, (newDate) => {
  if (newDate) {
    form.date = newDate.toString() // Convert CalendarDate to string for Laravel
  }
})

// Dialog state
const editDialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const currentExpense = ref<any>(null)

// Event listeners for data table actions
onMounted(() => {
  // Listen for edit events from the data table
  document.addEventListener('editExpense', (event: any) => {
    openEditDialog(event.detail)
  })
  
  // Listen for delete events from the data table
  document.addEventListener('deleteExpense', (event: any) => {
    openDeleteDialog(event.detail)
  })
})

// Clean up event listeners
onUnmounted(() => {
  document.removeEventListener('editExpense', () => {})
  document.removeEventListener('deleteExpense', () => {})
})

// Submit function
const submit = () => {
    form.post(route('expense.store'), {
        onSuccess: () => {
            form.reset();
            mainDisplayAmount.value = ''; // Clear the display amount too
            selectedDate.value = today(getLocalTimeZone()); // Reset date picker to today
            toast.success('Expense added successfully! 🎉');
        },
        onError: () => {
            toast.error('Failed to add expense. Please check your inputs.');
        },
    });
};


// Dialog-based editing functions
const openEditDialog = (expense: any) => {
    currentExpense.value = expense
    editDialogOpen.value = true
}

const openDeleteDialog = (expense: any) => {
    currentExpense.value = expense
    deleteDialogOpen.value = true
}

// Real-time money formatting for main form
const mainDisplayAmount = ref('')

// Handle input changes and format in real-time for main form
const onMainAmountInput = (event: Event) => {
  const target = event.target as HTMLInputElement
  const value = target.value
  
  // Remove all non-numeric characters except decimal point
  const numericValue = value.replace(/[^\d.]/g, '')
  
  // Handle multiple decimal points
  const parts = numericValue.split('.')
  const cleanValue = parts[0] + (parts.length > 1 ? '.' + parts.slice(1).join('') : '')
  
  // Update form amount for submission
  form.amount = cleanValue
  
  // Format for display
  if (cleanValue && !isNaN(parseFloat(cleanValue))) {
    const num = parseFloat(cleanValue)
    mainDisplayAmount.value = new Intl.NumberFormat('en-NG', {
      style: 'currency',
      currency: 'NGN',
      minimumFractionDigits: 0,
      maximumFractionDigits: 2
    }).format(num)
  } else {
    mainDisplayAmount.value = cleanValue ? '₦' + cleanValue : ''
  }
  
  // Set cursor position after formatting
  setTimeout(() => {
    const newPosition = mainDisplayAmount.value.length
    target.setSelectionRange(newPosition, newPosition)
  }, 0)
}

</script>


<template>
    <Head title="Expenses" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">Expenses</h1>
            
            <!-- Add New Expense Form -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Plus class="w-5 h-5" />
                        Add New Expense
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="grid gap-4 md:grid-cols-3">
                        <!-- Description Field -->
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Input
                                id="description"
                                type="text"
                                v-model="form.description"
                                placeholder="Coffee, Lunch, Gas, etc."
                                required
                            />
                            <InputError :message="form.errors.description" />
                        </div>
                        
                        <!-- Amount Field -->
                        <div class="space-y-2">
                            <Label for="amount">Amount</Label>
                            <Input
                                id="amount"
                                type="text"
                                v-model="mainDisplayAmount"
                                @input="onMainAmountInput"
                                placeholder="₦0"
                                required
                            />
                            <InputError :message="form.errors.amount" />
                        </div>
                        
                        <!-- Date Field -->
                        <div class="space-y-2">
                            <Label>Date</Label>
                            <DatePicker 
                                v-model="selectedDate"
                                placeholder="Select date"
                            />
                            <InputError :message="form.errors.date" />
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="md:col-span-3">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full md:w-auto"
                            >
                                <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                {{ form.processing ? 'Adding...' : 'Add Expense' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
            
            <!-- Expenses Data Table -->
            <Card>
                <CardHeader class="pb-3">
                    <CardTitle class="text-lg">Recent Expenses</CardTitle>
                </CardHeader>
                <CardContent>
                    <ExpenseDataTable :columns="columns" :data="expenses" />
                </CardContent>
            </Card>
        </div>

        <!-- Edit Expense Dialog -->
        <EditExpenseDialog
            v-if="currentExpense"
            :open="editDialogOpen"
            @update:open="editDialogOpen = $event"
            :expense="currentExpense"
        />

        <!-- Delete Expense Dialog -->
        <DeleteExpenseDialog
            v-if="currentExpense"
            :open="deleteDialogOpen"
            @update:open="deleteDialogOpen = $event"
            :expense="currentExpense"
        />
    </AppLayout>
</template>