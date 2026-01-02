import { ref, reactive, computed } from 'vue'
import { openDB, type DBSchema, type IDBPDatabase } from 'idb'

// Vue Learning Point #1: Defining TypeScript interfaces for our data
interface OfflineExpense {
  id: string // We'll use UUIDs for offline expenses
  description: string
  amount: string
  date: string
  category_id?: number | null
  user_id: number
  created_at: string
  updated_at: string
  // Offline-specific fields
  localId: string // Unique local identifier
  syncStatus: 'pending' | 'synced' | 'failed'
  lastModified: number // Timestamp for conflict resolution
}

interface PendingSyncAction {
  id: string
  type: 'create' | 'update' | 'delete'
  expenseData: OfflineExpense
  timestamp: number
  attempts: number
}

interface AppSettings {
  lastSyncTime: number
  userId: number
}

// Vue Learning Point #2: Defining IndexedDB schema with TypeScript
interface SpendTrackerDB extends DBSchema {
  expenses: {
    key: string
    value: OfflineExpense
  }
  pendingSync: {
    key: string
    value: PendingSyncAction
  }
  settings: {
    key: string
    value: AppSettings
  }
}

// Shared state so all composable consumers reference the same storage instance.
const isInitialized = ref(false)
const db = ref<IDBPDatabase<SpendTrackerDB> | null>(null)
const expenses = ref<OfflineExpense[]>([])
const pendingSync = ref<PendingSyncAction[]>([])
const syncStatus = reactive({
  isOnline: navigator.onLine,
  isSyncing: false,
  lastSync: 0,
  errorCount: 0
})

const expenseCount = computed(() => expenses.value.length)
const pendingSyncCount = computed(() => pendingSync.value.length)
const hasUnsyncedData = computed(() => pendingSyncCount.value > 0)

