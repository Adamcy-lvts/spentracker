<script setup lang="ts">
// Import form handling from Inertia
import { useForm } from '@inertiajs/vue3'
// Import UI components
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import InputError from '@/components/InputError.vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
// Import icons
import { LoaderCircle } from 'lucide-vue-next'
// Import toast system
import { toast } from 'vue-sonner'
// Import Vue reactivity
import { watch, ref } from 'vue'
// Import date picker
import { DatePicker } from '@/components/ui/date-picker'
import { CalendarDate, today, getLocalTimeZone } from '@internationalized/date'
// Import offline storage and network status
import { useOfflineStorage } from '@/composables/useOfflineStorage'
import { useNetworkStatus } from '@/composables/useNetworkStatus'

// Define props and emits
interface Props {
  open: boolean
  expense: {
    id: number | string  // Can be number (server) or string (offline localId)
    description: string
    amount: string
    date: string
    category_id?: number | null
    category?: {
      id: number
      name: string
      icon: string
      color: string
    }
    user_id: number
    created_at: string
    updated_at: string
    isOffline?: boolean  // Flag to identify offline expenses
  }
  categories?: Array<{
    id: number
    name: string
    icon: string
    color: string
    description: string
  }>
}

const props = defineProps<Props>()
const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

// Initialize composables
const { isOnline } = useNetworkStatus()
const { updateExpenseOffline } = useOfflineStorage()
const isProcessing = ref(false)

// Function to convert date string to CalendarDate
const stringToCalendarDate = (dateString: string) => {
  try {
    // Parse date string (YYYY-MM-DD format from Laravel)
    const date = new Date(dateString)
    if (isNaN(date.getTime())) {
      return today(getLocalTimeZone())
    }
    
    const year = date.getFullYear()
    const month = date.getMonth() + 1 // getMonth() returns 0-11
    const day = date.getDate()
    
    return new CalendarDate(year, month, day)
  } catch (error) {
    return today(getLocalTimeZone())
  }
}

// Create form with initial data from the expense
const form = useForm({
  description: props.expense.description,
  amount: props.expense.amount,
  date: props.expense.date, // Keep as string for Laravel
  category_id: props.expense.category_id,
})

// Date picker state
const editSelectedDate = ref(stringToCalendarDate(props.expense.date))

// Watch for date changes and update form
watch(editSelectedDate, (newDate) => {
  if (newDate) {
    form.date = newDate.toString() // Convert CalendarDate to string for Laravel
  }
})

// Function to handle form submission
const submit = async () => {
  if (isProcessing.value || form.processing) return
  
  try {
    isProcessing.value = true

    if (props.expense.isOffline) {
      // Offline expense - update in local storage
      await updateExpenseOffline(props.expense.id as string, {
        description: form.description,
        amount: form.amount,
        date: form.date,
        category_id: form.category_id
      })
      emit('update:open', false)
      toast.success('Expense updated successfully! ✨')
    } else if (isOnline.value) {
      // Online expense - use Inertia form
      form.put(route('expense.update', props.expense.id), {
        onSuccess: () => {
          emit('update:open', false)
          toast.success('Expense updated successfully! ✨')
        },
        onError: () => {
          toast.error('Failed to update expense. Please check your inputs.')
        },
        onFinish: () => {
          isProcessing.value = false
        }
      })
      return // Don't set isProcessing to false here since onFinish will handle it
    } else {
      // Offline but trying to update server expense - queue for update
      await updateExpenseOffline(props.expense.id as string, {
        description: form.description,
        amount: form.amount,
        date: form.date,
        category_id: form.category_id
      })
      emit('update:open', false)
      toast.success('Expense queued for update when online! 📴')
    }
  } catch (error) {
    console.error('Failed to update expense:', error)
    toast.error('Failed to update expense. Please try again.')
  } finally {
    isProcessing.value = false
  }
}

// Function to handle cancel - just close dialog
const cancel = () => {
  emit('update:open', false)
}

// Real-time money formatting as user types
const displayAmount = ref('')

// Initialize display amount with formatted value
const initializeAmount = (amount: string) => {
  if (amount && !isNaN(parseFloat(amount))) {
    const num = parseFloat(amount)
    displayAmount.value = new Intl.NumberFormat('en-NG', {
      style: 'currency',
      currency: 'NGN',
      minimumFractionDigits: 0,
      maximumFractionDigits: 2
    }).format(num)
  } else {
    displayAmount.value = ''
  }
}

// Handle input changes and format in real-time
const onAmountInput = (event: Event) => {
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
    displayAmount.value = new Intl.NumberFormat('en-NG', {
      style: 'currency',
      currency: 'NGN',
      minimumFractionDigits: 0,
      maximumFractionDigits: 2
    }).format(num)
  } else {
    displayAmount.value = cleanValue ? '₦' + cleanValue : ''
  }
  
  // Set cursor position after formatting
  setTimeout(() => {
    const newPosition = displayAmount.value.length
    target.setSelectionRange(newPosition, newPosition)
  }, 0)
}

// Initialize when component mounts
initializeAmount(props.expense.amount)

// Reset form if expense changes (shouldn't happen, but good practice)
watch(() => props.expense, (newExpense) => {
  form.reset()
  form.description = newExpense.description
  form.amount = newExpense.amount
  form.date = newExpense.date
  form.category_id = newExpense.category_id
  editSelectedDate.value = stringToCalendarDate(newExpense.date)
  initializeAmount(newExpense.amount)
}, { deep: true })
</script>

<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Edit Expense</DialogTitle>
        <DialogDescription>
          Make changes to your expense here. Click save when you're done.
        </DialogDescription>
      </DialogHeader>
      
      <form @submit.prevent="submit" class="space-y-4">
        <!-- Description Field -->
        <div class="space-y-2">
          <Label for="edit-description">Description</Label>
          <Input
            id="edit-description"
            type="text"
            v-model="form.description"
            placeholder="Coffee, Lunch, Gas, etc."
            required
            :disabled="form.processing || isProcessing"
            class="w-full"
          />
          <InputError :message="form.errors.description" />
        </div>
        
        <!-- Amount Field -->
        <div class="space-y-2">
          <Label for="edit-amount">Amount</Label>
          <Input
            id="edit-amount"
            type="text"
            v-model="displayAmount"
            @input="onAmountInput"
            placeholder="₦0"
            required
            :disabled="form.processing || isProcessing"
            class="w-full"
          />
          <InputError :message="form.errors.amount" />
        </div>

        <!-- Category Field -->
        <div v-if="categories && categories.length > 0" class="space-y-2">
          <Label for="edit-category">Category</Label>
          <Select v-model="form.category_id" :disabled="form.processing || isProcessing">
            <SelectTrigger>
              <SelectValue placeholder="Select category" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">
                <div class="flex items-center gap-2">
                  <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                  Uncategorized
                </div>
              </SelectItem>
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
            v-model="editSelectedDate"
            placeholder="Select date"
            :disabled="form.processing || isProcessing"
          />
          <InputError :message="form.errors.date" />
        </div>
        
        <!-- Form Actions -->
        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            @click="cancel"
            :disabled="form.processing || isProcessing"
          >
            Cancel
          </Button>
          
          <Button 
            type="submit"
            :disabled="form.processing || isProcessing"
          >
            <LoaderCircle v-if="form.processing || isProcessing" class="w-4 h-4 mr-2 animate-spin" />
            {{ (form.processing || isProcessing) ? 'Saving...' : 'Save Changes' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>