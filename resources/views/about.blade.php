<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>About Me - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-extrabold tracking-tight bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                    FitCoachAleksandar
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-4 lg:gap-6">
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-200 border-b-2 border-gray-900">
                        About Me
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 lg:px-6 py-2 lg:py-2.5 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-sm lg:text-base">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 lg:px-6 py-2 lg:py-2.5 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105 text-sm lg:text-base">
                            Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 hover:text-gray-900 transition-colors">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="md:hidden pb-4 border-t border-gray-200 mt-2">
                <div class="flex flex-col gap-3 pt-4">
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-200 border-b-2 border-gray-900 px-2 py-2">
                        About Me
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-center">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-center">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- About Me Section -->
    <section class="pt-32 pb-24 bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-6 text-gray-900">About Me</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Get to know your fitness coach</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Coach Image -->
                <div class="relative group">
                    <div class="absolute -inset-4 bg-gradient-to-r from-gray-200 to-gray-300 rounded-3xl blur-2xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                    <div class="relative">
                        <img src="{{ $trainer['photo'] }}" 
                             alt="{{ $trainer['name'] }}" 
                             class="rounded-3xl shadow-2xl w-full transform transition-all duration-500 group-hover:scale-105 group-hover:shadow-3xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                </div>

                <!-- Coach Info -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">{{ $trainer['name'] }}</h2>
                        <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                            {{ $trainer['bio'] }}
                        </p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">{{ $trainer['clients_count'] }}+</div>
                            <div class="text-sm font-semibold text-gray-600">Active Clients</div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">{{ $trainer['experience_years'] }}+</div>
                            <div class="text-sm font-semibold text-gray-600">Years Experience</div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">20+</div>
                            <div class="text-sm font-semibold text-gray-600">Training Videos</div>
                        </div>
                    </div>

                    <!-- Services List -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">What I Offer</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($trainer['services'] as $service)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-2 h-2 bg-gray-900 rounded-full group-hover:scale-150 transition-transform duration-300"></div>
                                    <span class="text-gray-700 font-medium group-hover:text-gray-900 transition-colors">{{ $service }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="pt-4">
                        <a href="{{ route('home') }}#pricing" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                            View Pricing Plans
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-3xl font-extrabold mb-4">FitCoachAleksandar</h3>
                <p class="text-gray-400 mb-8 text-lg">Transform your body, transform your life.</p>
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Durleone. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>


















