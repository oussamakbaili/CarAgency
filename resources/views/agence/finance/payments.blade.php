@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Historique des Paiements</h1>
        <p class="text-gray-600">Suivez tous les paiements et transactions de votre agence</p>
    </div>

    <!-- Payment Statistics -->
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($payments->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} MAD</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($payments->where('status', 'pending')->sum('amount'), 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Échecs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($payments->where('status', 'failed')->sum('amount'), 0, ',', ' ') }} MAD</p>
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
                    <p class="text-sm font-medium text-gray-600">Total Transactions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $payments->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('agence.finance.payments') }}" id="filterForm">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échec</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les types</option>
                        <option value="rental_payment" {{ request('type') == 'rental_payment' ? 'selected' : '' }}>Paiement Location</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Retrait</option>
                        <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Remboursement</option>
                        <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>Commission</option>
                    </select>
                </div>
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ID transaction, client..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Filtrer
                    </button>
                    <a href="{{ route('agence.finance.payments') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Effacer
                    </a>
                    <a href="{{ route('agence.finance.export-payments', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                        Exporter
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Historique des Paiements</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $payment->id ?? '000' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($payment->rental->user->name ?? 'N/A', 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->rental->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->rental->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($payment->amount ?? 0, 0, ',', ' ') }} MAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                @if(($payment->type ?? '') == 'rental_payment')
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Paiement Location
                                @elseif(($payment->type ?? '') == 'withdrawal')
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Retrait
                                @elseif(($payment->type ?? '') == 'refund')
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Remboursement
                                @else
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ ucfirst($payment->type ?? 'Autre') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if(($payment->status ?? '') == 'completed') bg-green-100 text-green-800
                                @elseif(($payment->status ?? '') == 'pending') bg-yellow-100 text-yellow-800
                                @elseif(($payment->status ?? '') == 'failed') bg-red-100 text-red-800
                                @elseif(($payment->status ?? '') == 'refunded') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($payment->status ?? 'Inconnu') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->processed_at ? $payment->processed_at->format('d/m/Y H:i') : ($payment->created_at->format('d/m/Y H:i') ?? 'N/A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showPaymentDetails({{ $payment->id }})" class="text-blue-600 hover:text-blue-900">Voir</button>
                                @if(($payment->status ?? '') == 'pending')
                                    <button onclick="approvePayment({{ $payment->id }})" class="text-green-600 hover:text-green-900">Valider</button>
                                @endif
                                @if(($payment->status ?? '') == 'completed')
                                    <button onclick="showRefundModal({{ $payment->id }})" class="text-purple-600 hover:text-purple-900">Rembourser</button>
                                @endif
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
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Payment Details Modal -->
<div id="paymentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails du Paiement</h3>
                <button onclick="closePaymentDetails()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="paymentDetailsContent" class="space-y-4">
                <!-- Payment details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Remboursement</h3>
                <button onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="refundInfo" class="mb-4 p-3 bg-gray-50 rounded-lg">
                <!-- Payment info will be loaded here -->
            </div>
            
            <form id="refundForm">
                @csrf
                <input type="hidden" id="refundPaymentId" name="payment_id">
                
                <div class="mb-4">
                    <label for="refundAmount" class="block text-sm font-medium text-gray-700 mb-2">Montant du Remboursement (DH)</label>
                    <input type="number" id="refundAmount" name="amount" step="0.01" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label for="refundReason" class="block text-sm font-medium text-gray-700 mb-2">Raison du Remboursement</label>
                    <select id="refundReason" name="reason" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Sélectionner une raison</option>
                        <option value="cancellation">Annulation de réservation</option>
                        <option value="vehicle_issue">Problème avec le véhicule</option>
                        <option value="service_issue">Problème de service</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="refundNotes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                    <textarea id="refundNotes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Détails supplémentaires..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRefundModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                        Confirmer le Remboursement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showPaymentDetails(paymentId) {
    fetch(`/agence/finance/payments/${paymentId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('paymentDetailsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Informations de Transaction</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>ID Transaction:</strong> #${data.id}</p>
                            <p><strong>Type:</strong> ${data.type_label}</p>
                            <p><strong>Montant:</strong> ${data.amount} DH</p>
                            <p><strong>Statut:</strong> <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.status_color}">${data.status}</span></p>
                            <p><strong>Date:</strong> ${data.created_at}</p>
                            ${data.processed_at ? `<p><strong>Traité le:</strong> ${data.processed_at}</p>` : ''}
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Informations Client</h4>
                        <div class="space-y-2 text-sm">
                            <p><strong>Nom:</strong> ${data.client_name}</p>
                            <p><strong>Email:</strong> ${data.client_email}</p>
                            ${data.rental_id ? `<p><strong>Réservation:</strong> #${data.rental_id}</p>` : ''}
                            ${data.vehicle ? `<p><strong>Véhicule:</strong> ${data.vehicle}</p>` : ''}
                        </div>
                    </div>
                </div>
                ${data.description ? `
                <div class="mt-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Description</h4>
                    <p class="text-sm text-gray-600">${data.description}</p>
                </div>
                ` : ''}
            `;
            document.getElementById('paymentDetailsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading payment details:', error);
            alert('Erreur lors du chargement des détails du paiement');
        });
}

function closePaymentDetails() {
    document.getElementById('paymentDetailsModal').classList.add('hidden');
}

function approvePayment(paymentId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce paiement ?')) {
        fetch(`/agence/finance/payments/${paymentId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Paiement validé avec succès!');
                location.reload();
            } else {
                alert('Erreur lors de la validation: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Error approving payment:', error);
            alert('Erreur lors de la validation du paiement');
        });
    }
}

function showRefundModal(paymentId) {
    fetch(`/agence/finance/payments/${paymentId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('refundPaymentId').value = paymentId;
            document.getElementById('refundAmount').value = data.amount;
            document.getElementById('refundAmount').max = data.amount;
            document.getElementById('refundInfo').innerHTML = `
                <div class="text-sm">
                    <h4 class="font-medium text-gray-900">Transaction #${data.id}</h4>
                    <p class="text-gray-600 mt-1">Montant: ${data.amount} DH - ${data.client_name}</p>
                </div>
            `;
            document.getElementById('refundModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading payment info:', error);
            alert('Erreur lors du chargement des informations du paiement');
        });
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
    document.getElementById('refundForm').reset();
}

// Handle refund form submission
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const paymentId = formData.get('payment_id');
    const amount = formData.get('amount');
    const reason = formData.get('reason');
    const notes = formData.get('notes');
    
    // Validate amount
    const maxAmount = parseFloat(document.getElementById('refundAmount').max);
    if (parseFloat(amount) > maxAmount) {
        alert('Le montant du remboursement ne peut pas dépasser le montant du paiement.');
        return;
    }
    
    if (parseFloat(amount) <= 0) {
        alert('Le montant du remboursement doit être supérieur à 0.');
        return;
    }
    
    // Send refund request
    fetch(`/agence/finance/payments/${paymentId}/refund`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            amount: amount,
            reason: reason,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Remboursement traité avec succès!');
            closeRefundModal();
            location.reload();
        } else {
            alert('Erreur lors du remboursement: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error processing refund:', error);
        alert('Erreur lors du traitement du remboursement');
    });
});
</script>
@endsection
