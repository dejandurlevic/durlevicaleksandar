<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display all payments.
     */
    public function index()
    {
        $payments = Payment::with('user')
            ->latest()
            ->paginate(20);
        
        $users = User::where('is_admin', false)
            ->with('payments')
            ->latest()
            ->get();
        
        return view('admin.payments.index', compact('payments', 'users'));
    }

    /**
     * Activate subscription based on payment.
     */
    public function activateSubscription(Request $request, Payment $payment)
    {
        $user = $payment->user;
        
        // Activate subscription
        $user->update([
            'subscription_active' => true,
            'subscription_expires_at' => now()->addDays(30),
        ]);

        // Update payment status
        $payment->update([
            'status' => 'paid',
        ]);

        // Create or update subscription record
        $subscription = \App\Models\Subscription::where('user_id', $user->id)
            ->where('active', true)
            ->first();

        if ($subscription) {
            $subscription->update([
                'active' => true,
                'expires_at' => now()->addDays(30),
            ]);
        } else {
            \App\Models\Subscription::create([
                'user_id' => $user->id,
                'plan_name' => 'Standard Plan',
                'price' => $payment->amount,
                'active' => true,
                'expires_at' => now()->addDays(30),
            ]);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Subscription activated successfully for user: ' . $user->name);
    }
}
















