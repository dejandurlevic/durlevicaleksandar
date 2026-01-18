<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 sm:space-y-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-6 sm:p-8">
                <div class="text-center mb-6 sm:mb-8">
                    <a href="{{ route('home') }}" class="text-2xl sm:text-3xl font-extrabold tracking-tight bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                        FitCoachAleksandar
                    </a>
                    <h2 class="mt-4 sm:mt-6 text-2xl sm:text-3xl font-bold text-gray-900">Registration</h2>
                    <p class="mt-2 text-xs sm:text-sm text-gray-600">Please select a plan first to register</p>
                </div>

                @if(isset($inquiry) && $inquiry)
                    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Registration Invitation</p>
                        <p class="text-lg font-bold text-green-700">{{ $inquiry->plan }}</p>
                        <p class="text-sm text-gray-600 mt-2">Email: {{ $inquiry->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', isset($inquiry) && $inquiry ? $inquiry->email : '') }}" 
                               required 
                               autocomplete="username"
                               {{ isset($inquiry) && $inquiry ? 'readonly' : '' }}
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200 {{ isset($inquiry) && $inquiry ? 'bg-gray-100' : '' }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input id="phone" 
                               type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                    </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white py-3 rounded-xl font-bold text-base sm:text-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            Register
                        </button>
                    </form>
                @elseif(request()->has('plan'))
                    @if(request('plan'))
                        <div class="mb-6 p-4 bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-200 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Selected Plan:</p>
                            <p class="text-lg font-bold text-pink-700">{{ request('plan') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input id="phone" 
                               type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200">
                    </div>

                        <input type="hidden" name="selected_plan" value="{{ request('plan') }}">

                        <button type="submit" class="w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white py-3 rounded-xl font-bold text-base sm:text-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            Submit Registration Request
                        </button>
                    </form>

                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Your registration will be reviewed and approved by the administrator. You will receive an email notification once your account is activated.
                        </p>
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-yellow-800">
                                    <strong>Please select a plan first.</strong> You need to choose a subscription plan before you can register. 
                                    <a href="{{ route('home') }}#pricing" class="underline font-semibold">Go to pricing plans</a> to select your plan.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('home') }}#pricing" class="inline-block px-6 py-3 bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            View Plans
                        </a>
                    </div>
                @endif

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:text-gray-700">
                            Sign in
                        </a>
                    </p>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
