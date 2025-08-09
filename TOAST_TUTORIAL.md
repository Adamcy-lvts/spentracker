# Toast Notification System Tutorial

## File 1: useToast.ts (The Data Manager)

```typescript
// Import Vue's reactive functions
// 'ref' makes data reactive (auto-updates UI when changed)
// 'readonly' prevents external modification of our internal state
import { ref, readonly } from 'vue'

// Define what a single toast notification looks like
// This is like a blueprint - every toast must have these properties
interface Toast {
  id: string        // Unique identifier (so we can find and remove specific toasts)
  message: string   // The text to show the user
  type: 'success' | 'error' | 'info'  // What kind of message (for different colors)
  duration: number  // How long to show the toast (in milliseconds)
}

// Create our main data storage
// ref([]) creates a reactive array that Vue will watch for changes
// When this array changes, any component using it will automatically update
const toasts = ref<Toast[]>([])

// Export our main function that other files can use
export function useToast() {
  
  // Function to add a new toast to our list
  const addToast = (
    message: string,                                    // What to display
    type: 'success' | 'error' | 'info' = 'success',   // Default to success
    duration = 4000                                     // Default to 4 seconds
  ) => {
    // Create a unique ID using the current timestamp
    // Date.now() gives us milliseconds since 1970 - guaranteed unique
    const id = Date.now().toString()
    
    // Create our toast object using the interface blueprint
    const toast: Toast = {
      id,       // Same as id: id
      message,  // Same as message: message
      type,     // Same as type: type
      duration, // Same as duration: duration
    }

    // Add the new toast to our reactive array
    // .value is needed because toasts is a ref()
    toasts.value.push(toast)

    // Set up automatic removal after the specified duration
    // setTimeout runs a function after a delay
    setTimeout(() => {
      removeToast(id)  // Remove this specific toast
    }, duration)

    // Return the ID in case the caller wants to manually remove it early
    return id
  }

  // Function to remove a toast from our list
  const removeToast = (id: string) => {
    // Find the position of the toast with this ID
    const index = toasts.value.findIndex(toast => toast.id === id)
    
    // If we found it (index will be -1 if not found)
    if (index > -1) {
      // Remove 1 item at the found position
      toasts.value.splice(index, 1)
    }
  }

  // Convenience functions - these are shortcuts for common toast types
  // Users can call success('Message') instead of addToast('Message', 'success')
  
  const success = (message: string, duration?: number) => {
    return addToast(message, 'success', duration)
  }

  const error = (message: string, duration?: number) => {
    return addToast(message, 'error', duration)
  }

  const info = (message: string, duration?: number) => {
    return addToast(message, 'info', duration)
  }

  // Return all the functions and data that other files can use
  return {
    toasts: readonly(toasts),  // readonly prevents external code from directly modifying our array
    addToast,
    removeToast,
    success,
    error,
    info,
  }
}
```

## File 2: ToastContainer.vue (The Visual Component)

