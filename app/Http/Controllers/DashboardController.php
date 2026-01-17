<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Inquiry;
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
        $recommendedVideos = Video::with('category')
            ->latest()
            ->take(6)
            ->get();
        
        // Get pending inquiries count for admin
        $pendingInquiriesCount = 0;
        if ($user->is_admin) {
            $pendingInquiriesCount = Inquiry::where('approved', false)->count();
        }
        
        return view('dashboard', compact(
            'user',
            'subscriptionActive',
            'subscriptionExpiresAt',
            'totalVideos',
            'premiumVideos',
            'recommendedVideos',
            'pendingInquiriesCount'
        ));
    }
}





















