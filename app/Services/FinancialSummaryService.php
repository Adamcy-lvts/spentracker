<?php

namespace App\Services;

use App\Enums\FinancialStatus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinancialSummaryService
{
    public function __construct(
        private IncomeService $incomeService,
        private ExpenseService $expenseService
    ) {}

    public function getSummary(int $year, int $month): array
    {
        $totalIncome = $this->incomeService->getTotalIncomeForMonth($year, $month);
        $totalExpenses = $this->expenseService->getTotalExpensesForMonth($year, $month);
        
        $netIncome = $totalIncome - $totalExpenses;
        $savingsRate = $totalIncome > 0 ? (($totalIncome - $totalExpenses) / $totalIncome) * 100 : 0;
        $expenseRatio = $totalIncome > 0 ? ($totalExpenses / $totalIncome) * 100 : 0;

        $status = match (true) {
            $savingsRate > 5 => FinancialStatus::SURPLUS,
            $savingsRate >= -5 => FinancialStatus::BALANCED,
            default => FinancialStatus::DEFICIT,
        };

        return [
            'period' => Carbon::create($year, $month)->format('Y-m'),
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'savings_rate' => round($savingsRate, 1),
            'expense_ratio' => round($expenseRatio, 1),
            'income_source_breakdown' => $this->incomeService->getIncomeSourceBreakdown($year, $month),
            'status' => $status->value,
        ];
    }
}
