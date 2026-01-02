import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Income } from '@/types/income';

export function useIncome() {
    const isLoading = ref(false);
    const selectedMonth = ref(new Date().toISOString().slice(0, 7));
    const setMonth = (yearMonth: string) => {
        selectedMonth.value = yearMonth;
        const [year, month] = yearMonth.split('-');
        router.get(route('income.index'), { year, month }, { preserveState: true });
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
    const addIncome = (data: Partial<Income>) => {
        isLoading.value = true;
        router.post(route('income.store'), data, {
            onFinish: () => isLoading.value = false,
        });
    };
    const updateIncome = (id: number, data: Partial<Income>) => {
        isLoading.value = true;
        router.put(route('income.update', id), data, {
            onFinish: () => isLoading.value = false,
        });
    };
    const deleteIncome = (id: number) => {
        if (confirm('Are you sure you want to delete this income?')) {
            router.delete(route('income.destroy', id));
        }
    };
    return {
        isLoading,
        selectedMonth,
        setMonth,
        previousMonth,
        nextMonth,
        addIncome,
        updateIncome,
        deleteIncome,
    };
}
