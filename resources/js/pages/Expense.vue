<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import { LoaderCircle, Plus } from 'lucide-vue-next';
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { toast } from 'vue-sonner';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import EditExpenseDialog from '@/components/EditExpenseDialog.vue';
import DeleteExpenseDialog from '@/components/DeleteExpenseDialog.vue';
import BulkDeleteExpenseDialog from '@/components/BulkDeleteExpenseDialog.vue';
import { DatePicker } from '@/components/ui/date-picker';
import { today, getLocalTimeZone } from '@internationalized/date';
import ExpenseDataTable from '@/components/expenses/ExpenseDataTable.vue';
import { columns } from '@/components/expenses/columns';
import { useOfflineStorage } from '@/composables/useOfflineStorage';
import { useNetworkStatus } from '@/composables/useNetworkStatus';
// Removed unused imports - using global instance instead

const props = defineProps<{
  expenses: Array<{
    id: number;
    description: string;
    amount: string; // Laravel returns decimals as strings
    date: string;
    user_id: number;
    category_id: number | null;
    category?: {
      id: number;
      name: string;
      icon: string;
      color: string;
    };
    created_at: string;
    updated_at: string;
  }>;
  categories: Array<{
    id: number;
    name: string;
    icon: string;
    color: string;
    description: string;
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
    category_id: null,
});

// Date picker state
const selectedDate = ref(today(getLocalTimeZone()))

// Initialize form date with today's date
form.date = selectedDate.value.toString()

// Watch for date changes and update form
watch(selectedDate, (newDate) => {
  if (newDate) {
    form.date = newDate.toString() // Convert CalendarDate to string for Laravel
  }
})

// Dialog state
const createDialogOpen = ref(false)
const editDialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const bulkDeleteDialogOpen = ref(false)
const currentExpense = ref<any>(null)
const selectedExpensesForBulkDelete = ref<any[]>([])

// Vue Learning Point #51: Integrating Multiple Composables for Different Features
const { isOnline } = useNetworkStatus()
const { 
  initDB, 
  initNetworkSync,
  createExpenseOffline, 
  deleteExpenseOffline,
  bulkDeleteExpensesOffline,
  isInitialized,
  expenses: offlineExpenses,
  hasUnsyncedData,
  syncWithServer,
  cleanupInvalidSyncActions,
  clearPendingSync
} = useOfflineStorage()

// Using global offline systems instead of local instances

// Vue Learning Point #30: Computed property to merge server and offline data
const allExpenses = computed(() => {
  const serverExpenses = props.expenses || []
  const offline = offlineExpenses.value || []
  
  console.log('🔍 Debug expenses:', { serverCount: serverExpenses.length, offlineCount: offline.length })
  
  // Convert offline expenses to match server format (these should only be unsynced expenses)
  const formattedOfflineExpenses = offline
    .filter((expense: any) => {
      // Only include expenses that haven't been synced to server yet
      // (synced expenses should have been removed from offline storage)
      return !expense.id || expense.syncStatus === 'pending' || expense.syncStatus === 'failed'
    })
    .map((expense: any) => ({
      id: null, // Offline expenses don't have server IDs yet
      localId: expense.localId, // Keep localId for offline operations
      description: expense.description,
      amount: String(expense.amount || '0'), // Safely convert to string
      date: expense.date,
      user_id: expense.user_id,
      created_at: new Date().toISOString(), // Use current time as placeholder
      updated_at: new Date().toISOString(),
      isOffline: true // Mark as offline for styling/indication
    }))
  
  // Additional deduplication check (safety net)
  // Remove any server expenses that might match offline expenses by description+amount+date
  const serverExpenseKeys = new Set(
    serverExpenses.map(exp => `${exp.description}-${exp.amount}-${exp.date}`)
  )
  
  const uniqueOfflineExpenses = formattedOfflineExpenses.filter(offlineExp => {
    const key = `${offlineExp.description}-${offlineExp.amount}-${offlineExp.date}`
    return !serverExpenseKeys.has(key)
  })
  
  // Combine and sort by date (newest first)
  const combined = [...serverExpenses, ...uniqueOfflineExpenses]
  return combined.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
})


// Event listeners for data table actions  
onMounted(async () => {
  try {
    console.log('🚀 Initializing expense page...')
    
    // Initialize local storage system
    await initDB()
    initNetworkSync()
    
    console.log('✅ Expense page storage ready!')
    
  } catch (error) {
    console.error('Failed to initialize expense page:', error)
    toast.error('Failed to initialize app')
  }

  // Listen for edit events from the data table
  document.addEventListener('editExpense', (event: any) => {
    openEditDialog(event.detail)
  })
  
  // Listen for delete events from the data table
  document.addEventListener('deleteExpense', (event: any) => {
    openDeleteDialog(event.detail)
  })

  // Expose debugging functions to console (remove in production)
  if (import.meta.env.DEV) {
    (window as any).debugOfflineStorage = {
      cleanupInvalidSyncActions,
      clearPendingSync,
      syncWithServer,
      offlineExpenses,
      hasUnsyncedData
    }
    console.log('🔧 Debug functions available:')
    console.log('  - window.debugOfflineStorage')
  }
})

// Clean up event listeners
onUnmounted(() => {
  document.removeEventListener('editExpense', () => {})
  document.removeEventListener('deleteExpense', () => {})
})

