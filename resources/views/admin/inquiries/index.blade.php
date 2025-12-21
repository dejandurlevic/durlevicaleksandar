<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Inquiries - Admin</title>
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
                class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:transition-none h-screen overflow-y-auto shadow-lg lg:shadow-none">
            <div class="p-3 sm:p-4 lg:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6 lg:mb-8">
                    <h1 class="text-base sm:text-lg lg:text-xl xl:text-2xl font-bold text-gray-900 truncate">FitCoach Admin</h1>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-600 hover:text-gray-900 p-1 -mr-1">
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
                        
                        <a href="{{ route('admin.inquiries.index') }}" class="flex items-center justify-between px-3 sm:px-4 py-2 sm:py-3 text-gray-900 bg-gray-100 rounded-lg font-medium text-sm sm:text-base">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                Manage Inquiries
                            </div>
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        
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
            <div class="lg:hidden bg-white border-b border-gray-200 px-3 sm:px-4 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <button @click="sidebarOpen = true" class="text-gray-700 hover:text-gray-900 p-2 -ml-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-gray-900 truncate mx-2">FitCoach Admin</h1>
                <div class="w-6"></div>
            </div>

            <div class="p-3 sm:p-4 lg:p-8">
                <div class="mb-4 sm:mb-6">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1 sm:mb-2 text-center sm:text-left">Manage Inquiries</h1>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600 text-center sm:text-left">Review and approve user registration requests</p>
                </div>

                <!-- Notification Banner for New Inquiries -->
                @if(isset($pendingCount) && $pendingCount > 0)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                        <div class="flex items-start sm:items-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm lg:text-base font-semibold text-blue-800 break-words">
                                    You have <span class="font-bold">{{ $pendingCount }}</span> {{ $pendingCount === 1 ? 'new inquiry' : 'new inquiries' }} waiting for approval!
                                </p>
                                <p class="text-xs sm:text-sm text-blue-700 mt-1 break-words">Someone has chosen a plan and submitted a subscription request.</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                        <p class="text-xs sm:text-sm lg:text-base text-green-800 font-semibold mb-2 break-words">{{ session('success') }}</p>
                        @if(session('registration_url'))
                            <div class="mt-3">
                                <p class="text-xs sm:text-sm text-green-700 mb-2 font-medium break-words">Registration Link (copy and send to user if email failed):</p>
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 bg-white p-2 sm:p-3 rounded-lg border border-green-200">
                                    <input type="text" 
                                           id="registration-url-{{ session('inquiry_id') }}" 
                                           value="{{ session('registration_url') }}" 
                                           readonly 
                                           class="flex-1 text-xs sm:text-sm text-gray-900 bg-transparent border-none focus:outline-none break-all">
                                    <button onclick="copyRegistrationLink('registration-url-{{ session('inquiry_id') }}')" 
                                            class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm font-semibold whitespace-nowrap">
                                        Copy Link
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                        <p class="text-xs sm:text-sm lg:text-base text-red-800 font-semibold mb-2 break-words">{{ session('error') }}</p>
                        @if(session('registration_url'))
                            <div class="mt-3">
                                <p class="text-xs sm:text-sm text-red-700 mb-2 font-medium break-words">Registration Link (copy and send manually):</p>
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 bg-white p-2 sm:p-3 rounded-lg border border-red-200">
                                    <input type="text" 
                                           id="registration-url-{{ session('inquiry_id') }}" 
                                           value="{{ session('registration_url') }}" 
                                           readonly 
                                           class="flex-1 text-xs sm:text-sm text-gray-900 bg-transparent border-none focus:outline-none break-all">
                                    <button onclick="copyRegistrationLink('registration-url-{{ session('inquiry_id') }}')" 
                                            class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-xs sm:text-sm font-semibold whitespace-nowrap">
                                        Copy Link
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if($inquiries->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden sm:table-cell">Name</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">Phone</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Plan</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">Date</th>
                                        <th class="px-2 sm:px-4 lg:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($inquiries as $inquiry)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-900">#{{ $inquiry->id }}</td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-900 hidden sm:table-cell break-words">{{ $inquiry->name ?? 'N/A' }}</td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-900 break-all">
                                                <div class="font-medium">{{ $inquiry->email }}</div>
                                                <div class="text-gray-500 sm:hidden mt-1">
                                                    {{ $inquiry->name ?? 'N/A' }} • {{ $inquiry->plan }}
                                                </div>
                                            </td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-900 hidden md:table-cell break-words">{{ $inquiry->phone }}</td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm font-semibold text-gray-900 hidden lg:table-cell break-words">{{ $inquiry->plan }}</td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4">
                                                @if($inquiry->approved)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 whitespace-nowrap">Approved</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 whitespace-nowrap">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-500 hidden md:table-cell whitespace-nowrap">{{ $inquiry->created_at->format('M d, Y') }}</td>
                                            <td class="px-2 sm:px-4 lg:px-6 py-3 sm:py-4">
                                                @if(!$inquiry->approved)
                                                    <form method="POST" action="{{ route('admin.inquiries.approve', $inquiry) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="px-2 sm:px-3 py-1 sm:py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm font-semibold whitespace-nowrap">
                                                            Approve
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-1 sm:gap-2">
                                                        @if($inquiry->invite_token)
                                                            <button onclick="showRegistrationLink({{ $inquiry->id }}, '{{ route('register', ['token' => $inquiry->invite_token]) }}')" 
                                                                    class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold hover:bg-blue-200 transition-colors whitespace-nowrap">
                                                                Copy Link
                                                            </button>
                                                        @endif
                                                        <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline" onsubmit="return confirmDelete('{{ $inquiry->email }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="px-2 py-1 bg-red-600 text-white rounded text-xs font-semibold hover:bg-red-700 transition-colors whitespace-nowrap w-full sm:w-auto">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6 px-3 sm:px-0">
                        {{ $inquiries->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 text-center">
                        <p class="text-sm sm:text-base text-gray-500">No inquiries found.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Registration Link Modal -->
    <div id="registrationLinkModal" class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50 p-3 sm:p-4" onclick="hideRegistrationLinkModal()">
        <div class="bg-white rounded-xl p-4 sm:p-6 w-full max-w-2xl shadow-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-3 sm:mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Registration Link</h3>
                <button onclick="hideRegistrationLinkModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 break-words">Copy this link and send it to the user:</p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 bg-gray-50 p-3 sm:p-4 rounded-lg border border-gray-200">
                <input type="text" 
                       id="modal-registration-url" 
                       readonly 
                       class="flex-1 text-xs sm:text-sm text-gray-900 bg-transparent border-none focus:outline-none break-all">
                <button onclick="copyRegistrationLink('modal-registration-url')" 
                        class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs sm:text-sm font-semibold whitespace-nowrap">
                    Copy
                </button>
            </div>
            <div id="copy-success" class="hidden mt-3 p-2 bg-green-100 text-green-700 rounded text-xs sm:text-sm text-center">
                Link copied to clipboard!
            </div>
        </div>
    </div>

    <script>
        function copyRegistrationLink(inputId) {
            const input = document.getElementById(inputId);
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(input.value).then(function() {
                // Show success message
                const successDiv = document.getElementById('copy-success');
                if (successDiv) {
                    successDiv.classList.remove('hidden');
                    setTimeout(() => {
                        successDiv.classList.add('hidden');
                    }, 2000);
                }
            }).catch(function(err) {
                // Fallback for older browsers
                document.execCommand('copy');
            });
        }

        function showRegistrationLink(inquiryId, url) {
            const modal = document.getElementById('registrationLinkModal');
            const input = document.getElementById('modal-registration-url');
            input.value = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideRegistrationLinkModal() {
            const modal = document.getElementById('registrationLinkModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmDelete(email) {
            return confirm(`Are you sure you want to delete the inquiry from "${email}"? This action cannot be undone and will permanently delete the inquiry from the database.`);
        }
    </script>
</body>
</html>

