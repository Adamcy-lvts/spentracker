<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the user's expenses.
     */
    public function index(Request $request)
    {
        $query = Expense::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Optional filtering by date range
        if ($request->has('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        // Optional filtering by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Pagination for mobile apps
        $perPage = $request->get('per_page', 15);
        $expenses = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $expenses->items(),
            'pagination' => [
                'current_page' => $expenses->currentPage(),
                'last_page' => $expenses->lastPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'has_more' => $expenses->hasMorePages(),
            ]
        ]);
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $expense = Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        // Load the category relationship
        $expense->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Expense created successfully',
            'data' => $expense,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified expense.
     */
    public function show(string $id)
    {
        $expense = Expense::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('category')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $expense,
        ]);
    }

    public function sync(Request $request)
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get(); // No pagination - get all

        return response()->json([
            'success' => true,
            'data' => $expenses,
        ]);
    }


    /**
     * Update the specified expense.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        // Load the category relationship
        $expense->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully',
            'data' => $expense,
        ]);
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(string $id)
    {
        $expense = Expense::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully',
        ]);
    }

    /**
     * Bulk delete expenses
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'expense_ids' => 'required|array',
            'expense_ids.*' => 'integer|exists:expenses,id',
        ]);

        $deletedCount = Expense::whereIn('id', $request->expense_ids)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} expenses",
        ]);
    }

    /**
     * Get expense statistics
     */
    public function statistics(Request $request)
    {
        $query = Expense::where('user_id', Auth::id());

        // Filter by date range if provided
        if ($request->has('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $total = $query->sum('amount');
        $count = $query->count();
        $average = $count > 0 ? $total / $count : 0;

        // Get top categories
        $topCategories = Expense::where('user_id', Auth::id())
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.color, SUM(expenses.amount) as total_amount')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_amount' => round($total, 2),
                'total_count' => $count,
                'average_amount' => round($average, 2),
                'top_categories' => $topCategories,
            ]
        ]);
    }
}
