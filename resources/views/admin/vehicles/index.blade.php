@extends('layouts.admin')

@section('header', 'Gestion des Véhicules')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 reveal-section">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-blue-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Véhicules</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-green-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Disponibles</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['available'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-purple-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">En Location</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['rented'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-yellow-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">En Maintenance</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['maintenance'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg reveal-section">
        <div class="p-4 sm:p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher véhicule..." 
                           class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                    <select name="status" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les statuts</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>En location</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div>
                    <select name="agency_id" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Toutes les agences</option>
                    @foreach($agencies as $agency)
                    <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>
                        {{ $agency->agency_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" name="brand" value="{{ request('brand') }}" 
                       placeholder="Marque" 
                           class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Rechercher
                </button>
                    <a href="{{ route('admin.vehicles.index') }}" class="flex-1 text-center bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg reveal-section">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900">Liste des Véhicules</h3>
            <a href="{{ route('admin.vehicles.export') }}" class="inline-flex items-center gap-1.5 sm:gap-2 bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter CSV
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Véhicule
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Agence
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix/jour
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                                    @if($vehicle->image)
                                    <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-cover border border-gray-200" src="{{ Storage::url($vehicle->image) }}" alt="{{ $vehicle->brand }}">
                                    @else
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-semibold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                    <div class="text-xs text-gray-500">{{ $vehicle->year }} • {{ $vehicle->registration_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $vehicle->agency->agency_name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $vehicle->agency->city ?? '' }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-semibold text-gray-900">
                            {{ number_format($vehicle->price_per_day, 0, ',', ' ') }} MAD
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-700' : 
                                   ($vehicle->status === 'rented' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                                {{ $vehicle->status === 'available' ? 'Disponible' : 
                                   ($vehicle->status === 'rented' ? 'En location' : 'Maintenance') }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                            @if($vehicle->track_stock)
                                {{ $vehicle->available_stock }}/{{ $vehicle->stock_quantity }}
                            @else
                                <span class="text-gray-500">Illimité</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium">
                            <a href="{{ route('admin.vehicles.show', $vehicle) }}" 
                               class="text-blue-600 hover:text-blue-700 font-medium">Voir Détails</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-gray-900 mb-1">Aucun véhicule trouvé</p>
                                <p class="text-xs sm:text-sm text-gray-500">Essayez de modifier vos filtres de recherche</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($vehicles->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
