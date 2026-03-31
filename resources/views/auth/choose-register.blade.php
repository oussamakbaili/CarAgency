<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - ToubCar</title>

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
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="font-sans antialiased bg-orange-50 register-mobile">
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
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8 pb-20 md:pb-12">
        <div class="sm:mx-auto sm:w-full sm:max-w-7xl">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-2 sm:mb-4">Choose Your Account Type</h2>
                <p class="text-sm sm:text-lg text-gray-600">Select the type of account that best fits your needs</p>
    </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 px-4">
        <!-- Client Registration Card -->
                <div class="card-hover bg-white p-6 sm:p-10 rounded-2xl shadow-xl border-2 border-gray-200 hover:border-orange-400 transition-all duration-300 flex flex-col">
            <div class="text-center mb-6 sm:mb-8">
                        <div class="w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-4">I'm a Customer</h3>
                <p class="text-sm sm:text-lg text-gray-600 leading-relaxed">Create a customer account to rent cars from our partner agencies</p>
            </div>
            
            <ul class="space-y-3 sm:space-y-4 mb-6 sm:mb-10 flex-grow">
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Access to all available cars
                </li>
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Simple online booking
                </li>
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Best price comparison
                </li>
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    24/7 customer support
                </li>
            </ul>
            
                    <a href="{{ route('register.client') }}" class="w-full bg-gradient-to-r from-orange-600 to-orange-500 hover:from-orange-700 hover:to-orange-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-xl text-sm sm:text-lg font-semibold transition duration-200 flex items-center justify-center">
                <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Register as Customer
            </a>
        </div>

        <!-- Agency Registration Card -->
                <div class="card-hover bg-white p-6 sm:p-10 rounded-2xl shadow-xl border-2 border-gray-200 hover:border-blue-400 transition-all duration-300 flex flex-col">
            <div class="text-center mb-6 sm:mb-8">
                        <div class="w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-4">I'm an Agency</h3>
                <p class="text-sm sm:text-lg text-gray-600 leading-relaxed">Create an agency account to manage your vehicle fleet and rentals</p>
            </div>
            
            <ul class="space-y-3 sm:space-y-4 mb-6 sm:mb-10 flex-grow">
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Complete fleet management
                </li>
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Professional dashboard
                </li>
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Analytics and insights
                </li>
                <li class="flex items-center text-xs sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Secure payment processing
                </li>
            </ul>
            
                    <a href="{{ route('register.agency') }}" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white py-3 sm:py-4 px-4 sm:px-6 rounded-xl text-sm sm:text-lg font-semibold transition duration-200 flex items-center justify-center">
                <svg class="w-4 h-4 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Register as Agency
            </a>
        </div>
    </div>

            <div class="mt-8 sm:mt-12 text-center">
                <p class="text-xs sm:text-base text-gray-600">
            Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-[#0A66C2] hover:text-[#0A66C2] transition duration-200">
                Sign in here
            </a>
        </p>
    </div>
        </div>
    </div>

    <!-- Footer - Hidden on mobile -->
    <footer class="hidden md:block bg-[#0A66C2] text-white py-8 mt-12">
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

            <!-- Log in Button -->
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500">Log in</span>
            </a>
        </div>
    </div>

    <style>
        /* Mobile-only text scaling for register page */
        @media (max-width: 640px) {
            .register-mobile {
                font-size: 0.94rem;
            }
            .register-mobile h2 {
                font-size: 1.5rem;
            }
            .register-mobile h3 {
                font-size: 1.25rem;
            }
            .register-mobile p, .register-mobile .text-base {
                font-size: 0.95rem;
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
