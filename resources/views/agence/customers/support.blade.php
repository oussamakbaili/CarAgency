@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Support Client</h1>
        <p class="text-gray-600">Gérez les demandes de support et les tickets clients</p>
    </div>

    <!-- Support Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tickets</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_tickets'] ?? 0 }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Ouverts</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['open_tickets'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">En Cours</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['in_progress_tickets'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Résolus</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['resolved_tickets'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Urgents</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['urgent_tickets'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('agence.customers.support') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Sujet, description, numéro de ticket..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Ouvert</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Résolu</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fermé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                <select name="priority" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Toutes les priorités</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Faible</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Élevée</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Toutes les catégories</option>
                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technique</option>
                    <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Facturation</option>
                    <option value="booking" {{ request('category') == 'booking' ? 'selected' : '' }}>Réservation</option>
                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>Général</option>
                    <option value="complaint" {{ request('category') == 'complaint' ? 'selected' : '' }}>Plainte</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tri</label>
                <select name="sort" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                    <option value="priority_high" {{ request('sort') == 'priority_high' ? 'selected' : '' }}>Priorité élevée</option>
                    <option value="priority_low" {{ request('sort') == 'priority_low' ? 'selected' : '' }}>Priorité faible</option>
                    <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Par statut</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Filtrer
                </button>
                <a href="{{ route('agence.customers.support') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 text-sm">
                    Effacer
                </a>
            </div>
        </form>
    </div>

    <!-- Support Tickets List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tickets de Support</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($tickets as $ticket)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <h4 class="text-sm font-medium text-gray-900">{{ $ticket->subject }}</h4>
                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $ticket->status_color }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $ticket->priority_color }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $ticket->category_label }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ Str::limit($ticket->description, 150) }}</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span>Par {{ $ticket->client->user->name ?? 'Client anonyme' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>Ticket #{{ $ticket->ticket_number }}</span>
                            @if($ticket->rental)
                                <span class="mx-2">•</span>
                                <span>{{ $ticket->rental->car->brand ?? '' }} {{ $ticket->rental->car->model ?? '' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4 flex space-x-2">
                        <button onclick="showTicketDetails({{ $ticket->id }})" class="text-sm text-blue-600 hover:text-blue-900">Voir</button>
                        <button onclick="replyToTicket({{ $ticket->id }})" class="text-sm text-green-600 hover:text-green-900">Répondre</button>
                        <button onclick="updateTicketStatus({{ $ticket->id }})" class="text-sm text-gray-600 hover:text-gray-900">Assigner</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun ticket de support</h3>
                <p class="mt-1 text-sm text-gray-500">Les demandes de support de vos clients apparaîtront ici.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($tickets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Ticket Details Modal -->
<div id="ticketDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails du Ticket</h3>
                <button onclick="closeTicketDetails()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="ticketDetailsContent" class="space-y-4">
                <!-- Ticket details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Répondre au Ticket</h3>
                <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="ticketInfo" class="mb-4 p-3 bg-gray-50 rounded-lg">
                <!-- Ticket info will be loaded here -->
            </div>
            
            <form id="replyForm">
                @csrf
                <input type="hidden" id="ticketId" name="ticket_id">
                
                <div class="mb-4">
                    <label for="replyMessage" class="block text-sm font-medium text-gray-700 mb-2">Votre réponse</label>
                    <textarea id="replyMessage" name="message" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tapez votre réponse ici..." required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReplyModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Envoyer la réponse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Modifier le Statut</h3>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="statusForm">
                @csrf
                <input type="hidden" id="statusTicketId" name="ticket_id">
                
                <div class="mb-4">
                    <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                    <select id="newStatus" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="open">Ouvert</option>
                        <option value="in_progress">En cours</option>
                        <option value="resolved">Résolu</option>
                        <option value="closed">Fermé</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="newPriority" class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                    <select id="newPriority" name="priority" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="low">Faible</option>
                        <option value="medium">Moyenne</option>
                        <option value="high">Élevée</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTicketDetails(ticketId) {
    fetch(`/agence/customers/support/${ticketId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('ticketDetailsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">${data.subject}</h4>
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.status_color}">
                                ${data.status}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${data.priority_color}">
                                ${data.priority}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                ${data.category_label}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p><strong>Client:</strong> ${data.client_name}</p>
                            <p><strong>Ticket #:</strong> ${data.ticket_number}</p>
                            <p><strong>Créé le:</strong> ${data.created_at}</p>
                            ${data.vehicle ? `<p><strong>Véhicule:</strong> ${data.vehicle}</p>` : ''}
                        </div>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-gray-900 mb-2">Description</h5>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">${data.description}</p>
                    </div>
                </div>
            `;
            document.getElementById('ticketDetailsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading ticket details:', error);
            alert('Erreur lors du chargement des détails du ticket');
        });
}

function closeTicketDetails() {
    document.getElementById('ticketDetailsModal').classList.add('hidden');
}

function replyToTicket(ticketId) {
    fetch(`/agence/customers/support/${ticketId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('ticketId').value = ticketId;
            document.getElementById('ticketInfo').innerHTML = `
                <div class="text-sm">
                    <h4 class="font-medium text-gray-900">${data.subject}</h4>
                    <p class="text-gray-600 mt-1">Ticket #${data.ticket_number} - ${data.client_name}</p>
                </div>
            `;
            document.getElementById('replyModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading ticket info:', error);
            alert('Erreur lors du chargement des informations du ticket');
        });
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    document.getElementById('replyForm').reset();
}

function updateTicketStatus(ticketId) {
    fetch(`/agence/customers/support/${ticketId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('statusTicketId').value = ticketId;
            document.getElementById('newStatus').value = data.status;
            document.getElementById('newPriority').value = data.priority;
            document.getElementById('statusModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading ticket info:', error);
            alert('Erreur lors du chargement des informations du ticket');
        });
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusForm').reset();
}

// Handle reply form submission
document.addEventListener('DOMContentLoaded', function() {
    const replyForm = document.getElementById('replyForm');
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const ticketId = formData.get('ticket_id');
            const message = formData.get('message');
            
            fetch(`/agence/customers/support/${ticketId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Réponse envoyée avec succès!');
                    closeReplyModal();
                    location.reload();
                } else {
                    alert('Erreur lors de l\'envoi de la réponse: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Error sending reply:', error);
                alert('Erreur lors de l\'envoi de la réponse');
            });
        });
    }
    
    // Handle status form submission
    const statusForm = document.getElementById('statusForm');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const ticketId = formData.get('ticket_id');
            const status = formData.get('status');
            const priority = formData.get('priority');
            
            fetch(`/agence/customers/support/${ticketId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: status,
                    priority: priority
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Statut mis à jour avec succès!');
                    closeStatusModal();
                    location.reload();
                } else {
                    alert('Erreur lors de la mise à jour: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                alert('Erreur lors de la mise à jour du statut');
            });
        });
    }
});
</script>
@endsection