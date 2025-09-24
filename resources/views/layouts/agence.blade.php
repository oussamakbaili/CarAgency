<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Agence</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js for dashboard charts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-30" x-data="{ open: true }">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-center h-16 bg-blue-600">
                        <span class="text-white text-lg font-semibold">Agence Panel</span>
                    </div>

                    <!-- Agency Info -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->agency->agency_name ?? 'Agence' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                        <!-- Dashboard -->
                        <a href="{{ route('agence.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.dashboard') ? 'bg-gray-100' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>

                        <!-- Fleet Management -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Gestion de la Flotte</span>
                            </div>
                            <a href="{{ route('agence.cars.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.cars.*') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span>Tous les véhicules</span>
                            </a>
                            <a href="{{ route('agence.fleet.categories') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.fleet.categories') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Catégories de véhicules</span>
                            </a>
                            <a href="{{ route('agence.fleet.maintenance') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.fleet.maintenance') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <span>Planification maintenance</span>
                            </a>
                        </div>

                        <!-- Booking Management -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Gestion des Réservations</span>
                            </div>
                            <a href="{{ route('agence.bookings.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.bookings.index') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>Toutes les réservations</span>
                            </a>
                            <a href="{{ route('agence.bookings.calendar') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.bookings.calendar') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Calendrier des réservations</span>
                            </a>
                            <a href="{{ route('agence.bookings.history') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.bookings.history') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Historique des locations</span>
                            </a>
                        </div>

                        <!-- Customer Management -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Gestion des Clients</span>
                            </div>
                            <a href="{{ route('agence.customers.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.customers.*') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <span>Tous les clients</span>
                            </a>
                            <a href="{{ route('agence.customers.reviews') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.customers.reviews') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <span>Avis clients</span>
                            </a>
                            <a href="{{ route('agence.customers.support') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.customers.support') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Support client</span>
                            </a>
                        </div>

                        <!-- Financial Management -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Gestion Financière</span>
                            </div>
                            <a href="{{ route('agence.finance.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.finance.index') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>Tableau de bord financier</span>
                            </a>
                            <a href="{{ route('agence.finance.payments') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.finance.payments') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span>Historique des paiements</span>
                            </a>
                            <a href="{{ route('agence.finance.commissions') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.finance.commissions') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span>Rapports de commission</span>
                            </a>
                            <a href="{{ route('agence.finance.payouts') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.finance.payouts') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span>Suivi des paiements</span>
                            </a>
                        </div>

                        <!-- Pricing & Availability -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Tarification & Disponibilité</span>
                            </div>
                            <a href="{{ route('agence.pricing.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.pricing.*') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span>Gestion des prix</span>
                            </a>
                            <a href="{{ route('agence.pricing.dynamic') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.pricing.dynamic') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Tarification dynamique</span>
                            </a>
                            <a href="{{ route('agence.pricing.seasonal') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.pricing.seasonal') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Tarifs saisonniers</span>
                            </a>
                            <a href="{{ route('agence.pricing.offers') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.pricing.offers') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Offres promotionnelles</span>
                            </a>
                        </div>

                        <!-- Reports & Analytics -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Rapports & Analytiques</span>
                            </div>
                            <a href="{{ route('agence.reports.performance') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.reports.performance') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span>Performance</span>
                            </a>
                            <a href="{{ route('agence.reports.revenue') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.reports.revenue') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Revenus</span>
                            </a>
                            <a href="{{ route('agence.reports.customers') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.reports.customers') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <span>Clients</span>
                            </a>
                            <a href="{{ route('agence.reports.fleet') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.reports.fleet') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span>Flotte</span>
                            </a>
                        </div>

                        <!-- Profile & Settings -->
                        <a href="{{ route('agence.profile.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.profile.*') ? 'bg-gray-100' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil & Paramètres</span>
                        </a>

                        <!-- Support & Help -->
                        <div class="space-y-1">
                            <div class="flex items-center px-4 py-2 text-gray-500 text-sm font-medium">
                                <span>Support & Aide</span>
                            </div>
                            <a href="{{ route('agence.support.contact') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.support.contact') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Contacter le support</span>
                            </a>
                            <a href="{{ route('agence.support.training') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('agence.support.training') ? 'bg-gray-100' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Formation</span>
                            </a>
                        </div>
                    </nav>

                    <!-- User Menu -->
                    <div class="p-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col ml-64">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 lg:hidden">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <h1 class="ml-4 text-xl font-semibold text-gray-900">
                                @yield('header', 'Tableau de bord')
                            </h1>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="flex items-center space-x-4">
                            <button class="relative p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @php
                                    $notificationCount = \App\Models\Rental::where('agency_id', auth()->user()->agency->id ?? 0)->where('status', 'pending')->count();
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                                @endif
                            </button>
                            
                            <!-- Quick Stats -->
                            <div class="hidden md:flex items-center space-x-6 text-sm">
                                <div class="text-center">
                                    <p class="text-gray-500">Revenus du mois</p>
                                    <p class="font-semibold text-green-600">{{ number_format(auth()->user()->agency->total_earnings ?? 0, 2) }}€</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-gray-500">Locations actives</p>
                                    <p class="font-semibold text-blue-600">{{ \App\Models\Rental::where('agency_id', auth()->user()->agency->id ?? 0)->where('status', 'active')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-20 bg-gray-600 bg-opacity-75 lg:hidden" style="display: none;"></div>
        
        @stack('scripts')
    </body>
</html>
