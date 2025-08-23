<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // At least one of email or phone_number must be provided
        if (empty($request->email) && empty($request->phone_number)) {
            throw ValidationException::withMessages([
                'email' => ['Either email or phone number must be provided.'],
                'phone_number' => ['Either email or phone number must be provided.'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Track registration location (same as login)
        $user->updateLoginTracking($request);

        // Create token with expiry (24 hours)
        $tokenResult = $user->createToken('mobile-app', ['*'], Carbon::now()->addHours(24));
        $token = $tokenResult->plainTextToken;

        // Calculate expiry info
        $expiresAt = Carbon::now()->addHours(24);
        $expiresIn = $expiresAt->diffInSeconds(Carbon::now());

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
            ],
            'token' => $token,
            'access_token' => $token, // For consistency
            'expires_in' => $expiresIn, // Seconds until expiry
            'expires_at' => $expiresAt->toISOString(), // ISO timestamp
        ], Response::HTTP_CREATED);
    }

    /**
     * Refresh the user's access token
     */
    public function refresh(Request $request)
    {
        $user = $request->user();

        // Delete current token
        $request->user()->currentAccessToken()->delete();

        // Create new token with expiry (24 hours)
        $tokenResult = $user->createToken('mobile-app', ['*'], Carbon::now()->addHours(24));
        $token = $tokenResult->plainTextToken;

        // Calculate expiry info
        $expiresAt = Carbon::now()->addHours(24);
        $expiresIn = $expiresAt->diffInSeconds(Carbon::now());

        return response()->json([
            'message' => 'Token refreshed successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
            ],
            'token' => $token,
            'access_token' => $token,
            'expires_in' => $expiresIn,
            'expires_at' => $expiresAt->toISOString(),
        ]);
    }


    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Determine if login field is email or phone number
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        
        $user = User::where($loginField, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens for this user (optional - for single device login)
        // $user->tokens()->delete();

        // Update login tracking
        $user->updateLoginTracking($request);

        // Create new API token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'is_admin' => $user->is_admin,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        // Delete current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get current user information
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'phone_number' => $request->user()->phone_number,
                'is_admin' => $request->user()->is_admin,
            ]
        ]);
    }
}
