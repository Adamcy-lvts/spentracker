<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseService
{
    public function getTotalExpensesForMonth(int $year, int $month): float
    {
        $monthStart = Carbon::create($year, $month)->startOfMonth();
        $monthEnd = Carbon::create($year, $month)->endOfMonth();

        return Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('amount');
    }

    public function getExpensesForMonth(int $year, int $month): Collection
    {
        $monthStart = Carbon::create($year, $month)->startOfMonth();
        $monthEnd = Carbon::create($year, $month)->endOfMonth();

        return Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->orderBy('date', 'desc')
            ->get();
    }
}
