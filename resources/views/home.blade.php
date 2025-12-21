<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Premium Fitness Training</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .animate-delay-100 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-200 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-300 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-400 { animation-delay: 0.4s; opacity: 0; }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
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
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-200">
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
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 font-semibold transition-colors duration-200 px-2 py-2">
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

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(0,0,0,0.03),transparent_50%)]"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Content -->
                <div class="animate-fade-in-up">
                    <div class="inline-block px-4 py-1.5 bg-gray-100 rounded-full text-sm font-semibold text-gray-700 mb-8 animate-fade-in-up animate-delay-100">
                        Premium Fitness Training
                    </div>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 leading-tight text-gray-900 animate-fade-in-up animate-delay-200">
                    Трансформишите своје тело.<br>
                        <span class="bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                        Тренирајте паметније.
                        </span><br>
                        <span class="bg-gradient-to-r from-gray-700 to-gray-500 bg-clip-text text-transparent">
                        Осећајте се јаче.
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-2xl mb-10 leading-relaxed animate-fade-in-up animate-delay-300">
                    Персонализовани програми вежбања, видео библиотека тренинга, праћење напретка и директан приступ вашем тренеру — све на једној платформи.
                    </p>
                    <div class="flex flex-wrap gap-4 animate-fade-in-up animate-delay-400">
                        <a href="#pricing" class="px-8 py-4 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        Почни обуку
                        </a>
                        <a href="#services" class="px-8 py-4 bg-white border-2 border-gray-300 text-gray-900 rounded-xl font-bold text-lg hover:border-gray-900 hover:shadow-xl transition-all duration-300">
                        Сазнајте више
                        </a>
                    </div>
                </div>
                
                <!-- Right: Trainer Image -->
                <div class="relative animate-fade-in-up animate-delay-300">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-gray-200 to-gray-300 rounded-3xl blur-2xl opacity-50"></div>
                        <img src="{{ $trainer['photo'] }}" alt="{{ $trainer['name'] }}" class="relative rounded-3xl shadow-2xl w-full max-w-lg mx-auto transform hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ $trainer['name'] }}</div>
                        <div class="text-sm text-gray-600">Сертификовани фитнес тренер</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Inquiry Popup -->
    <div id="inquiryPopup" class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50 p-4">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl animate-fade-in relative">
            <button type="button" onclick="hidePopup()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <h2 class="text-3xl font-bold mb-2 text-gray-900">Send Inquiry</h2>
            <p class="text-gray-600 mb-6">Get in touch about your selected plan</p>

            <form id="inquiryForm" class="space-y-4">
                @csrf
                <input type="hidden" name="plan" id="selectedPlan">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name (optional)</label>
                    <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Message (optional)</label>
                    <textarea name="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-gray-900 to-gray-800 text-white py-3 rounded-xl font-bold hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        Send Inquiry
                    </button>
                    <button type="button" onclick="hidePopup()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- About Trainer Section -->
    <section class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div class="relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-gray-200 to-gray-300 rounded-3xl blur opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>
                    <img src="{{ $trainer['photo_all'] }}" alt="{{ $trainer['name'] }}" class="relative rounded-3xl shadow-xl w-full object-cover h-[500px]">
                </div>
                
                <div>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6 text-gray-900">Ваш тренер</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8">
                        {{ $trainer['bio'] }}
                    </p>
                    
                    <div class="grid grid-cols-3 gap-8">
                        <div class="text-center p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-300">
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">{{ $trainer['clients_count'] }}+</div>
                            <div class="text-sm font-semibold text-gray-600">Активни клијенти
                            </div>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-300">
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">{{ $trainer['experience_years'] }}+</div>
                            <div class="text-sm font-semibold text-gray-600">Вишегодишње искуство</div>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors duration-300">
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">20+</div>
                            <div class="text-sm font-semibold text-gray-600">Видео снимци за обуку</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-gray-900">Шта добијате</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Свеобухватна фитнес решења прилагођена вашим јединственим циљевима</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $serviceData = [
                        'Програми обуке' => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                            'desc' => 'Комплетни, лако пративи програми осмишљени да вам помогну да безбедно и ефикасно напредујете.'
                        ],
                        'Планови оброка
                        ' => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>',
                            'desc' => 'Прилагођени планови исхране који допуњују ваш тренинг и убрзавају резултате.'
                        ],
                        '1-on-1 Coaching' => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                            'desc' => 'Personalized coaching sessions with direct feedback and technique refinement.'
                        ],
                        'Group Fitness Classes' => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>',
                            'desc' => 'Engaging group sessions that build community and motivation.'
                        ],
                        'Nutrition Counseling' => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                            'desc' => 'Expert nutritional guidance to optimize your performance and recovery.'
                        ],
                        'Онлајн обука' => [
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>',
                            'desc' => 'Потпун приступ видео записима за обуку након уплате претплате са праћењем напретка.'
                        ]
                    ];
                @endphp
                
                @foreach($trainer['services'] as $service)
                    @php
                        $data = $serviceData[$service] ?? ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>', 'desc' => 'Professional service tailored to your fitness goals.'];
                    @endphp
                    <div class="group relative bg-white p-8 rounded-2xl border-2 border-gray-100 hover:border-gray-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-2xl"></div>
                        <div class="relative">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-900 to-gray-700 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $data['icon'] !!}
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $service }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $data['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-gray-900">изазов за глутеус</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Изаберите план који одговара вашем фитнес путовању

</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Basic Plan -->
                <div class="bg-white rounded-3xl shadow-lg p-8 border-2 border-gray-200 hover:border-gray-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Планови оброка</h3>
                        <p class="text-gray-600 mb-6">Идеално само за планове оброка</p>
                        <div class="mb-6">
                            <span class="text-5xl font-extrabold text-gray-900">€35</span>
                            <span class="text-gray-600 text-lg">/month</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Праћење напретка</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Недељне провере</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Директни chat са тренером</span>
                        </li>
                    </ul>
                    <button type="button" onclick="showPopup('Meal Plans')" class="block w-full bg-gray-100 text-gray-900 py-4 rounded-xl font-bold text-center hover:bg-gray-200 transition-colors duration-200">
                        Choose Plan
                    </button>
                </div>

                <!-- Standard Plan - Most Popular -->
                <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl shadow-2xl p-8 border-4 border-gray-700 transform scale-105 z-10 flex flex-col">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 px-4 py-1.5 rounded-full text-sm font-bold shadow-lg">MOST POPULAR</span>
                    </div>
                    <div class="mb-8 text-white">
                        <h3 class="text-2xl font-bold mb-2">Видео снимци за тренинг и планови оброка</h3>
                        <p class="text-gray-300 mb-6">Најбоља вредност за озбиљне спортисте</p>
                        <div class="mb-6">
                            <span class="text-5xl font-extrabold">€49</span>
                            <span class="text-gray-300 text-lg">/month</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow text-white">
                    <li class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Потпун приступ</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Комплетна видео библиотека</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Приоритетна подршка</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Праћење напретка</span>
                        </li>
                    </ul>
                    <button type="button" onclick="showPopup('Training Plans and Meal Plans')" class="block w-full bg-white text-gray-900 py-4 rounded-xl font-bold text-center hover:bg-gray-100 transition-colors duration-200 shadow-lg">
                        Choose Plan
                    </button>
                </div>

                <!-- Premium Plan -->
                <div class="bg-white rounded-3xl shadow-lg p-8 border-2 border-gray-200 hover:border-gray-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Видео снимци за тренинг</h3>
                        <p class="text-gray-600 mb-6">Врхунски фитнес тренинг</p>
                        <div class="mb-6">
                            <span class="text-5xl font-extrabold text-gray-900">€39</span>
                            <span class="text-gray-600 text-lg">/month</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Комплетна видео библиотека</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Видео снимци вежби за глутеус</span>
                        </li>
            
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 font-medium">Подршка путем е-поште</span>
                        </li>
                    </ul>
                    <button type="button" onclick="showPopup('Training Videos')" class="block w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white py-4 rounded-xl font-bold text-center hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        Choose Plan
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-3xl font-extrabold mb-4">FitCoachAleksandar</h3>
                <p class="text-gray-400 mb-8 text-lg">Трансформишите своје тело, трансформишите свој живот.</p>
                <div class="flex justify-center space-x-6 mb-8">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                        </svg>
                    </a>
                </div>
                Website & Platform by 
<a href="https://durlevicdejan.com" target="_blank">
    Durlevic Dejan
</a>
            </div>
        </div>
    </footer>

    <script>
        function showPopup(plan) {
            const popup = document.getElementById('inquiryPopup');
            document.getElementById('selectedPlan').value = plan;
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function hidePopup() {
            const popup = document.getElementById('inquiryPopup');
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }

        document.getElementById('inquiryForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            
            // Disable button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';

            try {
                const response = await fetch("{{ route('send.inquiry') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                });

                // Check if response is ok (status 200-299)
                if (!response.ok) {
                    // Try to parse error response
                    let errorData;
                    try {
                        errorData = await response.json();
                    } catch {
                        errorData = { message: `Server error (${response.status}). Please try again.` };
                    }
                    throw new Error(errorData.message || `Error: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-fade-in';
                    successDiv.textContent = result.message || 'Your request has been sent. You will be contacted shortly.';
                    document.body.appendChild(successDiv);
                    
                    // Remove after 5 seconds
                    setTimeout(() => {
                        successDiv.remove();
                    }, 5000);
                    
                    hidePopup();
                    this.reset();
                } else {
                    // Handle validation errors
                    let errorMsg = result.message || 'There was an issue sending your inquiry. Please try again.';
                    
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join('\n');
                        errorMsg = errorList || errorMsg;
                    }
                    
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'An error occurred. Please check your connection and try again.');
            } finally {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            }
        });
    </script>
</body>
</html>
