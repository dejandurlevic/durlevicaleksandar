<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    /**
     * Display a listing of meal plans.
     */
    public function index()
    {
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            $mealPlans = MealPlan::latest()->paginate(15);
            return view('admin.meal-plans.index', compact('mealPlans'));
        }
        
        $user = Auth::user();
        
        // Check subscription status - meal plans require active subscription
        if (!$user->subscription_active) {
            return redirect()->route('home')
                ->with('error', 'You need an active subscription to access meal plans. Please subscribe to continue.');
        }
        
        $mealPlans = MealPlan::where('is_active', true)
            ->latest()
            ->paginate(12);
        
        return view('meal-plans.index', compact('mealPlans'));
    }

    /**
     * Display the specified meal plan.
     */
    public function show(MealPlan $mealPlan)
    {
        // Check if this is an admin request
        if (request()->routeIs('admin.*')) {
            return view('admin.meal-plans.show', compact('mealPlan'));
        }
        
        $user = Auth::user();
        
        // Check subscription status - meal plans require active subscription
        if (!$user->subscription_active) {
            return redirect()->route('home')
                ->with('error', 'You need an active subscription to access meal plans. Please subscribe to continue.');
        }
        
        // Check if meal plan is active
        if (!$mealPlan->is_active) {
            return redirect()->route('meal-plans.index')
                ->with('error', 'This meal plan is not available.');
        }
        
        // Get related meal plans
        $relatedMealPlans = MealPlan::where('is_active', true)
            ->where('id', '!=', $mealPlan->id)
            ->latest()
            ->take(6)
            ->get();
        
        return view('meal-plans.show', compact('mealPlan', 'relatedMealPlans'));
    }

    /**
     * Show the form for creating a new meal plan (Admin).
     */
    public function create()
    {
        return view('admin.meal-plans.create');
    }

    /**
     * Store a newly created meal plan (Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'breakfast' => 'nullable|string',
            'lunch' => 'nullable|string',
            'dinner' => 'nullable|string',
            'snacks' => 'nullable|string',
            'calories' => 'nullable|integer|min:0',
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        MealPlan::create($validated);

        return redirect()->route('admin.meal-plans.index')
            ->with('success', 'Meal plan created successfully.');
    }

    /**
     * Show the form for editing the specified meal plan (Admin).
     */
    public function edit(MealPlan $mealPlan)
    {
        return view('admin.meal-plans.edit', compact('mealPlan'));
    }

    /**
     * Update the specified meal plan (Admin).
     */
    public function update(Request $request, MealPlan $mealPlan)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'breakfast' => 'nullable|string',
            'lunch' => 'nullable|string',
            'dinner' => 'nullable|string',
            'snacks' => 'nullable|string',
            'calories' => 'nullable|integer|min:0',
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $mealPlan->update($validated);

        return redirect()->route('admin.meal-plans.index')
            ->with('success', 'Meal plan updated successfully.');
    }

    /**
     * Remove the specified meal plan (Admin).
     */
    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();

        return redirect()->route('admin.meal-plans.index')
            ->with('success', 'Meal plan deleted successfully.');
    }
}
