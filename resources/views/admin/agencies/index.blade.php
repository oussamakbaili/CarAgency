@extends('layouts.admin')

@section('header', 'Gestion des Agences')

@section('content')
<style>
/* Désactiver COMPLÈTEMENT tous les effets hover sur les boutons d'action */
.approve-btn,
.reject-btn {
    transition: none !important;
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative !important;
    z-index: 10 !important;
    display: inline-flex !important;
}

/* Forcer la couleur de fond au survol */
.approve-btn:hover {
    background-color: #2563eb !important;
    background-image: none !important;
    opacity: 1 !important;
    transform: none !important;
    box-shadow: none !important;
    border-color: inherit !important;
    color: white !important;
    filter: none !important;
}

.reject-btn:hover {
    background-color: #dc2626 !important;
    background-image: none !important;
    opacity: 1 !important;
    transform: none !important;
    box-shadow: none !important;
    border-color: inherit !important;
    color: white !important;
    filter: none !important;
}

/* Couleurs de base */
.approve-btn {
    background-color: #2563eb !important;
    color: white !important;
}

.reject-btn {
    background-color: #dc2626 !important;
    color: white !important;
}

/* Disabled button styles */
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Empêcher les transitions Tailwind */
.approve-btn *,
.reject-btn * {
    transition: none !important;
    pointer-events: none !important;
}

/* Assurer que les boutons ne sont pas bloqués */
td:last-child {
    position: relative !important;
    z-index: 1 !important;
}

td:last-child .approve-btn,
td:last-child .reject-btn {
    pointer-events: auto !important;
    z-index: 999 !important;
}

/* Empêcher toute interception d'événements sur la ligne du tableau */
tbody tr {
    position: relative;
}

tbody tr td:last-child {
    pointer-events: auto !important;
}
</style>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Total</h2>
                            <p class="text-gray-600">{{ $statistics['total'] }} agences</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">En Attente</h2>
                            <p class="text-gray-600">{{ $statistics['pending'] }} agences</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Approuvées</h2>
                            <p class="text-gray-600">{{ $statistics['approved'] }} agences</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Rejetées</h2>
                            <p class="text-gray-600">{{ $statistics['rejected'] }} agences</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Liste des Agences</h2>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.agencies.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Tous
                        </a>
                        <a href="{{ route('admin.agencies.index', ['status' => 'pending']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-300 rounded-md font-semibold text-xs text-yellow-700 uppercase tracking-widest hover:bg-yellow-200 active:bg-yellow-300 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            En Attente
                        </a>
                        <a href="{{ route('admin.agencies.index', ['status' => 'approved']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-100 border border-green-300 rounded-md font-semibold text-xs text-green-700 uppercase tracking-widest hover:bg-green-200 active:bg-green-300 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Approuvées
                        </a>
                        <a href="{{ route('admin.agencies.index', ['status' => 'rejected']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-100 border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-200 active:bg-red-300 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Rejetées
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agencies Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($agencies as $agency)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $agency->agency_name }}</div>
                                    <div class="text-sm text-gray-500">RC: {{ $agency->commercial_register_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $agency->responsable_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $agency->responsable_position }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $agency->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $agency->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $agency->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($agency->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($agency->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $agency->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2 justify-end" style="pointer-events: auto !important;">
                                        @if($agency->status === 'pending')
                                            <button type="button" 
                                                    class="approve-btn inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-md"
                                                    style="background-color: #2563eb !important; color: white !important; cursor: pointer !important; pointer-events: auto !important; position: relative !important; z-index: 9999 !important; border: none !important;"
                                                    data-agency-id="{{ $agency->id }}"
                                                    data-approve-url="{{ route('admin.agencies.approve', $agency) }}">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approuver
                                            </button>
                                            <button type="button" 
                                                    class="reject-btn inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs rounded-md"
                                                    style="background-color: #dc2626 !important; color: white !important; cursor: pointer !important; pointer-events: auto !important; position: relative !important; z-index: 9999 !important; border: none !important;"
                                                    data-agency-id="{{ $agency->id }}">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Rejeter
                                            </button>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune agence trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $agencies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rejeter l'Agence</h3>
                <button onclick="closeRejectModal()" class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="rejectForm" method="POST" onsubmit="return handleReject(event)">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison du rejet
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Expliquez pourquoi cette agence est rejetée..."
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 text-gray-600">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md">
                        Rejeter l'Agence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function handleApproveClick(agencyId, approveUrl, csrfToken) {
    console.log('handleApproveClick called', { agencyId, approveUrl, csrfToken });
    
    // Trouver le bouton cliqué par data attribute
    let button = document.querySelector(`button.approve-btn[data-agency-id="${agencyId}"]`);
    
    // Fallback: chercher par onclick attribute
    if (!button) {
        const buttons = document.querySelectorAll('.approve-btn');
        buttons.forEach(btn => {
            const onclickAttr = btn.getAttribute('onclick');
            if (onclickAttr && onclickAttr.includes(agencyId.toString())) {
                button = btn;
            }
        });
    }
    
    // Dernier fallback: utiliser window.event
    if (!button && window.event) {
        button = window.event.target.closest('button.approve-btn');
    }
    
    if (!button) {
        console.error('Button not found for agency:', agencyId);
        alert('Erreur: Bouton non trouvé pour l\'agence ' + agencyId);
        return false;
    }
    
    // Vérifier si déjà en cours
    if (button.disabled) {
        return false;
    }
    
    // Disable the button to prevent double clicks
    button.disabled = true;
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Traitement...';
    
    // Récupérer le token CSRF depuis la meta tag si disponible
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    const token = metaToken ? metaToken.getAttribute('content') : csrfToken;
    
    console.log('Using CSRF token:', token ? 'Found' : 'Missing');
    
    try {
        // Créer un FormData avec le token CSRF
        const formData = new FormData();
        formData.append('_token', token);
        
        const resp = await fetch(approveUrl, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        });
        
        console.log('Response status:', resp.status);
        
        if (!resp.ok) {
            const text = await resp.text();
            console.error('Error response:', text);
            throw new Error('Request failed with status ' + resp.status);
        }
        
        const data = await resp.json();
        console.log('Response data:', data);
        
        if (data.success) {
            // Trouver la ligne dans le tableau
            const row = button.closest('tr');
            if (row) {
                // Update status badge
                const badge = row.querySelector('span.rounded-full');
                if (badge) {
                    badge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                    badge.textContent = 'Approved';
                }
                
                // Hide action buttons
                const actionsCell = row.querySelector('td:last-child');
                if (actionsCell) {
                    actionsCell.innerHTML = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                }
            }
            
            // Show success message
            alert(data.message || 'Agence approuvée avec succès!');
            
            // Reload page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            let errorMessage = data.message || 'Erreur lors de l\'approbation. Veuillez réessayer.';
            
            // If there are validation errors, show them
            if (data.errors && typeof data.errors === 'object') {
                const errors = Object.values(data.errors).flat().join('\n');
                errorMessage = errors;
            }
            
            alert(errorMessage);
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    } catch (err) {
        console.error('Error approving agency:', err);
        alert('Erreur lors de l\'approbation: ' + err.message);
        button.disabled = false;
        button.innerHTML = originalContent;
    }
    
    return false;
}

async function handleReject(e) {
    e.preventDefault();
    e.stopPropagation();
    const form = e.target;
    
    // Disable the button to prevent double clicks
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
    }
    
    try {
        const formData = new FormData(form);
        const resp = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        });
        
        if (resp.ok) {
            // Close modal
            closeRejectModal();
            
            // Rechargement silencieux sans message
            window.location.reload();
        } else {
            let errorMessage = 'Erreur lors du rejet. Veuillez réessayer.';
            try {
                const errorData = await resp.json();
                errorMessage = errorData.message || errorMessage;
                
                // If there are validation errors, show them
                if (errorData.errors && typeof errorData.errors === 'object') {
                    const errors = Object.values(errorData.errors).flat().join('\n');
                    errorMessage = errors;
                }
            } catch (e) {
                console.error('Error parsing response:', e);
            }
            
            alert(errorMessage);
            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    } catch (err) {
        console.error('Error:', err);
        alert('Erreur lors du rejet. Veuillez réessayer.');
        if (submitButton) {
            submitButton.disabled = false;
        }
    }
    
    return false;
}

