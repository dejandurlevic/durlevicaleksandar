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
                    <h2 class="mt-4 sm:mt-6 text-2xl sm:text-3xl font-bold text-gray-900">Create your account</h2>
                    <p class="mt-2 text-xs sm:text-sm text-gray-600">Join us and start your fitness journey</p>
                </div>

                @if(!isset($validToken) || (!$validToken && !request()->has('token')))
                    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Registration requires approval.</strong> To create an account, please select a plan on our homepage and submit an inquiry. Once approved by the administrator, you will receive a registration link via email.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(request()->has('token') && !$validToken)
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Invalid or expired registration token.</strong> Please contact the administrator or request a new registration link.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($validToken) && $validToken)
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>Valid registration token.</strong> Please complete the form below to create your account.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Token (hidden if valid, visible if invalid) -->
                    <div>
                        <label for="token" class="block text-sm font-semibold text-gray-700 mb-2">Registration Token <span class="text-red-500">*</span></label>
                        <input id="token" 
                               type="text" 
                               name="token" 
                               value="{{ old('token', $token ?? '') }}" 
                               required 
                               @if(isset($validToken) && $validToken) readonly @endif
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200 @if(isset($validToken) && $validToken) bg-gray-100 @endif">
                        @error('token')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if((!isset($validToken) || !$validToken) && !request()->has('token'))
                            <p class="mt-1 text-xs text-gray-500">You need a valid registration token to create an account. Please check your email for the registration link.</p>
                        @endif
                    </div>

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
                               value="{{ old('email', $email ?? '') }}" 
                               required 
                               autocomplete="username"
                               @if(isset($validToken) && $validToken && isset($email) && $email) readonly @endif
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200 @if(isset($validToken) && $validToken && isset($email) && $email) bg-gray-100 @endif">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if(isset($validToken) && $validToken && isset($email) && $email)
                            <p class="mt-1 text-xs text-gray-500">Email is pre-filled from your inquiry.</p>
                        @endif
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

                    <button type="submit" 
                            @if(!isset($validToken) || !$validToken) disabled @endif
                            class="w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white py-3 rounded-xl font-bold text-base sm:text-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        Create account
                    </button>
                </form>

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
