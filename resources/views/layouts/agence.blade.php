<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ToubCar - Espace Agence</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Temporary Tailwind CSS via CDN until Vite is set up -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- ULTIMATE NAVIGATION FIX - SOLUTION ULTIME POUR DOUBLE-CLIC -->
        <script src="{{ asset('js/ultimate-navigation-fix.js') }}"></script>
        
        <!-- Mobile Optimizations CSS -->
        <link rel="stylesheet" href="{{ asset('css/agency-mobile.css') }}">
        
        <!-- Chart.js for dashboard charts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Agency Validation Script -->
        <script src="{{ asset('js/agency-validation.js') }}" defer></script>
        
        <!-- Agency Notifications Script -->
        <script src="{{ asset('js/agency-notifications.js') }}" defer></script>
        
        <!-- Support Messages Notifications -->
        <script>
        // Update support messages notification badge
        async function updateSupportMessagesBadge() {
            try {
                const response = await fetch('/agence/support/unread-count');
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
                        <a href="{{ route('agence.dashboard') }}" class="flex items-center gap-3 group hover:opacity-90 transition-opacity py-2">
                            <img src="{{ asset('images/toubcar-logo.png') }}" alt="ToubCar Logo" class="h-24 w-auto">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Espace Agence</p>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 overflow-y-auto">
                        <!-- Main Section -->
                        <div class="mb-8">
                            <p class="nav-section-title px-3 mb-3">PRINCIPAL</p>
                            
                            <!-- Dashboard -->
                            <a href="{{ route('agence.dashboard') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('agence.dashboard') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.dashboard') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-sm">Tableau de bord</span>
                            </a>
                        </div>

                        <!-- Operations Section -->
                        <div class="mb-8">
                            <p class="nav-section-title px-3 mb-3">OPÉRATIONS</p>
                            
                            <!-- Fleet Management -->
                            <a href="{{ route('agence.cars.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('agence.cars.*') || request()->routeIs('agence.fleet.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ (request()->routeIs('agence.cars.*') || request()->routeIs('agence.fleet.*')) ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span class="text-sm">Flotte</span>
                            </a>

                            <!-- Booking Management -->
                            <a href="{{ route('agence.bookings.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('agence.bookings.*') || request()->routeIs('agence.rentals.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ (request()->routeIs('agence.bookings.*') || request()->routeIs('agence.rentals.*')) ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">Réservations</span>
                            </a>

                            <!-- Messages -->
                            <a href="{{ route('agence.messages.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('agence.messages.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.messages.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="text-sm">Messages</span>
                            </a>

                            <!-- Customer Management -->
                            <a href="{{ route('agence.customers.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('agence.customers.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.customers.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <span class="text-sm">Clients</span>
                            </a>
                        </div>

                        <!-- Finance Section -->
                        <div class="mb-8">
                            <p class="nav-section-title px-3 mb-3">FINANCE</p>
                            
                            <!-- Financial Management -->
                            <a href="{{ route('agence.finance.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('agence.finance.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.finance.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span class="text-sm">Finances</span>
                            </a>

                            <!-- Pricing Management -->
                            <a href="{{ route('agence.pricing.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('agence.pricing.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.pricing.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span class="text-sm">Tarification</span>
                            </a>
                        </div>

                        <!-- Analytics Section -->
                        <div class="mb-8">
                            <p class="nav-section-title px-3 mb-3">ANALYSES</p>
                            
                            <!-- Reports & Analytics -->
                            <a href="{{ route('agence.reports.performance') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('agence.reports.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.reports.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="text-sm">Rapports</span>
                            </a>
                        </div>

                        <!-- Settings Section -->
                        <div class="mb-8">
                            <p class="nav-section-title px-3 mb-3">PARAMÈTRES</p>
                            

                            <!-- Support -->
                            <a href="{{ route('agence.support.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('agence.support.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('agence.support.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                                </svg>
                                <span class="text-sm">Support</span>
                            </a>
                        </div>
                    </nav>

                    <!-- User Menu - Compact Modern Design -->
                    <div class="border-t border-gray-200 p-4">
                        <!-- User Profile Card - Compact Design -->
                        <div class="bg-white rounded-xl p-3 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                            <!-- Profile Link -->
                            <a href="{{ route('agence.profile.index') }}" class="block group">
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
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ auth()->user() && auth()->user()->agency ? auth()->user()->agency->agency_name : 'Agence' }}
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
                <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
                    <div class="px-8 py-5">
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-semibold text-gray-900">
                                @yield('header', 'Tableau de bord')
                            </h1>
                            
                            <!-- Quick Stats -->
                            <div class="hidden md:flex items-center gap-6">
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Revenus du mois</p>
                                    <p class="text-lg font-bold text-green-600">{{ number_format(auth()->user() && auth()->user()->agency ? auth()->user()->agency->total_earnings : 0, 0) }}€</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Locations actives</p>
                                    @php
                                        $agencyId = auth()->user() && auth()->user()->agency ? auth()->user()->agency->id : 0;
                                        $activeCount = \App\Models\Rental::where('agency_id', $agencyId)->where('status', 'active')->count();
                                    @endphp
                                    <p class="text-lg font-bold text-blue-600">{{ $activeCount }}</p>
                                </div>
                                <!-- Notifications Dropdown -->
                                <div x-data="notificationsDropdown()" @click.away="open = false" class="relative">
                                    <button @click="toggleDropdown()" class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        <span x-show="unreadCount > 0" 
                                              x-text="unreadCount" 
                                              class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-orange-600 rounded-full min-w-[20px]"></span>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                                         style="display: none;">
                                        
                                        <!-- Header -->
                                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                            <button @click="markAllAsRead()" 
                                                    x-show="unreadCount > 0"
                                                    class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                                Tout marquer comme lu
                                            </button>
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
                                                             class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="notification.icon_svg"/>
                                                            </svg>
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
                                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
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
                </header>

                <!-- Page Content -->
                <main class="p-8">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-20 bg-gray-600 bg-opacity-75 lg:hidden" style="display: none;"></div>
        
        <!-- Notifications Dropdown Script -->
        <script>
            function notificationsDropdown() {
                return {
                    open: false,
                    notifications: [],
                    unreadCount: 0,
                    totalCount: 0,
                    loading: false,
                    showingAll: false,
                    
                    init() {
                        this.loadNotifications();
                        // Refresh notifications every 60 seconds
                        setInterval(() => {
                            this.loadNotifications();
                        }, 60000);
                    },
                    
                    toggleDropdown() {
                        this.open = !this.open;
                        if (this.open) {
                            this.loadNotifications();
                        }
                    },
                    
                    async loadNotifications() {
                        try {
                            const limit = this.showingAll ? 50 : 10;
                            const response = await fetch(`{{ route("agence.notifications.index") }}?limit=${limit}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                this.notifications = data.notifications;
                                this.unreadCount = data.unread_count;
                                this.totalCount = data.total_count;
                            }
                        } catch (error) {
                            console.error('Error loading notifications:', error);
                        }
                    },
                    
                    async handleNotificationClick(notification) {
                        // Mark as read
                        if (!notification.is_read) {
                            await this.markAsRead(notification.id);
                        }
                        
                        // Navigate to action URL if exists
                        if (notification.action_url) {
                            window.location.href = notification.action_url;
                        }
                    },
                    
                    async markAsRead(notificationId) {
                        try {
                            const response = await fetch(`/agence/notifications/${notificationId}/read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            if (response.ok) {
                                // Update local state
                                const notification = this.notifications.find(n => n.id === notificationId);
                                if (notification) {
                                    notification.is_read = true;
                                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                                }
                            }
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                    },
                    
                    async markAllAsRead() {
                        try {
                            const response = await fetch('{{ route("agence.notifications.read-all") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            if (response.ok) {
                                // Update local state
                                this.notifications.forEach(n => n.is_read = true);
                                this.unreadCount = 0;
                            }
                        } catch (error) {
                            console.error('Error marking all notifications as read:', error);
                        }
                    },
                    
                    async loadAllNotifications() {
                        this.showingAll = true;
                        await this.loadNotifications();
                    }
                }
            }
        </script>
        
        @stack('scripts')
    </body>
</html>
