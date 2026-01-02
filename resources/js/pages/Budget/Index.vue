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
import {
    LoaderCircle, Plus, PieChart, AlertTriangle, Bell,
    Target, PiggyBank, X
} from 'lucide-vue-next';
import { ref } from 'vue';
import { useBudget } from '@/composables/useBudget';
import type { Budget, BudgetSummary } from '@/types/budget';
import type { Category } from '@/types/category';
import MonthNavigator from '@/components/MonthNavigator.vue';
import BudgetCard from '@/components/BudgetCard.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

const props = defineProps<{
    summary: BudgetSummary;
    categories: Category[];
    selectedMonth: string;
}>();

const {
    selectedMonth,
    previousMonth,
    nextMonth,
    deleteBudget,
    dismissAlert,
    getStatusColor
} = useBudget();

// Initialize selectedMonth from props
selectedMonth.value = props.selectedMonth;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Budget',
        href: '/budget',
    },
];

const formatCurrency = (amount: number) => {
    return '₦' + amount.toLocaleString();
};

// Form state
const createDialogOpen = ref(false);
const editDialogOpen = ref(false);
const editingBudget = ref<Budget | null>(null);

const form = useForm({
    budget_type: 'category' as 'overall' | 'category',
    category_id: null as number | null,
    amount: '',
    period_type: 'monthly' as 'monthly' | 'custom',
    start_date: new Date().toISOString().split('T')[0],
    end_date: '' as string,
    is_recurring: true as boolean,
    alert_at_80: true as boolean,
    alert_at_100: true as boolean,
    alert_over_budget: true as boolean,
    enable_notifications: true as boolean,
});

const openCreateDialog = () => {
    form.reset();
    form.clearErrors();
    form.start_date = new Date().toISOString().split('T')[0];
    createDialogOpen.value = true;
};

const openEditDialog = (budget: Budget) => {
    editingBudget.value = budget;
    form.budget_type = budget.budget_type;
    form.category_id = budget.category_id;
    form.amount = budget.amount.toString();
    form.period_type = budget.period_type;
    form.start_date = budget.start_date;
    form.end_date = budget.end_date || '';
    form.is_recurring = budget.is_recurring;
    form.alert_at_80 = budget.alert_at_80;
    form.alert_at_100 = budget.alert_at_100;
    form.alert_over_budget = budget.alert_over_budget;
    form.enable_notifications = budget.enable_notifications;
    editDialogOpen.value = true;
};

const submitCreate = () => {
    form.post(route('budget.store'), {
        onSuccess: () => {
            createDialogOpen.value = false;
            toast.success('Budget created successfully');
            form.reset();
        }
    });
};

const submitUpdate = () => {
    if (!editingBudget.value) return;
    form.put(route('budget.update', editingBudget.value.id), {
        onSuccess: () => {
            editDialogOpen.value = false;
            toast.success('Budget updated successfully');
            editingBudget.value = null;
        }
    });
};

const handleDelete = (id: number) => {
    deleteBudget(id);
};

const handleDismissAlert = (id: number) => {
    dismissAlert(id);
};

</script>

