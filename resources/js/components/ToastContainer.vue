<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 w-80">
      <TransitionGroup 
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-x-full"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-full"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="relative overflow-hidden rounded-lg border bg-card text-card-foreground shadow-lg pointer-events-auto"
          :class="{
            'border-green-200 dark:border-green-800': toast.type === 'success',
            'border-red-200 dark:border-red-800': toast.type === 'error',
            'border-blue-200 dark:border-blue-800': toast.type === 'info',
          }"
        >
          <div class="flex items-center justify-between p-4">
            <div class="flex items-center">
              <div 
                class="flex-shrink-0 w-5 h-5 mr-3"
                :class="{
                  'text-green-500': toast.type === 'success',
                  'text-red-500': toast.type === 'error',
                  'text-blue-500': toast.type === 'info',
                }"
              >
                <!-- Success Icon -->
                <svg v-if="toast.type === 'success'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                
                <!-- Error Icon -->
                <svg v-else-if="toast.type === 'error'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                
                <!-- Info Icon -->
                <svg v-else fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              
              <p class="text-sm font-medium text-foreground">{{ toast.message }}</p>
            </div>
            
            <button
              @click="removeToast(toast.id)"
              class="flex-shrink-0 ml-4 text-muted-foreground hover:text-foreground transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Progress Bar -->
          <div 
            class="absolute bottom-0 left-0 h-1 bg-current opacity-30"
            :class="{
              'text-green-500': toast.type === 'success',
              'text-red-500': toast.type === 'error',
              'text-blue-500': toast.type === 'info',
            }"
            :style="{
              width: '100%',
              animation: `shrink ${toast.duration}ms linear forwards`
            }"
          ></div>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { useToast } from '@/composables/useToast'

const { toasts, removeToast } = useToast()
</script>

<style scoped>
@keyframes shrink {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}
</style>