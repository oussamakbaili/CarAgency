@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Historique des Paiements</h1>
        <p class="text-gray-600">Suivez les paiements reçus de la plateforme</p>
    </div>

    <!-- Payout Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Paiements Reçus</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['completed_payouts'], 0, ',', ' ') }} DH</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">En Attente</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['pending_payouts'], 0, ',', ' ') }} DH</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Paiements</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_payouts'] }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Ce Mois</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['this_month'], 0, ',', ' ') }} DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Schedule -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations des Paiements</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Prochain Paiement</p>
                        <p class="text-lg font-semibold text-blue-900">
                            @if($payouts->where('status', 'pending')->count() > 0)
                                En attente d'approbation
                            @else
                                Aucun en attente
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-green-900">Dernier Paiement</p>
                        <p class="text-lg font-semibold text-green-900">
                            @if($lastPayout)
                                {{ $lastPayout->created_at->format('d/m/Y') }}
                            @else
                                Aucun paiement
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-900">Statut</p>
                        <p class="text-lg font-semibold text-yellow-900">
                            @if($stats['pending_payouts'] > 0)
                                {{ $payouts->where('status', 'pending')->count() }} en attente
                            @else
                                À jour
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('agence.finance.payouts') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échec</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select name="period" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Toutes les périodes</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
                <select name="method" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Toutes les méthodes</option>
                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                    <option value="check" {{ request('method') == 'check' ? 'selected' : '' }}>Chèque</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                </select>
            </div>
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ID paiement, référence..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Filtrer
                </button>
                <a href="{{ route('agence.finance.payouts') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                    Effacer
                </a>
            </div>
        </form>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Historique des Paiements</h3>
            <div class="flex space-x-2">
                <a href="{{ route('agence.finance.export-payouts', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exporter CSV
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payouts as $payout)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $payout->id ?? '000' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($payout->amount ?? 0, 0, ',', ' ') }} MAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                @php
                                    $method = $payout->metadata['payment_method'] ?? 'bank_transfer';
                                @endphp
                                @if($method == 'bank_transfer')
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Virement bancaire
                                @elseif($method == 'check')
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Chèque
                                @elseif($method == 'cash')
                                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Espèces
                                @else
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if(($payout->status ?? '') == 'completed') bg-green-100 text-green-800
                                @elseif(($payout->status ?? '') == 'pending') bg-yellow-100 text-yellow-800
                                @elseif(($payout->status ?? '') == 'scheduled') bg-blue-100 text-blue-800
                                @elseif(($payout->status ?? '') == 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($payout->status ?? 'Inconnu') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payout->created_at->format('d/m/Y H:i') ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payout->id ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showPayoutDetails({{ $payout->id }})" class="text-blue-600 hover:text-blue-900">Voir</button>
                                <a href="{{ route('agence.finance.export-payouts', array_merge(request()->query(), ['single' => $payout->id])) }}" class="text-gray-600 hover:text-gray-900">Télécharger</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun paiement trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payouts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payouts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Payout Details Modal -->
<div id="payoutDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails du Paiement</h3>
                <button onclick="closePayoutDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="payoutDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showPayoutDetails(payoutId) {
    fetch(`/agence/finance/payouts/${payoutId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const content = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">ID Paiement</label>
                                <p class="text-sm text-gray-900">#${data.data.id}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Montant</label>
                                <p class="text-sm text-gray-900 font-semibold">${data.data.amount} DH</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Méthode</label>
                                <p class="text-sm text-gray-900">${data.data.method}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Statut</label>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    ${data.data.status === 'Complété' ? 'bg-green-100 text-green-800' :
                                      data.data.status === 'En attente' ? 'bg-yellow-100 text-yellow-800' :
                                      'bg-red-100 text-red-800'}">
                                    ${data.data.status}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Description</label>
                            <p class="text-sm text-gray-900">${data.data.description}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date de Création</label>
                                <p class="text-sm text-gray-900">${data.data.created_at}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date de Traitement</label>
                                <p class="text-sm text-gray-900">${data.data.processed_at}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Solde Avant</label>
                                <p class="text-sm text-gray-900">${data.data.balance_before} DH</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Solde Après</label>
                                <p class="text-sm text-gray-900">${data.data.balance_after} DH</p>
                            </div>
                        </div>
                        
                        ${data.data.notes !== 'N/A' ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Notes</label>
                            <p class="text-sm text-gray-900">${data.data.notes}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('payoutDetailsContent').innerHTML = content;
                document.getElementById('payoutDetailsModal').classList.remove('hidden');
            } else {
                alert('Erreur lors du chargement des détails: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Error fetching payout details:', error);
            alert('Erreur lors du chargement des détails du paiement');
        });
}

function closePayoutDetailsModal() {
    document.getElementById('payoutDetailsModal').classList.add('hidden');
    document.getElementById('payoutDetailsContent').innerHTML = '';
}
</script>
@endsection
