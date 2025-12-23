<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-me', [AboutController::class, 'index'])->name('about');

// Authentication Routes
require __DIR__.'/auth.php';

// Inquiry Route
Route::post('/send-inquiry', [InquiryController::class, 'send'])->name('send.inquiry');


// Protected Routes (Auth Required)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (with subscription check)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Video Library (accessible to all, but premium content requires subscription)
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    
    // Meal Plans (requires active subscription)
    Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
    Route::get('/meal-plans/{mealPlan}', [MealPlanController::class, 'show'])->name('meal-plans.show');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin Routes
    Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
        // Manage Videos
        Route::get('/videos', [\App\Http\Controllers\Admin\VideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/debug', [\App\Http\Controllers\Admin\VideoController::class, 'debug'])->name('videos.debug');
        Route::get('/videos/create', [\App\Http\Controllers\Admin\VideoController::class, 'create'])->name('videos.create');
        Route::post('/videos/store', [\App\Http\Controllers\Admin\VideoController::class, 'store'])->name('videos.store');
        Route::get('/videos/{video}/preview', [\App\Http\Controllers\Admin\VideoController::class, 'preview'])->name('videos.preview');
        Route::delete('/videos/{video}', [\App\Http\Controllers\Admin\VideoController::class, 'destroy'])->name('videos.destroy');
        
        // Manage Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Subscriptions Management
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions/{user}/update-status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');
        
        // Payments Management
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/activate', [PaymentController::class, 'activateSubscription'])->name('payments.activate');
        
        // Manage Meal Plans
        Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
        Route::get('/meal-plans/create', [MealPlanController::class, 'create'])->name('meal-plans.create');
        Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
        Route::get('/meal-plans/{mealPlan}/edit', [MealPlanController::class, 'edit'])->name('meal-plans.edit');
        Route::put('/meal-plans/{mealPlan}', [MealPlanController::class, 'update'])->name('meal-plans.update');
        Route::delete('/meal-plans/{mealPlan}', [MealPlanController::class, 'destroy'])->name('meal-plans.destroy');
        
        // Category Videos Management (view and delete videos in a category)
        Route::get('/categories/{category}/videos', [CategoryController::class, 'show'])->name('categories.videos');
        Route::delete('/categories/{category}/videos/{video}', [CategoryController::class, 'destroyVideo'])->name('categories.videos.destroy');
        
        // Manage Inquiries
        Route::get('/inquiries', [\App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('inquiries.index');
        Route::post('/inquiries/{inquiry}/approve', [\App\Http\Controllers\Admin\InquiryController::class, 'approve'])->name('inquiries.approve');
    });
});
