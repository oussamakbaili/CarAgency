@extends('layouts.admin')

@section('header', 'Gestion des Clients')

@section('content')
<!-- Header with Export Button -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6 reveal-section">
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Tous les Clients</h2>
        <p class="text-xs sm:text-sm text-gray-600 mt-1">Gérez et consultez tous les clients de la plateforme</p>
    </div>
    <a href="{{ route('admin.customers.export') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium text-xs sm:text-sm transition duration-200">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exporter CSV
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6 reveal-section">
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-blue-50">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-xs sm:text-sm font-medium text-gray-600">Total Clients</h2>
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
                    <h2 class="text-xs sm:text-sm font-medium text-gray-600">Avec Réservations</h2>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['withBookings'] }}</p>
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
                    <h2 class="text-xs sm:text-sm font-medium text-gray-600">Nouveaux ce Mois</h2>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['newThisMonth'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-purple-50">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-xs sm:text-sm font-medium text-gray-600">Réservations Actives</h2>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['activeBookings'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg p-4 sm:p-6 mb-4 sm:mb-6 reveal-section">
    <form method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom, CIN, email..." 
                       class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input type="text" name="city" value="{{ request('city') }}" 
                       placeholder="Ville" 
                       class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Date de début</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Réservations</label>
                <select name="has_bookings" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tous les clients</option>
                    <option value="yes" {{ request('has_bookings') === 'yes' ? 'selected' : '' }}>Avec réservations</option>
                    <option value="no" {{ request('has_bookings') === 'no' ? 'selected' : '' }}>Sans réservations</option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg hover:bg-blue-700 text-xs sm:text-sm font-medium transition-colors">
                    Rechercher
                </button>
                <a href="{{ route('admin.customers.index') }}" class="flex-1 text-center bg-gray-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg hover:bg-gray-700 text-xs sm:text-sm font-medium transition-colors">
                    Réinitialiser
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Customer Profiles Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 reveal-section">
    @forelse($customers as $customer)
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4 sm:p-6">
            <!-- Customer Avatar and Basic Info -->
            <div class="flex items-center mb-3 sm:mb-4">
                <div class="flex-shrink-0 h-12 w-12 sm:h-16 sm:w-16">
                    <div class="h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 truncate">{{ $customer->name }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $customer->user->email }}</p>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $customer->phone }}</p>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="space-y-1.5 sm:space-y-2 mb-3 sm:mb-4">
                <div class="flex justify-between text-xs sm:text-sm">
                    <span class="text-gray-500">CIN:</span>
                    <span class="text-gray-900 font-medium">{{ $customer->cin }}</span>
                </div>
                <div class="flex justify-between text-xs sm:text-sm">
                    <span class="text-gray-500">Date de naissance:</span>
                    <span class="text-gray-900 font-medium">{{ $customer->birthday ? $customer->birthday->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-xs sm:text-sm">
                    <span class="text-gray-500">Adresse:</span>
                    <span class="text-gray-900 font-medium truncate ml-2">{{ $customer->address }}</span>
                </div>
                <div class="flex justify-between text-xs sm:text-sm">
                    <span class="text-gray-500">Membre depuis:</span>
                    <span class="text-gray-900 font-medium">{{ $customer->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="bg-gray-50 rounded-lg p-2.5 sm:p-3 mb-3 sm:mb-4">
                <h4 class="text-xs sm:text-sm font-medium text-gray-900 mb-1.5 sm:mb-2">Statistiques</h4>
                <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Réservations:</span>
                        <span class="text-gray-900 font-medium">{{ $customer->rentals->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Actives:</span>
                        <span class="text-gray-900 font-medium">{{ $customer->rentals->where('status', 'active')->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            @if($customer->rentals->count() > 0)
            <div class="mb-3 sm:mb-4">
                <h4 class="text-xs sm:text-sm font-medium text-gray-900 mb-1.5 sm:mb-2">Dernières réservations</h4>
                <div class="space-y-1">
                    @foreach($customer->rentals->take(2) as $rental)
                    <div class="text-xs text-gray-600">
                        <span class="truncate block">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium mt-0.5
                            {{ $rental->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($rental->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $rental->status === 'active' ? 'Active' : 
                               ($rental->status === 'completed' ? 'Terminée' : 'En attente') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.customers.show', $customer) }}" 
                   class="flex-1 bg-blue-600 text-white text-center px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-700 text-xs sm:text-sm font-medium transition-colors">
                    Voir Profil
                </a>
                <a href="{{ route('admin.customers.booking-history', $customer) }}" 
                   class="flex-1 bg-green-600 text-white text-center px-3 sm:px-4 py-2 rounded-lg hover:bg-green-700 text-xs sm:text-sm font-medium transition-colors">
                    Historique
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-8 sm:py-12 reveal-section">
        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
        </svg>
        <h3 class="mt-2 text-base sm:text-lg font-medium text-gray-900">Aucun client trouvé</h3>
        <p class="mt-1 text-sm text-gray-500">Aucun client ne correspond à vos critères de recherche.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-4 sm:mt-6">
    {{ $customers->links() }}
</div>
@endsection
