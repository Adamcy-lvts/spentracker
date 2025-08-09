<script setup lang="ts">
// Import form handling from Inertia
import { useForm } from '@inertiajs/vue3'
// Import UI components
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
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

// Define props and emits
interface Props {
  open: boolean
  expense: {
    id: number
    description: string
    amount: string
    date: string
    user_id: number
    created_at: string
    updated_at: string
  }
}

const props = defineProps<Props>()
const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

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
const submit = () => {
  form.put(route('expense.update', props.expense.id), {
    onSuccess: () => {
      // Close dialog and show success message
      emit('update:open', false)
      toast.success('Expense updated successfully! ✨')
    },
    onError: () => {
      // Keep dialog open but show error
      toast.error('Failed to update expense. Please check your inputs.')
    },
  })
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
            :disabled="form.processing"
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
            :disabled="form.processing"
            class="w-full"
          />
          <InputError :message="form.errors.amount" />
        </div>
        
        <!-- Date Field -->
        <div class="space-y-2">
          <Label>Date</Label>
          <DatePicker 
            v-model="editSelectedDate"
            placeholder="Select date"
            :disabled="form.processing"
          />
          <InputError :message="form.errors.date" />
        </div>
        
        <!-- Form Actions -->
        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            @click="cancel"
            :disabled="form.processing"
          >
            Cancel
          </Button>
          
          <Button 
            type="submit"
            :disabled="form.processing"
          >
            <LoaderCircle v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            {{ form.processing ? 'Saving...' : 'Save Changes' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>