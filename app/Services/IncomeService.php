<?php

namespace App\Services;

use App\Models\Income;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncomeService
{
    public function getAllIncomes(): Collection
    {
        return Income::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getIncomesForMonth(int $year, int $month): Collection
    {
        return Income::where('user_id', Auth::id())
            ->forMonth($year, $month)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getTotalIncomeForMonth(int $year, int $month): float
    {
        return Income::where('user_id', Auth::id())
            ->forMonth($year, $month)
            ->sum('amount');
    }

    public function getRecurringIncomes(): Collection
    {
        return Income::where('user_id', Auth::id())
            ->recurring()
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getIncomeSources(): Collection
    {
        return Income::where('user_id', Auth::id())
            ->select('source')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');
    }

    public function createIncome(array $data): Income
    {
        return Income::create([
            'user_id' => Auth::id(),
            ...$data
        ]);
    }

    public function updateIncome(Income $income, array $data): Income
    {
        $income->update($data);
        return $income->fresh();
    }

    public function deleteIncome(Income $income): bool
    {
        return $income->delete();
    }

    public function getIncomeSourceBreakdown(int $year, int $month): Collection
    {
        $incomes = $this->getIncomesForMonth($year, $month);
        $total = $incomes->sum('amount');

        return $incomes->groupBy('source')->map(function ($group, $source) use ($total) {
            $amount = $group->sum('amount');
            return [
                'source' => $source,
                'amount' => $amount,
                'count' => $group->count(),
                'percentage' => $total > 0 ? round(($amount / $total) * 100, 1) : 0,
            ];
        })->values();
    }
}