// Vue Learning Point #3: Creating a composable function
// Composables always start with 'use' and return reactive data/functions
export function useOfflineStorage() {

  // Initialize IndexedDB
  // Vue Learning Point #6: Async functions in composables
  const initDB = async () => {
    try {
      console.log('🗄️ Initializing IndexedDB for offline storage...')
      
      // Open database (creates it if it doesn't exist)
      const database = await openDB<SpendTrackerDB>('SpendTracker', 1, {
        upgrade(db) {
          console.log('📦 Creating IndexedDB tables...')
          
          // Create expenses table
          if (!db.objectStoreNames.contains('expenses')) {
            const expenseStore = db.createObjectStore('expenses', { keyPath: 'localId' })
            expenseStore.createIndex('syncStatus', 'syncStatus' as keyof OfflineExpense)
            expenseStore.createIndex('date', 'date' as keyof OfflineExpense)
          }

          // Create pending sync queue table
          if (!db.objectStoreNames.contains('pendingSync')) {
            const syncStore = db.createObjectStore('pendingSync', { keyPath: 'id' })
            syncStore.createIndex('timestamp', 'timestamp' as keyof PendingSyncAction)
            syncStore.createIndex('type', 'type' as keyof PendingSyncAction)
          }

          // Create app settings table
          if (!db.objectStoreNames.contains('settings')) {
            db.createObjectStore('settings', { keyPath: 'key' })
          }
        },
      })

      db.value = database
      isInitialized.value = true
      
      // Load existing data
      await loadExpenses()
      await loadPendingSync()
      
      // Clean up any invalid sync actions
      await cleanupInvalidSyncActions()
      
      console.log('✅ IndexedDB initialized successfully!')
      console.log(`📊 Loaded ${expenseCount.value} expenses, ${pendingSyncCount.value} pending sync`)
      
    } catch (error) {
      console.error('❌ Failed to initialize IndexedDB:', error)
      throw error
    }
  }

  // Load all expenses from IndexedDB
  const loadExpenses = async () => {
    if (!db.value) return []
    
    try {
      const allExpenses = await db.value.getAll('expenses')
      
      // Filter out expenses with invalid data (missing description, amount, or date)
      const validExpenses = allExpenses.filter(expense => 
        expense.description && 
        expense.amount !== undefined && 
        expense.date && 
        expense.date !== 'undefined'
      )
      
      // Remove invalid expenses from IndexedDB
      for (const expense of allExpenses) {
        if (!validExpenses.includes(expense)) {
          console.log('🗑️ Removing invalid expense:', expense)
          await db.value.delete('expenses', expense.localId)
        }
      }
      
      expenses.value = validExpenses.sort((a, b) => 
        new Date(b.date).getTime() - new Date(a.date).getTime()
      )
      return validExpenses
    } catch (error) {
      console.error('Failed to load expenses:', error)
      return []
    }
  }

  // Load pending sync actions
  const loadPendingSync = async () => {
    if (!db.value) return []
    
    try {
      const pending = await db.value.getAll('pendingSync')
      
      // Filter out sync actions with invalid expense data
      const validPending = pending.filter(action => 
        action.expenseData?.description && 
        action.expenseData?.amount !== undefined && 
        action.expenseData?.date && 
        action.expenseData?.date !== 'undefined'
      )
      
      // Remove invalid pending sync actions from IndexedDB
      for (const action of pending) {
        if (!validPending.includes(action)) {
          console.log('🗑️ Removing invalid pending sync:', action)
          await db.value.delete('pendingSync', action.id)
        }
      }
      
      pendingSync.value = validPending.sort((a, b) => a.timestamp - b.timestamp)
      return validPending
    } catch (error) {
      console.error('Failed to load pending sync:', error)
      return []
    }
  }

  // Generate unique ID for offline expenses
  const generateOfflineId = () => {
    return 'offline_' + Date.now() + '_' + Math.random().toString(36).substring(2, 11)
  }

  // Vue Learning Point #7: CRUD Operations with Reactive Updates
  
  // CREATE: Add new expense offline
  const createExpenseOffline = async (expenseData: {
    description: string
    amount: string
    date: string
    category_id?: number | null
    user_id: number
  }) => {
    if (!db.value) throw new Error('Database not initialized')
    
    try {
      // Create offline expense object
      const localId = generateOfflineId()
      const now = new Date().toISOString()
      
      const offlineExpense: OfflineExpense = {
        id: '', // Will be assigned by server when synced
        localId,
        description: expenseData.description,
        amount: expenseData.amount,
        date: expenseData.date,
        category_id: expenseData.category_id,
        user_id: expenseData.user_id,
        created_at: now,
        updated_at: now,
        syncStatus: 'pending',
        lastModified: Date.now()
      }
      
      // Save to IndexedDB
      await db.value.put('expenses', offlineExpense)
      
      // Add to pending sync queue
      const syncAction: PendingSyncAction = {
        id: generateOfflineId(),
        type: 'create',
        expenseData: offlineExpense,
        timestamp: Date.now(),
        attempts: 0
      }
      
      await db.value.put('pendingSync', syncAction)
      
      // Vue Learning Point #8: Automatic UI Updates
      // When we update reactive arrays, Vue automatically updates the UI!
      expenses.value.unshift(offlineExpense) // Add to beginning of array
      pendingSync.value.push(syncAction)
      
      console.log('💾 Created offline expense:', localId)
      return offlineExpense
      
    } catch (error) {
      console.error('Failed to create offline expense:', error)
      throw error
    }
  }

  // UPDATE: Modify existing expense offline
  const updateExpenseOffline = async (id: string | number, updateData: {
    description?: string
    amount?: string
    date?: string
    category_id?: number | null
  }) => {
    if (!db.value) throw new Error('Database not initialized')
    
    try {
      // Find existing expense by ID (either localId or server id)
      const existingExpense = await findExpenseById(id)
      if (!existingExpense) throw new Error('Expense not found')
      
      // Create updated expense
      const updatedExpense: OfflineExpense = {
        ...existingExpense,
        ...updateData,
        updated_at: new Date().toISOString(),
        syncStatus: 'pending',
        lastModified: Date.now()
      }
      
      // Save to IndexedDB using localId
      await db.value.put('expenses', updatedExpense)
      
      // Add to pending sync queue
      const syncAction: PendingSyncAction = {
        id: generateOfflineId(),
        type: 'update',
        expenseData: updatedExpense,
        timestamp: Date.now(),
        attempts: 0
      }
      
      await db.value.put('pendingSync', syncAction)
      
      // Vue Learning Point #9: Array updates trigger reactivity
      const index = expenses.value.findIndex(e => e.localId === existingExpense.localId)
      if (index !== -1) {
        expenses.value[index] = updatedExpense // Vue detects this change!
      }
      pendingSync.value.push(syncAction)
      
      console.log('✏️ Updated offline expense:', existingExpense.localId)
      return updatedExpense
      
    } catch (error) {
      console.error('Failed to update offline expense:', error)
      throw error
    }
  }

  // Helper function to find expense by ID (either localId or server id)
  const findExpenseById = async (id: string | number): Promise<OfflineExpense | null> => {
    if (!db.value) return null
    
    try {
      // First try to find by localId
      if (typeof id === 'string') {
        const expense = await db.value.get('expenses', id)
        if (expense) return expense
      }
      
      // If not found, search all expenses for server ID match
      const allExpenses = await db.value.getAll('expenses')
      return allExpenses.find(expense => expense.id === id.toString()) || null
    } catch (error) {
      console.error('Failed to find expense by ID:', error)
      return null
    }
  }

  // DELETE: Remove expense offline
  const deleteExpenseOffline = async (id: string | number) => {
    if (!db.value) throw new Error('Database not initialized')
    
    try {
      // Find expense by ID (either localId or server id)
      const expense = await findExpenseById(id)
      if (!expense) throw new Error('Expense not found')
      
      // Remove from IndexedDB using localId
      await db.value.delete('expenses', expense.localId)
      
      // Add to sync queue if it was previously synced (has server ID)
      if (expense.id) {
        const syncAction: PendingSyncAction = {
          id: generateOfflineId(),
          type: 'delete',
          expenseData: expense,
          timestamp: Date.now(),
          attempts: 0
        }
        
        await db.value.put('pendingSync', syncAction)
        pendingSync.value.push(syncAction)
      }
      
      // Vue Learning Point #10: Reactive array filtering
      expenses.value = expenses.value.filter(e => e.localId !== expense.localId)
      
      console.log('🗑️ Deleted offline expense:', expense.localId)
      return true
      
    } catch (error) {
      console.error('Failed to delete offline expense:', error)
      throw error
    }
  }

  // BULK DELETE: Remove multiple expenses offline
  const bulkDeleteExpensesOffline = async (ids: (string | number)[]) => {
    if (!db.value) throw new Error('Database not initialized')
    
    try {
      const deletedExpenses: OfflineExpense[] = []
      const errors: string[] = []
      
      for (const id of ids) {
        try {
          // Find expense by ID (either localId or server id)
          const expense = await findExpenseById(id)
          if (!expense) {
            errors.push(`Expense with ID ${id} not found`)
            continue
          }
          
          // Remove from IndexedDB using localId
          await db.value.delete('expenses', expense.localId)
          
          // Add to sync queue if it was previously synced (has server ID)
          if (expense.id) {
            const syncAction: PendingSyncAction = {
              id: generateOfflineId(),
              type: 'delete',
              expenseData: expense,
              timestamp: Date.now(),
              attempts: 0
            }
            
            await db.value.put('pendingSync', syncAction)
            pendingSync.value.push(syncAction)
          }
          
          deletedExpenses.push(expense)
          
        } catch (error) {
          console.error(`Failed to delete expense ${id}:`, error)
          errors.push(`Failed to delete expense ${id}: ${error instanceof Error ? error.message : 'Unknown error'}`)
        }
      }
      
      // Update reactive array by filtering out deleted expenses
      const deletedLocalIds = deletedExpenses.map(e => e.localId)
      expenses.value = expenses.value.filter(e => !deletedLocalIds.includes(e.localId))
      
      console.log(`🗑️ Bulk deleted ${deletedExpenses.length} offline expenses`)
      
      if (errors.length > 0) {
        console.warn('Some deletions failed:', errors)
      }
      
      return {
        deletedCount: deletedExpenses.length,
        errors,
        success: errors.length === 0
      }
      
    } catch (error) {
      console.error('Failed to bulk delete offline expenses:', error)
      throw error
    }
  }

  // UTILITY: Clear all offline data (for testing/reset)
  const clearOfflineData = async () => {
    if (!db.value) return
    
    try {
      await db.value.clear('expenses')
      await db.value.clear('pendingSync')
      
      expenses.value = []
      pendingSync.value = []
      
      console.log('🧹 Cleared all offline data')
    } catch (error) {
      console.error('Failed to clear offline data:', error)
    }
  }

  // UTILITY: Clean up invalid pending sync actions
  const cleanupInvalidSyncActions = async () => {
    if (!db.value) return
    
    try {
      const allPending = await db.value.getAll('pendingSync')
      console.log(`🔍 Found ${allPending.length} pending sync actions to review:`)
      
      // Log all pending actions for debugging
      allPending.forEach((action, index) => {
        console.log(`📋 Sync Action ${index + 1}:`, {
          id: action.id,
          type: action.type,
          expenseId: action.expenseData?.id,
          localId: action.expenseData?.localId,
          description: action.expenseData?.description,
          amount: action.expenseData?.amount,
          date: action.expenseData?.date,
          attempts: action.attempts,
          timestamp: new Date(action.timestamp).toISOString()
        })
      })
      
      let cleanedCount = 0
      
      for (const action of allPending) {
        // Check if the expense data is valid
        const expense = action.expenseData
        let shouldDelete = false
        let reason = ''
        
        if (!expense) {
          shouldDelete = true
          reason = 'No expense data'
        } else if (!expense.description) {
          shouldDelete = true
          reason = 'Missing description'
        } else if (expense.amount === undefined || expense.amount === null) {
          shouldDelete = true
          reason = 'Missing amount'
        } else if (!expense.date || expense.date === 'undefined') {
          shouldDelete = true
          reason = 'Missing or invalid date'
        } else if (action.type === 'delete' && !expense.id) {
          shouldDelete = true
          reason = 'Delete action for expense without server ID'
        } else if (action.attempts && action.attempts > 5) {
          shouldDelete = true
          reason = 'Too many failed attempts'
        } else if (action.type === 'delete' && expense.id) {
          // Smart detection for stale delete actions
          const actionAge = Date.now() - action.timestamp
          const isOld = actionAge > (2 * 60 * 1000) // Older than 2 minutes
          
          // If it's a delete action that's old and has already failed sync attempts,
          // it's likely stale (expense was deleted via bulk delete)
          if (isOld && action.attempts > 0) {
            shouldDelete = true
            reason = 'Stale delete action - old with failed sync attempts'
          }
          // Also remove very old delete actions regardless of attempts
          else if (actionAge > (10 * 60 * 1000)) { // Older than 10 minutes
            shouldDelete = true
            reason = 'Very old delete action - likely already processed'
          }
        }
        
        if (shouldDelete) {
          console.log(`🗑️ Removing invalid sync action (${reason}):`, action)
          await db.value.delete('pendingSync', action.id)
          cleanedCount++
        }
      }
      
      if (cleanedCount > 0) {
        console.log(`🧹 Cleaned up ${cleanedCount} invalid sync actions`)
        await loadPendingSync() // Refresh the reactive array
      } else {
        console.log('✅ No invalid sync actions found')
      }
      
    } catch (error) {
      console.error('Failed to cleanup invalid sync actions:', error)
    }
  }

  // UTILITY: Clear all pending sync actions (for debugging/reset)
  const clearPendingSync = async () => {
    if (!db.value) return
    
    try {
      await db.value.clear('pendingSync')
      pendingSync.value = []
      console.log('🧹 Cleared all pending sync actions')
    } catch (error) {
      console.error('Failed to clear pending sync:', error)
    }
  }

  // Vue Learning Point #19: Advanced Async Operations & Sync Logic
  
  // Sync pending changes with server
  const syncWithServer = async () => {
    if (!db.value || !navigator.onLine) {
      console.log('⏸️ Cannot sync: database not ready or offline')
      return false
    }

    if (syncStatus.isSyncing) {
      console.log('⏸️ Sync already in progress')
      return false
    }

    try {
      console.log('🔄 Starting sync process...')
      syncStatus.isSyncing = true
      syncStatus.errorCount = 0

      // Get all pending sync actions, sorted by timestamp
      const pendingActions = await db.value.getAll('pendingSync')
      const sortedActions = pendingActions.sort((a, b) => a.timestamp - b.timestamp)

      console.log(`📤 Found ${sortedActions.length} actions to sync`)

      let successCount = 0
      let errorCount = 0

      // Vue Learning Point #20: Processing arrays with async operations
      for (const action of sortedActions) {
        try {
          await processSyncAction(action)
          
          // Remove successful action from queue
          await db.value.delete('pendingSync', action.id)
          
          // Vue Learning Point #21: Real-time reactive updates during loops
          pendingSync.value = pendingSync.value.filter(p => p.id !== action.id)
          
          successCount++
          console.log(`✅ Synced ${action.type} for expense: ${action.expenseData.localId}`)
          
        } catch (error) {
          console.error(`❌ Failed to sync ${action.type}:`, error)
          
          // Increment attempt counter and update timestamp
          action.attempts++
          action.timestamp = Date.now()
          await db.value.put('pendingSync', action)
          
          errorCount++
          
          // Vue Learning Point #22: Error handling strategies
          // Stop if too many consecutive failures (avoid overwhelming server)
          if (errorCount >= 3) {
            console.log('🛑 Too many sync errors, stopping batch')
            break
          }
        }

        // Small delay between requests to be nice to server
        await new Promise(resolve => setTimeout(resolve, 100))
      }

      syncStatus.lastSync = Date.now()
      console.log(`🎉 Sync completed: ${successCount} successful, ${errorCount} errors`)
      
      return errorCount === 0

    } catch (error) {
      console.error('💥 Sync process failed:', error)
      syncStatus.errorCount++
      return false
      
    } finally {
      syncStatus.isSyncing = false
    }
  }

  // Process individual sync action
  const processSyncAction = async (action: PendingSyncAction): Promise<void> => {
    const { type, expenseData } = action

    // Vue Learning Point #23: Making API calls in composables
    switch (type) {
      case 'create':
        return await syncCreateExpense(expenseData)
      
      case 'update':
        return await syncUpdateExpense(expenseData)
      
      case 'delete':
        return await syncDeleteExpense(expenseData)
      
      default:
        throw new Error(`Unknown sync action type: ${type}`)
    }
  }

  // Get fresh CSRF token from server
  const getFreshCsrfToken = async (): Promise<string> => {
    try {
      const response = await fetch('/expense', {
        method: 'GET',
        headers: {
          'Accept': 'text/html',
          'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'include'
      })
      
      const html = await response.text()
      const match = html.match(/<meta name="csrf-token" content="([^"]+)"/)
      if (match) {
        // Update the meta tag with fresh token
        const metaTag = document.querySelector('meta[name="csrf-token"]')
        if (metaTag) {
          metaTag.setAttribute('content', match[1])
        }
        return match[1]
      }
      throw new Error('CSRF token not found in response')
    } catch (error) {
      console.error('Failed to get fresh CSRF token:', error)
      // Fallback to existing token
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    }
  }

  // Sync individual operations with server
  const syncCreateExpense = async (expense: OfflineExpense): Promise<void> => {
    // Debug the sync data
    const syncData = {
      description: expense.description,
      amount: expense.amount,
      date: expense.date
    }
    console.log('🔍 Syncing expense data:', syncData)
    
    // Get fresh CSRF token
    const csrfToken = await getFreshCsrfToken()
    console.log('🔍 CSRF token:', csrfToken ? 'Found' : 'Missing')
    
    const response = await fetch('/expense', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
        // Deliberately NOT sending X-Inertia header to get JSON response
      },
      credentials: 'include', // Include all cookies
      body: JSON.stringify(syncData)
    })

    if (!response.ok) {
      const responseText = await response.text()
      console.log('🚨 Sync failed - server response:', responseText.substring(0, 200))
      throw new Error(`Server responded with ${response.status}: ${response.statusText}`)
    }

    const responseText = await response.text()
    let serverExpense
    try {
      serverExpense = JSON.parse(responseText)
    } catch (parseError) {
      console.log('🚨 Invalid JSON response:', responseText.substring(0, 200))
      throw new Error('Server returned HTML instead of JSON - likely authentication issue')
    }
    
    // Vue Learning Point #24: Updating local data with server response
    // Remove from offline storage since it's now on the server
    await db.value!.delete('expenses', expense.localId)

    // Remove from reactive array
    const index = expenses.value.findIndex(e => e.localId === expense.localId)
    if (index !== -1) {
      expenses.value.splice(index, 1)
    }
    
    console.log(`✅ Moved expense "${expense.description}" from offline to server (ID: ${serverExpense.id})`)
  }

  const syncUpdateExpense = async (expense: OfflineExpense): Promise<void> => {
    if (!expense.id) throw new Error('Cannot update expense without server ID')

    const csrfToken = await getFreshCsrfToken()

    const response = await fetch(`/expense/${expense.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      },
      credentials: 'include',
      body: JSON.stringify({
        description: expense.description,
        amount: expense.amount,
        date: expense.date
      })
    })

    if (!response.ok) {
      throw new Error(`Server responded with ${response.status}: ${response.statusText}`)
    }

    // Remove from offline storage since it's now synced to server
    await db.value!.delete('expenses', expense.localId)

    // Remove from reactive array
    const index = expenses.value.findIndex(e => e.localId === expense.localId)
    if (index !== -1) {
      expenses.value.splice(index, 1)
    }
    
    console.log(`✅ Updated expense "${expense.description}" on server (ID: ${expense.id})`)
  }

  const syncDeleteExpense = async (expense: OfflineExpense): Promise<void> => {
    if (!expense.id) throw new Error('Cannot delete expense without server ID')

    const csrfToken = await getFreshCsrfToken()

    const response = await fetch(`/expense/${expense.id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      },
      credentials: 'include'
    })

    // Handle 404 as success - expense was already deleted
    if (response.status === 404) {
      console.log(`✅ Expense ${expense.id} was already deleted (404) - considering sync successful`)
      // Remove from local storage if it still exists
      try {
        await db.value!.delete('expenses', expense.localId)
      } catch (error) {
        console.log('Expense already removed from local storage')
      }
      return
    }

    if (!response.ok) {
      throw new Error(`Server responded with ${response.status}: ${response.statusText}`)
    }

    // Remove from local storage (already removed from expenses array during delete)
    await db.value!.delete('expenses', expense.localId)
  }

  // Vue Learning Point #25: Auto-sync on network changes
  const handleNetworkChange = async (isOnline: boolean) => {
    if (isOnline && hasUnsyncedData.value) {
      console.log('🌐 Back online with pending data, starting auto-sync...')
      // Small delay to ensure connection is stable
      setTimeout(() => {
        syncWithServer()
      }, 1000)
    }
  }

  // Initialize network change listeners
  const initNetworkSync = () => {
    // Listen for custom events from network status composable
    window.addEventListener('app:online', () => handleNetworkChange(true))
    window.addEventListener('app:offline', () => handleNetworkChange(false))
    
    // Listen for manual sync requests
    window.addEventListener('app:sync-requested', () => {
      if (navigator.onLine) {
        syncWithServer()
      }
    })
  }

  // Vue Learning Point #26: Complete composable with all functionality
  return {
    // Reactive state
    isInitialized,
    expenses,
    pendingSync,
    syncStatus,
    
    // Computed properties
    expenseCount,
    pendingSyncCount,
    hasUnsyncedData,
    
    // Database functions
    initDB,
    loadExpenses,
    loadPendingSync,
    
    // CRUD operations
    createExpenseOffline,
    updateExpenseOffline,
    deleteExpenseOffline,
    bulkDeleteExpensesOffline,
    clearOfflineData,
    cleanupInvalidSyncActions,
    clearPendingSync,
    
    // Sync functions
    syncWithServer,
    initNetworkSync,
    
    // Internal functions (exposed for testing/debugging)
    processSyncAction,
    handleNetworkChange,
    findExpenseById
  }
}

// Vue Learning Point #8: Why composables are powerful
// 1. Reusable logic across multiple components
// 2. Reactive data that automatically updates the UI
// 3. Easy to test in isolation
// 4. Clean separation of concerns
// 5. TypeScript support for better development experience
