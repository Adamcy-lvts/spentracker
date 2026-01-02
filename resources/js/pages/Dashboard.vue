<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { TrendingUp, TrendingDown, DollarSign, Calendar, Clock, BarChart3 } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement
} from 'chart.js'
import { Bar, Doughnut } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement)

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
        firstExpenseDate?: string | null;
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

// Chart.js Configuration
const barChartData = computed(() => {
    // Create gradient if context is available (would require ref to chart, for now simple premium color)
    return {
        labels: props.statistics.monthlyTrend.map(m => m.month),
        datasets: [{
            label: 'Monthly Spending',
            data: props.statistics.monthlyTrend.map(m => m.amount),
            backgroundColor: '#3b82f6', // Fallback
            hoverBackgroundColor: '#2563eb',
            borderRadius: 6,
            barThickness: 24,
            categoryPercentage: 0.8 // Slimmer bars
        }]
    }
});

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleFont: { size: 13, weight: 'bold' as const }, // Fix type error
            bodyFont: { size: 12 },
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
                label: (context: any) => formatCurrency(context.raw)
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(255, 255, 255, 0.05)', // Subtle grid
                drawBorder: false,
            },
            ticks: {
                font: { size: 11 },
                color: '#94a3b8',
                callback: (value: any) => '₦' + (value / 1000) + 'k'
            },
            border: { display: false }
        },
        x: {
            grid: { display: false },
            ticks: {
                font: { size: 11 },
                color: '#94a3b8'
            },
            border: { display: false }
        }
    }
};

const doughnutChartData = computed(() => ({
    labels: props.statistics.categoryBreakdown.map(c => c.name),
    datasets: [{
        data: props.statistics.categoryBreakdown.map(c => c.amount),
        backgroundColor: props.statistics.categoryBreakdown.map(c => c.color),
        borderWidth: 0,
        hoverOffset: 4
    }]
}));

const doughnutChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '75%', // Thinner ring
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            bodyFont: { size: 12 },
            cornerRadius: 8,
            callbacks: {
                label: (context: any) => {
                    const value = context.raw;
                    const total = context.chart._metasets[context.datasetIndex].total;
                    const percentage = Math.round((value / total) * 100) + '%';
                    return `${context.label}: ${formatCurrency(value)} (${percentage})`;
                }
            }
        }
    }
};
const { props: pageProps } = usePage();
const user = computed(() => pageProps.auth.user);

