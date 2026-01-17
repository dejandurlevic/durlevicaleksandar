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
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            // Get subscription status
            $subscriptionActive = $user->subscription_active ?? false;
            $subscriptionExpiresAt = $user->subscription_expires_at ?? null;
            
            // Get video counts
            $totalVideos = Video::count();
            $premiumVideos = Video::where('is_premium', true)->count();
            
            // Get recommended videos (latest 6 videos)
            // Use with('category') but handle null categories gracefully
            $recommendedVideos = Video::with('category')
                ->orderBy('id', 'desc')
                ->take(6)
                ->get()
                ->map(function($video) {
                    // Ensure category relationship is properly loaded
                    if (!$video->relationLoaded('category')) {
                        $video->load('category');
                    }
                    return $video;
                });
            
            return view('dashboard', compact(
                'user',
                'subscriptionActive',
                'subscriptionExpiresAt',
                'totalVideos',
                'premiumVideos',
                'recommendedVideos'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'An error occurred while loading the dashboard. Please try again later.');
        }
    }
}





















