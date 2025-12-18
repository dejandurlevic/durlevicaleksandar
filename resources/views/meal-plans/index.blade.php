<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meal Plans - {{ config('app.name', 'Laravel') }}</title>
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
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900">FitCoach</h1>
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
                    
                    <a href="{{ route('videos.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Video Library
                    </a>
                    
                    <a href="{{ route('categories.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Categories
                    </a>
                    
                    <a href="{{ route('meal-plans.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-900 bg-gray-100 rounded-lg font-medium text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Meal Plans
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                    
                    @if(auth()->user()->is_admin)
                        <div class="pt-4 mt-4 border-t border-gray-200">
                            <p class="px-3 sm:px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Admin</p>
                            
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
                        </div>
                    @endif
                    
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
                <h1 class="text-lg font-bold text-gray-900">FitCoach</h1>
                <div class="w-6"></div>
            </div>

            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Header -->
                <div class="mb-6 lg:mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Meal Plans</h1>
                    <p class="text-sm sm:text-base text-gray-600">Discover nutritious meal plans tailored for your fitness goals</p>
                </div>

                <!-- Premium Access Banner -->
                <div class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 mb-6 lg:mb-8 rounded-lg">
                    <div class="flex items-start sm:items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-green-800">
                                <strong>Premium Access:</strong> You have full access to all meal plans as part of your subscription.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Meal Plans Grid -->
                @if($mealPlans->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 lg:mb-8">
                        @foreach($mealPlans as $mealPlan)
                            <a href="{{ route('meal-plans.show', $mealPlan) }}" class="group">
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                                    <!-- Image -->
                                    <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                        @if($mealPlan->image)
                                            <img src="{{ $mealPlan->image }}" alt="{{ $mealPlan->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-green-200 to-green-300 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        @if($mealPlan->calories)
                                            <div class="absolute top-2 right-2 bg-white/90 text-gray-900 px-3 py-1 rounded-full text-xs font-bold">
                                                {{ $mealPlan->calories }} cal
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Meal Plan Info -->
                                    <div class="p-4 sm:p-5">
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 group-hover:text-gray-600 transition-colors line-clamp-2">
                                            {{ $mealPlan->title }}
                                        </h3>
                                        @if($mealPlan->description)
                                            <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 mb-3">
                                                {{ $mealPlan->description }}
                                            </p>
                                        @endif
                                        <p class="text-xs sm:text-sm text-gray-500 flex items-center">
                                            View meal plan
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 lg:mt-8">
                        {{ $mealPlans->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No meal plans available</h3>
                        <p class="text-sm sm:text-base text-gray-600">Check back soon for new meal plans.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>

