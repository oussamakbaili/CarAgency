<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RentCar Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- ULTIMATE NAVIGATION FIX - SOLUTION ULTIME POUR DOUBLE-CLIC -->
    <script src="{{ asset('js/ultimate-navigation-fix.js') }}"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        accent: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #ea580c;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #c2410c;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <!-- Desktop: Full navigation with background -->
    <nav class="hidden md:block bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="flex items-center group py-2">
                        <img src="{{ asset('images/toubcar-logo.png') }}" alt="ToubCar Logo" class="h-32 w-auto transform group-hover:scale-105 transition-transform duration-200">
                    </a>
                </div>

                <!-- Navigation Links - Center -->
                <div class="flex items-center space-x-10">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">{{ __('common.nav.home') }}</a>
                    <a href="{{ route('public.about') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">{{ __('common.nav.about') }}</a>
                    <a href="{{ route('public.agencies') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">{{ __('common.nav.partners') }}</a>
                    <a href="{{ route('public.how-it-works') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">{{ __('common.nav.how_it_works') }}</a>
                </div>

                <!-- Auth Buttons - Right -->
                <div class="flex items-center space-x-3">
                    <!-- Language Selector -->
                    <div class="relative group">
                        <button type="button" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors" id="language-selector">
                            @if(app()->getLocale() === 'fr')
                                <svg class="w-6 h-6" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#fff" d="M0 0h640v480H0z"/>
                                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                                    </g>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">FR</span>
                            @else
                                <svg class="w-6 h-6" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#012169" d="M0 0h640v480H0z"/>
                                        <path fill="#FFF" d="m75 0 244 181.7L562 0h78v62.5l-228 173.1 228 173.6v62.5h-78L319 301.2 81 471.2H0v-62.5l229-173.6L0 62.5V0h75z"/>
                                        <path fill="#C8102E" d="m424 281.2 216 162.5v37.5H426.7zm-184 10.3 6.5 4.8-222-166v-35.3l215.5 161.5zm398-123.5L410.2 192l-9.7-7.3L610 64.2v35.3zm-52.3 69.1-216-162.5H0v35.3l215.5 161.5 6.5-4.8z"/>
                                        <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
                                        <path fill="#C8102E" d="M0 193.3v93.4h640v-93.4H0zM273.3 0v480h93.4V0h-93.4z"/>
                                    </g>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">EN</span>
                            @endif
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Language Dropdown -->
                        <div id="language-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="{{ route('lang.switch', 'fr') }}" class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 transition-colors {{ app()->getLocale() === 'fr' ? 'bg-orange-50' : '' }}">
                                <svg class="w-6 h-6" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#fff" d="M0 0h640v480H0z"/>
                                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                                    </g>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ __('common.language.french') }}</span>
                                @if(app()->getLocale() === 'fr')
                                    <svg class="w-4 h-4 text-orange-600 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 transition-colors {{ app()->getLocale() === 'en' ? 'bg-orange-50' : '' }}">
                                <svg class="w-6 h-6" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#012169" d="M0 0h640v480H0z"/>
                                        <path fill="#FFF" d="m75 0 244 181.7L562 0h78v62.5l-228 173.1 228 173.6v62.5h-78L319 301.2 81 471.2H0v-62.5l229-173.6L0 62.5V0h75z"/>
                                        <path fill="#C8102E" d="m424 281.2 216 162.5v37.5H426.7zm-184 10.3 6.5 4.8-222-166v-35.3l215.5 161.5zm398-123.5L410.2 192l-9.7-7.3L610 64.2v35.3zm-52.3 69.1-216-162.5H0v35.3l215.5 161.5 6.5-4.8z"/>
                                        <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
                                        <path fill="#C8102E" d="M0 193.3v93.4h640v-93.4H0zM273.3 0v480h93.4V0h-93.4z"/>
                                    </g>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ __('common.language.english') }}</span>
                                @if(app()->getLocale() === 'en')
                                    <svg class="w-4 h-4 text-orange-600 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </a>
                        </div>
                    </div>
                    
                    @auth
                        @if(auth()->user()->role === 'client')
                            <a href="{{ route('client.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-medium transition duration-200">
                                {{ __('common.nav.my_account') }}
                            </a>
                        @elseif(auth()->user()->role === 'agency')
                            <a href="{{ route('agency.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-medium transition duration-200">
                                {{ __('common.nav.dashboard') }}
                            </a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-medium transition duration-200">
                                {{ __('common.nav.admin') }}
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-orange-600 font-medium">
                                {{ __('common.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 font-medium transition duration-200">
                            {{ __('common.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-full font-medium transition duration-200">
                            {{ __('common.register') }}
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div>
                    <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden bg-white border-t border-gray-100">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('public.home') }}" class="block text-gray-700 hover:text-orange-600 font-medium py-2">{{ __('common.nav.home') }}</a>
                <a href="{{ route('public.about') }}" class="block text-gray-700 hover:text-orange-600 font-medium py-2">{{ __('common.nav.about') }}</a>
                <a href="{{ route('public.agencies') }}" class="block text-gray-700 hover:text-orange-600 font-medium py-2">{{ __('common.nav.partners') }}</a>
                <a href="{{ route('public.how-it-works') }}" class="block text-gray-700 hover:text-orange-600 font-medium py-2">{{ __('common.nav.how_it_works') }}</a>
                
                <!-- Language Selector Mobile -->
                <div class="pt-2 pb-2 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ __('common.footer.language') }}</span>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('lang.switch', 'fr') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg {{ app()->getLocale() === 'fr' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg class="w-5 h-5" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#fff" d="M0 0h640v480H0z"/>
                                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                                    </g>
                                </svg>
                                <span class="text-xs font-medium">FR</span>
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg {{ app()->getLocale() === 'en' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg class="w-5 h-5" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#012169" d="M0 0h640v480H0z"/>
                                        <path fill="#FFF" d="m75 0 244 181.7L562 0h78v62.5l-228 173.1 228 173.6v62.5h-78L319 301.2 81 471.2H0v-62.5l229-173.6L0 62.5V0h75z"/>
                                        <path fill="#C8102E" d="m424 281.2 216 162.5v37.5H426.7zm-184 10.3 6.5 4.8-222-166v-35.3l215.5 161.5zm398-123.5L410.2 192l-9.7-7.3L610 64.2v35.3zm-52.3 69.1-216-162.5H0v35.3l215.5 161.5 6.5-4.8z"/>
                                        <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
                                        <path fill="#C8102E" d="M0 193.3v93.4h640v-93.4H0zM273.3 0v480h93.4V0h-93.4z"/>
                                    </g>
                                </svg>
                                <span class="text-xs font-medium">EN</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                @guest
                    <div class="pt-4 space-y-2">
                        <a href="{{ route('login') }}" class="block bg-gray-200 hover:bg-gray-300 text-gray-900 px-6 py-2.5 rounded-full font-medium text-center transition duration-200">
                            {{ __('common.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="block bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-full font-medium text-center transition duration-200">
                            {{ __('common.register') }}
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0F3B63] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="mb-6">
                        <span class="text-3xl font-bold">ToubCar</span>
                        </div>
                    <p class="text-blue-100 mb-6 text-lg">
                        Your trusted partner in car rentals. Experience the world's largest car sharing & rental marketplace with exclusive deals and premium service.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-6">{{ __('common.footer.quick_links') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('public.home') }}" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.nav.home') }}</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.nav.about') }}</a></li>
                        <li><a href="{{ route('public.agencies') }}" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.nav.partners') }}</a></li>
                        <li><a href="{{ route('public.how-it-works') }}" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.nav.how_it_works') }}</a></li>
                        <li><a href="#" class="text-blue-100 hover:text-white transition duration-200">FAQs</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-6">{{ __('common.footer.get_in_touch') }}</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start text-blue-100">
                            <svg class="w-5 h-5 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+212 5XX XXX XXX</span>
                        </li>
                        <li class="flex items-start text-blue-100">
                            <svg class="w-5 h-5 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>contact@toubcar.com</span>
                        </li>
                        <li class="flex items-start text-blue-100">
                            <svg class="w-5 h-5 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Casablanca, Morocco</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white border-opacity-50 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-blue-100 text-center md:text-left">
                        &copy; {{ date('Y') }} ToubCar. {{ __('common.footer.all_rights_reserved') }}.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.footer.privacy_policy') }}</a>
                        <a href="#" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.footer.terms_of_service') }}</a>
                        <a href="#" class="text-blue-100 hover:text-white transition duration-200">{{ __('common.footer.cookie_policy') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <script>
        // Smooth scroll function
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                element.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Language selector dropdown
            const languageSelector = document.getElementById('language-selector');
            const languageDropdown = document.getElementById('language-dropdown');
            
            if (languageSelector && languageDropdown) {
                // Toggle dropdown
                languageSelector.addEventListener('click', function(e) {
                    e.stopPropagation();
                    languageDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!languageSelector.contains(e.target) && !languageDropdown.contains(e.target)) {
                        languageDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
