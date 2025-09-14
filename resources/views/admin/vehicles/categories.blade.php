@extends('layouts.admin')

@section('header', 'Catégories de Véhicules')

@section('content')
<!-- Categories Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Car Categories -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par Marque</h3>
        <div class="space-y-4">
            @forelse($categories as $category)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $category->brand }}</h4>
                        <p class="text-sm text-gray-500">{{ $category->total_cars }} véhicules</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ number_format($category->avg_price, 0, ',', ' ') }} MAD</p>
                    <p class="text-sm text-gray-500">Prix moyen/jour</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-gray-500">Aucune catégorie trouvée.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Fuel Types -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Types de Carburant</h3>
        <div class="space-y-3">
            @forelse($fuelTypes as $fuelType)
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-green-400 mr-3"></div>
                    <span class="text-sm text-gray-900">{{ ucfirst($fuelType->fuel_type) }}</span>
                </div>
                <span class="text-sm font-medium text-gray-900">{{ $fuelType->count }}</span>
            </div>
            @empty
            <p class="text-gray-500 text-sm">Aucune donnée disponible.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Year Distribution -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par Année</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($yearDistribution as $year)
        <div class="text-center p-3 bg-gray-50 rounded-lg">
            <div class="text-lg font-semibold text-gray-900">{{ $year->year }}</div>
            <div class="text-sm text-gray-500">{{ $year->count }} véhicules</div>
        </div>
        @empty
        <div class="col-span-full text-center py-8">
            <p class="text-gray-500">Aucune donnée disponible.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Price Range Analysis -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Analyse des Prix</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">
                {{ number_format(\App\Models\Car::min('price_per_day'), 0, ',', ' ') }} MAD
            </div>
            <div class="text-sm text-blue-600">Prix minimum/jour</div>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="text-2xl font-bold text-green-600">
                {{ number_format(\App\Models\Car::avg('price_per_day'), 0, ',', ' ') }} MAD
            </div>
            <div class="text-sm text-green-600">Prix moyen/jour</div>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">
                {{ number_format(\App\Models\Car::max('price_per_day'), 0, ',', ' ') }} MAD
            </div>
            <div class="text-sm text-purple-600">Prix maximum/jour</div>
        </div>
    </div>
</div>
@endsection
