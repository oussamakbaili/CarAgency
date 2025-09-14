@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                    <p class="mt-2 text-gray-600">{{ $category->description ?? 'Véhicules de cette catégorie' }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.categories.edit', $category) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('agence.fleet.categories') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Category Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de la catégorie</h2>
                    
                    <!-- Category Icon and Color -->
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center" style="background-color: {{ $category->color }}20; border: 2px solid {{ $category->color }}">
                            <svg class="w-8 h-8" style="color: {{ $category->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Category Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="text-sm text-gray-900">{{ $category->description ?? 'Aucune description' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Ordre d'affichage</label>
                            <p class="text-sm text-gray-900">{{ $category->sort_order }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Date de création</label>
                            <p class="text-sm text-gray-900">{{ $category->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-500">Dernière modification</label>
                            <p class="text-sm text-gray-900">{{ $category->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Statistiques</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Total des véhicules</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $category->cars_count }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Véhicules disponibles</span>
                            <span class="text-lg font-semibold text-green-600">{{ $cars->where('status', 'available')->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Véhicules en location</span>
                            <span class="text-lg font-semibold text-orange-600">{{ $cars->where('status', 'rented')->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">En maintenance</span>
                            <span class="text-lg font-semibold text-red-600">{{ $cars->where('status', 'maintenance')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicles List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Véhicules de cette catégorie</h2>
                    </div>
                    
                    @if($cars->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($cars as $car)
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($car->image)
                                                <img class="h-16 w-16 rounded-lg object-cover" src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}">
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                                            <p class="text-sm text-gray-500">{{ $car->registration_number }} • {{ $car->year }}</p>
                                            <p class="text-sm text-gray-500">{{ number_format($car->price_per_day, 0, ',', ' ') }} MAD/jour</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($car->status == 'available') bg-green-100 text-green-800
                                            @elseif($car->status == 'rented') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            @if($car->status == 'available') Disponible
                                            @elseif($car->status == 'rented') En location
                                            @else Maintenance
                                            @endif
                                        </span>
                                        <a href="{{ route('agence.cars.show', $car) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun véhicule</h3>
                            <p class="mt-1 text-sm text-gray-500">Cette catégorie ne contient aucun véhicule pour le moment.</p>
                            <div class="mt-6">
                                <a href="{{ route('agence.cars.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Ajouter un véhicule
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
