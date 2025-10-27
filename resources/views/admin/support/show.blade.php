@extends('layouts.admin')

@section('header', 'Détails du Ticket')

@section('content')
<div class="space-y-6">
    <!-- Ticket Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->ticket_number }}</h1>
                        <p class="text-gray-600 mt-1">{{ $ticket->subject }}</p>
                    </div>
                </div>

                <!-- Ticket Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($ticket->client)
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">C</span>
                                    </div>
                                @elseif($ticket->agency)
                                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-purple-600">A</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Utilisateur</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $ticket->client ? 'Client' : 'Agence' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ $ticket->client ? $ticket->client->user->name : ($ticket->agency ? $ticket->agency->nom : 'N/A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full {{ $ticket->priority === 'urgent' ? 'bg-red-100' : ($ticket->priority === 'high' ? 'bg-orange-100' : ($ticket->priority === 'medium' ? 'bg-yellow-100' : 'bg-green-100')) }} flex items-center justify-center">
                                    @switch($ticket->priority)
                                        @case('urgent')
                                            <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            @break
                                        @case('high')
                                            <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="h-4 w-4 {{ $ticket->priority === 'medium' ? 'text-yellow-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                            </svg>
                                    @endswitch
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Priorité</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $ticket->priority_label }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full {{ $ticket->status === 'open' ? 'bg-blue-100' : ($ticket->status === 'in_progress' ? 'bg-yellow-100' : ($ticket->status === 'resolved' ? 'bg-green-100' : 'bg-gray-100')) }} flex items-center justify-center">
                                    @switch($ticket->status)
                                        @case('open')
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            @break
                                        @case('in_progress')
                                            <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @break
                                        @case('resolved')
                                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                    @endswitch
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Statut</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $ticket->status_label }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Créé le</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $ticket->created_at->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $ticket->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col space-y-2 ml-6">
                <a href="{{ route('admin.support.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                
                @if($ticket->status !== 'closed')
                <form method="POST" action="{{ route('admin.support.destroy', $ticket->id) }}" class="inline" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Message -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        @if($ticket->client)
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600">C</span>
                            </div>
                        @elseif($ticket->agency)
                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-purple-600">A</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <div class="flex items-center">
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ $ticket->client ? $ticket->client->user->name : ($ticket->agency ? $ticket->agency->nom : 'Utilisateur') }}
                            </h3>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $ticket->client ? 'Client' : 'Agence' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->message }}</p>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messages-container" class="space-y-6">
                <!-- Messages will be loaded here via JavaScript -->
            </div>

            <!-- Reply Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Envoyer un message</h3>
                <form id="message-form" onsubmit="sendMessage(event)">
                    @csrf
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" id="message-input" rows="4" required 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Tapez votre message ici..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" id="send-button"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span id="send-text">Envoyer le message</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    <!-- Status Update -->
                    <form method="POST" action="{{ route('admin.support.update-status', $ticket->id) }}" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" id="status" onchange="this.form.submit()" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Ouvert</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Résolu</option>
                        </select>
                    </form>

                    <!-- Priority Update -->
                    <form method="POST" action="{{ route('admin.support.update-priority', $ticket->id) }}" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                        <select name="priority" id="priority" onchange="this.form.submit()" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Faible</option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>Élevée</option>
                            <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </form>

                    <!-- Assignment -->
                    <form method="POST" action="{{ route('admin.support.assign', $ticket->id) }}" class="space-y-2">
                        @csrf
                        <label for="admin_id" class="block text-sm font-medium text-gray-700">Assigner à</label>
                        <select name="admin_id" id="admin_id" onchange="this.form.submit()" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Non assigné</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $ticket->assigned_to === $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Ticket Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du ticket</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Numéro</dt>
                        <dd class="text-sm text-gray-900">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($ticket->category) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigné à</dt>
                        <dd class="text-sm text-gray-900">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Non assigné' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dernière réponse</dt>
                        <dd class="text-sm text-gray-900">
                            @if($ticket->last_reply_at)
                                {{ \Carbon\Carbon::parse($ticket->last_reply_at)->format('d/m/Y H:i') }}
                            @else
                                Aucune
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mis à jour</dt>
                        <dd class="text-sm text-gray-900">{{ $ticket->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ticketId = {{ $ticket->id }};
let messagesLoaded = false;

// Auto-resize textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Load messages on page load
document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
    markMessagesAsRead();
    
    // Auto-refresh messages every 5 seconds
    setInterval(loadMessages, 5000);
});

