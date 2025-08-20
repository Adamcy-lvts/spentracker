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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create token with expiry (24 hours)
        $tokenResult = $user->createToken('mobile-app', ['*'], Carbon::now()->addHours(24));
        $token = $tokenResult->plainTextToken;

        // Calculate expiry info
        $expiresAt = Carbon::now()->addHours(24);
        $expiresIn = $expiresAt->diffInSeconds(Carbon::now());

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens for this user (optional - for single device login)
        // $user->tokens()->delete();

        // Create new API token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
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
            ]
        ]);
    }
}
