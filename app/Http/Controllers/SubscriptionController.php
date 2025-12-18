<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Subscribe user to a plan.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Cancel existing subscriptions
        Auth::user()->subscriptions()->update(['active' => false]);

        // Create new subscription
        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'plan_name' => $validated['plan_name'],
            'price' => $validated['price'],
            'active' => true,
            'expires_at' => now()->addMonth(),
        ]);

        // Update user subscription status
        Auth::user()->update([
            'subscription_active' => true,
            'subscription_expires_at' => $subscription->expires_at,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Subscription activated successfully.');
    }

    /**
     * Cancel user subscription.
     */
    public function cancel()
    {
        Auth::user()->subscriptions()->update(['active' => false]);
        
        Auth::user()->update([
            'subscription_active' => false,
            'subscription_expires_at' => null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Check subscription status.
     */
    public function checkStatus()
    {
        $user = Auth::user();
        
        return response()->json([
            'active' => $user->subscription_active,
            'expires_at' => $user->subscription_expires_at,
        ]);
    }
}



















