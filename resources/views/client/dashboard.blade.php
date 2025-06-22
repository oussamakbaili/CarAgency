<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de Bord Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Bienvenue, {{ auth()->user()->name }}!
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Gérez vos locations de véhicules et explorez notre flotte
                    </p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Rentals -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Locations</h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $totalRentals ?? 0 }} locations</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('client.rentals.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Voir l'historique →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Locations Actives</h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $activeRentals ?? 0 }} en cours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Rentals -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">En Attente</h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $pendingRentals ?? 0 }} demandes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                <svg class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Dépensé</h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ number_format($totalSpent ?? 0, 2) }}€</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions Rapides</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('client.cars.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">Parcourir les Véhicules</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $availableCars ?? 0 }} véhicules disponibles</p>
                            </div>
                        </a>

                        <a href="{{ route('client.rentals.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                            <svg class="h-8 w-8 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">Mes Locations</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Gérer vos réservations</p>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                            <svg class="h-8 w-8 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">Mon Profil</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Modifier mes informations</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Rentals -->
            @if(isset($recentRentals) && $recentRentals->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Locations Récentes</h2>
                        <a href="{{ route('client.rentals.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                            Voir tout →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentRentals as $rental)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-4">
                                @if($rental->car->image)
                                    <img src="{{ asset('storage/' . $rental->car->image) }}" alt="{{ $rental->car->brand }} {{ $rental->car->model }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $rental->car->brand }} {{ $rental->car->model }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $rental->car->agency->user->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @switch($rental->status)
                                        @case('approved')
                                            bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                            @break
                                        @case('pending')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200
                                            @break
                                        @case('rejected')
                                            bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                            @break
                                        @case('completed')
                                            bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                    @endswitch">
                                    {{ ucfirst($rental->status) }}
                                </span>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mt-1">
                                    {{ number_format($rental->total_price, 2) }}€
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <!-- No Rentals Yet -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucune location</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez en parcourant nos véhicules disponibles.</p>
                    <div class="mt-6">
                        <a href="{{ route('client.cars.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Parcourir les Véhicules
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout> 