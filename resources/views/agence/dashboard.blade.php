@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Check Agency Status -->
        @if(!auth()->user()->agency)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Aucune agence associée à ce compte
                        </h3>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->agency->status === 'pending')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Votre demande d'inscription est en cours de traitement
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Nous examinerons votre demande dans les plus brefs délais. Vous recevrez une notification par email dès qu'une décision sera prise.</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->agency->status === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Votre demande d'inscription a été rejetée
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ auth()->user()->agency->rejection_reason }}</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'update-info')" class="bg-red-50 px-2 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
                                    Mettre à jour les informations
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Approved Agency Dashboard -->
        @if(auth()->user()->agency && auth()->user()->agency->status === 'approved')
            <!-- Cancellation Warning Widget -->
            <x-agency-cancellation-widget :agency="auth()->user()->agency" />
            
            <!-- Welcome Header -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord - {{ auth()->user()->agency->agency_name }}</h1>
                    <p class="mt-2 text-gray-600">Gérez votre flotte de véhicules et vos locations</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Solde actuel</p>
                                <p class="text-2xl font-bold text-green-600">{{ number_format(auth()->user()->agency->balance ?? 0, 2) }}€</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Gains totaux</p>
                                <p class="text-lg font-semibold text-blue-600">{{ number_format(auth()->user()->agency->total_earnings ?? 0, 2) }}€</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Row - Business Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Fleet Size -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Flotte Totale</h2>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalCars ?? 0 }}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-600">
                                    <span class="text-green-600">{{ $availableCars ?? 0 }} disponibles</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-yellow-600">{{ $carsInMaintenance ?? 0 }} maintenance</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('agence.cars.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Gérer la flotte →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Locations Actives</h2>
                                <p class="text-3xl font-bold text-gray-900">{{ $activeRentals ?? 0 }}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-600">
                                    <span class="text-yellow-600">{{ $pendingBookings ?? 0 }} en attente</span>
                            </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('agence.bookings.pending') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Voir les locations →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Revenus Mensuels</h2>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($monthlyRevenue ?? 0, 2) }}€</p>
                                <div class="flex items-center mt-2 text-sm">
                                    @if(($revenueGrowth ?? 0) >= 0)
                                        <span class="text-green-600">↗ +{{ number_format($revenueGrowth ?? 0, 1) }}%</span>
                                    @else
                                        <span class="text-red-600">↘ {{ number_format($revenueGrowth ?? 0, 1) }}%</span>
                                    @endif
                                    <span class="ml-2 text-gray-600">vs mois dernier</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Rating -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Satisfaction Client</h2>
                                <p class="text-3xl font-bold text-gray-900">{{ $customerRating ?? 0 }}/5</p>
                                <div class="flex items-center mt-2 text-sm text-gray-600">
                                    <span>{{ $reviewCount ?? 0 }} avis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row - Recent Bookings and Revenue Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Recent Bookings Table -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Réservations Récentes</h2>
                            <a href="{{ route('agence.bookings.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir tout →
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($dataTables['recentBookings'] ?? [] as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $booking->client->first_name ?? 'N/A' }} {{ $booking->client->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->car->brand }} {{ $booking->car->model }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->start_date->format('d/m') }} - {{ $booking->end_date->format('d/m') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($booking->status === 'active') bg-green-100 text-green-800
                                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Aucune réservation récente
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tendances des Revenus</h2>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row - Fleet Utilization and Maintenance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Fleet Utilization -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Utilisation de la Flotte</h2>
                        <div class="h-64">
                            <canvas id="fleetUtilizationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Maintenance -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Maintenance à Venir</h2>
                        <div class="space-y-3">
                            @forelse($dataTables['upcomingMaintenance'] ?? [] as $maintenance)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $maintenance->car->brand }} {{ $maintenance->car->model }}</p>
                                    <p class="text-xs text-gray-500">{{ $maintenance->due_date }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $maintenance->type }}
                                </span>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aucune maintenance prévue</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row - Recent Activity Feed -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h2>
                    <div class="space-y-4">
                        @forelse($recentActivity ?? [] as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                    @if($activity['type'] === 'booking') bg-blue-100
                                    @elseif($activity['type'] === 'transaction') bg-green-100
                                    @else bg-gray-100 @endif">
                                    <svg class="w-4 h-4
                                        @if($activity['type'] === 'booking') text-blue-600
                                        @elseif($activity['type'] === 'transaction') text-green-600
                                        @else text-gray-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($activity['type'] === 'booking')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        @elseif($activity['type'] === 'transaction')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($activity['status'] === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($activity['status'] === 'completed') bg-green-100 text-green-800
                                    @elseif($activity['status'] === 'active') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($activity['status']) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucune activité récente</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('agence.cars.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Ajouter un Véhicule</h3>
                                <p class="text-sm text-gray-600">Ajouter un nouveau véhicule à votre flotte</p>
                            </div>
                        </a>

                        <a href="{{ route('agence.bookings.pending') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                            <svg class="h-8 w-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Demandes en Attente</h3>
                                <p class="text-sm text-gray-600">{{ $quickActions['pendingBookingsCount'] ?? 0 }} demandes à traiter</p>
                            </div>
                        </a>

                        <a href="{{ route('agence.cars.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Gérer la Flotte</h3>
                                <p class="text-sm text-gray-600">{{ $quickActions['availableCarsCount'] ?? 0 }} véhicules disponibles</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Rapports</h3>
                                <p class="text-sm text-gray-600">Générer des rapports de performance</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Basic welcome message for non-approved agencies -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-semibold mb-4">Bienvenue dans votre espace agence</h2>
                    <p>Votre compte d'agence est en cours de traitement. Une fois approuvé, vous pourrez accéder à toutes les fonctionnalités de gestion.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Update Information Modal for Rejected Agencies -->
@if(auth()->user()->agency && auth()->user()->agency->status === 'rejected')
    <x-modal name="update-info" focusable>
        <form method="POST" action="{{ route('agence.update') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Mettre à jour les informations') }}
            </h2>

            <!-- Agency Information -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="agency_name" value="Nom de l'Agence" />
                    <x-text-input id="agency_name" name="agency_name" type="text" class="mt-1 block w-full" :value="old('agency_name', auth()->user()->agency->agency_name)" required />
                    <x-input-error :messages="$errors->get('agency_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="commercial_register_doc" value="Registre de Commerce (PDF)" />
                    <input type="file" id="commercial_register_doc" name="commercial_register_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="identity_doc" value="Document d'Identité (PDF)" />
                    <input type="file" id="identity_doc" name="identity_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('identity_doc')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tax_doc" value="Document Fiscal (PDF)" />
                    <input type="file" id="tax_doc" name="tax_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('tax_doc')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Mettre à jour') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($performanceCharts['revenueTrends'] ?? []);
    
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => {
                const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
                return monthNames[item.month - 1] + ' ' + item.year;
            }),
            datasets: [{
                label: 'Revenus (€)',
                data: revenueData.map(item => item.revenue),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '€';
                        }
                    }
                }
            }
        }
    });

    // Fleet Utilization Chart
    const fleetCtx = document.getElementById('fleetUtilizationChart').getContext('2d');
    const fleetData = @json($performanceCharts['fleetUtilization'] ?? []);
    
    const fleetChart = new Chart(fleetCtx, {
        type: 'doughnut',
        data: {
            labels: fleetData.map(item => {
                const statusMap = {
                    'available': 'Disponible',
                    'rented': 'En location',
                    'maintenance': 'Maintenance'
                };
                return statusMap[item.status] || item.status;
            }),
            datasets: [{
                data: fleetData.map(item => item.count),
                backgroundColor: [
                    'rgb(34, 197, 94)',  // Green for available
                    'rgb(59, 130, 246)', // Blue for rented
                    'rgb(245, 158, 11)'  // Yellow for maintenance
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection