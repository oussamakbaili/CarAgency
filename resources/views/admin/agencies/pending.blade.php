@extends('layouts.admin')

@section('header', 'Agences en Attente')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">Demandes d'Agences en Attente</h2>
        <div class="flex space-x-2">
            <button onclick="bulkApprove()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Approuver Sélectionnés
            </button>
            <button onclick="bulkReject()" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                Rejeter Sélectionnés
            </button>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par nom d'agence, email..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Rechercher
        </button>
        <a href="{{ route('admin.agencies.pending') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Réinitialiser
        </a>
    </form>
</div>

<!-- Agencies Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <form id="bulkForm" method="POST">
        @csrf
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Agence
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ville
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date d'inscription
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agencies as $agency)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="agency_ids[]" value="{{ $agency->id }}" class="agency-checkbox">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $agency->agency_name }}</div>
                                <div class="text-sm text-gray-500">{{ $agency->responsable_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $agency->email }}</div>
                        <div class="text-sm text-gray-500">{{ $agency->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $agency->city }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $agency->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.agencies.show', $agency) }}" 
                               class="text-blue-600 hover:text-blue-900">Voir</a>
                            <button onclick="approveAgency({{ $agency->id }})" 
                                    class="text-green-600 hover:text-green-900">Approuver</button>
                            <button onclick="rejectAgency({{ $agency->id }})" 
                                    class="text-red-600 hover:text-red-900">Rejeter</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Aucune agence en attente trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </form>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $agencies->links() }}
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Approuver l'Agence</h3>
            <form id="approvalForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Taux de Commission (%)
                    </label>
                    <input type="number" name="commission_rate" value="10" min="0" max="100" step="0.1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('approvalModal')" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approuver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter l'Agence</h3>
            <form id="rejectionForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Raison du rejet
                    </label>
                    <textarea name="rejection_reason" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Expliquez la raison du rejet..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('rejectionModal')" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.agency-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function approveAgency(agencyId) {
    const form = document.getElementById('approvalForm');
    form.action = `{{ route('admin.agencies.approve', '') }}/${agencyId}`;
    document.getElementById('approvalModal').classList.remove('hidden');
}

function rejectAgency(agencyId) {
    const form = document.getElementById('rejectionForm');
    form.action = `{{ route('admin.agencies.reject', '') }}/${agencyId}`;
    document.getElementById('rejectionModal').classList.remove('hidden');
}

function bulkApprove() {
    const selectedIds = Array.from(document.querySelectorAll('.agency-checkbox:checked')).map(cb => cb.value);
    if (selectedIds.length === 0) {
        alert('Veuillez sélectionner au moins une agence.');
        return;
    }
    
    const form = document.getElementById('bulkForm');
    form.action = '{{ route("admin.agencies.bulk-approve") }}';
    form.submit();
}

function bulkReject() {
    const selectedIds = Array.from(document.querySelectorAll('.agency-checkbox:checked')).map(cb => cb.value);
    if (selectedIds.length === 0) {
        alert('Veuillez sélectionner au moins une agence.');
        return;
    }
    
    const reason = prompt('Raison du rejet:');
    if (!reason) return;
    
    const form = document.getElementById('bulkForm');
    form.action = '{{ route("admin.agencies.bulk-reject") }}';
    
    // Add rejection reason input
    const reasonInput = document.createElement('input');
    reasonInput.type = 'hidden';
    reasonInput.name = 'rejection_reason';
    reasonInput.value = reason;
    form.appendChild(reasonInput);
    
    form.submit();
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection
