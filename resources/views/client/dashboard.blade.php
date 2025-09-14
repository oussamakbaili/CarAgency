@extends('layouts.client')

@section('title', 'Dashboard Client')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header with Profile Status -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->client->profile_picture)
                            <img class="h-16 w-16 rounded-full object-cover" 
                                 src="{{ Storage::url(auth()->user()->client->profile_picture) }}" 
                                 alt="{{ auth()->user()->name }}">
                        @else
                            <div class="h-16 w-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <span class="text-2xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold">Bonjour, {{ auth()->user()->name }}!</h1>
                        <p class="text-blue-100">Bienvenue sur votre tableau de bord personnel</p>
                        @if(!$quickActions['profileComplete'])
                            <div class="mt-2 flex items-center text-yellow-200">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">Complétez votre profil pour une meilleure expérience</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $totalRentals }}</div>
                    <div class="text-blue-100">Locations totales</div>
                </div>
            </div>
        </div>

        <!-- Notifications Widget -->
        @include('client.dashboard.widgets.notifications')

        <!-- Statistics Widget -->
        @include('client.dashboard.widgets.statistics')

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activité Récente
                        </h2>
                        <div class="space-y-4">
                            @forelse($recentActivity as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-{{ $activity['color'] }}-100 flex items-center justify-center">
                                        @if($activity['icon'] === 'car')
                                            <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        @elseif($activity['icon'] === 'star')
                                            <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['date']->diffForHumans() }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune activité récente</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par explorer nos véhicules disponibles.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
                    <div class="space-y-3">
                        <a href="{{ route('client.cars.index') }}" 
                           class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Parcourir Véhicules</h3>
                                <p class="text-sm text-gray-600">{{ $availableCars }} disponibles</p>
                            </div>
                        </a>

                        <a href="{{ route('client.rentals.index') }}" 
                           class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Mes Locations</h3>
                                <p class="text-sm text-gray-600">Gérer mes réservations</p>
                            </div>
                        </a>

                        <a href="{{ route('client.profile.index') }}" 
                           class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Mon Profil</h3>
                                <p class="text-sm text-gray-600">Modifier mes informations</p>
                            </div>
                        </a>

                        @if($openSupportTickets > 0)
                        <a href="#" 
                           class="flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Support</h3>
                                <p class="text-sm text-gray-600">{{ $openSupportTickets }} tickets ouverts</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Profile Completion -->
                @if(!$quickActions['profileComplete'])
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Profil Incomplet</h3>
                            <p class="text-sm text-yellow-700 mt-1">Complétez votre profil pour une meilleure expérience de location.</p>
                            <a href="{{ route('client.profile.index') }}" 
                               class="mt-2 inline-flex items-center text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                Compléter maintenant →
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Rentals -->
        @if($upcomingRentals->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Locations à Venir
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($upcomingRentals as $rental)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            @if($rental->car->image)
                                <img src="{{ asset('storage/' . $rental->car->image) }}" 
                                     alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                                     class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate">
                                    {{ $rental->car->brand }} {{ $rental->car->model }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                </p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ number_format($rental->total_price, 0) }}€
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Analytics Widget -->
        @include('client.dashboard.widgets.analytics')

        <!-- Favorite Cars -->
        @if($favoriteCars->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                    </svg>
                    Véhicules Recommandés
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($favoriteCars as $car)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-3">
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" 
                                     alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate">
                                    {{ $car->brand }} {{ $car->model }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $car->agency->user->name ?? 'N/A' }}</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ number_format($car->price_per_day, 0) }}€/jour
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('client.cars.show', $car->id) }}" 
                               class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                Voir Détails
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
// Add any interactive features here
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh notifications every 30 seconds
    setInterval(function() {
        // You can implement AJAX refresh here if needed
    }, 30000);
});
</script>
@endsection