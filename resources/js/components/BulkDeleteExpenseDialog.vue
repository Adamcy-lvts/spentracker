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
// Import toast system
import { toast } from 'vue-sonner'
// Import network status
import { useNetworkStatus } from '@/composables/useNetworkStatus'
import { ref, computed } from 'vue'

// Define props and emits
interface Props {
  open: boolean
  expenses: any[]
  isInitialized: boolean
  bulkDeleteExpensesOffline: (ids: (string | number)[]) => Promise<{
    deletedCount: number
    errors: string[]
    success: boolean
  }>
}

const props = defineProps<Props>()
const emit = defineEmits<{
  'update:open': [value: boolean]
  'confirmed': [expenses: any[]]
}>()

// Initialize composables
const { isOnline } = useNetworkStatus()
const isProcessing = ref(false)

// Computed properties
const expenseCount = computed(() => props.expenses?.length || 0)
const expensePreview = computed(() => {
  if (!props.expenses?.length) return ''
  
  if (props.expenses.length <= 3) {
    return props.expenses.map(e => `"${e.description}"`).join(', ')
  } else {
    const first3 = props.expenses.slice(0, 3).map(e => `"${e.description}"`).join(', ')
    return `${first3} and ${props.expenses.length - 3} more`
  }
})

// Function to handle deletion
const confirmDelete = async () => {
  if (isProcessing.value || !props.expenses?.length) return
  
  try {
    isProcessing.value = true

    if (!props.isInitialized) {
      toast.error('App is still initializing, please wait...')
      return
    }

    console.log('🔍 Debug expenses for bulk delete:', props.expenses)
    console.log('🔍 Expense details:', props.expenses.map(e => ({ id: e.id, isOffline: e.isOffline, localId: e.localId })))

    if (isOnline.value) {
      // Online: Handle both server and offline expenses
      console.log('📡 Bulk deleting expenses online')
      
      // Separate server and offline expenses
      const serverExpenses = props.expenses.filter(expense => expense.id && !expense.isOffline)
      const offlineExpenses = props.expenses.filter(expense => expense.isOffline)
      
      console.log('🔍 Server expenses:', serverExpenses)
      console.log('🔍 Offline expenses:', offlineExpenses)
      
      // Delete server expenses via API if any
      if (serverExpenses.length > 0) {
        const expenseIds = serverExpenses.map(expense => expense.id)
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        
        const response = await fetch('/expenses/bulk', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token || ''
          },
          credentials: 'include',
          body: JSON.stringify({
            expense_ids: expenseIds
          })
        })

        if (!response.ok) {
          const errorData = await response.json()
          throw new Error(errorData.message || `Server error: ${response.status}`)
        }
      }
      
      // Delete offline expenses locally if any
      if (offlineExpenses.length > 0) {
        const offlineIds = offlineExpenses.map(expense => expense.localId)
        await props.bulkDeleteExpensesOffline(offlineIds)
      }
      
      toast.success(`Successfully deleted ${expenseCount.value} expense(s)!`)
      
      // Close dialog and refresh page
      emit('update:open', false)
      window.location.reload()
      
    } else {
      // Offline: Use offline storage for all expenses
      console.log('📴 Bulk deleting expenses offline')
      
      // Extract IDs (use localId for offline expenses, id for server expenses)
      const expenseIds = props.expenses.map(expense => expense.isOffline ? expense.localId : expense.id)
      
      const result = await props.bulkDeleteExpensesOffline(expenseIds)
      
      emit('update:open', false)
      
      if (result.success) {
        toast.success(`Successfully deleted ${result.deletedCount} expense(s) offline! 📴`)
      } else {
        toast.warning(`Deleted ${result.deletedCount} expense(s), but some failed: ${result.errors.join(', ')}`)
      }
    }
    
  } catch (error) {
    console.error('Bulk delete failed:', error)
    toast.error(`Failed to delete expenses: ${error instanceof Error ? error.message : 'Unknown error'}`)
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
    <DialogContent class="sm:max-w-[500px]">
      <DialogHeader>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-destructive/10 flex items-center justify-center">
            <AlertTriangle class="w-5 h-5 text-destructive" />
          </div>
          <div>
            <DialogTitle class="text-destructive">Delete {{ expenseCount }} Expense{{ expenseCount > 1 ? 's' : '' }}</DialogTitle>
          </div>
        </div>
        <DialogDescription class="pt-2">
          <p class="mb-2">
            Are you sure you want to delete {{ expenseCount }} expense{{ expenseCount > 1 ? 's' : '' }}?
            This action cannot be undone.
          </p>
          <div v-if="expensePreview" class="text-sm text-muted-foreground bg-muted/50 rounded-md p-3">
            <strong>Expenses to delete:</strong><br>
            {{ expensePreview }}
          </div>
        </DialogDescription>
      </DialogHeader>
      
      <DialogFooter>
        <Button
          type="button"
          variant="outline"
          @click="cancel"
          :disabled="isProcessing"
        >
          Cancel
        </Button>
        
        <Button 
          type="button"
          variant="destructive"
          @click="confirmDelete"
          :disabled="isProcessing"
        >
          <LoaderCircle v-if="isProcessing" class="w-4 h-4 mr-2 animate-spin" />
          {{ isProcessing ? 'Deleting...' : `Delete ${expenseCount} Expense${expenseCount > 1 ? 's' : ''}` }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>