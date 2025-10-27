@extends('layouts.agence')

@section('content')
<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Toutes les Réservations</h1>
                    <p class="mt-2 text-gray-600">Gérez toutes les réservations de votre flotte</p>
                </div>
                <div class="flex space-x-3">
                    <form method="GET" class="flex space-x-3">
                        <!-- Dropdown Filter -->
                        <div class="relative inline-block min-w-[240px]">
                            <select name="status" onchange="this.form.submit()" class="appearance-none w-full pl-4 pr-10 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer">
                                <option value="">Toutes les réservations</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminées</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulées</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetées</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Export Button -->
                        <a href="{{ route('agence.bookings.index', array_merge(request()->query(), ['export' => 'csv'])) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-[#C2410C] hover:bg-[#9A3412] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Exporter
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $totalRentals = $rentals->total();
                $pendingCount = \App\Models\Rental::where('rentals.agency_id', auth()->user()->agency->id)->where('status', 'pending')->count();
                $activeCount = \App\Models\Rental::where('rentals.agency_id', auth()->user()->agency->id)->where('status', 'active')->count();
                $monthlyRevenue = \App\Models\Rental::where('rentals.agency_id', auth()->user()->agency->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_price');
            @endphp
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Réservations</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalRentals }}</p>
                            <p class="text-xs text-gray-500">Toutes confondues</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En Attente</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                            <p class="text-xs text-yellow-600">Nécessitent une action</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Actives</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $activeCount }}</p>
                            <p class="text-xs text-gray-500">En cours</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Revenus du Mois</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($monthlyRevenue, 0) }} DH</p>
                            <p class="text-xs text-gray-500">Ce mois-ci</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Toutes les Réservations</h3>
                    <div class="text-sm text-gray-500">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Cliquez sur une ligne pour voir les détails
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rentals as $rental)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="handleRowClick(event, {{ $rental->id }})">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @php
                                            $statusColors = [
                                                'pending' => 'yellow',
                                                'active' => 'blue',
                                                'completed' => 'green',
                                                'cancelled' => 'red',
                                                'rejected' => 'gray'
                                            ];
                                            $color = $statusColors[$rental->status] ?? 'gray';
                                        @endphp
                                        <div class="h-10 w-10 rounded-full bg-{{ $color }}-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-{{ $color }}-600">#{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Réservation #{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($rental->created_at)->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $rental->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $rental->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }} {{ $rental->car->year }}</div>
                                <div class="text-sm text-gray-500">{{ $rental->car->color ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rental->start_date && $rental->end_date)
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($rental->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($rental->end_date)->format('d M') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) }} jours
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">N/A</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($rental->total_price, 0) }} DH</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'rejected' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'active' => 'Active',
                                        'completed' => 'Terminée',
                                        'cancelled' => 'Annulée',
                                        'rejected' => 'Rejetée'
                                    ];
                                    $class = $statusClasses[$rental->status] ?? 'bg-gray-100 text-gray-800';
                                    $label = $statusLabels[$rental->status] ?? ucfirst($rental->status);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2" onclick="event.stopPropagation()">
                                    @if($rental->status === 'pending')
                                        <form method="POST" action="{{ route('agence.rentals.approve', $rental) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-xs font-medium transition duration-200">
                                                Approuver
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('agence.rentals.reject', $rental) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition duration-200">
                                                Rejeter
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('agence.rentals.show', $rental) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium transition duration-200">
                                            Voir
                                        </a>
                                        @if($rental->status === 'completed')
                                            <a href="{{ route('agence.rentals.invoice', $rental) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium transition duration-200">
                                                Facture
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">Aucune réservation trouvée</p>
                                    <p class="text-gray-500">Il n'y a actuellement aucune réservation correspondant à vos critères.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($rentals->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($rentals->previousPageUrl())
                        <a href="{{ $rentals->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Précédent
                        </a>
                    @endif
                    @if($rentals->nextPageUrl())
                        <a href="{{ $rentals->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Suivant
                        </a>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">{{ $rentals->firstItem() }}</span> à <span class="font-medium">{{ $rentals->lastItem() }}</span> sur <span class="font-medium">{{ $rentals->total() }}</span> résultats
                        </p>
                    </div>
                    <div>
                        {{ $rentals->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleRowClick(event, rentalId) {
    // Empêche le clic si l'utilisateur clique sur un bouton ou un lien
    if (event.target.tagName === 'BUTTON' || event.target.tagName === 'A' || event.target.closest('button') || event.target.closest('a')) {
        return;
    }
    
    // Redirige vers la page de détails de la réservation
    window.location.href = `/agence/rentals/${rentalId}`;
}

// Améliorer l'expérience utilisateur avec des effets visuels
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un effet de survol plus prononcé
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(2px)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Ajouter des animations aux boutons
    const buttons = document.querySelectorAll('button, .bg-blue-600, .bg-green-600, .bg-red-600, .bg-gray-600');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush

@endsection