<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ToubCar - Administration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Temporary Tailwind CSS via CDN until Vite is set up -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- ULTIMATE NAVIGATION FIX - SOLUTION ULTIME POUR DOUBLE-CLIC -->
    <script src="{{ asset('js/ultimate-navigation-fix.js') }}"></script>
    
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
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="h-20 border-b border-gray-200 flex items-center px-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group hover:opacity-90 transition-opacity py-2">
                        <img src="{{ asset('images/toubcar-logo.png') }}" alt="ToubCar Logo" class="h-24 w-auto">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Administration</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <!-- Main Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">PRINCIPAL</p>
                        
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="text-sm">Tableau de bord</span>
                        </a>
                    </div>

                    <!-- Management Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">GESTION</p>
                        
                        <!-- Gestion des Agences -->
                        <a href="{{ route('admin.agencies.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.agencies.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.agencies.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-sm flex-1">Agences</span>
                            @php
                                $pendingCount = \App\Models\Agency::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto bg-orange-600 text-white py-0.5 px-2 rounded-full text-xs font-semibold">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Gestion des Utilisateurs -->
                        <a href="{{ route('admin.users.main') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.customers.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.customers.*')) ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <span class="text-sm">Utilisateurs</span>
                        </a>

                        <!-- Gestion des Véhicules -->
                        <a href="{{ route('admin.vehicles.main') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.vehicles.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.vehicles.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="text-sm">Véhicules</span>
                        </a>
                    </div>

                    <!-- Operations Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">OPÉRATIONS</p>
                        
                        <!-- Réservations -->
                        <a href="{{ route('admin.bookings.main') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.bookings.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.bookings.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm">Réservations</span>
                        </a>
                    </div>

                    <!-- Finance Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">FINANCE</p>
                        
                        <!-- Commissions & Revenus -->
                        <a href="{{ route('admin.commissions.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.commissions.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.commissions.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm">Commissions & Revenus</span>
                        </a>
                    </div>

                    <!-- Stratégie Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">STRATÉGIE</p>
                        
                        <!-- Analyse Concurrentielle -->
                        <a href="{{ route('admin.competitors.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.competitors.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.competitors.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <!-- Distinct search/analysis icon to avoid duplication with Rapports -->
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                            <span class="text-sm">Analyse Concurrentielle</span>
                        </a>
                    </div>



                    <!-- Reports Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">RAPPORTS</p>
                        
                        <!-- Rapports -->
                        <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.reports.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="text-sm">Rapports</span>
                        </a>
                    </div>

                    <!-- Support Section -->
                    <div class="mb-8">
                        <p class="nav-section-title px-3 mb-3">SUPPORT</p>
                        
                        <!-- Messages -->
                        <a href="{{ route('admin.messages.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.messages.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.messages.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span class="text-sm flex-1">Messages</span>
                        </a>

                        <!-- Support -->
                        <a href="{{ route('admin.support.index') }}" class="sidebar-link flex items-center px-3 py-2.5 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 mb-1 {{ request()->routeIs('admin.support.*') ? 'active bg-gray-50 text-gray-900 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.support.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                            </svg>
                            <span class="text-sm flex-1">Support</span>
                            @php
                                $openTicketsCount = \App\Models\SupportTicket::where('status', 'open')->count();
                            @endphp
                @if($openTicketsCount > 0)
                    <span class="ml-auto bg-orange-600 text-white py-0.5 px-2 rounded-full text-xs font-semibold support-notification-badge">
                        {{ $openTicketsCount }}
                    </span>
                @endif
                        </a>
                    </div>
                </nav>

                <!-- User Menu - Compact Modern Design -->
                <div class="border-t border-gray-200 p-4">
                    <!-- User Profile Card - Compact Design -->
                    <div class="bg-white rounded-xl p-3 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                        <!-- Profile Link -->
                        <a href="{{ route('profile.edit') }}" class="block group">
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
                                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Admin
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
                <div class="px-8 py-5 flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        @yield('header')
                    </h1>
                    
                    <!-- Notifications -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span id="notification-badge" class="absolute -top-1 -right-1 bg-orange-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="markAllAsRead()" class="text-sm text-orange-600 hover:text-orange-800">Tout marquer comme lu</button>
                                            <button onclick="clearAllNotifications()" class="text-sm text-red-600 hover:text-red-800">Effacer tout</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="max-h-96 overflow-y-auto" id="notifications-list">
                                    <div class="p-4 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        <p>Chargement des notifications...</p>
                                    </div>
                                </div>
                                
                                <div class="p-4 border-t border-gray-200">
                                    <a href="{{ route('admin.notifications.index') }}" class="block w-full text-center text-orange-600 hover:text-orange-800 font-medium">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{-- Messages de succès supprimés --}}

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Scripts Stack -->
    @stack('scripts')
    
    <!-- Admin Notifications Script -->
    <script>
    // Load notifications on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        updateNotificationBadge();
        
        // Auto-refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
        setInterval(updateNotificationBadge, 30000);
    });

    // Load notifications for dropdown
    async function loadNotifications() {
        try {
            const response = await fetch('/admin/notifications?wants_json=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                displayNotifications(data.notifications);
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    // Display notifications in dropdown
    function displayNotifications(notifications) {
        const container = document.getElementById('notifications-list');
        
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p>Aucune notification</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = notifications.map(notification => createNotificationElement(notification)).join('');
    }

    // Create notification element
    function createNotificationElement(notification) {
        const isUnread = !notification.is_read;
        const timeAgo = new Date(notification.created_at).toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        return `
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer ${isUnread ? 'bg-orange-50' : ''}" onclick="markNotificationAsRead(${notification.id})">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full ${notification.icon_color_class} flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${notification.icon_svg}"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 truncate">${notification.title}</p>
                            <p class="text-xs text-gray-500 ml-2">${timeAgo}</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">${notification.message}</p>
                        ${notification.data && notification.data.sender_name ? `
                            <p class="text-xs text-gray-500 mt-1">De: ${notification.data.sender_name}</p>
                        ` : ''}
                    </div>
                    ${isUnread ? '<div class="w-2 h-2 bg-orange-600 rounded-full flex-shrink-0 mt-2"></div>' : ''}
                </div>
            </div>
        `;
    }

    // Update notification badge
    async function updateNotificationBadge() {
        try {
            const response = await fetch('/admin/notifications/unread-count');
            const data = await response.json();
            
            const badge = document.getElementById('notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Error updating notification badge:', error);
        }
    }

    // Mark notification as read
    async function markNotificationAsRead(notificationId) {
        try {
            await fetch(`/admin/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            // Reload notifications and update badge
            loadNotifications();
            updateNotificationBadge();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    // Mark all notifications as read
    async function markAllAsRead() {
        try {
            await fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            // Reload notifications and update badge
            loadNotifications();
            updateNotificationBadge();
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    // Clear all notifications
    async function clearAllNotifications() {
        if (!confirm('Êtes-vous sûr de vouloir supprimer toutes les notifications ?')) {
            return;
        }
        
        try {
            await fetch('/admin/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            // Reload notifications and update badge
            loadNotifications();
            updateNotificationBadge();
        } catch (error) {
            console.error('Error clearing all notifications:', error);
        }
    }
    </script>
</body>
</html> 