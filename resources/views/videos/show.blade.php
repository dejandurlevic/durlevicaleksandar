<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $video->title }} - {{ config('app.name', 'Laravel') }}</title>
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
                    
                    <a href="{{ route('videos.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-900 bg-gray-100 rounded-lg font-medium text-sm sm:text-base">
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
                    
                    @if(auth()->user()->subscription_active)
                    <a href="{{ route('meal-plans.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        Meal Plans
                    </a>
                    @endif
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                    
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
                <a href="{{ route('videos.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4 sm:mb-6 font-medium text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Video Library
                </a>

                <!-- Video Player Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    <!-- Main Video -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Video Player -->
                            <div class="aspect-video bg-black relative">
                                @if($video->video_path)
                                    <video 
                                        controls 
                                        class="w-full h-full"
                                        poster="{{ $video->thumbnail ?? '' }}"
                                    >
                                        <source src="{{ $video->video_path }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <div class="text-center text-white">
                                            <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-lg">Video not available</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Video Info -->
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                    <span class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs sm:text-sm font-semibold">
                                        {{ $video->category->name }}
                                    </span>
                                    @if($video->is_premium)
                                        <span class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs sm:text-sm font-semibold">
                                            Premium
                                        </span>
                                    @endif
                                </div>
                                
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">{{ $video->title }}</h1>
                                
                                @if($video->description)
                                    <div class="prose max-w-none text-sm sm:text-base">
                                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $video->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Related Videos -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Related Videos</h2>
                            
                            @if($relatedVideos->count() > 0)
                                <div class="space-y-3 sm:space-y-4">
                                    @foreach($relatedVideos as $relatedVideo)
                                        <a href="{{ route('videos.show', $relatedVideo) }}" class="block group">
                                            <div class="flex gap-3 sm:gap-4">
                                                <div class="relative w-24 h-16 sm:w-32 sm:h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                                    @if($relatedVideo->thumbnail)
                                                        <img src="{{ $relatedVideo->thumbnail }}" alt="{{ $relatedVideo->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    @if($relatedVideo->is_premium)
                                                        <div class="absolute top-1 right-1 bg-yellow-400 text-yellow-900 px-1.5 py-0.5 rounded text-xs font-bold">
                                                            Premium
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-xs sm:text-sm font-bold text-gray-900 mb-1 group-hover:text-gray-600 transition-colors line-clamp-2">
                                                        {{ $relatedVideo->title }}
                                                    </h3>
                                                    <p class="text-xs text-gray-500">{{ $relatedVideo->category->name }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No related videos available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>




