@extends('layouts.agence')

@section('content')
<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion Financière</h1>
                    <p class="mt-2 text-gray-600">Suivez vos revenus, commissions et paiements</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.finance.export') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exporter
                    </a>
                    <button onclick="showPaymentRequestModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Demander Paiement
                    </button>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Solde Actuel</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($overview['current_balance'] ?? 0, 0) }} DH</p>
                            <p class="text-xs text-green-600">Disponible</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Revenus du Mois</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($overview['monthly_revenue'] ?? 0, 0) }} DH</p>
                            @php
                                $rentalsCount = \App\Models\Rental::where('rentals.agency_id', auth()->user()->agency->id)
                                    ->whereIn('rentals.status', ['active', 'completed'])
                                    ->whereMonth('rentals.created_at', \Carbon\Carbon::now()->month)
                                    ->whereYear('rentals.created_at', \Carbon\Carbon::now()->year)
                                    ->count();
                            @endphp
                            <p class="text-xs text-gray-500">
                                {{ $rentalsCount }} location(s) ce mois
                            </p>
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
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($overview['pending_earnings'] ?? 0, 0) }} DH</p>
                            <p class="text-xs text-yellow-600">En cours de traitement</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Taux de Commission</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $overview['commission_rate'] ?? 0 }}%</p>
                            <p class="text-xs text-gray-500">Plateforme</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Trends Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des Revenus</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Méthodes de Paiement</h3>
                    <div class="h-64">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Transactions Récentes</h3>
                        <p class="text-sm text-gray-500 mt-1">Historique de vos dernières transactions financières</p>
                    </div>
                    <a href="{{ route('agence.finance.payments') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        Voir tout
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions ?? [] as $transaction)
                        @php
                            // Déterminer si c'est un revenu ou une dépense
                            $isIncome = in_array($transaction->type, [
                                \App\Models\Transaction::TYPE_RENTAL_PAYMENT,
                                \App\Models\Transaction::TYPE_AGENCY_COMMISSION
                            ]);
                            
                            // Labels des types
                            $typeLabels = [
                                \App\Models\Transaction::TYPE_RENTAL_PAYMENT => 'Paiement Location',
                                \App\Models\Transaction::TYPE_WITHDRAWAL => 'Retrait',
                                \App\Models\Transaction::TYPE_WITHDRAWAL_REQUEST => 'Demande Retrait',
                                \App\Models\Transaction::TYPE_REFUND => 'Remboursement',
                                \App\Models\Transaction::TYPE_COMMISSION => 'Commission',
                                \App\Models\Transaction::TYPE_ADMIN_COMMISSION => 'Commission Admin',
                                \App\Models\Transaction::TYPE_AGENCY_COMMISSION => 'Commission Agence',
                                \App\Models\Transaction::TYPE_PENALTY => 'Pénalité',
                            ];
                            
                            $typeLabel = $typeLabels[$transaction->type] ?? ucfirst(str_replace('_', ' ', $transaction->type));
                            
                            // Statut labels
                            $statusLabels = [
                                \App\Models\Transaction::STATUS_COMPLETED => 'Terminé',
                                \App\Models\Transaction::STATUS_PENDING => 'En attente',
                                \App\Models\Transaction::STATUS_FAILED => 'Échoué',
                                \App\Models\Transaction::STATUS_CANCELLED => 'Annulé',
                            ];
                            
                            $statusLabel = $statusLabels[$transaction->status] ?? ucfirst($transaction->status);
                        @endphp
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->description ?: $typeLabel }}</div>
                                @if($transaction->rental_id)
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Réservation #{{ $transaction->rental_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $isIncome ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold {{ $isIncome ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $isIncome ? '+' : '-' }}{{ number_format($transaction->amount, 0) }} DH
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->status === \App\Models\Transaction::STATUS_COMPLETED)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $statusLabel }}
                                    </span>
                                @elseif($transaction->status === \App\Models\Transaction::STATUS_PENDING)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ $statusLabel }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        {{ $statusLabel }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Aucune transaction récente</p>
                                    <p class="text-xs text-gray-500">Vos transactions apparaîtront ici une fois créées</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Rapports Financiers Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Rapports Financiers</h3>
                                <p class="text-sm text-gray-500 mt-1">Générez des rapports détaillés</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('agence.finance.reports') }}" class="block w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors duration-200 text-center shadow-sm hover:shadow">
                        Voir les Rapports
                    </a>
                </div>
            </div>

            <!-- Demander Paiement Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Demander Paiement</h3>
                                <p class="text-sm text-gray-500 mt-1">Retirez vos gains</p>
                            </div>
                        </div>
                    </div>
                    <button onclick="showPaymentRequestModal()" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors duration-200 shadow-sm hover:shadow">
                        Demander Paiement
                    </button>
                </div>
            </div>

            <!-- Documents Fiscaux Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Documents Fiscaux</h3>
                                <p class="text-sm text-gray-500 mt-1">Téléchargez vos documents</p>
                            </div>
                        </div>
                    </div>
                    <button onclick="downloadTaxDocuments()" class="w-full bg-purple-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-purple-700 transition-colors duration-200 shadow-sm hover:shadow">
                        Télécharger
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Request Modal -->
<div id="paymentRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Demander un Paiement</h3>
                <button onclick="closePaymentRequestModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="paymentRequestForm">
                @csrf
                
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Montant (DH)</label>
                    <input type="number" id="amount" name="amount" min="100" max="{{ $overview['current_balance'] ?? 0 }}" step="0.01" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Montant à retirer" required>
                    <p class="text-xs text-gray-500 mt-1">Solde disponible: {{ number_format($overview['current_balance'] ?? 0, 0) }} DH</p>
                </div>
                
                <!-- Bank details (transfer only) -->
                <div id="bank_details" class="mb-4">
                    <div class="mb-3">
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Banque</label>
                        <select id="bank_name" name="bank_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Sélectionner une banque</option>
                            <option value="Attijariwafa Bank">Attijariwafa Bank</option>
                            <option value="CIH Bank">CIH Bank</option>
                            <option value="Bank of Africa (BMCE)">Bank of Africa (BMCE)</option>
                            <option value="Banque Populaire">Banque Populaire</option>
                            <option value="Société Générale Maroc">Société Générale Maroc</option>
                            <option value="Crédit du Maroc">Crédit du Maroc</option>
                            <option value="CFG Bank">CFG Bank</option>
                            <option value="Arab Bank Maroc">Arab Bank Maroc</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rib_number" class="block text-sm font-medium text-gray-700 mb-2">RIB / IBAN</label>
                        <input type="text" id="rib_number" name="rib_number" maxlength="34" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: MA1234..." required>
                        <p class="text-xs text-gray-500 mt-1">Saisissez le RIB/IBAN du compte.</p>
                    </div>
                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">Nom complet du titulaire</label>
                        <input type="text" id="account_holder" name="account_holder" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nom et prénom sur le compte" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Informations supplémentaires..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaymentRequestModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Demander le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    @php
        $chartLabels = $monthLabels ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $chartData = $revenueTrendsData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    @endphp
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Revenus (DH)',
                data: @json($chartData),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' DH';
                        }
                    }
                }
            }
        }
    });

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Carte Bancaire', 'Virement', 'Espèces', 'Chèque'],
            datasets: [{
                data: [
                    {{ $paymentMethodsData['Carte Bancaire'] ?? 0 }},
                    {{ $paymentMethodsData['Virement'] ?? 0 }},
                    {{ $paymentMethodsData['Espèces'] ?? 0 }},
                    {{ $paymentMethodsData['Chèque'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(245, 158, 11)',
                    'rgb(168, 85, 247)'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});

// Payment Request Modal Functions
function showPaymentRequestModal() {
    document.getElementById('paymentRequestModal').classList.remove('hidden');
}

function closePaymentRequestModal() {
    document.getElementById('paymentRequestModal').classList.add('hidden');
    document.getElementById('paymentRequestForm').reset();
}

// Handle payment request form submission
document.addEventListener('DOMContentLoaded', function() {
    const paymentRequestForm = document.getElementById('paymentRequestForm');
    if (paymentRequestForm) {
        paymentRequestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const amount = formData.get('amount');
            const notes = formData.get('notes');
            const bankName = formData.get('bank_name');
            const ribNumber = formData.get('rib_number');
            const accountHolder = formData.get('account_holder');
            
            // Validate amount
            const maxAmount = {{ $overview['current_balance'] ?? 0 }};
            if (parseFloat(amount) > maxAmount) {
                alert('Le montant demandé ne peut pas dépasser votre solde disponible.');
                return;
            }
            if (parseFloat(amount) < 100) {
                alert('Le montant minimum de retrait est de 100 DH.');
                return;
            }
            // Validate bank details
            if (!bankName) { alert('Veuillez sélectionner une banque.'); return; }
            if (!accountHolder || accountHolder.trim().length < 4) { alert('Veuillez saisir le nom complet du titulaire.'); return; }
            if (!ribNumber || ribNumber.replace(/\s+/g,'').length < 16) { alert('Veuillez saisir un RIB/IBAN valide.'); return; }
            
            // Send payment request (forced to bank_transfer)
            fetch('/agence/finance/request-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    amount: amount,
                    payment_method: 'bank_transfer',
                    notes: notes,
                    bank_name: bankName,
                    rib_number: ribNumber,
                    account_holder: accountHolder
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Demande de paiement envoyée avec succès!');
                    closePaymentRequestModal();
                    location.reload();
                } else {
                    alert('Erreur lors de l\'envoi de la demande: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Error sending payment request:', error);
                alert('Erreur lors de l\'envoi de la demande de paiement');
            });
        });
    }
});
</script>
@endpush
@endsection
