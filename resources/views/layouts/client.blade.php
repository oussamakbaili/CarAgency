<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ToubCar - Espace Client</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Temporary Tailwind CSS via CDN until Vite is set up -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- ULTIMATE NAVIGATION FIX - SOLUTION ULTIME POUR DOUBLE-CLIC -->
    <script src="{{ asset('js/ultimate-navigation-fix.js') }}"></script>
    
    <!-- Client Notifications Script - Must load before Alpine.js -->
    <script src="{{ asset('js/client-notifications.js') }}"></script>
    
    <!-- Support Messages Notifications -->
    <script>
    // Update support messages notification badge
    async function updateSupportMessagesBadge() {
        try {
            const response = await fetch('/client/support/unread-count');
            const data = await response.json();
            
            const badge = document.getElementById('support-messages-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Error updating support messages badge:', error);
        }
    }
    
    // Update badge on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSupportMessagesBadge();
        
        // Update every 30 seconds
        setInterval(updateSupportMessagesBadge, 30000);
    });
    </script>
    
    
    <!-- Chart.js for dashboard charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-link {
            position: relative;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: #ea580c;
            border-radius: 0 2px 2px 0;
            transition: height 0.2s ease;
        }
        
        .sidebar-link.active::before,
        .sidebar-link:hover::before {
            height: 60%;
        }
        
        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #9ca3af;
        }
    </style>
