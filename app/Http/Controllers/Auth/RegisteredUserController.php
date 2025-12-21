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
            $inquiry = \App\Models\Inquiry::where('invite_token', $token)
                ->where('approved', true)
                ->where('invite_token_expires_at', '>', now())
                ->first();
            
            if (!$inquiry) {
                return view('auth.register', [
                    'error' => 'Invalid or expired invitation token. Please contact the administrator.',
                    'token' => $token,
                ]);
            }
        } else {
            return view('auth.register', [
                'error' => 'Registration is only available via invitation. Please submit an inquiry first.',
            ]);
        }
        
        return view('auth.register', [
            'token' => $token,
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verify token and inquiry
        $inquiry = \App\Models\Inquiry::where('invite_token', $request->token)
            ->where('approved', true)
            ->where('invite_token_expires_at', '>', now())
            ->first();

        if (!$inquiry) {
            return redirect()->route('register', ['token' => $request->token])
                ->withErrors(['token' => 'Invalid or expired invitation token.']);
        }

        // Verify email matches inquiry
        if ($inquiry->email !== $request->email) {
            return redirect()->route('register', ['token' => $request->token])
                ->withErrors(['email' => 'Email must match the one used in your inquiry.']);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'invite_token' => $request->token,
            'invite_token_used_at' => now(),
        ]);

        // Mark inquiry token as used (optional - you might want to keep it for reference)
        // The token is already stored in the user, so we can track usage

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
