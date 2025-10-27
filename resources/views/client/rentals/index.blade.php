@extends('layouts.client')

@section('header', 'Mes Locations')

@section('content')
<!-- Page Header -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Mes Locations</h1>
                <p class="text-gray-600 mt-1">Gérez vos réservations et suivez l'état de vos locations</p>
            </div>
            <a href="{{ route('client.cars.index') }}" 
               class="inline-flex items-center px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvelle Location
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Rentals -->
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
                    <p class="text-2xl font-bold text-gray-900">{{ $rentals->total() }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-gray-500">Toutes confondues</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Rentals -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-green-50">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Locations Actives</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $rentals->where('status', 'approved')->count() }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">En cours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Rentals -->
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
                    <p class="text-2xl font-bold text-gray-900">{{ $rentals->where('status', 'pending')->count() }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-orange-600">En validation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Spent -->
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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($rentals->whereIn('status', ['approved', 'completed'])->sum('total_price'), 0) }} MAD</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">Toutes locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Filtrer les Locations</h3>
            <button onclick="toggleFilters()" class="text-orange-600 hover:text-orange-800 text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                </svg>
                Filtres
            </button>
        </div>

                <form method="GET" action="{{ route('client.rentals.index') }}" class="space-y-4" id="rentalsFiltersForm">
                    <div id="filtersGrid" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvée</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetée</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            {{ $rentals->total() }} location(s) trouvée(s)
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('client.rentals.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Réinitialiser
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                                Appliquer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<!-- Rentals List -->
@if($rentals->count() > 0)
<div class="space-y-4">
    @foreach($rentals as $rental)
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow border border-gray-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <!-- Car Image and Info -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                @if($rental->car->image)
                                    <img src="{{ $rental->car->image_url }}" 
                                         alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $rental->car->brand }} {{ $rental->car->model }}
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @switch($rental->status)
                                            @case('approved')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('pending')
                                                bg-orange-100 text-orange-800
                                                @break
                                            @case('completed')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('cancelled')
                                                bg-gray-100 text-gray-800
                                                @break
                                            @case('rejected')
                                                bg-red-100 text-red-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>{{ $rental->car->agency->user->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>{{ $rental->car->registration_number }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                            <span>{{ number_format($rental->total_price, 0) }}€</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $rental->start_date->diffInDays($rental->end_date) + 1 }} jour(s)</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>Créée le {{ $rental->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col items-end space-y-2">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ number_format($rental->total_price, 0) }} MAD
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ number_format($rental->total_price / ($rental->start_date->diffInDays($rental->end_date) + 1), 0) }} MAD/jour
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('client.rentals.show', $rental) }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                                    Voir détails
                                </a>
                                
                                @if($rental->status === 'active')
                                    <a href="{{ route('client.messages.show', $rental) }}" 
                                       class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Contacter
                                    </a>
                                @endif
                                
                                @if($rental->status === 'pending')
                                    <button onclick="cancelRental({{ $rental->id }})" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                        Annuler
                                    </button>
                                @elseif($rental->status === 'active' && $rental->start_date > now())
                                    <button onclick="cancelRental({{ $rental->id }})" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                        Annuler
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

<!-- Pagination -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        {{ $rentals->appends(request()->query())->links() }}
    </div>
</div>
        @else
<!-- No Rentals -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune location trouvée</h3>
        <p class="text-gray-600 mb-6">Vous n'avez pas encore fait de demande de location ou aucun résultat ne correspond à vos filtres.</p>
        <div class="space-x-4">
            <a href="{{ route('client.cars.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Parcourir les Véhicules
            </a>
            <button onclick="clearFilters()" 
                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                Effacer les filtres
            </button>
        </div>
    </div>
</div>
        @endif
    </div>
</div>

<script>
function toggleFilters() {
    const filtersGrid = document.getElementById('filtersGrid');
    filtersGrid.classList.toggle('hidden');
}

function clearFilters() {
    // Reset all form inputs
    document.querySelector('select[name="status"]').value = '';
    document.querySelector('input[name="date_from"]').value = '';
    document.querySelector('input[name="date_to"]').value = '';
    
    // Submit the specific filters form
    document.getElementById('rentalsFiltersForm').submit();
}

function cancelRental(rentalId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette location ?')) {
        // You can implement AJAX cancellation here
        alert('Fonctionnalité d\'annulation bientôt disponible !');
    }
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('select[name="status"], input[name="date_from"], input[name="date_to"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('rentalsFiltersForm').submit();
        });
    });
});
</script>
@endsection