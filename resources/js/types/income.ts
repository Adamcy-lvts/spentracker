export interface Income {
    id: number;
    user_id: number;
    source: string;
    amount: number;
    date: string;
    category_id: number | null;
    description: string;
    is_recurring: boolean;
    recurrence_type: RecurrenceType | null;
    created_at: string;
    updated_at: string;
}
export type RecurrenceType = 'weekly' | 'biweekly' | 'monthly' | 'quarterly' | 'yearly';
export interface IncomeSourceBreakdown {
    source: string;
    amount: number;
    count: number;
    percentage: number;
}
export interface FinancialSummary {
    period: string;
    total_income: number;
    total_expenses: number;
    net_income: number;
    savings_rate: number;
    expense_ratio: number;
    income_source_breakdown: IncomeSourceBreakdown[];
    status: FinancialStatus;
}
export type FinancialStatus = 'surplus' | 'balanced' | 'deficit';
