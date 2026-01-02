<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetAlert;
use App\Models\Category;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function __construct(private BudgetService $budgetService) {}

    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $summary = $this->budgetService->getBudgetSummary($year, $month);
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('Budget/Index', [
            'summary' => $summary,
            'categories' => $categories,
            'selectedMonth' => Carbon::create($year, $month)->format('Y-m'),
        ]);
    }

    public function store(Request $request)
    {
        $this->budgetService->createBudget($request->all());
        return back()->with('success', 'Budget created successfully.');
    }

    public function update(Request $request, Budget $budget)
    {
        $this->budgetService->updateBudget($budget, $request->all());
        return back()->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $this->budgetService->deleteBudget($budget);
        return back()->with('success', 'Budget deleted successfully.');
    }

    public function dismissAlert(BudgetAlert $alert)
    {
        $this->budgetService->dismissAlert($alert);
        return back()->with('success', 'Alert dismissed.');
    }
}
