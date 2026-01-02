import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { today, getLocalTimeZone } from '@internationalized/date';
import { useNetworkStatus } from '@/composables/useNetworkStatus';
import { useOfflineStorage } from '@/composables/useOfflineStorage';

export function useExpenseActions() {
    const { isOnline } = useNetworkStatus();
    const { createExpenseOffline, isInitialized } = useOfflineStorage();

    // Format currency for display
    const formatCurrency = (amount: string) => {
        const num = parseFloat(amount);
        if (isNaN(num)) return '';

        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(num);
    };

    // Parse friendly currency string back to number
    const parseCurrency = (value: string) => {
        return value.replace(/[^\d.]/g, '');
    };

    // Submit expense logic
    const submitExpense = async (
        formData: { description: string; amount: string; date: string; category_id: string | null },
        options: { onSuccess?: () => void; onError?: () => void } = {}
    ) => {
        if (!isInitialized.value) {
            toast.error('App is still initializing, please wait...');
            return;
        }

        try {
            if (isOnline.value) {
                // Online: Use Inertia form submission equivalent
                // We create a temporary form here just for the submission to use Inertia's router
                const form = useForm(formData);

                form.post(route('expense.store'), {
                    onSuccess: () => {
                        toast.success('Expense added successfully! 🎉');
                        options.onSuccess?.();
                    },
                    onError: () => {
                        toast.error('Failed to add expense. Please check your inputs.');
                        options.onError?.();
                    },
                });
            } else {
                // Offline: Save to local storage
                const user = usePage().props.auth?.user as any;

                await createExpenseOffline({
                    description: formData.description,
                    amount: formData.amount,
                    date: formData.date,
                    category_id: formData.category_id,
                    user_id: user?.id || 1
                });

                toast.success('Expense saved offline! 📴 Will sync when online.');
                options.onSuccess?.();
            }
        } catch (error) {
            console.error('Failed to create expense:', error);
            toast.error('Failed to save expense. Please try again.');
            options.onError?.();
        }
    };

    return {
        submitExpense,
        formatCurrency,
        parseCurrency
    };
}
