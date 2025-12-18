<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $mealPlan->title }} - {{ config('app.name', 'Laravel') }}</title>
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
                <!-- Back Button -->
                <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4 sm:mb-6 transition-colors text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Meal Plans
                </a>

                <!-- Meal Plan Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 lg:mb-8">
                    @if($mealPlan->image)
                        <div class="relative h-48 sm:h-64 bg-gray-100 overflow-hidden">
                            <img src="{{ $mealPlan->image }}" alt="{{ $mealPlan->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    
                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $mealPlan->title }}</h1>
                            @if($mealPlan->calories)
                                <span class="bg-green-100 text-green-800 px-3 sm:px-4 py-1 sm:py-2 rounded-full text-xs sm:text-sm font-semibold self-start sm:self-auto">
                                    {{ $mealPlan->calories }} calories
                                </span>
                            @endif
                        </div>
                        
                        @if($mealPlan->description)
                            <p class="text-sm sm:text-base lg:text-lg text-gray-600 mb-4 sm:mb-6">{{ $mealPlan->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Meal Plan Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 lg:mb-8">
                    @if($mealPlan->breakfast)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Breakfast</h2>
                            </div>
                            <div class="text-sm sm:text-base text-gray-700 whitespace-pre-line">{{ $mealPlan->breakfast }}</div>
                        </div>
                    @endif

                    @if($mealPlan->lunch)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Lunch</h2>
                            </div>
                            <div class="text-sm sm:text-base text-gray-700 whitespace-pre-line">{{ $mealPlan->lunch }}</div>
                        </div>
                    @endif

                    @if($mealPlan->dinner)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Dinner</h2>
                            </div>
                            <div class="text-sm sm:text-base text-gray-700 whitespace-pre-line">{{ $mealPlan->dinner }}</div>
                        </div>
                    @endif

                    @if($mealPlan->snacks)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Snacks</h2>
                            </div>
                            <div class="text-sm sm:text-base text-gray-700 whitespace-pre-line">{{ $mealPlan->snacks }}</div>
                        </div>
                    @endif
                </div>

                <!-- Related Meal Plans -->
                @if($relatedMealPlans->count() > 0)
                    <div class="mt-8 sm:mt-12">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Related Meal Plans</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($relatedMealPlans as $related)
                                <a href="{{ route('meal-plans.show', $related) }}" class="group">
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                                        @if($related->image)
                                            <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                                <img src="{{ $related->image }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            </div>
                                        @endif
                                        <div class="p-4 sm:p-5">
                                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 group-hover:text-gray-600 transition-colors">
                                                {{ $related->title }}
                                            </h3>
                                            @if($related->calories)
                                                <p class="text-xs sm:text-sm text-gray-500">{{ $related->calories }} calories</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>

