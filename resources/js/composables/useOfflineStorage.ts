import { ref, reactive, computed } from 'vue'
import { openDB, type DBSchema, type IDBPDatabase } from 'idb'

// Vue Learning Point #1: Defining TypeScript interfaces for our data
interface OfflineExpense {
  id: string // We'll use UUIDs for offline expenses
  description: string
  amount: string
  date: string
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

// Vue Learning Point #3: Creating a composable function
// Composables always start with 'use' and return reactive data/functions
export function useOfflineStorage() {
  // Vue Learning Point #4: Reactive state management
  // ref() - for primitive values (strings, numbers, booleans)
  // reactive() - for objects and arrays
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

  // Vue Learning Point #5: Computed properties
  // These automatically update when their dependencies change
  const expenseCount = computed(() => expenses.value.length)
  const pendingSyncCount = computed(() => pendingSync.value.length)
  const hasUnsyncedData = computed(() => pendingSyncCount.value > 0)

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
      expenses.value = allExpenses.sort((a, b) => 
        new Date(b.date).getTime() - new Date(a.date).getTime()
      )
      return allExpenses
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
      pendingSync.value = pending.sort((a, b) => a.timestamp - b.timestamp)
      return pending
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
  const updateExpenseOffline = async (localId: string, updateData: {
    description?: string
    amount?: string
    date?: string
  }) => {
    if (!db.value) throw new Error('Database not initialized')
    
    try {
      // Find existing expense
      const existingExpense = await db.value.get('expenses', localId)
      if (!existingExpense) throw new Error('Expense not found')
      
      // Create updated expense
      const updatedExpense: OfflineExpense = {
        ...existingExpense,
        ...updateData,
        updated_at: new Date().toISOString(),
        syncStatus: 'pending',
        lastModified: Date.now()
      }
      
      // Save to IndexedDB
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
      const index = expenses.value.findIndex(e => e.localId === localId)
      if (index !== -1) {
        expenses.value[index] = updatedExpense // Vue detects this change!
      }
      pendingSync.value.push(syncAction)
      
      console.log('✏️ Updated offline expense:', localId)
      return updatedExpense
      
    } catch (error) {
      console.error('Failed to update offline expense:', error)
      throw error
    }
  }

  // DELETE: Remove expense offline
  const deleteExpenseOffline = async (localId: string) => {
    if (!db.value) throw new Error('Database not initialized')
    
    try {
      // Get expense before deleting
      const expense = await db.value.get('expenses', localId)
      if (!expense) throw new Error('Expense not found')
      
      // Remove from IndexedDB
      await db.value.delete('expenses', localId)
      
      // Only add to sync queue if it was previously synced (has server ID)
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
      expenses.value = expenses.value.filter(e => e.localId !== localId)
      
      console.log('🗑️ Deleted offline expense:', localId)
      return true
      
    } catch (error) {
      console.error('Failed to delete offline expense:', error)
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

  // Sync individual operations with server
  const syncCreateExpense = async (expense: OfflineExpense): Promise<void> => {
    const response = await fetch('/expense', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        description: expense.description,
        amount: expense.amount,
        date: expense.date
      })
    })

    if (!response.ok) {
      throw new Error(`Server responded with ${response.status}: ${response.statusText}`)
    }

    const serverExpense = await response.json()
    
    // Vue Learning Point #24: Updating local data with server response
    // Update local expense with server ID and mark as synced
    const updatedExpense: OfflineExpense = {
      ...expense,
      id: serverExpense.id.toString(),
      syncStatus: 'synced',
      lastModified: Date.now()
    }

    await db.value!.put('expenses', updatedExpense)

    // Update reactive array
    const index = expenses.value.findIndex(e => e.localId === expense.localId)
    if (index !== -1) {
      expenses.value[index] = updatedExpense
    }
  }

  const syncUpdateExpense = async (expense: OfflineExpense): Promise<void> => {
    if (!expense.id) throw new Error('Cannot update expense without server ID')

    const response = await fetch(`/expense/${expense.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        description: expense.description,
        amount: expense.amount,
        date: expense.date
      })
    })

    if (!response.ok) {
      throw new Error(`Server responded with ${response.status}: ${response.statusText}`)
    }

    // Mark as synced
    const syncedExpense = { ...expense, syncStatus: 'synced' as const }
    await db.value!.put('expenses', syncedExpense)

    // Update reactive array
    const index = expenses.value.findIndex(e => e.localId === expense.localId)
    if (index !== -1) {
      expenses.value[index] = syncedExpense
    }
  }

  const syncDeleteExpense = async (expense: OfflineExpense): Promise<void> => {
    if (!expense.id) throw new Error('Cannot delete expense without server ID')

    const response = await fetch(`/expense/${expense.id}`, {
      method: 'DELETE',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })

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
    clearOfflineData,
    
    // Sync functions
    syncWithServer,
    initNetworkSync,
    
    // Internal functions (exposed for testing/debugging)
    processSyncAction,
    handleNetworkChange
  }
}

// Vue Learning Point #8: Why composables are powerful
// 1. Reusable logic across multiple components
// 2. Reactive data that automatically updates the UI
// 3. Easy to test in isolation
// 4. Clean separation of concerns
// 5. TypeScript support for better development experience