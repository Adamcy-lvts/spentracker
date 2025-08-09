<script setup lang="ts">
import { computed } from 'vue'
import { useNetworkStatus } from '@/composables/useNetworkStatus'
import { useOfflineStorage } from '@/composables/useOfflineStorage'
import { Wifi, WifiOff, CloudOff, RotateCw, AlertCircle } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

// Vue Learning Point #19: Using multiple composables in a component
const { isOnline, connectionType, isSlowConnection } = useNetworkStatus()
const { pendingSyncCount, hasUnsyncedData, syncStatus } = useOfflineStorage()

// Vue Learning Point #20: Complex computed properties
const statusInfo = computed(() => {
  if (!isOnline.value) {
    return {
      icon: WifiOff,
      message: 'You\'re offline',
      description: `${pendingSyncCount.value} changes will sync when online`,
      color: 'text-orange-600',
      bgColor: 'bg-orange-50 border-orange-200',
      showSync: false
    }
  }

  if (hasUnsyncedData.value) {
    return {
      icon: CloudOff,
      message: 'Syncing pending changes',
      description: `${pendingSyncCount.value} changes waiting to sync`,
      color: 'text-blue-600',
      bgColor: 'bg-blue-50 border-blue-200',
      showSync: true
    }
  }

  if (isSlowConnection.value) {
    return {
      icon: AlertCircle,
      message: 'Slow connection',
      description: `Connected via ${connectionType.value}`,
      color: 'text-yellow-600',
      bgColor: 'bg-yellow-50 border-yellow-200',
      showSync: false
    }
  }

  return {
    icon: Wifi,
    message: 'You\'re online',
    description: 'All changes are synced',
    color: 'text-green-600',
    bgColor: 'bg-green-50 border-green-200',
    showSync: false
  }
})

// Vue Learning Point #21: Emitting events to parent components
const emit = defineEmits<{
  syncRequested: []
}>()

const handleSyncClick = () => {
  emit('syncRequested')
}
</script>

<template>
  <!-- Vue Learning Point #22: Conditional rendering and dynamic classes -->
  <div 
    v-if="!isOnline || hasUnsyncedData || isSlowConnection"
    class="fixed bottom-4 right-4 z-50 max-w-sm"
  >
    <div 
      :class="[
        'rounded-lg border p-4 shadow-lg transition-all duration-300',
        statusInfo.bgColor
      ]"
    >
      <div class="flex items-start gap-3">
        <!-- Dynamic icon component -->
        <component 
          :is="statusInfo.icon" 
          :class="['h-5 w-5 mt-0.5 flex-shrink-0', statusInfo.color]"
        />
        
        <div class="flex-1 min-w-0">
          <h4 :class="['text-sm font-medium', statusInfo.color]">
            {{ statusInfo.message }}
          </h4>
          <p class="text-xs text-gray-600 mt-1">
            {{ statusInfo.description }}
          </p>
          
          <!-- Vue Learning Point #23: Conditional buttons -->
          <Button 
            v-if="statusInfo.showSync && !syncStatus.isSyncing"
            @click="handleSyncClick"
            size="sm"
            variant="outline"
            class="mt-2 h-7 text-xs"
          >
            <RotateCw class="w-3 h-3 mr-1" />
            Sync Now
          </Button>

          <div 
            v-else-if="syncStatus.isSyncing"
            class="flex items-center mt-2 text-xs text-gray-600"
          >
            <RotateCw class="w-3 h-3 mr-1 animate-spin" />
            Syncing...
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- Vue Learning Point #24: Scoped styles -->
<style scoped>
/* Vue automatically scopes these styles to this component only */
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>