// Load messages from server
async function loadMessages() {
    try {
        const response = await fetch(`/admin/support/messages/${ticketId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayMessages(data.messages);
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

// Display messages in the container
function displayMessages(messages) {
    const container = document.getElementById('messages-container');
    container.innerHTML = '';
    
    messages.forEach(message => {
        const messageDiv = createMessageElement(message);
        container.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    container.scrollTop = container.scrollHeight;
}

// Create message element
function createMessageElement(message) {
    const senderInfo = getSenderInfo(message.sender_type);
    const isOwnMessage = message.sender_type === 'App\\Models\\User';
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `bg-white rounded-lg shadow-sm border border-gray-200 p-6 ${isOwnMessage ? 'ml-8' : 'mr-8'}`;
    
    messageDiv.innerHTML = `
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-${senderInfo.color}-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-${senderInfo.color}-600">${senderInfo.avatar}</span>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">${message.sender.name || message.sender.nom || 'Utilisateur'}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${senderInfo.color}-100 text-${senderInfo.color}-800">
                            ${senderInfo.type === 'admin' ? 'Admin' : (senderInfo.type === 'agency' ? 'Agence' : 'Client')}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">${new Date(message.created_at).toLocaleString('fr-FR')}</p>
                </div>
            </div>
        </div>
        <div class="prose max-w-none">
            <p class="text-gray-700 whitespace-pre-wrap">${message.message}</p>
        </div>
    `;
    
    return messageDiv;
}

// Get sender info based on type
function getSenderInfo(senderType) {
    switch(senderType) {
        case 'App\\Models\\User':
            return { name: 'Admin', type: 'admin', avatar: 'A', color: 'orange' };
        case 'App\\Models\\Agency':
            return { name: 'Agence', type: 'agency', avatar: 'A', color: 'purple' };
        case 'App\\Models\\Client':
            return { name: 'Client', type: 'client', avatar: 'C', color: 'blue' };
        default:
            return { name: 'Système', type: 'system', avatar: 'S', color: 'gray' };
    }
}

// Send message
async function sendMessage(event) {
    event.preventDefault();
    
    const form = event.target;
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const sendText = document.getElementById('send-text');
    
    if (!messageInput.value.trim()) return;
    
    // Disable form
    sendButton.disabled = true;
    sendText.textContent = 'Envoi...';
    
    try {
        const response = await fetch(`/admin/support/messages/${ticketId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: messageInput.value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            loadMessages(); // Reload messages
        } else {
            alert('Erreur lors de l\'envoi du message');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Erreur lors de l\'envoi du message');
    } finally {
        // Re-enable form
        sendButton.disabled = false;
        sendText.textContent = 'Envoyer le message';
    }
}

// Mark messages as read
async function markMessagesAsRead() {
    try {
        await fetch(`/admin/support/messages/${ticketId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    } catch (error) {
        console.error('Error marking messages as read:', error);
    }
}

// Update unread count in navigation
async function updateUnreadCount() {
    try {
        const response = await fetch('/admin/support/unread-count');
        const data = await response.json();
        
        // Update notification badge in navigation
        const badge = document.querySelector('.support-notification-badge');
        if (badge) {
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Error updating unread count:', error);
    }
}

// Update unread count every 30 seconds
setInterval(updateUnreadCount, 30000);
</script>
@endpush
@endsection