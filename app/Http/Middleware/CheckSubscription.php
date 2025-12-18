<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Allow admins to access everything
        if ($user && $user->is_admin) {
            return $next($request);
        }

        // Check if user has active subscription
        if (!$user || !$user->subscription_active) {
            return redirect()->route('home')
                ->with('error', 'Your subscription is inactive. Please choose a plan.');
        }

        // Check if subscription has expired
        if ($user->subscription_expires_at && $user->subscription_expires_at->isPast()) {
            $user->update(['subscription_active' => false]);
            return redirect()->route('home')
                ->with('error', 'Your subscription has expired. Please renew your subscription.');
        }

        return $next($request);
    }
}



