const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Good Morning';
    if (hour < 18) return 'Good Afternoon';
    return 'Good Evening';
});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 overflow-x-hidden animate-in fade-in duration-500">
            <!-- Header & Greeting -->
            <div class="flex flex-col gap-1">
                <span class="text-sm text-muted-foreground">{{ greeting }},</span>
                <h1
                    class="text-3xl font-bold tracking-tight bg-gradient-to-r from-foreground to-foreground/70 bg-clip-text text-transparent">
                    {{ user.name }}
                </h1>
            </div>

            <!-- Mobile Priority Layout -->
            <!-- We use a custom grid layout that shifts based on screen size -->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Total Expenses (Priority 1) -->
                <Card
                    class="bg-card/50 backdrop-blur-xl border-border/50 hover:bg-card/80 transition-all duration-300 shadow-sm hover:shadow-md group">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Expenses</CardTitle>
                        <div
                            class="h-8 w-8 rounded-full bg-emerald-500/10 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                            <DollarSign class="h-4 w-4 text-emerald-500" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold tracking-tight mt-2">{{ formatCurrency(statistics.total) }}</div>
                        <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                            All time spending
                            <span v-if="statistics.firstExpenseDate" class="inline-flex items-center">
                                <span class="mx-1">•</span> Since {{ statistics.firstExpenseDate }}
                            </span>
                        </p>
                    </CardContent>
                </Card>

                <!-- This Week (Priority 2) -->
                <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm hover:shadow-md transition-all">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">This Week</CardTitle>
                        <div class="h-8 w-8 rounded-full bg-violet-500/10 flex items-center justify-center">
                            <Clock class="h-4 w-4 text-violet-500" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold tracking-tight mt-2">{{ formatCurrency(statistics.thisWeek) }}
                        </div>
                        <p class="text-xs text-muted-foreground mt-1">Current week spending</p>
                    </CardContent>
                </Card>

                <!-- This Month (Priority 2.5 - Hidden on small mobile if desired, or kept) -->
                <!-- We keep it as it's useful -->
                <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm hover:shadow-md transition-all">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">This Month</CardTitle>
                        <div class="h-8 w-8 rounded-full bg-blue-500/10 flex items-center justify-center">
                            <Calendar class="h-4 w-4 text-blue-500" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold tracking-tight mt-2">{{ formatCurrency(statistics.thisMonth) }}
                        </div>
                        <div class="flex items-center text-xs mt-1">
                            <div
                                :class="['flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium', monthlyChange > 0 ? 'bg-red-500/10 text-red-500' : 'bg-emerald-500/10 text-emerald-500']">
                                <component :is="monthlyChangeIcon" class="h-3 w-3 mr-1" />
                                {{ Math.abs(monthlyChange) }}%
                            </div>
                            <span class="text-muted-foreground ml-2">from last month</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Lower Section -->
            <div class="flex flex-col lg:grid lg:grid-cols-7 gap-6">

                <!-- Charts Section (Priority 3 on Mobile) -->
                <!-- Use order-first on mobile to put it above recent expenses if flex-col, but chart is Priority 3? -->
                <!-- User Request: Total(1) -> Week(2) -> Category(3) -> Recent(4) -->
                <!-- Our Grid above handles 1 & 2. -->
                <!-- Now we have Category & Recent. -->
                <!-- We need Category to come BEFORE Recent on mobile. -->

                <div class="lg:col-span-3 space-y-6 order-1 lg:order-2">
                    <!-- Category Breakdown -->
                    <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm">
                        <CardHeader>
                            <CardTitle class="text-lg font-semibold">This Month by Category</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="statistics.categoryBreakdown.length === 0"
                                class="flex flex-col items-center justify-center py-8 text-muted-foreground">
                                <p class="text-sm">No data to display</p>
                            </div>
                            <div v-else class="flex flex-row items-center justify-between gap-4">
                                <!-- Chart Left -->
                                <div class="h-[140px] w-[140px] flex-shrink-0 relative">
                                    <Doughnut :data="doughnutChartData" :options="doughnutChartOptions" />
                                </div>

                                <!-- Detailed List Right -->
                                <div class="flex-1 space-y-2 max-h-[160px] overflow-y-auto pr-1 custom-scrollbar">
                                    <div v-for="category in statistics.categoryBreakdown" :key="category.name"
                                        class="flex items-center justify-between text-xs group">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-2 h-2 rounded-full shrink-0"
                                                :style="{ backgroundColor: category.color }"></div>
                                            <div class="flex flex-col min-w-0">
                                                <span class="font-medium truncate">{{ category.name }}</span>
                                                <span class="text-[10px] text-muted-foreground">{{ category.percentage
                                                    }}%</span>
                                            </div>
                                        </div>
                                        <span class="font-semibold tabular-nums">{{ formatCurrency(category.amount)
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                            <div v-if="statistics.categoryBreakdown.length > 0"
                                class="mt-4 flex justify-center pt-2 border-t border-border/30">
                                <a href="/categories"
                                    class="text-xs text-muted-foreground hover:text-foreground transition-colors">
                                    View full details
                                </a>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Monthly Trend (Priority 5 - push down) -->
                    <Card class="bg-card/50 backdrop-blur-xl border-border/50 shadow-sm">
                        <CardHeader>
                            <CardTitle class="text-lg font-semibold">Monthly Trend</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="h-[200px] w-full">
                                <Bar :data="barChartData" :options="barChartOptions" />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Recent Expenses (Priority 4 on Mobile) -->
                <Card class="lg:col-span-4 bg-card/50 backdrop-blur-xl border-border/50 shadow-sm order-2 lg:order-1">
                    <CardHeader>
                        <CardTitle class="text-lg font-semibold">Recent Transactions</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="recentExpenses.length === 0"
                            class="flex flex-col items-center justify-center py-12 text-muted-foreground">
                            <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center mb-4">
                                <BarChart3 class="h-8 w-8 opacity-50" />
                            </div>
                            <p class="font-medium">No expenses yet</p>
                            <p class="text-sm mt-1">Start by adding your first expense!</p>
                        </div>
                        <div v-else class="space-y-1">
                            <div v-for="expense in recentExpenses.slice(0, 5)" :key="expense.id"
                                class="flex items-center justify-between p-3 rounded-xl hover:bg-muted/50 transition-colors group cursor-default">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-2 h-2 rounded-full shrink-0"
                                        :style="{ backgroundColor: expense.category?.color || '#6B7280' }"></div>
                                    <div class="truncate">
                                        <p class="font-medium truncate group-hover:text-primary transition-colors">{{
                                            expense.description }}</p>
                                        <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                            <span>{{ new Date(expense.date).toLocaleDateString('en-US', {
                                                month:
                                                'short', day: 'numeric' }) }}</span>
                                            <span class="w-1 h-1 rounded-full bg-muted-foreground/30"></span>
                                            <span class="truncate">{{ expense.category?.name || 'Uncategorized'
                                                }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right pl-4">
                                    <p class="font-bold tabular-nums">{{ formatCurrency(parseFloat(expense.amount)) }}
                                    </p>
                                </div>
                            </div>
                            <div v-if="recentExpenses.length > 5"
                                class="text-center pt-4 border-t border-border/30 mt-2">
                                <a href="/expense"
                                    class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors">
                                    View all transactions
                                    <span class="ml-1 text-xs">→</span>
                                </a>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
