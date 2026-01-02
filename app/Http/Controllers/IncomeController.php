<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Services\IncomeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function __construct(private IncomeService $incomeService) {}

    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $incomes = $this->incomeService->getIncomesForMonth($year, $month);
        $totalIncome = $this->incomeService->getTotalIncomeForMonth($year, $month);
        $sources = $this->incomeService->getIncomeSources();
        $categories = \App\Models\Category::where('is_active', true)->get();

        return Inertia::render('Income/Index', [
            'incomes' => $incomes,
            'totalIncome' => $totalIncome,
            'sources' => $sources,
            'categories' => $categories,
            'selectedMonth' => Carbon::create($year, $month)->format('Y-m'),
        ]);
    }

    public function store(Request $request)
    {
        $this->incomeService->createIncome($request->all());
        return back()->with('success', 'Income added successfully.');
    }

    public function update(Request $request, Income $income)
    {
        $this->incomeService->updateIncome($income, $request->all());
        return back()->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        $this->incomeService->deleteIncome($income);
        return back()->with('success', 'Income deleted successfully.');
    }

    public function recurring()
    {
        $incomes = $this->incomeService->getRecurringIncomes();
        return Inertia::render('Income/Recurring', [
            'incomes' => $incomes,
        ]);
    }
}
