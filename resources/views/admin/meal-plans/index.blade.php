<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Meal Plans - Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:transition-none h-screen overflow-y-auto">
            <div class="p-4 lg:p-6">
                <div class="flex items-center justify-between mb-6 lg:mb-8">
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900">FitCoach Admin</h1>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <p class="px-3 sm:px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Admin Panel</p>
                        
                        <a href="{{ route('admin.videos.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Manage Videos
                        </a>
                        
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Manage Categories
                        </a>
                        
                        <a href="{{ route('admin.meal-plans.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-900 bg-gray-100 rounded-lg font-medium text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            Manage Meal Plans
                        </a>
                        
                        <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Subscriptions
                        </a>
                        
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Payments
                        </a>
                    </div>
                    
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 w-full">
            <!-- Mobile Header -->
            <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900">FitCoach Admin</h1>
                <div class="w-6"></div>
            </div>

            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Manage Meal Plans</h1>
                        <p class="text-sm sm:text-base text-gray-600">Create, edit, and delete meal plans</p>
                    </div>
                    <a href="{{ route('admin.meal-plans.create') }}" class="px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-semibold text-sm sm:text-base hover:shadow-lg transition-all duration-200 whitespace-nowrap">
                        + Add New Meal Plan
                    </a>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                        <div class="flex items-start sm:items-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs sm:text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                        <div class="flex items-start sm:items-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs sm:text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Meal Plans Table -->
                @if($mealPlans->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meal Plan</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Calories</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Status</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Created</th>
                                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mealPlans as $mealPlan)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 sm:px-6 py-4">
                                                <div class="flex items-center">
                                                    @if($mealPlan->image)
                                                        <img src="{{ $mealPlan->image }}" alt="{{ $mealPlan->title }}" class="h-10 w-16 sm:h-12 sm:w-20 object-cover rounded-lg mr-2 sm:mr-4 flex-shrink-0">
                                                    @else
                                                        <div class="h-10 w-16 sm:h-12 sm:w-20 bg-green-200 rounded-lg mr-2 sm:mr-4 flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $mealPlan->title }}</div>
                                                        @if($mealPlan->description)
                                                            <div class="text-xs sm:text-sm text-gray-500 truncate">{{ strlen($mealPlan->description) > 50 ? substr($mealPlan->description, 0, 50) . '...' : $mealPlan->description }}</div>
                                                        @endif
                                                        <div class="sm:hidden mt-1">
                                                            @if($mealPlan->calories)
                                                                <span class="text-xs text-gray-600">{{ $mealPlan->calories }} cal</span>
                                                            @endif
                                                            @if($mealPlan->is_active)
                                                                <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                                                            @else
                                                                <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Inactive</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                                @if($mealPlan->calories)
                                                    <span class="text-xs sm:text-sm text-gray-900">{{ $mealPlan->calories }} cal</span>
                                                @else
                                                    <span class="text-xs sm:text-sm text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                                @if($mealPlan->is_active)
                                                    <span class="px-2 sm:px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                                                @else
                                                    <span class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                                {{ $mealPlan->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2 sm:space-x-3">
                                                    <a href="{{ route('admin.meal-plans.edit', $mealPlan) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.meal-plans.destroy', $mealPlan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this meal plan?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-3 sm:px-6 py-4 border-t border-gray-200">
                            {{ $mealPlans->links() }}
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No meal plans found</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Get started by creating your first meal plan.</p>
                        <a href="{{ route('admin.meal-plans.create') }}" class="inline-block px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-semibold text-sm sm:text-base hover:shadow-lg transition-all duration-200">
                            Create First Meal Plan
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>

