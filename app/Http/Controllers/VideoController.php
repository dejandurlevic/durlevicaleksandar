<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index()
    {
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            $videos = Video::with('category')->latest()->paginate(15);
            return view('admin.videos.index', compact('videos'));
        }
        
        $user = auth()->user();
        
        // Check subscription status
        $hasSubscription = $user->subscription_active == 1;
        
        // Get category filter from request
        $categoryId = request()->query('category');
        
        // Get all categories for filter dropdown
        $categories = Category::withCount('videos')->orderBy('name')->get();
        
        // Get selected category if filtering
        $selectedCategory = $categoryId ? Category::find($categoryId) : null;
        
        // Build query
        $query = Video::with('category');
        
        // Filter by category if provided
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // Filter by subscription status
        if (!$hasSubscription) {
            $query->where('is_premium', false);
        }
        
        // Order by oldest first (first uploaded = first shown)
        $videos = $query->oldest()->paginate(12);
        
        return view('videos.index', compact('videos', 'hasSubscription', 'categories', 'selectedCategory'));
    }

    /**
     * Display the specified video.
     */
    public function show(Video $video)
    {
        $user = auth()->user();
        $hasSubscription = $user->subscription_active == 1;
        
        // Check if user can access this video
        if ($video->is_premium && !$hasSubscription) {
            return redirect()->route('videos.index')
                ->with('error', 'This is a premium video. Please subscribe to access premium content.');
        }
        
        $video->load('category');
        
        // Get related videos
        $relatedVideos = Video::with('category')
            ->where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->where(function($query) use ($hasSubscription) {
                if (!$hasSubscription) {
                    $query->where('is_premium', false);
                }
            })
            ->latest()
            ->take(6)
            ->get();
        
        return view('videos.show', compact('video', 'hasSubscription', 'relatedVideos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $categories = Category::all();
        
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            return view('admin.videos.create', compact('categories'));
        }
        
        return view('videos.create', compact('categories'));
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_path' => 'required|string',
            'thumbnail' => 'nullable|string',
            'is_premium' => 'boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video created successfully.');
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video)
    {
        $categories = Category::all();
        
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            return view('admin.videos.edit', compact('video', 'categories'));
        }
        
        return view('videos.edit', compact('video', 'categories'));
    }

    /**
     * Update the specified video.
     */
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_path' => 'required|string',
            'thumbnail' => 'nullable|string',
            'is_premium' => 'boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified video.
     */
    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully.');
    }
}




