import type { Category } from './category';

export interface Budget {
    id: number;
    user_id: number;
    budget_type: BudgetType;
    category_id: number | null;
    category?: Category;
    amount: number;
    period_type: BudgetPeriodType;
    start_date: string;
    end_date: string | null;
    is_recurring: boolean;
    alert_at_80: boolean;
    alert_at_100: boolean;
    alert_over_budget: boolean;
    enable_notifications: boolean;
    created_at: string;
    updated_at: string;
}

export type BudgetType = 'overall' | 'category';
export type BudgetPeriodType = 'monthly' | 'custom';
export type BudgetAlertType = 'threshold_80' | 'threshold_100' | 'over_budget';
export type BudgetStatus = 'safe' | 'warning' | 'critical' | 'over_budget';

export interface BudgetWithSpending {
    budget: Budget;
    spent: number;
    remaining: number;
    percentage_used: number;
    days_left: number;
    status: BudgetStatus;
    is_over_budget: boolean;
}

export interface BudgetAlert {
    id: number;
    alert_type: BudgetAlertType;
    message: string;
    percentage_used: number;
    spent_amount: number;
    budget_amount: number;
    triggered_at: string;
    category_name: string;
    category_color: string;
}

export interface BudgetSummary {
    overall_budget: BudgetWithSpending | null;
    category_budgets: BudgetWithSpending[];
    unbudgeted_spent: number;
    alerts: BudgetAlert[];
    has_budgets: boolean;
}
