@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $maintenance->title }}</h1>
                    <p class="mt-2 text-gray-600">{{ $maintenance->car->brand }} {{ $maintenance->car->model }} {{ $maintenance->car->year }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.maintenances.edit', $maintenance) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('agence.fleet.maintenance') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Maintenance Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Détails de la Maintenance</h2>
                    
                    <!-- Status Badge -->
                    <div class="mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($maintenance->status == 'scheduled') bg-yellow-100 text-yellow-800
                            @elseif($maintenance->status == 'in_progress') bg-red-100 text-red-800
                            @elseif($maintenance->status == 'completed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($maintenance->status == 'scheduled') Programmé
                            @elseif($maintenance->status == 'in_progress') En cours
                            @elseif($maintenance->status == 'completed') Terminé
                            @else Annulé
                            @endif
                        </span>
                    </div>

                    <!-- Maintenance Details -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Type de maintenance</label>
                                <p class="text-sm text-gray-900">{{ $maintenance->type_label }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Coût</label>
                                <p class="text-sm text-gray-900">{{ number_format($maintenance->cost, 0, ',', ' ') }} DH</p>
                            </div>
                        </div>

                        @if($maintenance->description)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="text-sm text-gray-900">{{ $maintenance->description }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date programmée</label>
                                <p class="text-sm text-gray-900">{{ $maintenance->scheduled_date->format('d/m/Y') }}</p>
                            </div>
                            
                            @if($maintenance->start_date)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date de début</label>
                                <p class="text-sm text-gray-900">{{ $maintenance->start_date->format('d/m/Y') }}</p>
                            </div>
                            @endif
                            
                            @if($maintenance->end_date)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date de fin</label>
                                <p class="text-sm text-gray-900">{{ $maintenance->end_date->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>

                        @if($maintenance->mileage_at_service)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kilométrage au service</label>
                            <p class="text-sm text-gray-900">{{ number_format($maintenance->mileage_at_service, 0, ',', ' ') }} km</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Garage Information -->
                @if($maintenance->garage_name || $maintenance->garage_contact)
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations du Garage</h2>
                    
                    <div class="space-y-4">
                        @if($maintenance->garage_name)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nom du garage</label>
                            <p class="text-sm text-gray-900">{{ $maintenance->garage_name }}</p>
                        </div>
                        @endif
                        
                        @if($maintenance->garage_contact)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Contact</label>
                            <p class="text-sm text-gray-900">{{ $maintenance->garage_contact }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($maintenance->notes)
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Notes</h2>
                    <p class="text-sm text-gray-900">{{ $maintenance->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Vehicle Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Véhicule</h2>
                    
                    <!-- Vehicle Image -->
                    @if($maintenance->car->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $maintenance->car->image) }}" alt="{{ $maintenance->car->brand }} {{ $maintenance->car->model }}" class="w-full h-32 object-cover rounded-lg">
                    </div>
                    @endif

                    <!-- Vehicle Details -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $maintenance->car->brand }} {{ $maintenance->car->model }}</h3>
                            <p class="text-sm text-gray-500">{{ $maintenance->car->year }} • {{ $maintenance->car->registration_number }}</p>
                        </div>
                        
                        @if($maintenance->car->category)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Catégorie</label>
                            <p class="text-sm text-gray-900">{{ $maintenance->car->category->name }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-500">Prix par jour</label>
                            <p class="text-sm text-gray-900">{{ number_format($maintenance->car->price_per_day, 0, ',', ' ') }} DH</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($maintenance->car->status == 'available') bg-green-100 text-green-800
                                @elseif($maintenance->car->status == 'rented') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($maintenance->car->status == 'available') Disponible
                                @elseif($maintenance->car->status == 'rented') En location
                                @else Maintenance
                                @endif
                            </span>
                        </div>

                        @if($maintenance->car->mileage)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kilométrage actuel</label>
                            <p class="text-sm text-gray-900">{{ number_format($maintenance->car->mileage, 0, ',', ' ') }} km</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('agence.cars.show', $maintenance->car) }}" class="w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Voir le véhicule
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Actions Rapides</h2>
                    
                    <div class="space-y-3">
                        @if($maintenance->status == 'scheduled')
                        <form method="POST" action="{{ route('agence.maintenances.update-status', $maintenance) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                Démarrer la maintenance
                            </button>
                        </form>
                        @endif

                        @if($maintenance->status == 'in_progress')
                        <form method="POST" action="{{ route('agence.maintenances.update-status', $maintenance) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                Marquer comme terminé
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('agence.maintenances.edit', $maintenance) }}" class="w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors block">
                            Modifier la maintenance
                        </a>

                        <form method="POST" action="{{ route('agence.maintenances.destroy', $maintenance) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Supprimer la maintenance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
