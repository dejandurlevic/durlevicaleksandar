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
        $inquiry = null;
        
        if ($token) {
            $inquiry = Inquiry::where('invite_token', $token)
                ->where('approved', true)
                ->where('invite_token_expires_at', '>', now())
                ->first();
            
            if (!$inquiry) {
                return view('auth.register', [
                    'error' => 'Invalid or expired invitation token. Please contact support.',
                    'token' => null,
                ]);
            }
        } else {
            // No token provided - registration is closed
            return view('auth.register', [
                'error' => 'Registration is by invitation only. Please submit an inquiry first.',
                'token' => null,
            ]);
        }
        
        return view('auth.register', [
            'token' => $token,
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required|string',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verify token
        $inquiry = Inquiry::where('invite_token', $request->token)
            ->where('approved', true)
            ->where('invite_token_expires_at', '>', now())
            ->first();

        if (!$inquiry) {
            return back()->withErrors([
                'token' => 'Invalid or expired invitation token.',
            ])->withInput();
        }

        // Check if email matches inquiry email
        if ($inquiry->email !== $request->email) {
            return back()->withErrors([
                'email' => 'Email must match the email used in your inquiry.',
            ])->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'invite_token_used_at' => now(),
        ]);

        // Mark inquiry token as used (optional - you can keep it for tracking)
        $inquiry->update([
            'invite_token' => null, // Clear token so it can't be reused
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
