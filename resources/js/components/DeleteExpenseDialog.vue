<script setup lang="ts">
// Import UI components
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
// Import icons
import { AlertTriangle, LoaderCircle } from 'lucide-vue-next'
// Import form handling
import { useForm } from '@inertiajs/vue3'
// Import toast system
import { toast } from 'vue-sonner'
// Import network status
import { useNetworkStatus } from '@/composables/useNetworkStatus'
import { ref } from 'vue'

// Define props and emits
interface Props {
  open: boolean
  expense: {
    id: number | string  // Can be number (server) or string (offline localId)
    description: string
    amount: string
    date: string
    user_id: number
    created_at: string
    updated_at: string
    isOffline?: boolean  // Flag to identify offline expenses
  }
  deleteExpenseOffline?: (id: string | number) => Promise<boolean>
}

const props = defineProps<Props>()
const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

// Initialize composables
const { isOnline } = useNetworkStatus()
const isProcessing = ref(false)

// Create delete form (for online operations)
const deleteForm = useForm({})

// Function to handle deletion
const confirmDelete = async () => {
  if (isProcessing.value) return
  
  try {
    isProcessing.value = true

    if (props.expense.isOffline) {
      // Offline expense - delete from local storage
      if (props.deleteExpenseOffline) {
        await props.deleteExpenseOffline(props.expense.id as string)
        emit('update:open', false)
        toast.success('Expense deleted successfully! 🗑️')
      } else {
        throw new Error('Offline delete function not available')
      }
    } else if (isOnline.value) {
      // Online expense - use Inertia form
      deleteForm.delete(route('expense.destroy', props.expense.id), {
        onSuccess: () => {
          emit('update:open', false)
          toast.success('Expense deleted successfully! 🗑️')
        },
        onError: () => {
          toast.error('Failed to delete expense. Please try again.')
        },
        onFinish: () => {
          isProcessing.value = false
        }
      })
      return // Don't set isProcessing to false here since onFinish will handle it
    } else {
      // Offline but trying to delete server expense - queue for deletion
      if (props.deleteExpenseOffline) {
        await props.deleteExpenseOffline(props.expense.id as string)
        emit('update:open', false)
        toast.success('Expense queued for deletion when online! 📴')
      } else {
        throw new Error('Offline delete function not available')
      }
    }
  } catch (error) {
    console.error('Failed to delete expense:', error)
    toast.error('Failed to delete expense. Please try again.')
  } finally {
    isProcessing.value = false
  }
}

// Function to handle cancel
const cancel = () => {
  emit('update:open', false)
}
</script>

<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-destructive/10 flex items-center justify-center">
            <AlertTriangle class="w-5 h-5 text-destructive" />
          </div>
          <div>
            <DialogTitle class="text-destructive">Delete Expense</DialogTitle>
          </div>
        </div>
        <DialogDescription class="pt-2">
          Are you sure you want to delete "{{ expense.description }}"? This action cannot be undone.
        </DialogDescription>
      </DialogHeader>
      
      <DialogFooter>
        <Button
          type="button"
          variant="outline"
          @click="cancel"
          :disabled="deleteForm.processing || isProcessing"
        >
          Cancel
        </Button>
        
        <Button 
          type="button"
          variant="destructive"
          @click="confirmDelete"
          :disabled="deleteForm.processing || isProcessing"
        >
          <LoaderCircle v-if="deleteForm.processing || isProcessing" class="w-4 h-4 mr-2 animate-spin" />
          {{ (deleteForm.processing || isProcessing) ? 'Deleting...' : 'Delete Expense' }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>