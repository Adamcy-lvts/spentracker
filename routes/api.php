<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Admin\UserMonitorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public authentication routes
Route::prefix('v1')->group(function () {
    // Authentication routes (no auth required)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // User routes
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']); // ADD THIS LINE


        // Category routes (read-only for mobile apps typically)
        Route::apiResource('categories', CategoryController::class);

        // Expense routes
        Route::apiResource('expenses', ExpenseController::class);

        // Additional expense endpoints
        Route::post('/expenses/bulk-delete', [ExpenseController::class, 'bulkDelete']);
        Route::get('/expenses-statistics', [ExpenseController::class, 'statistics']);

        // Admin routes (require admin role)
        Route::prefix('admin')->group(function () {
            Route::get('/users', [UserMonitorController::class, 'index']);
            Route::get('/users/{user}', [UserMonitorController::class, 'show']);
            Route::get('/users-statistics', [UserMonitorController::class, 'statistics']);
            Route::patch('/users/{user}/toggle-admin', [UserMonitorController::class, 'toggleAdmin']);
        });
    });
});

// Health check route (public)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
    ]);
});

// Debug endpoint (public)
Route::get('/debug', function () {
    return response()->json([
        'server_time' => now()->toISOString(),
        'app_env' => config('app.env'),
        'database_connected' => DB::connection()->getPdo() ? true : false,
        'sanctum_enabled' => class_exists('Laravel\Sanctum\Sanctum'),
        'request_ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'headers_received' => request()->headers->all(),
    ]);
});
