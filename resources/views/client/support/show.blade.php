@extends('layouts.client')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('client.support.index') }}" class="text-sm text-blue-600 hover:text-blue-800 mb-2 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour à mes tickets
            </a>
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Ticket #{{ $ticket->ticket_number }} • Créé le {{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    @if(in_array($ticket->status, ['open', 'in_progress']))
                        <form method="POST" action="{{ route('client.support.markResolved', $ticket->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Marquer comme résolu
                            </button>
                        </form>
                    @elseif($ticket->status === 'resolved' || $ticket->status === 'closed')
                        <form method="POST" action="{{ route('client.support.reopen', $ticket->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Rouvrir le ticket
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ticket->status_badge }}">
                                {{ $ticket->status_label }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ticket->priority_badge }}">
                                {{ $ticket->priority_label }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($ticket->category) }}
                        </span>
                    </div>
                </div>

                <!-- Original Message -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600">C</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-gray-900">Vous</h3>
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
                @if($ticket->status !== 'resolved')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Envoyer un message</h3>
                    <form id="message-form" onsubmit="sendMessage(event)">
                        @csrf
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea name="message" id="message-input" rows="4" required 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Tapez votre message ici..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="send-button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span id="send-text">Envoyer le message</span>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <!-- Resolved Message -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-center">
                        <div class="flex items-center text-green-700">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-green-800">Ticket Résolu</h3>
                                <p class="text-sm text-green-600">Ce ticket a été résolu. La conversation est fermée.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Ticket Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du ticket</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Numéro</dt>
                            <dd class="text-sm text-gray-900">#{{ $ticket->ticket_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status_badge }}">
                                    {{ $ticket->status_label }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Priorité</dt>
                            <dd class="text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->priority_badge }}">
                                    {{ $ticket->priority_label }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($ticket->category) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                            <dd class="text-sm text-gray-900">{{ $ticket->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dernière mise à jour</dt>
                            <dd class="text-sm text-gray-900">{{ $ticket->updated_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('client.support.messages') }}" 
                           class="block w-full text-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Voir tous les messages
                        </a>
                        <a href="{{ route('client.support.index') }}" 
                           class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Mes tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ticketId = {{ $ticket->id }};

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
        const response = await fetch(`/client/support/messages/${ticketId}`, {
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
    const isOwnMessage = message.sender_type === 'App\\Models\\Client';
    
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
        const response = await fetch(`/client/support/messages/${ticketId}/send`, {
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
        await fetch(`/client/support/messages/${ticketId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    } catch (error) {
        console.error('Error marking messages as read:', error);
    }
}
</script>
@endpush
@endsection