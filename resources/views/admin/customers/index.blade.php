@extends('layouts.admin')

@section('header', 'Gestion des Clients')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Total Clients</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Avec Réservations</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['withBookings'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Nouveaux ce Mois</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['newThisMonth'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Réservations Actives</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistics['activeBookings'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par nom, CIN, email..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <input type="text" name="city" value="{{ request('city') }}" 
                   placeholder="Ville" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <select name="has_bookings" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les clients</option>
                <option value="yes" {{ request('has_bookings') === 'yes' ? 'selected' : '' }}>Avec réservations</option>
                <option value="no" {{ request('has_bookings') === 'no' ? 'selected' : '' }}>Sans réservations</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Rechercher
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Customers Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">Liste des Clients</h3>
        <a href="{{ route('admin.customers.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
            Exporter CSV
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        CIN
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date d'inscription
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Réservations
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->address }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $customer->user->email }}</div>
                        <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $customer->cin }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $customer->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->rentals->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $customer->rentals->count() }} réservation{{ $customer->rentals->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.customers.show', $customer) }}" 
                               class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('admin.customers.booking-history', $customer) }}" 
                               class="text-green-600 hover:text-green-900">Historique</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Aucun client trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $customers->links() }}
</div>
@endsection