function openRejectModal(agencyId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    
    if (!modal || !form) {
        alert('Erreur: Modal non trouvé');
        return;
    }
    
    // Set the form action correctly
    const baseUrl = '{{ url("admin/agencies") }}';
    form.action = `${baseUrl}/${agencyId}/reject`;
    
    // Reset the form
    form.reset();
    
    // Show modal - forcer l'affichage
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    modal.style.zIndex = '99999';
    
    // Focus on the textarea
    const textarea = document.getElementById('rejection_reason');
    if (textarea) {
        setTimeout(() => {
            textarea.focus();
        }, 100);
    }
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
    
    // Reset the form
    if (form) {
        form.reset();
    }
}

// Initialisation des boutons - CODE SIMPLIFIÉ ET PROFESSIONNEL
(function() {
    function initAgencyButtons() {
        // Récupérer le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Boutons Approuver
        const approveButtons = document.querySelectorAll('.approve-btn[data-agency-id]');
        
        approveButtons.forEach(btn => {
            const agencyId = btn.getAttribute('data-agency-id');
            const approveUrl = btn.getAttribute('data-approve-url');
            
            // Supprimer tous les anciens event listeners
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            // Ajouter un nouveau event listener propre
            newBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                if (this.disabled) {
                    return false;
                }
                
                // Désactiver le bouton pour éviter les doubles clics
                this.disabled = true;
                
                try {
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    
                    const response = await fetch(approveUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        // Rechargement silencieux sans message
                        window.location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'approbation');
                        this.disabled = false;
                    }
                } catch (error) {
                    alert('Erreur lors de l\'approbation: ' + error.message);
                    this.disabled = false;
                }
                
                return false;
            }, true);
        });
        
        // Boutons Rejeter
        const rejectButtons = document.querySelectorAll('.reject-btn[data-agency-id]');
        
        rejectButtons.forEach(btn => {
            const agencyId = btn.getAttribute('data-agency-id');
            
            // Supprimer tous les anciens event listeners
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            // Ajouter un nouveau event listener propre
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                openRejectModal(parseInt(agencyId));
                return false;
            }, true);
        });
    }
    
    // Attendre que le DOM soit prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAgencyButtons);
    } else {
        initAgencyButtons();
    }
    
    // Modal de rejet
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('rejectModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRejectModal();
                }
            });
        }
    });
})();
</script>

@push('scripts')
<script>
// Ensure our handlers are attached after layout scripts
document.addEventListener('DOMContentLoaded', function() {
    // Remove any hover styling class that might be injected elsewhere
    document.querySelectorAll('tbody tr').forEach(function(row) {
        row.classList.remove('hover:bg-gray-50');
    });
});
</script>
@endpush
@endsection

