<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Inquiry;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $token = $request->query('token');
        $email = $request->query('email');
        
        // Validate token if provided
        $validToken = false;
        $tokenEmail = null;
        
        if ($token) {
            $inquiry = Inquiry::where('invite_token', $token)
                ->where('approved', true)
                ->where('invite_token_expires_at', '>', now())
                ->first();
            
            if ($inquiry) {
                $validToken = true;
                $tokenEmail = $inquiry->email;
            }
        }
        
        return view('auth.register', [
            'token' => $token,
            'email' => $email ?? $tokenEmail,
            'validToken' => $validToken,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate token is required
        $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verify token is valid
        $inquiry = Inquiry::where('invite_token', $request->token)
            ->where('approved', true)
            ->where('invite_token_expires_at', '>', now())
            ->first();

        if (!$inquiry) {
            return redirect()->route('register')
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['token' => 'Invalid or expired registration token. Please contact the administrator.']);
        }

        // Verify email matches the inquiry
        if ($inquiry->email !== $request->email) {
            return redirect()->route('register', ['token' => $request->token, 'email' => $inquiry->email])
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'The email must match the one used in your inquiry.']);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Mark inquiry token as used (optional - you can delete the token or mark it)
        $inquiry->update([
            'invite_token' => null, // Clear token so it can't be reused
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
