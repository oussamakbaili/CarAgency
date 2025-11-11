@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Demandes de Paiement</h1>
                    <p class="mt-2 text-gray-600">Gérez les demandes de retrait des agences</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Demandes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_requests'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En Attente</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_requests'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Approuvées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_requests'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rejetées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected_requests'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Montant Total</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_amount'], 0) }} DH</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Requests Table -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Liste des Demandes</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banque</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RIB / IBAN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titulaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paymentRequests as $request)
                        @php
                            $metadata = $request->metadata ?? [];
                            $bankName = $metadata['bank_name'] ?? '—';
                            $ribNumber = $metadata['rib_number'] ?? '—';
                            $accountHolder = $metadata['account_holder'] ?? '—';
                            $formattedRib = $ribNumber !== '—' ? trim(chunk_split(preg_replace('/\s+/', '', $ribNumber), 4, ' ')) : '—';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $request->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->agency->agency_name }}</div>
                                <div class="text-sm text-gray-500">{{ $request->agency->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($request->amount, 2, ',', ' ') }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bankName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $formattedRib }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $accountHolder }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status === 'completed') bg-green-100 text-green-800
                                    @elseif($request->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($request->status === 'pending')
                                        <button onclick="showApproveModal(this)"
                                            data-id="{{ $request->id }}"
                                            data-agency="{{ $request->agency->agency_name }}"
                                            data-amount="{{ number_format($request->amount, 2, ',', ' ') }}"
                                            data-bank="{{ $bankName }}"
                                            data-rib="{{ $formattedRib }}"
                                            data-holder="{{ $accountHolder }}"
                                            class="text-green-600 hover:text-green-900">
                                            Marquer comme traité
                                        </button>
                                        <button onclick="showRejectModal(this)"
                                            data-id="{{ $request->id }}"
                                            data-agency="{{ $request->agency->agency_name }}"
                                            data-amount="{{ number_format($request->amount, 2, ',', ' ') }}"
                                            class="text-red-600 hover:text-red-900">Rejeter</button>
                                    @endif
                                    <button onclick="showRequestDetails({{ $request->id }})" class="text-blue-600 hover:text-blue-900">Détails</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Aucune demande de paiement trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($paymentRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $paymentRequests->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Confirmer le virement</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 mb-2">Agence</p>
                <p id="approveAgency" class="text-base font-semibold text-gray-900"></p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                    <div>
                        <p class="text-xs uppercase text-gray-500">Montant</p>
                        <p id="approveAmount" class="font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Banque</p>
                        <p id="approveBank" class="font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">RIB / IBAN</p>
                        <p id="approveRib" class="font-semibold text-gray-900 break-all"></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Titulaire</p>
                        <p id="approveHolder" class="font-semibold text-gray-900"></p>
                    </div>
                </div>
            </div>

            <form id="approveForm">
                @csrf
                <input type="hidden" id="approveRequestId" name="request_id">

                <div class="mb-4">
                    <label for="transferReference" class="block text-sm font-medium text-gray-700 mb-2">Référence du virement (facultatif)</label>
                    <input type="text" id="transferReference" name="reference" maxlength="100" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Référence bancaire / numéro d\'opération">
                </div>

                <div class="mb-4">
                    <label for="approveNotes" class="block text-sm font-medium text-gray-700 mb-2">Notes internes (facultatif)</label>
                    <textarea id="approveNotes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ajouter une note interne ou des détails supplémentaires..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                        Confirmer le virement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rejeter la Demande</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="rejectForm">
                @csrf
                <input type="hidden" id="rejectRequestId" name="request_id">

                <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <p class="text-xs uppercase text-gray-500">Demande sélectionnée</p>
                    <p id="rejectSummary" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                </div>

                <div class="mb-4">
                    <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">Raison du rejet</label>
                    <textarea id="rejectReason" name="reason" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Expliquez pourquoi cette demande est rejetée..." required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                        Rejeter la Demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails de la demande</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="detailsContent" class="space-y-3 text-sm text-gray-700"></div>
            <div class="flex justify-end mt-4">
                <button onclick="closeDetailsModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showApproveModal(button) {
    const requestId = button.getAttribute('data-id');
    document.getElementById('approveRequestId').value = requestId;
    document.getElementById('approveAgency').textContent = button.getAttribute('data-agency');
    document.getElementById('approveAmount').textContent = button.getAttribute('data-amount') + ' DH';
    document.getElementById('approveBank').textContent = button.getAttribute('data-bank');
    document.getElementById('approveRib').textContent = button.getAttribute('data-rib');
    document.getElementById('approveHolder').textContent = button.getAttribute('data-holder');
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approveForm').reset();
}

function showRejectModal(button) {
    const requestId = button.getAttribute('data-id');
    const agencyName = button.getAttribute('data-agency');
    const amount = button.getAttribute('data-amount');

    document.getElementById('rejectRequestId').value = requestId;
    document.getElementById('rejectSummary').textContent = `${agencyName} — ${amount} DH`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

function showRequestDetails(requestId) {
    fetch(`/admin/payment-requests/${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message || 'Impossible de récupérer les détails.');
                return;
            }

            const details = data.data;
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs uppercase text-gray-500">Agence</p>
                        <p class="font-semibold text-gray-900">${details.agency_name}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Montant</p>
                        <p class="font-semibold text-gray-900">${details.amount_formatted} DH</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Banque</p>
                        <p class="font-semibold text-gray-900">${details.bank_name}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Titulaire</p>
                        <p class="font-semibold text-gray-900">${details.account_holder}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs uppercase text-gray-500">RIB / IBAN</p>
                        <p class="font-semibold text-gray-900 break-all">${details.rib_number}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Statut</p>
                        <p class="font-semibold text-gray-900">${details.status}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500">Demandé le</p>
                        <p class="font-semibold text-gray-900">${details.requested_at}</p>
                    </div>
                    ${details.processed_at ? `<div><p class="text-xs uppercase text-gray-500">Traitée le</p><p class="font-semibold text-gray-900">${details.processed_at}</p></div>` : ''}
                    ${details.approved_by_name ? `<div><p class="text-xs uppercase text-gray-500">Approuvée par</p><p class="font-semibold text-gray-900">${details.approved_by_name}</p></div>` : ''}
                    ${details.rejected_by_name ? `<div><p class="text-xs uppercase text-gray-500">Rejetée par</p><p class="font-semibold text-gray-900">${details.rejected_by_name}</p></div>` : ''}
                    ${details.notes ? `<div class="md:col-span-2"><p class="text-xs uppercase text-gray-500">Notes de l'agence</p><p class="font-semibold text-gray-900">${details.notes}</p></div>` : ''}
                    ${details.admin_notes ? `<div class="md:col-span-2"><p class="text-xs uppercase text-gray-500">Notes administrateur</p><p class="font-semibold text-gray-900">${details.admin_notes}</p></div>` : ''}
                    ${details.rejection_reason ? `<div class="md:col-span-2"><p class="text-xs uppercase text-gray-500">Raison du rejet</p><p class="font-semibold text-gray-900">${details.rejection_reason}</p></div>` : ''}
                    ${details.transfer_reference ? `<div class="md:col-span-2"><p class="text-xs uppercase text-gray-500">Référence de virement</p><p class="font-semibold text-gray-900">${details.transfer_reference}</p></div>` : ''}
                </div>`;

            document.getElementById('detailsContent').innerHTML = content;
            document.getElementById('detailsModal').classList.remove('hidden');
        })
        .catch(() => {
            alert('Erreur lors de la récupération des détails de la demande.');
        });
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
    document.getElementById('detailsContent').innerHTML = '';
}

function submitApproveForm(form) {
    const requestId = document.getElementById('approveRequestId').value;
    const reference = document.getElementById('transferReference').value;
    const notes = document.getElementById('approveNotes').value;

    fetch(`/admin/payment-requests/${requestId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            reference: reference,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Demande approuvée avec succès!');
            closeApproveModal();
            location.reload();
        } else {
            alert('Erreur lors de l\'approbation: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error approving request:', error);
        alert('Erreur lors de l\'approbation de la demande');
    });
}

// Handle approve form submission
document.addEventListener('DOMContentLoaded', function() {
    const approveForm = document.getElementById('approveForm');
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitApproveForm(this);
        });
    }

    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const requestId = formData.get('request_id');
            const reason = formData.get('reason');

            fetch(`/admin/payment-requests/${requestId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Demande rejetée avec succès!');
                    closeRejectModal();
                    location.reload();
                } else {
                    alert('Erreur lors du rejet: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Error rejecting request:', error);
                alert('Erreur lors du rejet de la demande');
            });
        });
    }
});
</script>
@endsection
