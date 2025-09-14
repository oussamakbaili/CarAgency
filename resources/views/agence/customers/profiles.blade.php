@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profils des Clients</h1>
        <p class="text-gray-600">Consultez les profils détaillés de vos clients</p>
    </div>

    <!-- Customer Profiles Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Clients</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_customers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clients Actifs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_customers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clients Fidèles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['loyal_customers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Locations Totales</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_rentals'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('agence.customers.profiles') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher un client</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Tous les clients</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Clients actifs</option>
                    <option value="loyal" {{ request('status') == 'loyal' ? 'selected' : '' }}>Clients fidèles</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nouveaux clients</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tri</label>
                <select name="sort" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                    <option value="rentals" {{ request('sort') == 'rentals' ? 'selected' : '' }}>Nombre de locations</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Filtrer
                </button>
                <a href="{{ route('agence.customers.profiles') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 text-sm">
                    Effacer
                </a>
            </div>
        </form>
    </div>

    <!-- Customer Profiles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($customers as $customer)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
            <!-- Customer Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-xl font-bold text-white">
                                {{ substr($customer->user->name ?? 'N/A', 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $customer->user->name ?? 'N/A' }}</h3>
                        <p class="text-sm text-gray-500">{{ $customer->user->email ?? 'N/A' }}</p>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $customer->rentals->count() }} location{{ $customer->rentals->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $customer->phone ?? 'Non renseigné' }}</span>
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $customer->cin ?? 'Non renseigné' }}</span>
                    </div>
                    
                    @if($customer->birthday)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $customer->birthday->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    
                    @if($customer->address)
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $customer->address }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Rentals -->
            <div class="px-6 pb-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Locations récentes</h4>
                <div class="space-y-2">
                    @forelse($customer->rentals->take(3) as $rental)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <span class="font-medium text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                            <span class="text-gray-500 ml-2">{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</span>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($rental->status == 'completed') bg-green-100 text-green-800
                            @elseif($rental->status == 'active') bg-blue-100 text-blue-800
                            @elseif($rental->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($rental->status == 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Aucune location récente</p>
                    @endforelse
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 pb-6 border-t border-gray-200 pt-4">
                <div class="flex space-x-2">
                    <button onclick="showCustomerDetails({{ $customer->id }})" class="flex-1 px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100">
                        Voir le profil
                    </button>
                    <a href="{{ route('agence.bookings.history') }}?client={{ $customer->user->email ?? '' }}" class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-md hover:bg-gray-100 text-center">
                        Historique
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun client trouvé</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des clients à votre agence.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="mt-6">
        {{ $customers->links() }}
    </div>
    @endif
</div>

<!-- Customer Details Modal -->
<div id="customerDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails du Client</h3>
                <button onclick="closeCustomerDetails()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div id="customerDetailsContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
            
            <div class="flex justify-end mt-6">
                <button onclick="closeCustomerDetails()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showCustomerDetails(customerId) {
    document.getElementById('customerDetailsContent').innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Chargement...</p></div>';
    document.getElementById('customerDetailsModal').classList.remove('hidden');
    
    // For now, we'll show a simple message. In a real implementation, you'd fetch customer details via AJAX
    setTimeout(() => {
        document.getElementById('customerDetailsContent').innerHTML = `
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-medium text-blue-900">Informations du Client</h4>
                    <p class="text-blue-700">ID: ${customerId}</p>
                    <p class="text-blue-700">Détails complets du client seront affichés ici.</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900">Actions Disponibles</h4>
                    <div class="mt-2 space-x-2">
                        <a href="${window.location.origin}/agence/bookings/history?client=${customerId}" class="inline-block px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Voir l'historique
                        </a>
                        <a href="${window.location.origin}/agence/customers" class="inline-block px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

function closeCustomerDetails() {
    document.getElementById('customerDetailsModal').classList.add('hidden');
}
</script>
@endsection
