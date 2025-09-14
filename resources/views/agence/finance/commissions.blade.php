@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Rapports de Commissions</h1>
        <p class="text-gray-600">Suivez les commissions et frais de plateforme</p>
    </div>

    <!-- Commission Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Commission Totale</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($commissions->sum('amount'), 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenus Nets</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($commissions->where('type', 'revenue')->sum('amount'), 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Frais Plateforme</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($commissions->where('type', 'fee')->sum('amount'), 0, ',', ' ') }} MAD</p>
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
                    <p class="text-sm font-medium text-gray-600">Taux Moyen</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($commissions->avg('rate'), 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des Commissions</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <p class="text-gray-500">Graphique des commissions (à implémenter)</p>
        </div>
    </div>

    <!-- Commission Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('agence.finance.commissions') }}" id="filterForm">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les types</option>
                        <option value="revenue" {{ request('type') == 'revenue' ? 'selected' : '' }}>Revenus</option>
                        <option value="fee" {{ request('type') == 'fee' ? 'selected' : '' }}>Frais</option>
                        <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>Commission</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
                    <select name="amount_range" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les montants</option>
                        <option value="0-100" {{ request('amount_range') == '0-100' ? 'selected' : '' }}>0 - 100 MAD</option>
                        <option value="100-500" {{ request('amount_range') == '100-500' ? 'selected' : '' }}>100 - 500 MAD</option>
                        <option value="500-1000" {{ request('amount_range') == '500-1000' ? 'selected' : '' }}>500 - 1000 MAD</option>
                        <option value="1000+" {{ request('amount_range') == '1000+' ? 'selected' : '' }}>1000+ MAD</option>
                    </select>
                </div>
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ID transaction, description..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Filtrer
                    </button>
                    <a href="{{ route('agence.finance.commissions') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Effacer
                    </a>
                    <a href="{{ route('agence.finance.export-commissions', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                        Exporter
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Commissions Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Détail des Commissions</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commissions as $commission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $commission->id ?? '000' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if(($commission->type ?? '') == 'revenue') bg-green-100 text-green-800
                                @elseif(($commission->type ?? '') == 'fee') bg-red-100 text-red-800
                                @elseif(($commission->type ?? '') == 'commission') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($commission->type ?? 'Inconnu') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $commission->description ?? 'Description de la commission' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                            @if(($commission->type ?? '') == 'revenue') text-green-600
                            @elseif(($commission->type ?? '') == 'fee') text-red-600
                            @else text-gray-900 @endif">
                            {{ number_format($commission->amount ?? 0, 0, ',', ' ') }} MAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($commission->rate ?? 0, 1) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $commission->created_at->format('d/m/Y H:i') ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showCommissionDetails({{ $commission->id }})" class="text-blue-600 hover:text-blue-900">Voir</button>
                                <button onclick="downloadCommissionReport({{ $commission->id }})" class="text-gray-600 hover:text-gray-900">Rapport</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune commission trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($commissions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Commission Details Modal -->
<div id="commissionDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails de la Commission</h3>
                <button onclick="closeCommissionDetails()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="commissionDetailsContent" class="space-y-4">
                <!-- Commission details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showCommissionDetails(commissionId) {
    fetch(`/agence/finance/commissions/${commissionId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('commissionDetailsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Informations de Commission</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>ID Commission:</strong> #${data.id}</p>
                            <p><strong>Type:</strong> <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.type_color}">${data.type_label}</span></p>
                            <p><strong>Montant:</strong> ${data.amount} MAD</p>
                            <p><strong>Taux:</strong> ${data.rate}%</p>
                            <p><strong>Date:</strong> ${data.created_at}</p>
                            ${data.processed_at ? `<p><strong>Traité le:</strong> ${data.processed_at}</p>` : ''}
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Détails Financiers</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>Montant Brut:</strong> ${data.gross_amount} MAD</p>
                            <p><strong>Commission Plateforme:</strong> ${data.platform_fee} MAD</p>
                            <p><strong>Montant Net:</strong> ${data.net_amount} MAD</p>
                            <p><strong>Statut:</strong> <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.status_color}">${data.status}</span></p>
                            ${data.rental_id ? `<p><strong>Réservation:</strong> #${data.rental_id}</p>` : ''}
                        </div>
                    </div>
                </div>
                ${data.description ? `
                <div class="mt-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Description</h4>
                    <p class="text-sm text-gray-600">${data.description}</p>
                </div>
                ` : ''}
                ${data.breakdown ? `
                <div class="mt-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Répartition des Frais</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-2 text-sm">
                            ${data.breakdown.map(item => `
                                <div class="flex justify-between">
                                    <span>${item.label}:</span>
                                    <span class="font-medium">${item.amount} MAD</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
                ` : ''}
            `;
            document.getElementById('commissionDetailsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading commission details:', error);
            alert('Erreur lors du chargement des détails de la commission');
        });
}

function closeCommissionDetails() {
    document.getElementById('commissionDetailsModal').classList.add('hidden');
}

function downloadCommissionReport(commissionId) {
    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Génération...';
    button.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        window.open(`/agence/finance/commissions/${commissionId}/report`, '_blank');
        button.textContent = originalText;
        button.disabled = false;
    }, 1500);
}
</script>
@endsection
