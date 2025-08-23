<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|lowercase|email|max:255|unique:'.User::class,
            'phone_number' => [
                'nullable',
                'string',
                'unique:'.User::class,
                'regex:/^(\+234|234|0)(70|80|81|90|91|70|71|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99)[0-9]{8}$/'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone_number.regex' => 'Please enter a valid Nigerian phone number (e.g., +234801234567, 08012345678)',
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

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
