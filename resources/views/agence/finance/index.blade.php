@extends('layouts.agence')

@section('content')
<div class="py-12">
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
                            <p class="text-xs text-green-600">+12% vs mois dernier</p>
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
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Transactions Récentes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions ?? [] as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->description }}</div>
                                <div class="text-sm text-gray-500">Réservation #{{ $transaction->rental_id ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaction->type === 'income' ? 'Revenu' : 'Dépense' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 0) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucune transaction récente
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Rapports Financiers</h3>
                            <p class="text-sm text-gray-500">Générez des rapports détaillés</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('agence.finance.reports') }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 inline-block text-center">
                            Voir les Rapports
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Demander Paiement</h3>
                            <p class="text-sm text-gray-500">Retirez vos gains</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button onclick="showPaymentRequestModal()" class="w-full bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                            Demander Paiement
                        </button>
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
                            <h3 class="text-lg font-medium text-gray-900">Documents Fiscaux</h3>
                            <p class="text-sm text-gray-500">Téléchargez vos documents</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button onclick="downloadTaxDocuments()" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700">
                            Télécharger
                        </button>
                    </div>
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
                
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Méthode de Paiement</label>
                    <select id="payment_method" name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner une méthode</option>
                        <option value="bank_transfer">Virement Bancaire</option>
                        <option value="check">Chèque</option>
                        <option value="cash">Espèces</option>
                    </select>
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
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Revenus (DH)',
                data: [12000, 15000, 18000, 22000, 19000, 25000, 28000, 26000, 30000, 32000, 29000, 35000],
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
                data: [45, 30, 15, 10],
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

// Tax Documents Download
function downloadTaxDocuments() {
    // Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Téléchargement...';
    button.disabled = true;
    
    // Simulate download (in real app, this would generate actual documents)
    setTimeout(() => {
        alert('Les documents fiscaux seront générés et téléchargés. Cette fonctionnalité sera implémentée avec un générateur de PDF.');
        button.textContent = originalText;
        button.disabled = false;
    }, 1500);
}

// Handle payment request form submission
document.getElementById('paymentRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const amount = formData.get('amount');
    const paymentMethod = formData.get('payment_method');
    const notes = formData.get('notes');
    
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
    
    // Send payment request
    fetch('/agence/finance/request-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            amount: amount,
            payment_method: paymentMethod,
            notes: notes
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
</script>
@endpush
@endsection