</head>
    <body class="font-sans antialiased">
    <!-- Session Messages for Notifications -->
    @if(session('success'))
        <div data-success-message="{{ session('success') }}" style="display:none;"></div>
    @endif
    @if(session('error'))
        <div data-error-message="{{ session('error') }}" style="display:none;"></div>
    @endif
    
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="h-20 border-b border-gray-200 flex items-center px-6">
                    <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3 group hover:opacity-90 transition-opacity py-2">
                        <img src="{{ asset('images/toubcar-logo.png') }}" alt="ToubCar Logo" class="h-24 w-auto">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Espace Client</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <!-- Main Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">PRINCIPAL</p>
                        
                    <!-- Dashboard -->
                        <a href="{{ route('client.dashboard') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('client.dashboard') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client.dashboard') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                            <span class="text-sm">Tableau de bord</span>
                        </a>
                    </div>

                    <!-- Location Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">LOCATION</p>
                        
                        <!-- Browse Cars -->
                        <a href="{{ route('client.cars.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('client.cars.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client.cars.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span class="text-sm">Véhicules</span>
                        </a>

                        <!-- Browse Agencies -->
                        <a href="{{ route('client.agencies.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('client.agencies.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client.agencies.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-sm">Agences</span>
                        </a>
                    </div>

                    <!-- Reservations Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">RÉSERVATIONS</p>
                        
                        <!-- All Reservations -->
                        <a href="{{ route('client.rentals.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('client.rentals.index') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client.rentals.index') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-sm">Toutes mes réservations</span>
                        </a>

                        <!-- Messages -->
                        <a href="{{ route('client.messages.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('client.messages.*') || request()->routeIs('client.support.messages') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client.messages.*') || request()->routeIs('client.support.messages') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span class="text-sm flex-1">Messages</span>
                            <span id="support-messages-badge" class="ml-auto bg-blue-600 text-white py-0.5 px-2 rounded-full text-xs font-semibold hidden">0</span>
                        </a>

                    </div>

                    <!-- Profile & Support Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">COMPTE</p>
                        

                        <!-- Support -->
                        <a href="{{ route('client.support.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('client.support.index') || request()->routeIs('client.support.create') || request()->routeIs('client.support.show') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('client.support.index') || request()->routeIs('client.support.create') || request()->routeIs('client.support.show') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                            </svg>
                            <span class="text-sm flex-1">Support</span>
                        </a>
                    </div>
                </nav>

                <!-- User Menu - Compact Modern Design -->
                <div class="border-t border-gray-200 p-4">
                    <!-- User Profile Card - Compact Design -->
                    <div class="bg-white rounded-xl p-3 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                        <!-- Profile Link -->
                        <a href="{{ route('client.profile.index') }}" class="block group">
                            <div class="flex items-center space-x-3">
                                <!-- Avatar - Compact -->
                                <div class="relative flex-shrink-0">
                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#C2410C] via-[#9A3412] to-[#7C2D12] flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-200 group-hover:scale-105">
                                        <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <!-- Online Status - Smaller -->
                                    <div class="absolute -bottom-0.5 -right-0.5 h-4 w-4 bg-green-500 border-2 border-white rounded-full shadow-sm">
                                        <div class="h-full w-full bg-green-400 rounded-full animate-pulse"></div>
                                    </div>
                                </div>
                                
                                <!-- User Info - Compact -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate group-hover:text-[#C2410C] transition-colors">
                                                {{ Auth::user()->name }}
                                            </h3>
                                            <div class="flex items-center space-x-1 mt-0.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Client
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Arrow Icon - Smaller -->
                                        <div class="flex items-center ml-2">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-[#C2410C] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Logout Button - Compact Design -->
                    <div class="mt-3 flex justify-center">
                        <form method="POST" action="{{ route('logout') }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                            @csrf
                            <button type="submit" class="group flex items-center space-x-2 px-4 py-2 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-[#C2410C] hover:to-[#9A3412] rounded-lg transition-all duration-200 hover:shadow-md border border-gray-200 hover:border-transparent">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="text-xs font-medium">Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="pl-64">
            <!-- Top Navigation -->
            <div class="bg-white border-b border-gray-200">
                <div class="px-8 py-5">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            @yield('header', 'Tableau de bord')
                        </h1>
                        
                        <!-- Quick Stats -->
                        <div class="hidden md:flex items-center gap-6">
                            @php
                                $userId = auth()->id();
                                $activeRentals = $userId ? \App\Models\Rental::where('user_id', $userId)->where('status', 'active')->count() : 0;
                                $totalRentals = $userId ? \App\Models\Rental::where('user_id', $userId)->count() : 0;
                            @endphp
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Locations actives</p>
                                <p class="text-lg font-bold text-green-600">{{ $activeRentals }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total locations</p>
                                <p class="text-lg font-bold text-blue-600">{{ $totalRentals }}</p>
                            </div>
                            <!-- Notifications -->
                            <div class="relative" x-data="clientNotificationsDropdown()" @click.away="open = false">
                                <button @click="toggleDropdown()" class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span x-show="unreadCount > 0" 
                                          x-text="unreadCount" 
                                          class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-orange-600 rounded-full min-w-[20px]"></span>
                        </button>
                        
                                <!-- Notifications Dropdown -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                     style="display: none;">
                                    
                                    <!-- Header -->
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                            <button @click="markAllAsRead()" 
                                                    x-show="unreadCount > 0"
                                                    class="text-xs text-orange-600 hover:text-orange-700 font-medium">
                                                Tout marquer comme lu
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Notifications List -->
                                    <div :class="{'max-h-96': !showingAll, 'max-h-[600px]': showingAll}" class="overflow-y-auto">
                                        <template x-if="notifications.length === 0">
                                            <div class="px-4 py-8 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500">Aucune notification</p>
                                            </div>
                                        </template>
                                        
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <div @click="handleNotificationClick(notification)" 
                                                 :class="{'bg-blue-50': !notification.is_read, 'bg-white': notification.is_read}"
                                                 class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <!-- Icon -->
                                                    <div :class="notification.icon_color_class" 
                                                         class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                                        <div x-html="notification.icon_svg"></div>
                                                    </div>
                                                    
                                                    <!-- Content -->
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                                        <p class="text-xs text-gray-600 mt-0.5" x-text="notification.message"></p>
                                                        <p class="text-xs text-gray-400 mt-1" x-text="notification.time_ago"></p>
                                                    </div>
                                                    
                                                    <!-- Unread Indicator -->
                                                    <div x-show="!notification.is_read" class="flex-shrink-0">
                                                        <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                        <template x-if="!showingAll && totalCount > notifications.length">
                                            <button @click="loadAllNotifications()" 
                                                    class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                                                Voir toutes les notifications (<span x-text="totalCount"></span>)
                                            </button>
                                        </template>
                                        <template x-if="showingAll">
                                            <p class="text-sm text-gray-500">
                                                Affichage de toutes les notifications (<span x-text="totalCount"></span>)
                                            </p>
                                        </template>
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts Stack -->
    @stack('scripts')
</body>
</html>


