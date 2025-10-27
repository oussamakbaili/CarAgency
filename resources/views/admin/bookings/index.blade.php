@extends('layouts.admin')

@section('header', 'Gestion des Réservations')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Réservations</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Actives</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statistics['active'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terminées</p>
                <p class="text-2xl font-bold text-gray-900">{{ $statistics['completed'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenus Mensuels</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['monthlyRevenue'], 0, ',', ' ') }} MAD</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white border border-gray-200 rounded-lg p-5 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher réservation..." 
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
        </div>
        <div>
            <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminée</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>
        <div>
            <select name="agency_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                <option value="">Toutes les agences</option>
                @foreach($agencies as $agency)
                <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>
                    {{ $agency->agency_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="jj/mm/aaaa"
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Rechercher
            </button>
            <a href="{{ route('admin.bookings.index') }}" class="flex-1 bg-gray-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors text-center">
                Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-base font-semibold text-gray-900">Liste des Réservations</h3>
        <div>
            <a href="{{ route('admin.bookings.calendar') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Vue Calendrier
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Réservation
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Véhicule
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Agence
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Période
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Prix
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                        →
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" 
                    onclick="window.location.href='{{ route('admin.bookings.show', $booking) }}'">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">#{{ $booking->id }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            @if($booking->user->client)
                                <a href="{{ route('admin.customers.show', $booking->user->client) }}" 
                                   class="text-[#C2410C] hover:text-[#9A3412] font-semibold hover:underline transition-colors duration-200"
                                   onclick="event.stopPropagation();">
                                    {{ $booking->user->name }}
                                </a>
                            @else
                                <span class="text-gray-900 font-semibold">{{ $booking->user->name }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->car->year }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->agency->agency_name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->agency->city ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->start_date->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">au {{ $booking->end_date->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} MAD
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $booking->status === 'active' ? 'bg-green-100 text-green-700' : 
                               ($booking->status === 'completed' ? 'bg-blue-100 text-blue-700' : 
                               ($booking->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $booking->status === 'active' ? 'Active' : 
                               ($booking->status === 'completed' ? 'Terminée' : 
                               ($booking->status === 'pending' ? 'En attente' : 'Annulée')) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm text-gray-500 font-medium">Aucune réservation trouvée</p>
                            <p class="text-xs text-gray-400 mt-1">Essayez de modifier vos filtres</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $bookings->links() }}
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le Statut</h3>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nouveau Statut
                    </label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">En attente</option>
                        <option value="active">Active</option>
                        <option value="completed">Terminée</option>
                        <option value="rejected">Annulée</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (optionnel)
                    </label>
                    <textarea name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Ajoutez des notes sur le changement de statut..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(bookingId, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `{{ route('admin.bookings.update-status', '') }}/${bookingId}`;
    
    // Set current status as selected
    const statusSelect = form.querySelector('select[name="status"]');
    statusSelect.value = currentStatus;
    
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
}
</script>
@endsection
