<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Repeat, Plus, Calendar, Clock, ArrowRight } from 'lucide-vue-next';
import type { Income } from '@/types/income';

const props = defineProps<{
    incomes: Income[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Income',
        href: '/income',
    },
    {
        title: 'Recurring',
        href: '/income/recurring',
    },
];

const formatCurrency = (amount: number) => {
    return '₦' + amount.toLocaleString();
};
</script>

<template>

    <Head title="Recurring Income" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6 animate-in fade-in duration-500">
            <!-- Header -->
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold tracking-tight">Recurring Income</h1>
                <p class="text-sm text-muted-foreground">Manage your automated income streams.</p>
            </div>

            <div v-if="incomes.length === 0"
                class="flex flex-col items-center justify-center py-20 bg-card/20 rounded-2xl border border-dashed border-border/50">
                <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center mb-4">
                    <Repeat class="h-8 w-8 opacity-30" />
                </div>
                <p class="font-medium text-muted-foreground">No recurring incomes setup</p>
                <Link :href="route('income.index')" class="mt-4">
                    <Button variant="outline">
                        <Plus class="h-4 w-4 mr-2" />
                        Go to Income
                    </Button>
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Card v-for="income in incomes" :key="income.id"
                    class="bg-card/50 backdrop-blur-sm border-border/50 hover:shadow-md transition-all group">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="p-2 rounded-lg bg-emerald-500/10">
                                    <Repeat class="h-4 w-4 text-emerald-500" />
                                </div>
                                <CardTitle class="text-base font-semibold">{{ income.source }}</CardTitle>
                            </div>
                            <span
                                class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider">
                                {{ income.recurrence_type }}
                            </span>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-4">
                            <div>
                                <span class="text-2xl font-bold">{{ formatCurrency(income.amount) }}</span>
                                <p class="text-xs text-muted-foreground mt-1">
                                    {{ income.description || 'No description provided' }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-2 pt-2 border-t border-border/30">
                                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <Clock class="h-3.5 w-3.5" />
                                    <span>Last processed: {{ new Date(income.date).toLocaleDateString() }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <Calendar class="h-3.5 w-3.5" />
                                    <span>Next due: Calculating...</span>
                                </div>
                            </div>

                            <Link :href="route('income.index')"
                                class="mt-2 text-xs font-semibold text-primary inline-flex items-center gap-1 hover:underline">
                                View in history
                                <ArrowRight class="h-3 w-3" />
                            </Link>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
