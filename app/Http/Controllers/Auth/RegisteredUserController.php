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
        // Validate token if provided
        $inquiry = null;
        if ($request->has('token')) {
            $inquiry = Inquiry::where('invite_token', $request->token)
                ->where('approved', true)
                ->where('invite_token_expires_at', '>', now())
                ->first();
                
            if (!$inquiry) {
                return redirect()->route('register')
                    ->withErrors(['token' => 'Invalid or expired registration token. Please contact the administrator.']);
            }
        } else {
            // If no token, require plan selection (old flow)
            if (!$request->has('plan')) {
                return redirect()->route('register')
                    ->withErrors(['plan' => 'Please select a plan first or use a valid registration token.']);
            }
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        // Use inquiry email if token was used
        $email = $inquiry ? $inquiry->email : $request->email;
        
        // Verify email matches if using token
        if ($inquiry && $email !== $request->email) {
            return redirect()->route('register', ['token' => $request->token])
                ->withErrors(['email' => 'Email must match the one from your invitation.']);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'subscription_active' => false, // User is not active until approved
        ]);

        // If token was used, update inquiry to mark token as used
        if ($inquiry) {
            // Token is single-use, so we can optionally invalidate it or leave it for tracking
            // For now, we'll leave it for reference
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
