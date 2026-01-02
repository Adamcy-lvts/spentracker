<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { LoaderCircle, Plus, Wallet, TrendingUp, Pencil, Trash2, Repeat } from 'lucide-vue-next';
import { ref } from 'vue';
import { useIncome } from '@/composables/useIncome';
import type { Income } from '@/types/income';
import type { Category } from '@/types/category';
import MonthNavigator from '@/components/MonthNavigator.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

const props = defineProps<{
    incomes: Income[];
    totalIncome: number;
    sources: string[];
    selectedMonth: string;
    categories: Category[];
}>();

const {
    selectedMonth,
    previousMonth,
    nextMonth,
    deleteIncome
} = useIncome();

// Initialize selectedMonth from props
selectedMonth.value = props.selectedMonth;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Income',
        href: '/income',
    },
];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(amount);
};

// Form state
const createDialogOpen = ref(false);
const editDialogOpen = ref(false);
const editingIncome = ref<Income | null>(null);

const form = useForm({
    source: '',
    amount: '',
    date: new Date().toISOString().split('T')[0],
    description: '',
    category_id: null as number | null,
    is_recurring: false as boolean,
    recurrence_type: null as string | null,
});

const openCreateDialog = () => {
    form.reset();
    form.clearErrors();
    form.date = new Date().toISOString().split('T')[0];
    createDialogOpen.value = true;
};

const openEditDialog = (income: Income) => {
    editingIncome.value = income;
    form.source = income.source;
    form.amount = income.amount.toString();
    form.date = income.date;
    form.description = income.description || '';
    form.category_id = income.category_id;
    form.is_recurring = income.is_recurring;
    form.recurrence_type = income.recurrence_type;
    editDialogOpen.value = true;
};

const submitCreate = () => {
    form.post(route('income.store'), {
        onSuccess: () => {
            createDialogOpen.value = false;
            toast.success('Income added successfully');
            form.reset();
        }
    });
};

const submitUpdate = () => {
    if (!editingIncome.value) return;
    form.put(route('income.update', editingIncome.value.id), {
        onSuccess: () => {
            editDialogOpen.value = false;
            toast.success('Income updated successfully');
            editingIncome.value = null;
        }
    });
};

const handleDelete = (id: number) => {
    deleteIncome(id);
};

// Recurrence options
const recurrenceTypes = [
    { label: 'Weekly', value: 'weekly' },
    { label: 'Bi-Weekly', value: 'biweekly' },
    { label: 'Monthly', value: 'monthly' },
    { label: 'Quarterly', value: 'quarterly' },
    { label: 'Yearly', value: 'yearly' },
];

</script>