<template>

    <Head title="Budget" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 overflow-x-hidden animate-in fade-in duration-500">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h1
                        class="text-3xl font-bold tracking-tight bg-gradient-to-r from-foreground to-foreground/70 bg-clip-text text-transparent">
                        Budget Management
                    </h1>
                    <p class="text-sm text-muted-foreground text-balance">Keep your spending in check and achieve your
                        goals.</p>
                </div>

                <div class="flex items-center gap-3">
                    <MonthNavigator :selected-month="selectedMonth" @previous="previousMonth" @next="nextMonth" />
                    <Button @click="openCreateDialog" class="bg-primary hover:bg-primary/90">
                        <Plus class="w-4 h-4 mr-2" />
                        Set Budget
                    </Button>
                </div>
            </div>

            <!-- Active Alerts -->
            <div v-if="summary.alerts.length > 0" class="space-y-3">
                <div class="flex items-center gap-2 text-sm font-semibold text-muted-foreground px-1">
                    <Bell class="h-4 w-4 text-orange-400" />
                    <span>Active Alerts</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div v-for="alert in summary.alerts" :key="alert.id"
                        class="relative flex flex-col gap-2 p-4 rounded-xl border border-orange-500/20 bg-orange-500/5 backdrop-blur-sm animate-in zoom-in-95 duration-200">
                        <button @click="handleDismissAlert(alert.id)"
                            class="absolute top-3 right-3 text-orange-400 hover:text-orange-500 transition-colors">
                            <X class="h-4 w-4" />
                        </button>
                        <div class="flex items-center gap-2">
                            <AlertTriangle class="h-4 w-4 text-orange-400" />
                            <span class="text-xs font-bold uppercase tracking-wider text-orange-400">
                                {{ alert.alert_type.replace('_', ' ') }}
                            </span>
                        </div>
                        <p class="text-sm font-medium text-foreground leading-relaxed">{{ alert.message }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-2 h-2 rounded-full" :style="{ backgroundColor: alert.category_color }"></div>
                            <span class="text-xs text-muted-foreground">{{ alert.category_name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Budget & Summary Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Overall Budget -->
                <div class="lg:col-span-1 space-y-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-muted-foreground px-1">
                        <Target class="h-4 w-4 text-primary" />
                        <span>Overall Budget</span>
                    </div>
                    <div v-if="summary.overall_budget">
                        <BudgetCard :budget-with-spending="summary.overall_budget"
                            @edit="openEditDialog(summary.overall_budget.budget)"
                            @delete="handleDelete(summary.overall_budget.budget.id)" />
                    </div>
                    <Card v-else class="bg-card/30 border-dashed border-border/50 shadow-none">
                        <CardContent class="flex flex-col items-center justify-center py-10 text-center">
                            <PiggyBank class="h-10 w-10 text-muted-foreground/30 mb-2" />
                            <p class="text-sm font-medium text-muted-foreground">No overall budget set</p>
                            <Button variant="link" size="sm" @click="openCreateDialog" class="mt-1">
                                Create one now
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Stats Card -->
                    <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm">
                        <CardContent class="pt-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Budgeted Spending</span>
                                <span class="text-sm font-bold">
                                    {{formatCurrency(summary.category_budgets.reduce((acc, b) => acc + b.spent, 0) +
                                        (summary.overall_budget?.spent || 0))}}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Unbudgeted Spending</span>
                                <span class="text-sm font-bold text-orange-400">
                                    {{ formatCurrency(summary.unbudgeted_spent) }}
                                </span>
                            </div>
                            <div class="pt-3 border-t border-border/50 flex justify-between items-center">
                                <span class="text-sm font-semibold">Financial Status</span>
                                <div
                                    class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase tracking-wider">
                                    HEALTHY
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right: Category Budgets -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2 text-sm font-semibold text-muted-foreground">
                            <PieChart class="h-4 w-4 text-blue-400" />
                            <span>Category Budgets</span>
                        </div>
                        <span class="text-xs text-muted-foreground">{{ summary.category_budgets.length }} Active</span>
                    </div>

                    <div v-if="summary.category_budgets.length === 0"
                        class="flex flex-col items-center justify-center py-20 bg-card/20 rounded-2xl border border-dashed border-border/50">
                        <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center mb-4">
                            <PieChart class="h-8 w-8 opacity-30" />
                        </div>
                        <p class="font-medium text-muted-foreground">No category budgets found</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <BudgetCard v-for="budgetSpending in summary.category_budgets" :key="budgetSpending.budget.id"
                            :budget-with-spending="budgetSpending" @edit="openEditDialog(budgetSpending.budget)"
                            @delete="handleDelete(budgetSpending.budget.id)" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Budget Dialog -->
        <Dialog :open="createDialogOpen || editDialogOpen"
            @update:open="createDialogOpen = $event; editDialogOpen = $event">
            <DialogContent class="sm:max-w-[500px]">
                <DialogHeader>
                    <DialogTitle>{{ editDialogOpen ? 'Edit Budget' : 'Set New Budget' }}</DialogTitle>
                    <DialogDescription>
                        {{ editDialogOpen ? `Update your budget settings below.` : `Define your spending limits to stay
                        on track.` }}
                    </DialogDescription>
                </DialogHeader>

                <form @submit.prevent="editDialogOpen ? submitUpdate() : submitCreate()" class="space-y-6 py-4">
                    <!-- Budget Type -->
                    <div class="space-y-3">
                        <Label>Budget Type</Label>
                        <div class="grid grid-cols-2 gap-2">
                            <Button type="button" :variant="form.budget_type === 'overall' ? 'default' : 'outline'"
                                @click="form.budget_type = 'overall'; form.category_id = null" class="w-full">
                                Overall
                            </Button>
                            <Button type="button" :variant="form.budget_type === 'category' ? 'default' : 'outline'"
                                @click="form.budget_type = 'category'" class="w-full">
                                Per Category
                            </Button>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div v-if="form.budget_type === 'category'" class="space-y-2 animate-in slide-in-from-top-2">
                        <Label for="category">Category</Label>
                        <Select v-model="form.category_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a category" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: category.color }">
                                        </div>
                                        {{ category.name }}
                                    </div>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Amount & Period -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="amount">Amount (₦)</Label>
                            <Input id="amount" type="number" v-model="form.amount" placeholder="0.00" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="period">Period</Label>
                            <Select v-model="form.period_type" @update:model-value="form.period_type = $event">
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="monthly">Monthly</SelectItem>
                                    <SelectItem value="custom">Custom</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <!-- Dates & Recurring -->
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="start_date">Start Date</Label>
                                <Input id="start_date" type="date" v-model="form.start_date" required />
                            </div>
                            <div v-if="form.period_type === 'custom'" class="space-y-2">
                                <Label for="end_date">End Date</Label>
                                <Input id="end_date" type="date" v-model="form.end_date" />
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_recurring" :checked="form.is_recurring"
                                @update:checked="form.is_recurring = $event" />
                            <Label for="is_recurring"
                                class="text-sm font-medium leading-none cursor-pointer text-balance">
                                Auto-renew every month
                            </Label>
                        </div>
                    </div>

                    <!-- Alert Settings -->
                    <div class="pt-4 border-t border-border/50 space-y-4">
                        <div class="flex items-center gap-2 text-sm font-semibold">
                            <Bell class="h-4 w-4 text-primary" />
                            <span>Alert Thresholds</span>
                        </div>

                        <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                            <div class="flex items-center space-x-2">
                                <Checkbox id="alert_80" :checked="form.alert_at_80"
                                    @update:checked="form.alert_at_80 = $event" />
                                <Label for="alert_80" class="text-xs font-medium cursor-pointer">80% Usage</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Checkbox id="alert_100" :checked="form.alert_at_100"
                                    @update:checked="form.alert_at_100 = $event" />
                                <Label for="alert_100" class="text-xs font-medium cursor-pointer">100% Usage</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Checkbox id="alert_over" :checked="form.alert_over_budget"
                                    @update:checked="form.alert_over_budget = $event" />
                                <Label for="alert_over" class="text-xs font-medium cursor-pointer">Over Budget</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Checkbox id="notify" :checked="form.enable_notifications"
                                    @update:checked="form.enable_notifications = $event" />
                                <Label for="notify" class="text-xs font-medium cursor-pointer">Notifications</Label>
                            </div>
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline"
                            @click="createDialogOpen = false; editDialogOpen = false">
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            {{ editDialogOpen ? 'Save Changes' : 'Set Budget' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
