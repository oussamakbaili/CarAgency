@extends('layouts.client')

@section('header', 'Tableau de bord')

@section('content')
<!-- Overview Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Rentals Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Total Locations</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRentals }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">{{ \App\Models\Rental::where('user_id', auth()->id())->where('status', 'active')->count() }} actives</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Rentals Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-green-50">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Locations Actives</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::where('user_id', auth()->id())->where('status', 'active')->count() }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">En cours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Rentals Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-orange-50">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">En Attente</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::where('user_id', auth()->id())->where('status', 'pending')->count() }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-orange-600">En validation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Spent Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-yellow-50">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Total Dépensé</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(\App\Models\Rental::where('user_id', auth()->id())->sum('total_price'), 0, ',', ' ') }} MAD</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">Toutes locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row: Recent Activity and Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Recent Activity -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h3>
            <div class="flow-root">
                <ul class="-mb-8">
                    @forelse($recentActivity as $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full {{ ($activity['color'] === 'green' ? 'bg-green-500' : ($activity['color'] === 'yellow' ? 'bg-yellow-500' : 'bg-blue-500')) }} flex items-center justify-center ring-8 ring-white">
                                        @if($activity['icon'] === 'car')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        @elseif($activity['icon'] === 'star')
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div>
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900">{{ $activity['title'] }}</p>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <p>{{ $activity['description'] }}</p>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-400">
                                            <time datetime="{{ $activity['date']->toISOString() }}">{{ $activity['date']->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune activité récente</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par explorer nos véhicules disponibles.</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="space-y-4">
                <a href="{{ route('client.cars.index') }}" 
                   class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-all duration-200 hover:shadow-md">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900">Parcourir Véhicules</h3>
                        <p class="text-sm text-gray-600">{{ $availableCars }} disponibles</p>
                    </div>
                </a>

                <a href="{{ route('client.rentals.index') }}" 
                   class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-all duration-200 hover:shadow-md">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900">Mes Locations</h3>
                        <p class="text-sm text-gray-600">Gérer mes réservations</p>
                    </div>
                </a>

                <a href="{{ route('client.profile.index') }}" 
                   class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-all duration-200 hover:shadow-md">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900">Mon Profil</h3>
                        <p class="text-sm text-gray-600">Modifier mes informations</p>
                    </div>
                </a>

                @if($openSupportTickets > 0)
                <a href="#" 
                   class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-all duration-200 hover:shadow-md">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900">Support</h3>
                        <p class="text-sm text-gray-600">{{ $openSupportTickets }} tickets ouverts</p>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Profile Completion Alert -->
@if(!$quickActions['profileComplete'])
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
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

<!-- Upcoming Rentals -->
@if($upcomingRentals->count() > 0)
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Locations à Venir</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingRentals as $rental)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    @if($rental->car->image)
                        <img src="{{ $rental->car->image_url }}" 
                             alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                             class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
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
                            {{ number_format($rental->total_price, 0) }} MAD
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Favorite Cars -->
@if($favoriteCars->count() > 0)
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Véhicules Recommandés</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($favoriteCars as $car)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    @if($car->image)
                        <img src="{{ $car->image_url }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900 truncate">
                            {{ $car->brand }} {{ $car->model }}
                        </h3>
                        <p class="text-sm text-gray-600">{{ $car->agency->user->name ?? 'N/A' }}</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ number_format($car->price_per_day, 0) }} MAD/jour
                        </p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('client.cars.show', $car->id) }}" 
                       class="w-full bg-orange-600 text-white text-center py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                        Voir Détails
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

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