// Vue Learning Point #29: Offline-first form submission
const submit = async () => {
    if (!isInitialized.value) {
        toast.error('App is still initializing, please wait...')
        return
    }

    try {
        console.log('🔍 Submit debug:', { isOnline: isOnline.value, formData: form.data })
        
        if (isOnline.value) {
            console.log('📡 Taking ONLINE path')
            // Online: Use traditional Laravel form submission
            form.post(route('expense.store'), {
                onSuccess: () => {
                    form.reset();
                    createDialogOpen.value = false;
                    mainDisplayAmount.value = '';
                    selectedDate.value = today(getLocalTimeZone());
                    toast.success('Expense added successfully! 🎉');
                },
                onError: () => {
                    toast.error('Failed to add expense. Please check your inputs.');
                },
            });
        } else {
            console.log('📴 Taking OFFLINE path')
            // Offline: Save to local storage
            const user = usePage().props.auth?.user as any
            
            console.log('🔍 Debug offline form data:', {
                description: form.description,
                amount: form.amount,
                date: form.date,
                user_id: user?.id
            })
            
            await createExpenseOffline({
                description: form.description,
                amount: form.amount,
                date: form.date,
                category_id: form.category_id,
                user_id: user?.id || 1 // Fallback user ID
            })

            // Reset form
            form.reset();
            createDialogOpen.value = false;
            mainDisplayAmount.value = '';
            selectedDate.value = today(getLocalTimeZone());
            
            toast.success('Expense saved offline! 📴 Will sync when online.');
        }
    } catch (error) {
        console.error('Failed to create expense:', error)
        toast.error('Failed to save expense. Please try again.')
    }
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

// Bulk action handlers
const handleBulkDelete = (expenses: any[]) => {
    selectedExpensesForBulkDelete.value = expenses
    bulkDeleteDialogOpen.value = true
}

const handleBulkExport = (expenses: any[]) => {
    // Create CSV content
    const headers = ['Description', 'Amount', 'Date']
    const csvContent = [
        headers.join(','),
        ...expenses.map(expense => [
            `"${expense.description}"`,
            expense.amount,
            expense.date
        ].join(','))
    ].join('\n')
    
    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
    const link = document.createElement('a')
    const url = URL.createObjectURL(blob)
    link.setAttribute('href', url)
    link.setAttribute('download', `expenses_${new Date().toISOString().split('T')[0]}.csv`)
    link.style.visibility = 'hidden'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    
    toast.success(`Exported ${expenses.length} expense(s) to CSV`)
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
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Expenses</h1>
                <Button @click="createDialogOpen = true" class="bg-primary hover:bg-primary/90 text-primary-foreground shadow-sm">
                    <Plus class="w-4 h-4 mr-2" />
                    Add Expense
                </Button>
            </div>
            
            <!-- Add New Expense Dialog -->
            <Dialog :open="createDialogOpen" @update:open="createDialogOpen = $event">
                <DialogContent class="sm:max-w-[500px]">
                    <DialogHeader>
                        <DialogTitle>Add New Expense</DialogTitle>
                        <DialogDescription>
                            Create a new expense record. Click save when you're done.
                        </DialogDescription>
                    </DialogHeader>
                    
                    <form @submit.prevent="submit" class="space-y-4 py-4">
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

                        <!-- Category Field -->
                        <div class="space-y-2">
                            <Label for="category">Category</Label>
                            <Select v-model="form.category_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select category" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="category in categories" :key="category.id" :value="category.id.toString()">
                                        <div class="flex items-center gap-2">
                                            <div 
                                                class="w-3 h-3 rounded-full" 
                                                :style="{ backgroundColor: category.color }"
                                            ></div>
                                            {{ category.name }}
                                        </div>
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.category_id" />
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
                        
                        <DialogFooter>
                            <Button type="button" variant="outline" @click="createDialogOpen = false">
                                Cancel
                            </Button>
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                            >
                                <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                {{ form.processing ? 'Adding...' : 'Add Expense' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
            
            <!-- Expenses Data Table -->
            <Card>
                <CardHeader class="pb-3">
                    <CardTitle class="text-lg">Recent Expenses</CardTitle>
                </CardHeader>
                <CardContent>
                    <ExpenseDataTable 
                        :columns="columns" 
                        :data="allExpenses"
                        @bulk-delete="handleBulkDelete"
                        @bulk-export="handleBulkExport"
                    />
                </CardContent>
            </Card>
        </div>

        <!-- Edit Expense Dialog -->
        <EditExpenseDialog
            v-if="currentExpense"
            :open="editDialogOpen"
            @update:open="editDialogOpen = $event"
            :expense="currentExpense"
            :categories="categories"
        />

        <!-- Delete Expense Dialog -->
        <DeleteExpenseDialog
            v-if="currentExpense"
            :open="deleteDialogOpen"
            @update:open="deleteDialogOpen = $event"
            :expense="currentExpense"
            :delete-expense-offline="deleteExpenseOffline"
        />

        <!-- Bulk Delete Expense Dialog -->
        <BulkDeleteExpenseDialog
            :open="bulkDeleteDialogOpen"
            @update:open="bulkDeleteDialogOpen = $event"
            :expenses="selectedExpensesForBulkDelete"
            :is-initialized="isInitialized"
            :bulk-delete-expenses-offline="bulkDeleteExpensesOffline"
        />
    </AppLayout>
</template>