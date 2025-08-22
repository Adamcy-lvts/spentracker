<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserMonitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                return response()->json(['message' => 'Unauthorized. Admin access required.'], Response::HTTP_FORBIDDEN);
            }
            return $next($request);
        });
    }

    /**
     * Get all users with their tracking information
     */
    public function index()
    {
        $users = User::select([
            'id', 'name', 'email', 'is_admin', 'email_verified_at', 
            'last_login_at', 'last_login_ip', 'last_login_user_agent', 
            'last_login_location', 'last_login_latitude', 'last_login_longitude',
            'last_login_city', 'last_login_country', 'last_login_device_type',
            'created_at', 'updated_at'
        ])
        ->orderBy('last_login_at', 'desc')
        ->paginate(20);

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Get detailed information about a specific user
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'email_verified_at' => $user->email_verified_at,
                'last_login_at' => $user->last_login_at,
                'last_login_ip' => $user->last_login_ip,
                'last_login_user_agent' => $user->last_login_user_agent,
                'last_login_location' => $user->last_login_location,
                'last_login_latitude' => $user->last_login_latitude,
                'last_login_longitude' => $user->last_login_longitude,
                'last_login_city' => $user->last_login_city,
                'last_login_country' => $user->last_login_country,
                'last_login_device_type' => $user->last_login_device_type,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'categories_count' => $user->categories()->count(),
                'expenses_count' => $user->categories()->withCount('expenses')->get()->sum('expenses_count'),
            ]
        ]);
    }

    /**
     * Get user activity statistics
     */
    public function statistics()
    {
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        $activeUsersLastWeek = User::where('last_login_at', '>=', now()->subWeek())->count();
        $activeUsersLastMonth = User::where('last_login_at', '>=', now()->subMonth())->count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();

        return response()->json([
            'statistics' => [
                'total_users' => $totalUsers,
                'admin_users' => $adminUsers,
                'regular_users' => $totalUsers - $adminUsers,
                'active_users_last_week' => $activeUsersLastWeek,
                'active_users_last_month' => $activeUsersLastMonth,
                'new_users_this_month' => $newUsersThisMonth,
                'inactive_users' => $totalUsers - $activeUsersLastMonth,
            ]
        ]);
    }

    /**
     * Toggle admin status of a user
     */
    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);

        return response()->json([
            'message' => $user->is_admin ? 'User promoted to admin' : 'User demoted from admin',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ]
        ]);
    }
}
