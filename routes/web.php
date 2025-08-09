<?php

use App\Models\Expense;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::get('expense', function () {
    // Get expenses for the current authenticated user
    $expenses = Expense::where('user_id', auth()->id())
        ->orderBy('date', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
    
    return Inertia::render('Expense', [
        'expenses' => $expenses
    ]);
})->middleware(['auth', 'verified'])->name('expense');

Route::post('expense', function () {
    // Validate the form data
    $validated = request()->validate([
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0.01',
        'date' => 'required|date'
    ]);
    
    // Create the expense and associate it with the current user
    Expense::create([
        ...$validated,
        'user_id' => auth()->id(),
    ]);
    
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
        'date' => 'required|date'
    ]);
    
    // Update the expense
    $expense->update($validated);
    
    return redirect()->route('expense')->with('message', 'Expense updated successfully!');
})->middleware(['auth', 'verified'])->name('expense.update');

Route::delete('expense/{expense}', function (Expense $expense) {
    // Check if the user owns this expense
    if ($expense->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // Delete the expense
    $expense->delete();
    
    return redirect()->route('expense')->with('message', 'Expense deleted successfully!');
})->middleware(['auth', 'verified'])->name('expense.destroy');
