import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Budget, BudgetStatus } from '@/types/budget';

export function useBudget() {
    const isLoading = ref(false);
    const selectedMonth = ref(new Date().toISOString().slice(0, 7));
    const setMonth = (yearMonth: string) => {
        selectedMonth.value = yearMonth;
        const [year, month] = yearMonth.split('-');
        router.get(route('budget.index'), { year, month }, { preserveState: true });
    };
    const previousMonth = () => {
        const date = new Date(selectedMonth.value + '-01');
        date.setMonth(date.getMonth() - 1);
        setMonth(date.toISOString().slice(0, 7));
    };
    const nextMonth = () => {
        const date = new Date(selectedMonth.value + '-01');
        date.setMonth(date.getMonth() + 1);
        setMonth(date.toISOString().slice(0, 7));
    };
    const createBudget = (data: Partial<Budget>) => {
        isLoading.value = true;
        router.post(route('budget.store'), data, {
            onFinish: () => isLoading.value = false,
        });
    };
    const updateBudget = (id: number, data: Partial<Budget>) => {
        isLoading.value = true;
        router.put(route('budget.update', id), data, {
            onFinish: () => isLoading.value = false,
        });
    };
    const deleteBudget = (id: number) => {
        if (confirm('Are you sure you want to delete this budget?')) {
            router.delete(route('budget.destroy', id));
        }
    };
    const dismissAlert = (alertId: number) => {
        router.post(route('budget.dismissAlert', alertId));
    };
    const getStatusColor = (status: BudgetStatus): string => {
        const colors = {
            safe: 'text-green-500',
            warning: 'text-yellow-500',
            critical: 'text-orange-500',
            over_budget: 'text-red-500',
        };
        return colors[status] || 'text-gray-500';
    };
    const getStatusBgColor = (status: BudgetStatus): string => {
        const colors = {
            safe: 'bg-green-500',
            warning: 'bg-yellow-500',
            critical: 'bg-orange-500',
            over_budget: 'bg-red-500',
        };
        return colors[status] || 'bg-gray-500';
    };
    const formatCurrency = (amount: number): string => {
        return '₦' + amount.toLocaleString();
    };

    return {
        isLoading,
        selectedMonth,
        setMonth,
        previousMonth,
        nextMonth,
        createBudget,
        updateBudget,
        deleteBudget,
        dismissAlert,
        getStatusColor,
        getStatusBgColor,
        formatCurrency,
    };
}
