@extends('layouts.client')

@section('title', 'Parcourir les Véhicules')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Parcourir les Véhicules</h1>
                    <p class="text-gray-600 mt-2">Trouvez le véhicule parfait pour votre prochaine location</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">{{ $cars->total() }}</div>
                        <div class="text-sm text-gray-500">véhicules disponibles</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Filtres de Recherche</h2>
                    <button onclick="toggleFilters()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Filtres avancés
                    </button>
                </div>

                <form method="GET" action="{{ route('client.cars.index') }}" class="space-y-6">
                    <!-- Main Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Rechercher par marque, modèle, couleur..."
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                    </div>

                    <!-- Filters Grid -->
                    <div id="filtersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Brand Filter -->
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Marque</label>
                            <select id="brand" name="brand" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les marques</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fuel Type Filter -->
                        <div>
                            <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-2">Carburant</label>
                            <select id="fuel_type" name="fuel_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tous types</option>
                                @foreach($fuelTypes as $fuelType)
                                    <option value="{{ $fuelType }}" {{ request('fuel_type') == $fuelType ? 'selected' : '' }}>
                                        {{ ucfirst($fuelType) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Max Price -->
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Prix max/jour</label>
                            <div class="relative">
                                <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" 
                                       placeholder="100"
                                       class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <span class="absolute right-3 top-2 text-gray-500">€</span>
                            </div>
                        </div>

                        <!-- Year From -->
                        <div>
                            <label for="year_from" class="block text-sm font-medium text-gray-700 mb-2">Année min</label>
                            <input type="number" id="year_from" name="year_from" value="{{ request('year_from') }}" 
                                   placeholder="2020"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            {{ $cars->total() }} véhicule(s) trouvé(s)
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('client.cars.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Réinitialiser
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Appliquer les filtres
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cars Grid -->
        @if($cars->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($cars as $car)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                <!-- Car Image -->
                <div class="relative aspect-video bg-gray-200 overflow-hidden">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disponible
                        </span>
                    </div>

                    <!-- Quick Actions Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('client.cars.show', $car) }}" 
                               class="px-4 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                Voir détails
                            </a>
                            <a href="{{ route('client.rentals.create', $car) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                Louer maintenant
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Car Details -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $car->brand }} {{ $car->model }}
                            </h3>
                            <p class="text-sm text-gray-600">{{ $car->year }} • {{ ucfirst($car->fuel_type) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-bold text-blue-600">
                                {{ number_format($car->price_per_day, 0) }}€
                            </div>
                            <div class="text-xs text-gray-500">par jour</div>
                        </div>
                    </div>

                    <!-- Car Specifications -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $car->agency->user->name }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ $car->registration_number }}
                        </div>
                    </div>

                    @if($car->description)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ Str::limit($car->description, 100) }}
                        </p>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                        <a href="{{ route('client.cars.show', $car) }}" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center text-sm font-medium">
                            Détails
                        </a>
                        <a href="{{ route('client.rentals.create', $car) }}" 
                           class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center text-sm font-medium">
                            Louer
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            {{ $cars->appends(request()->query())->links() }}
        </div>
        @else
        <!-- No Cars Found -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun véhicule trouvé</h3>
            <p class="text-gray-600 mb-6">Essayez de modifier vos critères de recherche pour trouver plus de véhicules.</p>
            <div class="space-x-4">
                <a href="{{ route('client.cars.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Voir tous les véhicules
                </a>
                <button onclick="clearFilters()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Effacer les filtres
                </button>
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
    document.querySelector('input[name="search"]').value = '';
    document.querySelector('select[name="brand"]').value = '';
    document.querySelector('select[name="fuel_type"]').value = '';
    document.querySelector('input[name="max_price"]').value = '';
    document.querySelector('input[name="year_from"]').value = '';
    
    // Submit the form
    document.querySelector('form').submit();
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('select[name="brand"], select[name="fuel_type"], input[name="max_price"], input[name="year_from"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.querySelector('form').submit();
        });
    });
});
</script>
@endsection