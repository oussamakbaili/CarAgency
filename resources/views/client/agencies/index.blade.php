@extends('layouts.client')

@section('header', 'Agences de Location')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Agences de Location</h1>
            <p class="text-gray-600 mt-1">Découvrez les meilleures agences de location de véhicules</p>
        </div>
        <div class="flex items-center space-x-6">
            <div class="text-right">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Agences disponibles</p>
                <p class="text-2xl font-bold text-orange-600">{{ $agencies->total() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Rechercher une Agence</h3>
            <button onclick="toggleFilters()" class="text-orange-600 hover:text-orange-800 text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                </svg>
                Filtres
            </button>
        </div>

        <form method="GET" action="{{ route('client.agencies.index') }}" class="space-y-4" id="agenciesFiltersForm">
            <!-- Main Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher par nom d'agence..."
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Filters Grid -->
            <div id="filtersGrid" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Location Filter -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Localisation</label>
                    <select id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label for="min_rating" class="block text-sm font-medium text-gray-700 mb-2">Note minimum</label>
                    <select id="min_rating" name="min_rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Toutes les notes</option>
                        <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ étoiles</option>
                        <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ étoiles</option>
                        <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ étoiles</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                    <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Note (plus haute)</option>
                        <option value="reviews_count" {{ request('sort') == 'reviews_count' ? 'selected' : '' }}>Nombre d'avis</option>
                        <option value="cars_count" {{ request('sort') == 'cars_count' ? 'selected' : '' }}>Nombre de véhicules</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    {{ $agencies->total() }} agence(s) trouvée(s)
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('client.agencies.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Réinitialiser
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                        Appliquer les filtres
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Agencies Grid -->
@if($agencies->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    @foreach($agencies as $agency)
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow border border-gray-200 group">
        <!-- Agency Header -->
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">
                        {{ $agency->user->name }}
                    </h3>
                    @if($agency->city)
                        <p class="text-gray-600 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $agency->city }}
                        </p>
                    @endif
                </div>
                
                <!-- Rating -->
                <div class="text-right">
                    @if($agency->avis_avg_rating)
                        <div class="flex items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $agency->avis_avg_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">
                                {{ number_format($agency->avis_avg_rating, 1) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $agency->avis_count }} avis
                        </p>
                    @else
                        <div class="text-sm text-gray-500">Aucun avis</div>
                    @endif
                </div>
            </div>

            <!-- Agency Stats -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-lg font-bold text-orange-600">{{ $agency->cars_count }}</div>
                    <div class="text-xs text-gray-600">Véhicules disponibles</div>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-lg font-bold text-green-600">{{ $agency->avis_count }}</div>
                    <div class="text-xs text-gray-600">Avis clients</div>
                </div>
            </div>

            @if($agency->description)
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                    {{ Str::limit($agency->description, 120) }}
                </p>
            @endif

            <!-- Action Button -->
            <a href="{{ route('client.agencies.show', $agency) }}" 
               class="w-full bg-orange-600 text-white text-center py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                Voir les véhicules
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        {{ $agencies->appends(request()->query())->links() }}
    </div>
</div>
@else
<!-- No Agencies Found -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune agence trouvée</h3>
        <p class="text-gray-600 mb-6">Essayez de modifier vos critères de recherche pour trouver plus d'agences.</p>
        <div class="space-x-4">
            <a href="{{ route('client.agencies.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Voir toutes les agences
            </a>
            <button onclick="clearFilters()" 
                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                Effacer les filtres
            </button>
        </div>
    </div>
</div>
@endif

<script>
function toggleFilters() {
    const filtersGrid = document.getElementById('filtersGrid');
    filtersGrid.classList.toggle('hidden');
}

function clearFilters() {
    // Reset all form inputs
    document.querySelector('input[name="search"]').value = '';
    document.querySelector('select[name="location"]').value = '';
    document.querySelector('select[name="min_rating"]').value = '';
    document.querySelector('select[name="sort"]').value = 'rating';
    
    // Submit the specific filters form
    document.getElementById('agenciesFiltersForm').submit();
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('select[name="location"], select[name="min_rating"], select[name="sort"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('agenciesFiltersForm').submit();
        });
    });
});
</script>
@endsection
