<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Videos - Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        use Illuminate\Support\Facades\Storage;
        use Illuminate\Support\Facades\Log;
    @endphp
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
                        
                        <a href="{{ route('admin.videos.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-900 bg-gray-100 rounded-lg font-medium text-sm sm:text-base">
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
                        
                        <a href="{{ route('admin.meal-plans.index') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors text-sm sm:text-base">
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
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Manage Videos</h1>
                        <p class="text-sm sm:text-base text-gray-600">Create, edit, and delete training videos</p>
                    </div>
                    <a href="{{ route('admin.videos.create') }}" class="px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-semibold text-sm sm:text-base hover:shadow-lg transition-all duration-200 whitespace-nowrap">
                        + Add New Video
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

                <!-- Videos Table -->
                @if($videos->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Video</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Category</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Type</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Created</th>
                                        <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($videos as $video)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer lg:cursor-default" 
                                            onclick="if(window.innerWidth < 1024) { window.location.href='{{ route('admin.videos.edit', $video) }}'; }">
                                            <td class="px-3 sm:px-6 py-4">
                                                <div class="flex items-center">
                                                @if($video->thumbnail)
    @php
        // Generate URL for thumbnail - construct S3 URL manually
        $thumbnailUrl = null;
        try {
            if (filter_var($video->thumbnail, FILTER_VALIDATE_URL)) {
                // It's already a full URL
                $thumbnailUrl = $video->thumbnail;
            } else {
                // Construct S3 URL manually using bucket and region
                if (!empty($video->thumbnail)) {
                    // Remove s3:// prefix and bucket name if present
                    $thumbnailPath = $video->thumbnail;
                    if (strpos($thumbnailPath, 's3://') === 0) {
                        // Remove s3:// prefix
                        $thumbnailPath = substr($thumbnailPath, 5);
                        // Remove bucket name if present (format: bucket-name/path)
                        $s3Config = config('filesystems.disks.s3');
                        $bucket = $s3Config['bucket'] ?? null;
                        if ($bucket && strpos($thumbnailPath, $bucket . '/') === 0) {
                            $thumbnailPath = substr($thumbnailPath, strlen($bucket) + 1);
                        }
                    }
                    
                    $s3Config = config('filesystems.disks.s3');
                    $bucket = $s3Config['bucket'] ?? null;
                    $region = $s3Config['region'] ?? 'us-east-1';
                    $usePathStyle = $s3Config['use_path_style_endpoint'] ?? false;
                    
                    if ($bucket) {
                        // Construct S3 URL based on endpoint style
                        if ($usePathStyle) {
                            // Path-style: https://s3.region.amazonaws.com/bucket/path
                            $endpoint = $s3Config['endpoint'] ?? "https://s3.{$region}.amazonaws.com";
                            $thumbnailUrl = rtrim($endpoint, '/') . '/' . $bucket . '/' . ltrim($thumbnailPath, '/');
                        } else {
                            // Virtual-hosted-style: https://bucket.s3.region.amazonaws.com/path
                            $endpoint = $s3Config['endpoint'] ?? "https://{$bucket}.s3.{$region}.amazonaws.com";
                            $thumbnailUrl = rtrim($endpoint, '/') . '/' . ltrim($thumbnailPath, '/');
                        }
                    } else {
                        // Fallback: try Storage::disk('s3')->url()
                        try {
                            $thumbnailUrl = Storage::disk('s3')->url($thumbnailPath);
                        } catch (\Exception $urlError) {
                            // Last resort: use temporaryUrl()
                            try {
                                $thumbnailUrl = Storage::disk('s3')->temporaryUrl($thumbnailPath, now()->addMinutes(60));
                            } catch (\Exception $tempUrlError) {
                                Log::warning('Failed to generate S3 thumbnail URL', [
                                    'thumbnail_path' => $video->thumbnail,
                                    'url_error' => $urlError->getMessage(),
                                    'temp_url_error' => $tempUrlError->getMessage()
                                ]);
                                $thumbnailUrl = null;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error processing thumbnail', [
                'thumbnail_path' => $video->thumbnail ?? 'null',
                'error' => $e->getMessage()
            ]);
            $thumbnailUrl = null;
        }
    @endphp
                                                        @if($thumbnailUrl)
                                                            <img src="{{ $thumbnailUrl }}" alt="{{ $video->title }}" class="h-10 w-16 sm:h-12 sm:w-20 object-cover rounded-lg mr-2 sm:mr-4 flex-shrink-0">
                                                        @else
                                                            <div class="h-10 w-16 sm:h-12 sm:w-20 bg-gray-200 rounded-lg mr-2 sm:mr-4 flex items-center justify-center flex-shrink-0">
                                                                <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="h-10 w-16 sm:h-12 sm:w-20 bg-gray-200 rounded-lg mr-2 sm:mr-4 flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $video->title }}</div>
                                                        @if($video->description)
                                                            <div class="text-xs sm:text-sm text-gray-500 truncate">{{ strlen($video->description) > 50 ? substr($video->description, 0, 50) . '...' : $video->description }}</div>
                                                        @endif
                                                        <div class="sm:hidden mt-1">
                                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                                {{ $video->category_name ?? 'No Category' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                                <span class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                                    {{ $video->category_name ?? 'No Category' }}
                                                </span>
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                                @if($video->is_premium)
                                                    <span class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Premium</span>
                                                @else
                                                    <span class="px-2 sm:px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Free</span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                                {{ $video->created_at && $video->created_at instanceof \Carbon\Carbon ? $video->created_at->format('M d, Y') : ($video->created_at ? \Carbon\Carbon::parse($video->created_at)->format('M d, Y') : 'N/A') }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium" onclick="event.stopPropagation();">
                                                <div class="flex items-center justify-end space-x-2 sm:space-x-3">
                                                    <button onclick="previewVideo({{ $video->id }}, '{{ $video->title }}')" 
                                                            class="text-blue-600 hover:text-blue-900 font-semibold text-xs sm:text-sm">
                                                        Preview
                                                    </button>
                                                    <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this video? This will permanently delete the video file from S3.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs sm:text-sm">
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
                            {{ $videos->links() }}
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No videos found</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Get started by creating your first video.</p>
                        <a href="{{ route('admin.videos.create') }}" class="inline-block px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-semibold text-sm sm:text-base hover:shadow-lg transition-all duration-200">
                            Create First Video
                        </a>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Video Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center" style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 my-8" style="max-height: 90vh; overflow-y: auto;">
            <div class="p-4 sm:p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 id="previewTitle" class="text-lg sm:text-xl font-bold text-gray-900"></h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6">
                <div id="previewLoading" class="text-center py-8">
                    <svg class="animate-spin h-8 w-8 text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-gray-600">Loading video...</p>
                </div>
                <div id="previewError" class="hidden text-center py-8">
                    <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-600 font-medium">Failed to load video preview</p>
                </div>
                <video id="previewVideo" class="hidden w-full rounded-lg" controls style="max-height: 70vh;">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>

    <script>
        function previewVideo(videoId, title) {
            const modal = document.getElementById('previewModal');
            const previewTitle = document.getElementById('previewTitle');
            const previewVideo = document.getElementById('previewVideo');
            const previewLoading = document.getElementById('previewLoading');
            const previewError = document.getElementById('previewError');
            
            // Show modal
            modal.style.display = 'flex';
            previewTitle.textContent = title;
            previewLoading.classList.remove('hidden');
            previewError.classList.add('hidden');
            previewVideo.classList.add('hidden');
            previewVideo.src = '';
            
            // Fetch presigned URL
            fetch(`/admin/videos/${videoId}/preview`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    previewVideo.src = data.url;
                    previewVideo.classList.remove('hidden');
                    previewLoading.classList.add('hidden');
                } else {
                    throw new Error(data.error || 'Failed to load video');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                previewLoading.classList.add('hidden');
                previewError.classList.remove('hidden');
            });
        }
        
        function closePreview() {
            const modal = document.getElementById('previewModal');
            const previewVideo = document.getElementById('previewVideo');
            modal.style.display = 'none';
            previewVideo.pause();
            previewVideo.src = '';
        }
        
        // Close modal on outside click
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreview();
            }
        });
    </script>
</body>
</html>

