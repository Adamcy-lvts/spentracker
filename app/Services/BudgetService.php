<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\BudgetAlert;
use App\Models\Expense;
use App\Enums\BudgetType;
use App\Enums\BudgetAlertType;
use App\Enums\BudgetStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetService
{
    public function getAllBudgets(): Collection
    {
        return Budget::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getBudgetsForMonth(int $year, int $month): Collection
    {
        return Budget::where('user_id', Auth::id())
            ->forMonth($year, $month)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOverallBudget(int $year, int $month): ?Budget
    {
        return Budget::where('user_id', Auth::id())
            ->overall()
            ->forMonth($year, $month)
            ->with('category')
            ->first();
    }

    public function getBudgetForCategory(int $categoryId, int $year, int $month): ?Budget
    {
        return Budget::where('user_id', Auth::id())
            ->category()
            ->where('category_id', $categoryId)
            ->forMonth($year, $month)
            ->first();
    }

    public function createBudget(array $data): Budget
    {
        return Budget::create([
            'user_id' => Auth::id(),
            ...$data
        ]);
    }

    public function updateBudget(Budget $budget, array $data): Budget
    {
        $budget->update($data);
        return $budget->fresh();
    }

    public function deleteBudget(Budget $budget): bool
    {
        $budget->alerts()->delete();
        return $budget->delete();
    }

    public function getBudgetWithSpending(Budget $budget, int $year, int $month): array
    {
        $monthStart = Carbon::create($year, $month)->startOfMonth();
        $monthEnd = Carbon::create($year, $month)->endOfMonth();

        $expenses = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$monthStart, $monthEnd]);

        // Calculate spending based on budget type
        if ($budget->budget_type === BudgetType::CATEGORY && $budget->category_id) {
            $spent = $expenses->where('category_id', $budget->category_id)->sum('amount');
        } else {
            $spent = $expenses->sum('amount');
        }

        $remaining = $budget->amount - $spent;
        $percentageUsed = $budget->amount > 0 ? (int) (($spent / $budget->amount) * 100) : 0;
        $daysLeft = max(0, now()->diffInDays($monthEnd, false));

        $status = match (true) {
            $spent > $budget->amount => BudgetStatus::OVER_BUDGET,
            $percentageUsed >= 100 => BudgetStatus::CRITICAL,
            $percentageUsed >= 80 => BudgetStatus::WARNING,
            default => BudgetStatus::SAFE,
        };

        return [
            'budget' => $budget,
            'spent' => $spent,
            'remaining' => $remaining,
            'percentage_used' => $percentageUsed,
            'days_left' => (int) $daysLeft,
            'status' => $status->value,
            'is_over_budget' => $spent > $budget->amount,
        ];
    }

    public function getBudgetSummary(int $year, int $month): array
    {
        $overallBudget = $this->getOverallBudget($year, $month);
        $categoryBudgets = $this->getBudgetsForMonth($year, $month)
            ->filter(fn($b) => $b->budget_type === BudgetType::CATEGORY);

        $overallWithSpending = $overallBudget 
            ? $this->getBudgetWithSpending($overallBudget, $year, $month) 
            : null;

        $categoryBudgetsWithSpending = $categoryBudgets->map(
            fn($b) => $this->getBudgetWithSpending($b, $year, $month)
        )->values();

        // Calculate unbudgeted spending
        $unbudgetedSpent = 0;
        if (!$overallBudget) {
            $budgetedCategoryIds = $categoryBudgets->pluck('category_id')->filter()->toArray();
            $monthStart = Carbon::create($year, $month)->startOfMonth();
            $monthEnd = Carbon::create($year, $month)->endOfMonth();
            
            $unbudgetedSpent = Expense::where('user_id', Auth::id())
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->whereNotIn('category_id', $budgetedCategoryIds)
                ->sum('amount');
        }

        $activeAlerts = $this->getActiveAlerts();

        return [
            'overall_budget' => $overallWithSpending,
            'category_budgets' => $categoryBudgetsWithSpending,
            'unbudgeted_spent' => $unbudgetedSpent,
            'alerts' => $activeAlerts,
            'has_budgets' => $overallBudget || $categoryBudgets->isNotEmpty(),
        ];
    }

    public function getActiveAlerts(): Collection
    {
        return BudgetAlert::whereHas('budget', fn($q) => $q->where('user_id', Auth::id()))
            ->active()
            ->with('budget.category')
            ->orderBy('triggered_at', 'desc')
            ->get()
            ->map(fn($alert) => [
                'id' => $alert->id,
                'alert_type' => $alert->alert_type->value,
                'message' => $alert->getMessage(),
                'percentage_used' => $alert->getPercentageUsed(),
                'spent_amount' => $alert->spent_amount,
                'budget_amount' => $alert->budget_amount,
                'triggered_at' => $alert->triggered_at,
                'category_name' => $alert->budget->category?->name ?? 'Overall Budget',
                'category_color' => $alert->budget->category?->color ?? '#6366f1',
            ]);
    }

    public function dismissAlert(BudgetAlert $alert): bool
    {
        return $alert->update(['is_dismissed' => true]);
    }

    public function checkAndTriggerAlerts(int $categoryId, int $year, int $month): array
    {
        $budget = $this->getBudgetForCategory($categoryId, $year, $month);
        if (!$budget || !$budget->enable_notifications) {
            return [];
        }

        $budgetWithSpending = $this->getBudgetWithSpending($budget, $year, $month);
        $triggeredAlerts = [];
        $percentageUsed = $budgetWithSpending['percentage_used'];

        // Check 80% threshold
        if ($budget->alert_at_80 && $percentageUsed >= 80 && $percentageUsed < 100) {
            if (!$this->hasActiveAlert($budget->id, BudgetAlertType::THRESHOLD_80)) {
                $triggeredAlerts[] = $this->createAlert($budget, BudgetAlertType::THRESHOLD_80, $budgetWithSpending);
            }
        }

        // Check 100% threshold
        if ($budget->alert_at_100 && $percentageUsed >= 100 && !$budgetWithSpending['is_over_budget']) {
            if (!$this->hasActiveAlert($budget->id, BudgetAlertType::THRESHOLD_100)) {
                $triggeredAlerts[] = $this->createAlert($budget, BudgetAlertType::THRESHOLD_100, $budgetWithSpending);
            }
        }

        // Check over budget
        if ($budget->alert_over_budget && $budgetWithSpending['is_over_budget']) {
            if (!$this->hasActiveAlert($budget->id, BudgetAlertType::OVER_BUDGET)) {
                $triggeredAlerts[] = $this->createAlert($budget, BudgetAlertType::OVER_BUDGET, $budgetWithSpending);
            }
        }

        return $triggeredAlerts;
    }

    private function hasActiveAlert(int $budgetId, BudgetAlertType $alertType): bool
    {
        return BudgetAlert::where('budget_id', $budgetId)
            ->where('alert_type', $alertType)
            ->active()
            ->exists();
    }

    private function createAlert(Budget $budget, BudgetAlertType $alertType, array $spending): BudgetAlert
    {
        return BudgetAlert::create([
            'budget_id' => $budget->id,
            'alert_type' => $alertType,
            'triggered_at' => now(),
            'spent_amount' => $spending['spent'],
            'budget_amount' => $budget->amount,
        ]);
    }
}
