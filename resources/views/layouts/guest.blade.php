<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RentCar Platform') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'inter': ['Inter', 'sans-serif'],
                        },
                        colors: {
                            'primary': {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                500: '#3b82f6',
                                600: '#2563eb',
                                700: '#1d4ed8',
                                900: '#1e3a8a',
                            },
                            'accent': {
                                500: '#f59e0b',
                                600: '#d97706',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .hero-gradient {
                background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #1d4ed8 100%);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            }
            
            .btn-secondary {
                background: linear-gradient(135deg, #f59e0b, #d97706);
                transition: all 0.3s ease;
            }
            
            .btn-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
            }
            
            .form-input {
                transition: all 0.3s ease;
                border: 2px solid #e5e7eb;
            }
            
            .form-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .card-hover {
                transition: all 0.3s ease;
            }
            
            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }
            
            .fade-in {
                animation: fadeIn 0.8s ease-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="font-inter antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="ml-2 text-xl font-bold text-gray-900">RentCar Platform</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Home</a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-2 rounded-md text-sm font-medium">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center">
                        <svg class="h-12 w-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span class="ml-2 text-2xl font-bold text-gray-900">RentCar Platform</span>
                    </a>
                </div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 fade-in">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