<template>

    <Head title="Income" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 overflow-x-hidden animate-in fade-in duration-500">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h1
                        class="text-3xl font-bold tracking-tight bg-gradient-to-r from-foreground to-foreground/70 bg-clip-text text-transparent">
                        Income Tracking
                    </h1>
                    <p class="text-sm text-muted-foreground text-balance">Manage your earnings and recurring income
                        sources.</p>
                </div>

                <div class="flex items-center gap-3">
                    <MonthNavigator :selected-month="selectedMonth" @previous="previousMonth" @next="nextMonth" />
                    <Button @click="openCreateDialog" class="bg-primary hover:bg-primary/90">
                        <Plus class="w-4 h-4 mr-2" />
                        Add Income
                    </Button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Income</CardTitle>
                        <Wallet class="h-4 w-4 text-emerald-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatCurrency(totalIncome) }}</div>
                        <p class="text-xs text-muted-foreground mt-1 text-balance">For {{ new
                            Date(selectedMonth).toLocaleDateString('default', { month: 'long', year: 'numeric' }) }}</p>
                    </CardContent>
                </Card>

                <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Transactions</CardTitle>
                        <TrendingUp class="h-4 w-4 text-blue-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ incomes.length }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Income entries this month</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Incomes Table -->
            <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm">
                <CardHeader>
                    <CardTitle class="text-lg font-semibold">Incomes</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="incomes.length === 0"
                        class="flex flex-col items-center justify-center py-12 text-muted-foreground">
                        <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center mb-4">
                            <Wallet class="h-8 w-8 opacity-50" />
                        </div>
                        <p class="font-medium">No income entries found</p>
                        <p class="text-sm mt-1">Add your first income for this month!</p>
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-border/50">
                                    <th class="pb-3 font-medium text-muted-foreground pl-2">Source</th>
                                    <th class="pb-3 font-medium text-muted-foreground">Date</th>
                                    <th class="pb-3 font-medium text-muted-foreground">Description</th>
                                    <th class="pb-3 font-medium text-muted-foreground text-right">Amount</th>
                                    <th class="pb-3 font-medium text-muted-foreground text-right pr-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/50">
                                <tr v-for="income in incomes" :key="income.id"
                                    class="group hover:bg-muted/50 transition-colors">
                                    <td class="py-4 pl-2">
                                        <div class="flex items-center gap-2">
                                            <div v-if="income.is_recurring" class="h-2 w-2 rounded-full bg-emerald-500"
                                                title="Recurring"></div>
                                            <span class="font-medium">{{ income.source }}</span>
                                            <Repeat v-if="income.is_recurring" class="h-3 w-3 text-muted-foreground" />
                                        </div>
                                    </td>
                                    <td class="py-4 text-muted-foreground">
                                        {{ new Date(income.date).toLocaleDateString('en-US', {
                                            month: 'short', day:
                                        'numeric' }) }}
                                    </td>
                                    <td class="py-4 text-muted-foreground truncate max-w-[200px]">
                                        {{ income.description || '-' }}
                                    </td>
                                    <td class="py-4 text-right font-bold">
                                        {{ formatCurrency(income.amount) }}
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="icon" @click="openEditDialog(income)"
                                                class="h-8 w-8 hover:text-primary">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                            <Button variant="ghost" size="icon" @click="handleDelete(income.id)"
                                                class="h-8 w-8 hover:text-red-500 font-bold">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Add Income Dialog -->
        <Dialog :open="createDialogOpen" @update:open="createDialogOpen = $event">
            <DialogContent class="sm:max-w-[450px]">
                <DialogHeader>
                    <DialogTitle>Add Income</DialogTitle>
                    <DialogDescription>Enter the details of your new income source.</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitCreate" class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label for="source">Source</Label>
                        <Input id="source" v-model="form.source" placeholder="e.g. Salary, Freelance" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="amount">Amount</Label>
                            <Input id="amount" type="number" v-model="form.amount" step="0.01" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="date">Date</Label>
                            <Input id="date" type="date" v-model="form.date" required />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="description">Description (Optional)</Label>
                        <Input id="description" v-model="form.description" placeholder="Short note..." />
                    </div>

                    <div class="flex flex-col gap-4 pt-2">
                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_recurring" :checked="form.is_recurring"
                                @update:checked="form.is_recurring = $event" />
                            <Label for="is_recurring" class="text-sm font-medium leading-none cursor-pointer">Recurring
                                income</Label>
                        </div>

                        <div v-if="form.is_recurring" class="space-y-2 animate-in slide-in-from-top-2 duration-200">
                            <Label for="recurrence_type">Recurrence Period</Label>
                            <Select v-model="form.recurrence_type">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select period" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="type in recurrenceTypes" :key="type.value" :value="type.value">
                                        {{ type.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="createDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Add Income
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit Income Dialog -->
        <Dialog :open="editDialogOpen" @update:open="editDialogOpen = $event">
            <DialogContent class="sm:max-w-[450px]">
                <DialogHeader>
                    <DialogTitle>Edit Income</DialogTitle>
                    <DialogDescription>Update the details of your income source.</DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitUpdate" class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label for="source_edit">Source</Label>
                        <Input id="source_edit" v-model="form.source" placeholder="e.g. Salary, Freelance" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="amount_edit">Amount</Label>
                            <Input id="amount_edit" type="number" v-model="form.amount" step="0.01" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="date_edit">Date</Label>
                            <Input id="date_edit" type="date" v-model="form.date" required />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="description_edit">Description (Optional)</Label>
                        <Input id="description_edit" v-model="form.description" placeholder="Short note..." />
                    </div>

                    <div class="flex flex-col gap-4 pt-2">
                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_recurring_edit" :checked="form.is_recurring"
                                @update:checked="form.is_recurring = $event" />
                            <Label for="is_recurring_edit"
                                class="text-sm font-medium leading-none cursor-pointer">Recurring income</Label>
                        </div>

                        <div v-if="form.is_recurring" class="space-y-2 animate-in slide-in-from-top-2 duration-200">
                            <Label for="recurrence_type_edit">Recurrence Period</Label>
                            <Select v-model="form.recurrence_type">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select period" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="type in recurrenceTypes" :key="type.value" :value="type.value">
                                        {{ type.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="editDialogOpen = false">Cancel</Button>
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Update Income
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
