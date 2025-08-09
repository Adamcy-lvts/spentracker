import { ref, onMounted, onUnmounted } from 'vue'
import { useOnline } from '@vueuse/core'

// Vue Learning Point #12: Network Status Management
export function useNetworkStatus() {
  // Vue Learning Point #13: Using VueUse for common patterns
  // @vueuse/core provides many useful composables
  const isOnline = useOnline()
  
  // Additional reactive state for our app
  const connectionType = ref<string>('unknown')
  const lastOnlineTime = ref<Date | null>(null)
  const lastOfflineTime = ref<Date | null>(null)
  const isSlowConnection = ref(false)
  
  // Vue Learning Point #14: Event handlers as reactive functions
  const handleOnline = () => {
    console.log('🌐 Back online!')
    lastOnlineTime.value = new Date()
    
    // Dispatch custom event for other parts of the app to listen
    window.dispatchEvent(new CustomEvent('app:online'))
  }

  const handleOffline = () => {
    console.log('📵 Gone offline!')
    lastOfflineTime.value = new Date()
    
    // Dispatch custom event
    window.dispatchEvent(new CustomEvent('app:offline'))
  }

  const handleConnectionChange = () => {
    // Check connection type (if supported by browser)
    if ('connection' in navigator) {
      const connection = (navigator as any).connection
      connectionType.value = connection.effectiveType || 'unknown'
      
      // Consider 2g/slow-2g as slow connections
      isSlowConnection.value = connection.effectiveType === '2g' || connection.effectiveType === 'slow-2g'
      
      console.log(`📡 Connection type: ${connectionType.value}`)
    }
  }

  // Vue Learning Point #15: Lifecycle Hooks
  // onMounted runs after the component is mounted to the DOM
  onMounted(() => {
    console.log('🎣 Setting up network event listeners...')
    
    // Set initial connection info
    handleConnectionChange()
    
    // Listen for online/offline events
    window.addEventListener('online', handleOnline)
    window.addEventListener('offline', handleOffline)
    
    // Listen for connection changes (mobile)
    if ('connection' in navigator) {
      (navigator as any).connection.addEventListener('change', handleConnectionChange)
    }
    
    // Set initial state
    if (navigator.onLine) {
      lastOnlineTime.value = new Date()
    } else {
      lastOfflineTime.value = new Date()
    }
  })

  // Vue Learning Point #16: Cleanup with onUnmounted
  // ALWAYS clean up event listeners to prevent memory leaks
  onUnmounted(() => {
    console.log('🧹 Cleaning up network event listeners...')
    
    window.removeEventListener('online', handleOnline)
    window.removeEventListener('offline', handleOffline)
    
    if ('connection' in navigator) {
      (navigator as any).connection.removeEventListener('change', handleConnectionChange)
    }
  })

  // Vue Learning Point #17: Computed-like getters
  const getConnectionStatus = () => ({
    isOnline: isOnline.value,
    connectionType: connectionType.value,
    isSlowConnection: isSlowConnection.value,
    lastOnlineTime: lastOnlineTime.value,
    lastOfflineTime: lastOfflineTime.value,
    timeSinceLastOnline: lastOnlineTime.value 
      ? Date.now() - lastOnlineTime.value.getTime()
      : null
  })

  // Utility functions for components to use
  const waitForOnline = (): Promise<void> => {
    return new Promise((resolve) => {
      if (isOnline.value) {
        resolve()
        return
      }
      
      const handleOnlineOnce = () => {
        window.removeEventListener('online', handleOnlineOnce)
        resolve()
      }
      
      window.addEventListener('online', handleOnlineOnce)
    })
  }

  return {
    // Reactive state
    isOnline,
    connectionType,
    lastOnlineTime,
    lastOfflineTime,
    isSlowConnection,
    
    // Functions
    getConnectionStatus,
    waitForOnline,
    
    // Event handlers (exposed for manual triggering if needed)
    handleOnline,
    handleOffline,
    handleConnectionChange
  }
}

// Vue Learning Point #18: Why this composable is powerful:
// 1. Automatically sets up and cleans up event listeners
// 2. Provides reactive network status across your entire app
// 3. Dispatches custom events for loose coupling between components
// 4. Handles edge cases (slow connections, connection type detection)
// 5. Provides utility functions like waitForOnline()