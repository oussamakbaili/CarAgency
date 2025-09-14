@extends('layouts.admin')

@section('header', 'Profils des Clients')

@section('content')
<!-- Search -->
<div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par nom, email..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Rechercher
        </button>
        <a href="{{ route('admin.customers.profiles') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Réinitialiser
        </a>
    </form>
</div>

<!-- Customer Profiles Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($customers as $customer)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <!-- Customer Avatar and Basic Info -->
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ $customer->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $customer->user->email }}</p>
                    <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">CIN:</span>
                    <span class="text-gray-900">{{ $customer->cin }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Date de naissance:</span>
                    <span class="text-gray-900">{{ $customer->birthday ? $customer->birthday->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Adresse:</span>
                    <span class="text-gray-900">{{ $customer->address }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Membre depuis:</span>
                    <span class="text-gray-900">{{ $customer->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Statistiques</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
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
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Dernières réservations</h4>
                <div class="space-y-1">
                    @foreach($customer->rentals->take(2) as $rental)
                    <div class="text-xs text-gray-600">
                        {{ $rental->car->brand }} {{ $rental->car->model }} - 
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium
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
            <div class="flex space-x-2">
                <a href="{{ route('admin.customers.show', $customer) }}" 
                   class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-md hover:bg-blue-700 text-sm">
                    Voir Profil
                </a>
                <a href="{{ route('admin.customers.booking-history', $customer) }}" 
                   class="flex-1 bg-green-600 text-white text-center px-3 py-2 rounded-md hover:bg-green-700 text-sm">
                    Historique
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun client trouvé</h3>
        <p class="mt-1 text-sm text-gray-500">Aucun client ne correspond à vos critères de recherche.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $customers->links() }}
</div>
@endsection
