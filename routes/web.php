<?php

use App\Models\Expense;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy-policy');

Route::redirect('privacy', 'privacy-policy');

Route::get('dashboard', function () {
    $userId = auth()->id();
    $now = now();
    
    // Use the actual date from now() instead of hardcoding
    $currentMonth = $now->month;
    $currentYear = $now->year;
    // dd($currentMonth, $currentYear);
    
    // Get recent expenses (last 10)
    $recentExpenses = Expense::with('category')
        ->where('user_id', $userId)
        ->orderBy('date', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    // Calculate statistics
    $totalExpenses = Expense::where('user_id', $userId)->sum('amount');
    $thisMonth = Expense::where('user_id', $userId)
        ->whereMonth('date', $currentMonth)
        ->whereYear('date', $currentYear)
        ->sum('amount');
    $lastMonth = Expense::where('user_id', $userId)
        ->whereMonth('date', $currentMonth - 1)
        ->whereYear('date', $currentYear)
        ->sum('amount');
    $thisWeek = Expense::where('user_id', $userId)
        ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
        ->sum('amount');
    
    // Monthly breakdown for the last 6 months
    $monthlyData = [];
    for ($i = 5; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $monthlyData[] = [
            'month' => $date->format('M Y'),
            'amount' => Expense::where('user_id', $userId)
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount')
        ];
    }
    
    // Category breakdown for this month
    $thisMonthExpenses = Expense::with('category')
        ->where('user_id', $userId)
        ->whereMonth('date', $currentMonth)
        ->whereYear('date', $currentYear)
        ->get();
    
    $categoryBreakdown = $thisMonthExpenses
        ->groupBy(function ($expense) {
            return $expense->category ? $expense->category->name : 'Uncategorized';
        })
        ->map(function ($expenses, $categoryName) {
            $category = $expenses->first()->category;
            return [
                'name' => $categoryName,
                'amount' => $expenses->sum('amount'),
                'color' => $category?->color ?? '#6B7280',
                'count' => $expenses->count(),
                'icon' => $category?->icon ?? 'more-horizontal'
            ];
        })
        ->sortByDesc('amount')
        ->values();
    
    // Calculate percentages
    $totalCategoryAmount = $categoryBreakdown->sum('amount');
    $categoryBreakdown = $categoryBreakdown->map(function ($category) use ($totalCategoryAmount) {
        $category['percentage'] = $totalCategoryAmount > 0 ? round(($category['amount'] / $totalCategoryAmount) * 100, 1) : 0;
        return $category;
    });
    
    
    return Inertia::render('Dashboard', [
        'recentExpenses' => $recentExpenses,
        'statistics' => [
            'total' => $totalExpenses,
            'thisMonth' => $thisMonth,
            'lastMonth' => $lastMonth,
            'thisWeek' => $thisWeek,
            'monthlyTrend' => $monthlyData,
            'categoryBreakdown' => $categoryBreakdown
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Category management routes
Route::get('categories', function () {
    $categories = App\Models\Category::withCount('expenses')
        ->orderBy('name')
        ->get();
    
    return Inertia::render('Categories', [
        'categories' => $categories
    ]);
})->middleware(['auth', 'verified'])->name('categories');

Route::post('categories', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255|unique:categories,name',
        'icon' => 'nullable|string|max:255',
        'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean'
    ]);
    
    $category = App\Models\Category::create($validated);
    
    return redirect()->route('categories')->with('message', 'Category created successfully!');
})->middleware(['auth', 'verified'])->name('categories.store');

Route::put('categories/{category}', function (App\Models\Category $category) {
    $validated = request()->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        'icon' => 'nullable|string|max:255',
        'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean'
    ]);
    
    $category->update($validated);
    
    return redirect()->route('categories')->with('message', 'Category updated successfully!');
})->middleware(['auth', 'verified'])->name('categories.update');

Route::delete('categories/{category}', function (App\Models\Category $category) {
    // Check if category has any expenses
    $expenseCount = $category->expenses()->count();
    
    if ($expenseCount > 0) {
        return redirect()->route('categories')->with('error', "Cannot delete category '{$category->name}' because it has {$expenseCount} associated expense(s).");
    }
    
    $category->delete();
    
    return redirect()->route('categories')->with('message', 'Category deleted successfully!');
})->middleware(['auth', 'verified'])->name('categories.destroy');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::get('expense', function () {
    // Get expenses for the current authenticated user
    $expenses = Expense::with('category')
        ->where('user_id', auth()->id())
        ->orderBy('date', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Get all active categories
    $categories = App\Models\Category::where('is_active', true)
        ->orderBy('name')
        ->get();
    
    return Inertia::render('Expense', [
        'expenses' => $expenses,
        'categories' => $categories
    ]);
})->middleware(['auth', 'verified'])->name('expense');

Route::post('expense', function () {
    // Validate the form data
    $validated = request()->validate([
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0.01',
        'date' => 'required|date',
        'category_id' => 'nullable|exists:categories,id'
    ]);
    
    // Create the expense and associate it with the current user
    $expense = Expense::create([
        ...$validated,
        'user_id' => auth()->id(),
    ]);
    
    // Return JSON only for non-Inertia AJAX requests (offline sync)
    if ((request()->wantsJson() || request()->ajax()) && !request()->header('X-Inertia')) {
        return response()->json($expense, 201);
    }
    
    return redirect()->route('expense')->with('message', 'Expense added successfully!');
})->middleware(['auth', 'verified'])->name('expense.store');

Route::put('expense/{expense}', function (Expense $expense) {
    // Check if the user owns this expense
    if ($expense->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // Validate the form data
    $validated = request()->validate([
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0.01',
        'date' => 'required|date',
        'category_id' => 'nullable|exists:categories,id'
    ]);
    
    // Update the expense
    $expense->update($validated);
    
    // Return JSON only for non-Inertia AJAX requests (offline sync)
    if ((request()->wantsJson() || request()->ajax()) && !request()->header('X-Inertia')) {
        return response()->json($expense, 200);
    }
    
    return redirect()->route('expense')->with('message', 'Expense updated successfully!');
})->middleware(['auth', 'verified'])->name('expense.update');

Route::delete('expense/{expense}', function (Expense $expense) {
    // Check if the user owns this expense
    if ($expense->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // Delete the expense
    $expense->delete();
    
    // Return JSON only for non-Inertia AJAX requests (offline sync)
    if ((request()->wantsJson() || request()->ajax()) && !request()->header('X-Inertia')) {
        return response()->json(['message' => 'Expense deleted successfully!'], 200);
    }
    
    return redirect()->route('expense')->with('message', 'Expense deleted successfully!');
})->middleware(['auth', 'verified'])->name('expense.destroy');

Route::delete('expenses/bulk', function () {
    // Validate the expense IDs
    $validated = request()->validate([
        'expense_ids' => 'required|array|min:1',
        'expense_ids.*' => 'required|integer|exists:expenses,id'
    ]);
    
    // Get expenses that belong to the current user
    $expenses = Expense::whereIn('id', $validated['expense_ids'])
        ->where('user_id', auth()->id())
        ->get();
    
    if ($expenses->count() !== count($validated['expense_ids'])) {
        return response()->json([
            'message' => 'Some expenses were not found or you do not have permission to delete them.'
        ], 403);
    }
    
    // Delete all expenses
    $deletedCount = Expense::whereIn('id', $validated['expense_ids'])
        ->where('user_id', auth()->id())
        ->delete();
    
    // Return JSON response
    if ((request()->wantsJson() || request()->ajax()) && !request()->header('X-Inertia')) {
        return response()->json([
            'message' => "Successfully deleted {$deletedCount} expense(s)!",
            'deleted_count' => $deletedCount
        ], 200);
    }
    
    return redirect()->route('expense')->with('message', "Successfully deleted {$deletedCount} expense(s)!");
})->middleware(['auth', 'verified'])->name('expenses.bulk-delete');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('users', function () {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }
        
        // Get users data
        $users = App\Models\User::select([
            'id', 'name', 'email', 'is_admin', 'email_verified_at', 
            'last_login_at', 'last_login_ip', 'last_login_user_agent', 
            'last_login_location', 'last_login_latitude', 'last_login_longitude',
            'last_login_city', 'last_login_country', 'last_login_device_type',
            'created_at', 'updated_at'
        ])
        ->orderBy('last_login_at', 'desc')
        ->get();

        // Calculate statistics
        $totalUsers = App\Models\User::count();
        $adminUsers = App\Models\User::where('is_admin', true)->count();
        $activeUsersLastWeek = App\Models\User::where('last_login_at', '>=', now()->subWeek())->count();
        $activeUsersLastMonth = App\Models\User::where('last_login_at', '>=', now()->subMonth())->count();
        $newUsersThisMonth = App\Models\User::where('created_at', '>=', now()->startOfMonth())->count();

        $statistics = [
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'regular_users' => $totalUsers - $adminUsers,
            'active_users_last_week' => $activeUsersLastWeek,
            'active_users_last_month' => $activeUsersLastMonth,
            'new_users_this_month' => $newUsersThisMonth,
            'inactive_users' => $totalUsers - $activeUsersLastMonth,
        ];
        
        return Inertia::render('admin/UserMonitor', [
            'users' => $users,
            'statistics' => $statistics
        ]);
    })->name('admin.users');

    Route::patch('users/{user}/toggle-admin', function (App\Models\User $user) {
        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }
        
        $user->update(['is_admin' => !$user->is_admin]);
        
        return redirect()->route('admin.users')->with('message', $user->is_admin ? 'User promoted to admin' : 'User demoted from admin');
    })->name('admin.users.toggle-admin');
});
