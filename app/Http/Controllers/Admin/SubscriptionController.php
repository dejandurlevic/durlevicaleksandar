<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display all subscriptions.
     */
    public function index()
    {
        $users = User::with('subscriptions')
            ->where('is_admin', false)
            ->latest()
            ->paginate(15);
        
        return view('admin.subscriptions.index', compact('users'));
    }

    /**
     * Update user subscription status.
     */
    public function updateStatus(Request $request, User $user)
    {
        // Handle checkbox - if not present or not equal to '1', it's false
        $subscriptionActive = $request->has('subscription_active') && $request->input('subscription_active') == '1';
        
        // Handle date - convert empty string to null
        $expiresAt = $request->input('subscription_expires_at');
        if ($expiresAt === '' || $expiresAt === null) {
            $expiresAt = null;
        }

        $user->update([
            'subscription_active' => $subscriptionActive,
            'subscription_expires_at' => $expiresAt,
        ]);

        // Update all subscriptions for this user
        $subscriptions = Subscription::where('user_id', $user->id)->get();

        if ($subscriptionActive) {
            // If activating, update or create subscription record
            $subscription = Subscription::where('user_id', $user->id)
                ->where('active', true)
                ->first();

            if ($subscription) {
                $subscription->update([
                    'active' => true,
                    'expires_at' => $expiresAt ?? now()->addDays(30),
                ]);
            } else {
                Subscription::create([
                    'user_id' => $user->id,
                    'plan_name' => 'Manual Activation',
                    'price' => 0,
                    'active' => true,
                    'expires_at' => $expiresAt ?? now()->addDays(30),
                ]);
            }
            
            // Deactivate all other subscriptions
            $activeSubscription = Subscription::where('user_id', $user->id)
                ->where('active', true)
                ->first();
            
            if ($activeSubscription) {
                Subscription::where('user_id', $user->id)
                    ->where('id', '!=', $activeSubscription->id)
                    ->update(['active' => false]);
            }
        } else {
            // If deactivating, set all subscriptions to inactive
            Subscription::where('user_id', $user->id)
                ->update([
                    'active' => false,
                    'expires_at' => $expiresAt,
                ]);
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription status updated successfully.');
    }
}

