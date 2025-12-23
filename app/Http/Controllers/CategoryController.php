<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            $categories = Category::withCount('videos')->latest()->get();
            return view('admin.categories.index', compact('categories'));
        }
        
        $categories = Category::withCount('videos')->latest()->get();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            return view('admin.categories.create');
        }
        
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique - if not, append number
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $category = Category::create($validated);
        
        // Log for debugging
        \Log::info('Category created', [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            return view('admin.categories.edit', compact('category'));
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Display videos in a specific category (Admin only).
     */
    public function show(Category $category)
    {
        $videos = $category->videos()->latest()->paginate(15);
        
        return view('admin.categories.show', compact('category', 'videos'));
    }

    /**
     * Delete a video from a specific category (Admin only).
     */
    public function destroyVideo(Category $category, \App\Models\Video $video)
    {
        // Verify that the video belongs to this category
        if ($video->category_id !== $category->id) {
            return redirect()->route('admin.categories.videos', $category)
                ->with('error', 'This video does not belong to this category.');
        }
        
        $video->delete();

        return redirect()->route('admin.categories.videos', $category)
            ->with('success', 'Video deleted successfully.');
    }
}




