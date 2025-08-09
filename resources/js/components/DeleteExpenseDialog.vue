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

// Create delete form
const deleteForm = useForm({})

// Function to handle deletion
const confirmDelete = () => {
  deleteForm.delete(route('expense.destroy', props.expense.id), {
    onSuccess: () => {
      emit('update:open', false)
      toast.success('Expense deleted successfully! 🗑️')
    },
    onError: () => {
      toast.error('Failed to delete expense. Please try again.')
    },
  })
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
          :disabled="deleteForm.processing"
        >
          Cancel
        </Button>
        
        <Button 
          type="button"
          variant="destructive"
          @click="confirmDelete"
          :disabled="deleteForm.processing"
        >
          <LoaderCircle v-if="deleteForm.processing" class="w-4 h-4 mr-2 animate-spin" />
          {{ deleteForm.processing ? 'Deleting...' : 'Delete Expense' }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>