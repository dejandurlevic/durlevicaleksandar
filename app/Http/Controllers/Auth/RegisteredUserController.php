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
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'selected_plan' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        // Create user with pending status (not active)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'subscription_active' => false, // User is not active until approved
        ]);

        // Create inquiry for approval
        \App\Models\Inquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? '',
            'plan' => $request->selected_plan,
            'message' => $request->message ?? null,
            'approved' => false,
        ]);

        // Send email notification to admin
        try {
            $recipientEmail = config('mail.from.address', 'durlevicaleksandar@gmail.com');
            \Illuminate\Support\Facades\Mail::send('emails.inquiry', [
                'plan' => $request->selected_plan,
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'name' => $request->name,
                'inquiryMessage' => $request->message ?? null,
            ], function ($mail) use ($request, $recipientEmail) {
                $mail->to($recipientEmail)
                    ->subject('New Registration Request: ' . $request->selected_plan);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send registration inquiry email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
        }

        // Don't login user automatically - they need approval
        return redirect(route('login'))
            ->with('status', 'Your registration request has been submitted. You will receive an email notification once your account is approved.');
    }
}
