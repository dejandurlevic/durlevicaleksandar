<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  
    public function index()
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Get subscription status
        $subscriptionActive = $user->subscription_active;
        $subscriptionExpiresAt = $user->subscription_expires_at;
        
        // Get video counts
        $totalVideos = Video::count();
        $premiumVideos = Video::where('is_premium', true)->count();
        
        // Get recommended videos (latest 6 videos)
        try {
            $recommendedVideos = Video::with('category')
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($video) {
                    // Ensure category is safely accessible
                    if (!$video->category) {
                        $video->category = null;
                    }
                    return $video;
                });
        } catch (\Exception $e) {
            // Fallback if there's an error
            $recommendedVideos = collect([]);
        }
        
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





















