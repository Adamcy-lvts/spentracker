<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { TrendingUp, TrendingDown, DollarSign, Calendar, Clock, BarChart3 } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    recentExpenses: Array<{
        id: number;
        description: string;
        amount: string;
        date: string;
        category_id: number | null;
        category?: {
            id: number;
            name: string;
            color: string;
        };
        created_at: string;
    }>;
    statistics: {
        total: number;
        thisMonth: number;
        lastMonth: number;
        thisWeek: number;
        monthlyTrend: Array<{
            month: string;
            amount: number;
        }>;
        categoryBreakdown: Array<{
            name: string;
            amount: number;
            color: string;
            count: number;
            icon: string;
            percentage: number;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(amount);
};

// Calculate month-over-month change
const monthlyChange = computed(() => {
    const current = props.statistics.thisMonth;
    const previous = props.statistics.lastMonth;
    
    if (previous === 0) return current > 0 ? 100 : 0;
    
    const change = ((current - previous) / previous) * 100;
    return Math.round(change * 100) / 100;
});

const monthlyChangeClass = computed(() => {
    return monthlyChange.value > 0 ? 'text-red-600' : 'text-green-600';
});

const monthlyChangeIcon = computed(() => {
    return monthlyChange.value > 0 ? TrendingUp : TrendingDown;
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            
            <!-- Statistics Cards -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Total Expenses -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Expenses</CardTitle>
                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatCurrency(statistics.total) }}</div>
                        <p class="text-xs text-muted-foreground">All time spending</p>
                    </CardContent>
                </Card>

                <!-- This Month -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">This Month</CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatCurrency(statistics.thisMonth) }}</div>
                        <div class="flex items-center text-xs">
                            <component :is="monthlyChangeIcon" :class="['h-3 w-3 mr-1', monthlyChangeClass]" />
                            <span :class="monthlyChangeClass">
                                {{ Math.abs(monthlyChange) }}% from last month
                            </span>
                        </div>
                    </CardContent>
                </Card>

                <!-- This Week -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">This Week</CardTitle>
                        <Clock class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatCurrency(statistics.thisWeek) }}</div>
                        <p class="text-xs text-muted-foreground">Current week spending</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Lower Section with Recent Expenses, Monthly Trend, and Category Breakdown -->
            <div class="grid gap-4 md:grid-cols-3">
                <!-- Recent Expenses -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Recent Expenses</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="recentExpenses.length === 0" class="text-center py-8 text-muted-foreground">
                            <BarChart3 class="mx-auto h-12 w-12 mb-2 opacity-50" />
                            <p>No expenses yet</p>
                            <p class="text-sm">Start by adding your first expense!</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div 
                                v-for="expense in recentExpenses.slice(0, 5)" 
                                :key="expense.id"
                                class="flex items-center justify-between p-3 border rounded-lg"
                            >
                                <div class="flex items-center gap-3 flex-1">
                                    <div 
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium"
                                        :style="{ backgroundColor: expense.category?.color || '#6B7280' }"
                                    >
                                        {{ expense.category?.name?.charAt(0) || '?' }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ expense.description }}</p>
                                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                            <span>{{ new Date(expense.date).toLocaleDateString() }}</span>
                                            <span>•</span>
                                            <span>{{ expense.category?.name || 'Uncategorized' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold">{{ formatCurrency(parseFloat(expense.amount)) }}</p>
                                </div>
                            </div>
                            <div v-if="recentExpenses.length > 5" class="text-center pt-2">
                                <a href="/expense" class="text-sm text-blue-600 hover:underline">
                                    View all expenses
                                </a>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Monthly Trend -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Monthly Spending Trend</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div 
                                v-for="month in statistics.monthlyTrend" 
                                :key="month.month"
                                class="flex items-center justify-between"
                            >
                                <span class="text-sm text-muted-foreground">{{ month.month }}</span>
                                <div class="flex items-center space-x-2 flex-1 mx-4">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div 
                                            class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            :style="{ width: `${Math.min((month.amount / Math.max(...statistics.monthlyTrend.map(m => m.amount))) * 100, 100)}%` }"
                                        ></div>
                                    </div>
                                </div>
                                <span class="text-sm font-medium">{{ formatCurrency(month.amount) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Category Breakdown -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">This Month by Category</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="statistics.categoryBreakdown.length === 0" class="text-center py-8 text-muted-foreground">
                            <BarChart3 class="mx-auto h-12 w-12 mb-2 opacity-50" />
                            <p>No expenses this month</p>
                        </div>
                        <div v-else class="space-y-4">
                            <div 
                                v-for="category in statistics.categoryBreakdown.slice(0, 6)" 
                                :key="category.name"
                                class="space-y-2"
                            >
                                <!-- Category Header -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div 
                                            class="w-3 h-3 rounded-full" 
                                            :style="{ backgroundColor: category.color }"
                                        ></div>
                                        <span class="text-sm font-medium">{{ category.name }}</span>
                                        <span class="text-xs text-muted-foreground">({{ category.count }} expense{{ category.count === 1 ? '' : 's' }})</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold">{{ formatCurrency(category.amount) }}</div>
                                        <div class="text-xs text-muted-foreground">{{ category.percentage }}%</div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div 
                                        class="h-2 rounded-full transition-all duration-300"
                                        :style="{ 
                                            backgroundColor: category.color, 
                                            width: `${category.percentage}%` 
                                        }"
                                    ></div>
                                </div>
                            </div>
                            
                            <!-- Show More Link -->
                            <div v-if="statistics.categoryBreakdown.length > 6" class="text-center pt-2">
                                <a href="/categories" class="text-sm text-blue-600 hover:underline">
                                    View all categories ({{ statistics.categoryBreakdown.length - 6 }} more)
                                </a>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
