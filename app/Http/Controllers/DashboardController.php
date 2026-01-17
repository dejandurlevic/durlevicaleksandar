<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get subscription status
        $subscriptionActive = $user->subscription_active;
        $subscriptionExpiresAt = $user->subscription_expires_at;
        
        // Get video counts
        $totalVideos = Video::count();
        $premiumVideos = Video::where('is_premium', true)->count();
        
        // Get recommended videos (latest 6 videos)
        // Use with('category') but handle null categories gracefully
        $recommendedVideos = Video::with('category')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();
        
        return view('dashboard', compact(
            'user',
            'subscriptionActive',
            'subscriptionExpiresAt',
            'totalVideos',
            'premiumVideos',
            'recommendedVideos'
        ));
    }
}





















