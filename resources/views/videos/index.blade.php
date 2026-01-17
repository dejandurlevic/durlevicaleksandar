<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Video Library - {{ config('app.name', 'Laravel') }}</title>
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

            <div class="p-3 sm:p-4 md:p-6 lg:p-8">
                <!-- Header -->
                <div class="mb-4 sm:mb-6 lg:mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 sm:mb-4">
                        <div class="mb-3 sm:mb-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">Video Library</h1>
                            <p class="text-xs sm:text-sm lg:text-base text-gray-600">Browse and watch your training videos</p>
                        </div>
                        
                        <!-- Category Filter -->
                        @if(isset($categories) && $categories->count() > 0)
                            <div class="mt-4 sm:mt-0 w-full sm:w-auto">
                                <form method="GET" action="{{ route('videos.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                    <select name="category" id="category-filter" onchange="this.form.submit()" class="block w-full sm:w-auto px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ $category->videos_count }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(request('category'))
                                        <a href="{{ route('videos.index') }}" class="w-full sm:w-auto px-3 sm:px-4 py-2 text-sm text-center text-gray-600 hover:text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors whitespace-nowrap">
                                            Clear
                                        </a>
                                    @endif
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Selected Category Badge -->
                    @if(isset($selectedCategory) && $selectedCategory)
                        <div class="flex flex-wrap items-center gap-2 px-3 sm:px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-xs sm:text-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="font-semibold text-blue-900">Showing: {{ $selectedCategory->name }}</span>
                            <span class="text-blue-600">({{ $videos->total() }} {{ Str::plural('video', $videos->total()) }})</span>
                        </div>
                    @endif
                </div>

                <!-- Subscription Status Banner -->
                @if(!$hasSubscription)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2.5 sm:p-3 lg:p-4 mb-4 sm:mb-6 lg:mb-8 rounded-lg">
                        <div class="flex items-start sm:items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-yellow-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-yellow-800 leading-relaxed">
                                    <strong>Ограничен приступ:</strong> You're viewing free videos only. 
                                    <a href="{{ route('home') }}#pricing" class="underline font-semibold">Subscribe now</a> to access all premium training videos.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-400 p-2.5 sm:p-3 lg:p-4 mb-4 sm:mb-6 lg:mb-8 rounded-lg">
                        <div class="flex items-start sm:items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-green-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-green-800 leading-relaxed">
                                    <strong>Premium Access:</strong> You have full access to all training videos, including premium content.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Videos Grid -->
                @if($videos->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-3 md:gap-4 lg:gap-5 mb-4 sm:mb-6 lg:mb-8">
                        @foreach($videos as $video)
                            <a href="{{ route('videos.show', $video) }}" class="group">
                                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform sm:hover:-translate-y-2">
                                    <!-- Thumbnail -->
                                    <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                        @if($video->thumbnail)
                                            @php
                                                // Generate thumbnail URL
                                                $thumbUrl = null;
                                                try {
                                                    $thumbPath = $video->thumbnail;
                                                    // Remove s3:// prefix if present
                                                    if (strpos($thumbPath, 's3://') === 0) {
                                                        $thumbPath = substr($thumbPath, 5);
                                                        $s3Config = config('filesystems.disks.s3');
                                                        $bucket = $s3Config['bucket'] ?? null;
                                                        if ($bucket && strpos($thumbPath, $bucket . '/') === 0) {
                                                            $thumbPath = substr($thumbPath, strlen($bucket) + 1);
                                                        }
                                                    }
                                                    
                                                    // Check if already a full URL
                                                    if (!filter_var($thumbPath, FILTER_VALIDATE_URL)) {
                                                        // Try to use Storage temporaryUrl first
                                                        try {
                                                            $thumbUrl = \Storage::disk('s3')->temporaryUrl($thumbPath, now()->addHours(24));
                                                        } catch (\Exception $e) {
                                                            // Fallback to manual URL construction
                                                            $s3Config = config('filesystems.disks.s3');
                                                            $bucket = $s3Config['bucket'] ?? null;
                                                            $region = $s3Config['region'] ?? 'us-east-1';
                                                            $usePathStyle = $s3Config['use_path_style_endpoint'] ?? false;
                                                            
                                                            if ($bucket) {
                                                                if ($usePathStyle) {
                                                                    $endpoint = $s3Config['endpoint'] ?? "https://s3.{$region}.amazonaws.com";
                                                                    $thumbUrl = rtrim($endpoint, '/') . '/' . $bucket . '/' . ltrim($thumbPath, '/');
                                                                } else {
                                                                    $endpoint = $s3Config['endpoint'] ?? "https://{$bucket}.s3.{$region}.amazonaws.com";
                                                                    $thumbUrl = rtrim($endpoint, '/') . '/' . ltrim($thumbPath, '/');
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        $thumbUrl = $thumbPath;
                                                    }
                                                } catch (\Exception $e) {
                                                    $thumbUrl = null;
                                                }
                                            @endphp
                                            @if($thumbUrl)
                                                <img src="{{ $thumbUrl }}" alt="{{ $video->title }}" class="w-full h-full object-cover sm:group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Premium Badge -->
                                        @if($video->is_premium)
                                            <div class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-yellow-400 text-yellow-900 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-bold">
                                                Premium
                                            </div>
                                        @endif
                                        
                                        <!-- Play Button Overlay -->
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/90 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-900 ml-0.5 sm:ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Video Info -->
                                    <div class="p-3 sm:p-4 lg:p-5">
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                            @if($video->category && $video->category->id)
                                                <a href="{{ route('videos.index') }}?category={{ $video->category->id }}" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold hover:bg-blue-100 transition-colors">
                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    <span class="truncate">{{ $video->category->name }}</span>
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 text-gray-600 rounded-md text-xs font-semibold">
                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    <span class="truncate">Uncategorized</span>
                                                </span>
                                            @endif
                                            @if($video->is_premium && !$hasSubscription)
                                                <span class="text-xs text-red-600 font-semibold whitespace-nowrap">🔒 Locked</span>
                                            @endif
                                        </div>
                                        <h3 class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 mb-1 sm:mb-2 group-hover:text-gray-600 transition-colors line-clamp-2">
                                            {{ $video->title }}
                                        </h3>
                                        @if($video->description)
                                            <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 hidden sm:block">
                                                {{ strlen($video->description) > 100 ? substr($video->description, 0, 100) . '...' : $video->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 sm:mt-6 lg:mt-8 overflow-x-auto">
                        <div class="min-w-full">
                            {{ $videos->links() }}
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-12 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16 text-gray-400 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-2">No videos available</h3>
                        <p class="text-xs sm:text-sm lg:text-base text-gray-600 mb-4 sm:mb-6">Check back soon for new training content.</p>
                        @if(!$hasSubscription)
                            <a href="{{ route('home') }}#pricing" class="inline-block px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-semibold text-sm sm:text-base hover:shadow-lg transition-all duration-200">
                                Subscribe to Access Videos
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>