```vue
<script setup lang="ts">
// Import our composable to access toast data and functions
import { useToast } from '@/composables/useToast'
// Import Vue icons for the close button
import { X } from 'lucide-vue-next'
// Import Teleport - this lets us render our toasts at the body level
// (outside of our component hierarchy)
import { Teleport } from 'vue'

// Get access to our toast system
const { toasts, removeToast } = useToast()

// Function to handle manual toast dismissal when user clicks X
const dismissToast = (id: string) => {
  removeToast(id)
}

// Function to determine what CSS classes to apply based on toast type
const getToastClasses = (type: 'success' | 'error' | 'info') => {
  // Base classes that all toasts share
  const base = 'flex items-center justify-between p-4 mb-3 rounded-lg shadow-lg backdrop-blur-sm border'
  
  // Different colors for different types
  switch (type) {
    case 'success':
      return `${base} bg-green-50 border-green-200 text-green-800 dark:bg-green-900/50 dark:border-green-700 dark:text-green-200`
    case 'error':
      return `${base} bg-red-50 border-red-200 text-red-800 dark:bg-red-900/50 dark:border-red-700 dark:text-red-200`
    case 'info':
      return `${base} bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/50 dark:border-blue-700 dark:text-blue-200`
    default:
      return base
  }
}
</script>

<template>
  <!-- Teleport renders our toasts at the body level, outside normal component flow -->
  <!-- This ensures toasts appear on top of everything else -->
  <Teleport to="body">
    <!-- Container positioned at top-right of screen -->
    <div 
      class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full"
      style="pointer-events: none;"  <!-- Let clicks pass through empty space -->
    >
      <!-- Loop through all toasts and render each one -->
      <div
        v-for="toast in toasts"
        :key="toast.id"                    <!-- Vue needs unique keys for list items -->
        :class="getToastClasses(toast.type)"  <!-- Apply styling based on toast type -->
        class="transform transition-all duration-300 ease-in-out"  <!-- Smooth animations -->
        style="pointer-events: auto;"      <!-- But allow clicks on actual toasts -->
      >
        <!-- Left side: The message -->
        <div class="flex-1 mr-3">
          <p class="text-sm font-medium">
            {{ toast.message }}
          </p>
        </div>
        
        <!-- Right side: Close button -->
        <button
          @click="dismissToast(toast.id)"    <!-- When clicked, remove this toast -->
          class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-black/10 dark:hover:bg-white/10 focus:ring-2 focus:ring-current transition-colors"
        >
          <!-- Screen reader text for accessibility -->
          <span class="sr-only">Close</span>
          <!-- X icon from lucide-vue-next -->
          <X class="w-4 h-4" />
        </button>
        
        <!-- Progress bar animation (shows how much time is left) -->
        <div 
          class="absolute bottom-0 left-0 h-1 bg-current opacity-30 rounded-b-lg"
          :style="`
            animation: shrink ${toast.duration}ms linear forwards;
          `"
        ></div>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
/* CSS animation for the progress bar */
/* It starts at full width (100%) and shrinks to 0% over the duration */
@keyframes shrink {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}
</style>
```

## File 3: AppShell.vue (Making Toasts Global)

```vue
<script setup lang="ts">
// Import the sidebar provider for layout
import { SidebarProvider } from '@/components/ui/sidebar';
// Import Inertia's page helper
import { usePage } from '@inertiajs/vue3';
// Import our toast container component
import ToastContainer from '@/components/ToastContainer.vue';

// Define what props this component accepts
interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

// Get sidebar state from Inertia
const isOpen = usePage().props.sidebarOpen;
</script>

<template>
    <!-- Conditional rendering based on variant -->
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <!-- This is where page content goes -->
        <slot />
    </div>
    <SidebarProvider v-else :default-open="isOpen">
        <!-- This is where page content goes when using sidebar -->
        <slot />
    </SidebarProvider>
    
    <!-- IMPORTANT: This makes toasts available on every page -->
    <!-- Because AppShell wraps all our pages, ToastContainer is always present -->
    <ToastContainer />
</template>
```

## How to Use the Toast System

In any Vue component (like our Expense.vue page):

```vue
<script setup>
// Import the composable
import { useToast } from '@/composables/useToast'

// Get the functions we need
const { success, error, info } = useToast()

// Use them in your functions
const saveExpense = () => {
    form.post(route('expense.store'), {
        onSuccess: () => {
            // Show success message
            success('Expense saved successfully! 🎉')
        },
        onError: () => {
            // Show error message
            error('Failed to save expense. Please try again.')
        }
    })
}
</script>
```

## Key Vue.js Concepts Explained

### 1. Reactive Data (ref)
```javascript
const toasts = ref([])
// Vue automatically updates any component using 'toasts' when it changes
```

### 2. Composables
- Reusable functions that contain reactive state and logic
- Can be shared between multiple components
- Follow the pattern: `use` + `FunctionName`

### 3. Teleport
- Renders content outside the normal component tree
- Perfect for modals, toasts, tooltips that need to be "on top"

### 4. Component Communication
- Components don't talk directly to each other
- They share data through composables (global state)

### 5. TypeScript Interfaces
- Define the shape/structure of objects
- Help catch errors during development
- Make code more maintainable

## Why This Architecture?

1. **Separation of Concerns**: Logic (composable) separate from UI (component)
2. **Reusability**: Any component can easily use toasts
3. **Global State**: All components share the same toast list
4. **Type Safety**: TypeScript prevents errors
5. **Accessibility**: Screen reader support, keyboard navigation
6. **Performance**: Only updates when needed (reactive system)

This pattern can be applied to create other global features like:
- Modal systems
- Loading indicators  
- User notifications
- Shopping cart state
- Theme management