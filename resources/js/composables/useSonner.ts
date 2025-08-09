import { toast } from 'vue-sonner'

export function useSonner() {
  
  // Success toast with green styling
  const success = (message: string, data?: any) => {
    return toast.success(message, {
      duration: 4000,
      ...data
    })
  }

  // Error toast with red styling
  const error = (message: string, data?: any) => {
    return toast.error(message, {
      duration: 5000, // Longer duration for errors
      ...data
    })
  }

  // Info toast with blue styling
  const info = (message: string, data?: any) => {
    return toast.info(message, {
      duration: 4000,
      ...data
    })
  }

  // Warning toast with yellow styling
  const warning = (message: string, data?: any) => {
    return toast.warning(message, {
      duration: 4000,
      ...data
    })
  }

  // Basic toast (default styling)
  const message = (text: string, data?: any) => {
    return toast(text, {
      duration: 4000,
      ...data
    })
  }

  // Custom toast with action button
  const action = (message: string, actionLabel: string, actionFn: () => void, data?: any) => {
    return toast(message, {
      action: {
        label: actionLabel,
        onClick: actionFn,
      },
      duration: 6000, // Longer duration for actionable toasts
      ...data
    })
  }

  // Promise toast - shows loading, success, or error based on promise result
  const promise = <T>(
    promise: Promise<T>,
    messages: {
      loading: string
      success: string | ((data: T) => string)
      error: string | ((error: any) => string)
    }
  ) => {
    return toast.promise(promise, messages)
  }

  // Dismiss a specific toast by ID
  const dismiss = (toastId?: string | number) => {
    toast.dismiss(toastId)
  }

  // Loading toast - shows a spinner
  const loading = (message: string, data?: any) => {
    return toast.loading(message, {
      duration: Infinity, // Don't auto-dismiss loading toasts
      ...data
    })
  }

  return {
    success,
    error,
    info,
    warning,
    message,
    action,
    promise,
    dismiss,
    loading
  }
}