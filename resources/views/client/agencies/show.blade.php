@extends('layouts.client')

@section('title', $agency->user->name . ' - Agence de Location')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Agency Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-8 text-white mb-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">{{ $agency->user->name }}</h1>
                    @if($agency->city)
                        <p class="text-blue-100 flex items-center mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $agency->city }}
                        </p>
                    @endif
                    @if($agency->description)
                        <p class="text-blue-100 text-lg">{{ $agency->description }}</p>
                    @endif
                </div>
                <div class="text-right">
                    @if($stats['average_rating'] > 0)
                        <div class="flex items-center justify-end mb-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= $stats['average_rating'] ? 'text-yellow-400' : 'text-blue-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-2xl font-bold">{{ $stats['average_rating'] }}</span>
                        </div>
                        <p class="text-blue-100">{{ $stats['total_reviews'] }} avis clients</p>
                    @else
                        <div class="text-blue-100">Aucun avis pour le moment</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Agency Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['total_cars'] }}</div>
                <div class="text-gray-600">Véhicules au total</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['available_cars'] }}</div>
                <div class="text-gray-600">Disponibles maintenant</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['completed_rentals'] }}</div>
                <div class="text-gray-600">Locations terminées</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-yellow-600 mb-2">{{ $stats['total_reviews'] }}</div>
                <div class="text-gray-600">Avis clients</div>
            </div>
        </div>

        <!-- Cars Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Véhicules Disponibles</h2>
                    <p class="text-gray-600">Classés par popularité (nombre de locations)</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">{{ $cars->total() }}</div>
                        <div class="text-sm text-gray-500">véhicules disponibles</div>
                    </div>
                </div>
            </div>

            <!-- Cars Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Filtrer les Véhicules</h3>
                        <button onclick="toggleCarFilters()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                            </svg>
                            Filtres
                        </button>
                    </div>

                    <form method="GET" action="{{ route('client.agencies.show', $agency) }}" class="space-y-4">
                        <!-- Main Search -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Rechercher par marque, modèle..."
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                        </div>

                        <!-- Filters Grid -->
                        <div id="carFiltersGrid" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                            <!-- Sort By -->
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                                <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularité</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Prix (bas)</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Prix (haut)</option>
                                    <option value="year_new" {{ request('sort') == 'year_new' ? 'selected' : '' }}>Année (récente)</option>
                                    <option value="year_old" {{ request('sort') == 'year_old' ? 'selected' : '' }}>Année (ancienne)</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                {{ $cars->total() }} véhicule(s) trouvé(s)
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('client.agencies.show', $agency) }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Réinitialiser
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Appliquer
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
                        
                        <!-- Popularity Badge -->
                        @if($car->rentals_count > 0)
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ $car->rentals_count }} location{{ $car->rentals_count > 1 ? 's' : '' }}
                            </span>
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
                <p class="text-gray-600 mb-6">Cette agence n'a pas de véhicules correspondant à vos critères.</p>
                <button onclick="clearCarFilters()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Effacer les filtres
                </button>
            </div>
            @endif
        </div>

        <!-- Recent Reviews -->
        @if($stats['recent_reviews']->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Avis Récents</h3>
            <div class="space-y-4">
                @foreach($stats['recent_reviews'] as $review)
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <h4 class="font-medium text-gray-900">{{ $review->client->user->name ?? 'Client anonyme' }}</h4>
                            <div class="flex items-center ml-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-600">{{ $review->comment }}</p>
                    <p class="text-sm text-gray-500 mt-1">Véhicule: {{ $review->rental->car->brand }} {{ $review->rental->car->model }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleCarFilters() {
    const filtersGrid = document.getElementById('carFiltersGrid');
    filtersGrid.classList.toggle('hidden');
}

function clearCarFilters() {
    // Reset all form inputs
    document.querySelector('input[name="search"]').value = '';
    document.querySelector('select[name="brand"]').value = '';
    document.querySelector('select[name="fuel_type"]').value = '';
    document.querySelector('input[name="max_price"]').value = '';
    document.querySelector('select[name="sort"]').value = 'popularity';
    
    // Submit the form
    document.querySelector('form').submit();
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('select[name="brand"], select[name="fuel_type"], input[name="max_price"], select[name="sort"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.querySelector('form').submit();
        });
    });
});
</script>
@endsection
