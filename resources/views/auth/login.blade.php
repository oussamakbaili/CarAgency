<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - ToubCar</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-orange-50 login-mobile">
    <!-- Navigation - Hidden on mobile -->
    <nav class="hidden md:block bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="flex items-center">
                        <img src="{{ asset('images/toubcar-logo.png') }}" alt="ToubCar Logo" class="h-24 w-auto">
                    </a>
                </div>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">Home</a>
                    <a href="{{ route('register') }}" class="bg-[#C2410C] hover:bg-[#9A3412] text-white px-6 py-2.5 rounded-full font-medium transition duration-200">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8 pb-20 md:pb-12">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-2 sm:mb-4">Welcome Back</h2>
                <p class="text-sm sm:text-lg text-gray-600">Sign in to your account to continue</p>
            </div>

            <div class="bg-white py-6 sm:py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-200">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4 sm:mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Email Address</label>
                        <input id="email" 
                            class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username" 
                            placeholder="Enter your email address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Password</label>
                        <input id="password" 
                            class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"
                            type="password"
                            name="password"
                            required 
                            autocomplete="current-password" 
                            placeholder="Enter your password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" 
                                type="checkbox" 
                                class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded transition duration-200" 
                                name="remember">
                            <label for="remember_me" class="ml-2 block text-xs sm:text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-xs sm:text-sm text-orange-600 hover:text-orange-500 font-medium transition duration-200" 
                               href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white py-2.5 sm:py-3 px-4 rounded-xl text-sm sm:text-base font-semibold transition duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-xs sm:text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-semibold text-[#C2410C] hover:text-[#9A3412] transition duration-200">
                                Sign up here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer - Hidden on mobile -->
    <footer class="hidden md:block bg-[#C2410C] text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-blue-100">
                    &copy; {{ date('Y') }} ToubCar. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bottom Navigation Bar - Mobile Only -->
    <div id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg md:hidden">
        <div class="flex items-center justify-around h-16 px-2">
            <!-- Explore Button -->
            <a href="{{ route('public.home') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500">Explore</span>
            </a>

            <!-- Wishlists Button -->
            <a href="{{ route('public.wishlists') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500">Wishlists</span>
            </a>

            <!-- Log in Button (Active) -->
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                <svg class="w-6 h-6 mb-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-semibold text-red-600">Log in</span>
            </a>
        </div>
    </div>

    <style>
        /* Mobile-only text scaling for login page */
        @media (max-width: 640px) {
            .login-mobile {
                font-size: 0.94rem;
            }
            .login-mobile h2 {
                font-size: 1.5rem;
            }
            .login-mobile p, .login-mobile .text-base {
                font-size: 0.95rem;
            }
            .login-mobile label {
                font-size: 0.85rem;
            }
            .login-mobile input {
                font-size: 0.9rem;
            }
            .login-mobile button {
                font-size: 0.9rem;
            }
        }
        
        #bottom-nav {
            transform: translateY(0);
            transition: transform 2.5s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
    </style>
</body>
</html>
