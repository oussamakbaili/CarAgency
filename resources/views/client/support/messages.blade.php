@extends('layouts.client')

@section('title', 'Messages Support')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('client.support.index') }}" class="text-sm text-blue-600 hover:text-blue-800 mb-2 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour au support
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Messages Support</h1>
            <p class="mt-1 text-sm text-gray-500">Communiquez directement avec notre équipe de support</p>
        </div>

        <!-- Tickets List -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Tickets Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Mes Tickets</h3>
                    </div>
                    <div class="p-4">
                        <div id="tickets-list" class="space-y-2">
                            <!-- Tickets will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow">
                    <!-- Messages Header -->
                    <div id="messages-header" class="p-4 border-b border-gray-200 hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 id="ticket-subject" class="text-lg font-semibold text-gray-900"></h3>
                                <p id="ticket-info" class="text-sm text-gray-500"></p>
                            </div>
                            <div id="ticket-status" class="flex items-center gap-2">
                                <!-- Status badge will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div id="messages-container" class="h-96 overflow-y-auto p-4 space-y-4">
                        <div class="flex items-center justify-center h-full text-gray-500">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p>Sélectionnez un ticket pour voir les messages</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message Form -->
                    <div id="message-form-container" class="p-4 border-t border-gray-200 hidden">
                        <form id="message-form" onsubmit="sendMessage(event)">
                            @csrf
                            <div class="flex gap-2">
                                <textarea id="message-input" rows="3" required 
                                          class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Tapez votre message..."></textarea>
                                <button type="submit" id="send-button"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentTicketId = null;
let messagesInterval = null;

// Load tickets on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTickets();
});

// Load user's support tickets
async function loadTickets() {
    try {
        const response = await fetch('/client/support/tickets', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayTickets(data.tickets);
        }
    } catch (error) {
        console.error('Error loading tickets:', error);
    }
}

// Display tickets in sidebar
function displayTickets(tickets) {
    const container = document.getElementById('tickets-list');
    container.innerHTML = '';
    
    if (tickets.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">Aucun ticket trouvé</p>';
        return;
    }
    
    tickets.forEach(ticket => {
        const ticketDiv = createTicketElement(ticket);
        container.appendChild(ticketDiv);
    });
}

// Create ticket element
function createTicketElement(ticket) {
    const ticketDiv = document.createElement('div');
    ticketDiv.className = `p-3 rounded-lg border cursor-pointer transition-colors hover:bg-gray-50 ${currentTicketId === ticket.id ? 'bg-blue-50 border-blue-200' : 'border-gray-200'}`;
    ticketDiv.onclick = () => selectTicket(ticket);
    
    const unreadCount = ticket.unread_messages_count || 0;
    
    ticketDiv.innerHTML = `
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-gray-900 truncate">${ticket.subject}</h4>
                <p class="text-xs text-gray-500 mt-1">${new Date(ticket.created_at).toLocaleDateString('fr-FR')}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(ticket.status)}">
                    ${getStatusLabel(ticket.status)}
                </span>
            </div>
            ${unreadCount > 0 ? `<span class="ml-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">${unreadCount}</span>` : ''}
        </div>
    `;
    
    return ticketDiv;
}

// Select a ticket
async function selectTicket(ticket) {
    currentTicketId = ticket.id;
    
    // Update UI
    document.querySelectorAll('#tickets-list > div').forEach(el => {
        el.className = el.className.replace('bg-blue-50 border-blue-200', 'border-gray-200');
    });
    
    event.currentTarget.className = event.currentTarget.className.replace('border-gray-200', 'bg-blue-50 border-blue-200');
    
    // Show ticket info
    document.getElementById('messages-header').classList.remove('hidden');
    document.getElementById('ticket-subject').textContent = ticket.subject;
    document.getElementById('ticket-info').textContent = `Ticket #${ticket.ticket_number} • Créé le ${new Date(ticket.created_at).toLocaleDateString('fr-FR')}`;
    
    // Update status
    const statusContainer = document.getElementById('ticket-status');
    statusContainer.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(ticket.status)}">${getStatusLabel(ticket.status)}</span>`;
    
    // Show message form only if ticket is not resolved
    if (ticket.status === 'resolved') {
        document.getElementById('message-form-container').classList.add('hidden');
        // Show resolved message
        showResolvedMessage();
    } else {
        document.getElementById('message-form-container').classList.remove('hidden');
        // Hide resolved message if exists
        hideResolvedMessage();
    }
    
    // Load messages
    await loadMessages();
    
    // Start auto-refresh
    if (messagesInterval) clearInterval(messagesInterval);
    messagesInterval = setInterval(loadMessages, 5000);
}

// Load messages for current ticket
async function loadMessages() {
    if (!currentTicketId) return;
    
    try {
        const response = await fetch(`/client/support/messages/${currentTicketId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayMessages(data.messages);
            markMessagesAsRead();
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

// Display messages
function displayMessages(messages) {
    const container = document.getElementById('messages-container');
    container.innerHTML = '';
    
    if (messages.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-500 py-8">Aucun message dans ce ticket</div>';
        return;
    }
    
    messages.forEach(message => {
        const messageDiv = createMessageElement(message);
        container.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    container.scrollTop = container.scrollHeight;
}

// Create message element
function createMessageElement(message) {
    const isOwnMessage = message.sender_type === 'App\\Models\\Client';
    const senderInfo = getSenderInfo(message.sender_type);
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'} mb-4`;
    
    messageDiv.innerHTML = `
        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isOwnMessage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'}">
            <div class="flex items-center mb-1">
                <span class="text-xs font-medium ${isOwnMessage ? 'text-blue-100' : 'text-gray-600'}">${message.sender.name || message.sender.nom || 'Utilisateur'}</span>
                <span class="ml-2 text-xs ${isOwnMessage ? 'text-blue-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span>
            </div>
            <p class="text-sm whitespace-pre-wrap">${message.message}</p>
        </div>
    `;
    
    return messageDiv;
}

// Get sender info
function getSenderInfo(senderType) {
    switch(senderType) {
        case 'App\\Models\\User':
            return { name: 'Admin', type: 'admin', color: 'orange' };
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
    
    if (!currentTicketId) return;
    
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    
    if (!messageInput.value.trim()) return;
    
    // Disable form
    sendButton.disabled = true;
    
    try {
        const response = await fetch(`/client/support/messages/${currentTicketId}/send`, {
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
            await loadMessages(); // Reload messages
        } else {
            alert('Erreur lors de l\'envoi du message');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Erreur lors de l\'envoi du message');
    } finally {
        // Re-enable form
        sendButton.disabled = false;
    }
}

// Mark messages as read
async function markMessagesAsRead() {
    if (!currentTicketId) return;
    
    try {
        await fetch(`/client/support/messages/${currentTicketId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        // Update unread count in sidebar
        loadTickets();
    } catch (error) {
        console.error('Error marking messages as read:', error);
    }
}

// Get status badge class
function getStatusBadgeClass(status) {
    switch(status) {
        case 'open': return 'bg-blue-100 text-blue-800';
        case 'in_progress': return 'bg-yellow-100 text-yellow-800';
        case 'resolved': return 'bg-green-100 text-green-800';
        case 'closed': return 'bg-gray-100 text-gray-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

// Get status label
function getStatusLabel(status) {
    switch(status) {
        case 'open': return 'Ouvert';
        case 'in_progress': return 'En cours';
        case 'resolved': return 'Résolu';
        case 'closed': return 'Fermé';
        default: return 'Inconnu';
    }
}

// Auto-resize textarea
document.getElementById('message-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Show resolved message
function showResolvedMessage() {
    let resolvedMessage = document.getElementById('resolved-message');
    if (!resolvedMessage) {
        const messageContainer = document.getElementById('message-form-container');
        resolvedMessage = document.createElement('div');
        resolvedMessage.id = 'resolved-message';
        resolvedMessage.className = 'p-4 border-t border-gray-200 bg-green-50';
        resolvedMessage.innerHTML = `
            <div class="flex items-center justify-center">
                <div class="flex items-center text-green-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Ce ticket a été résolu. La conversation est fermée.</span>
                </div>
            </div>
        `;
        messageContainer.parentNode.insertBefore(resolvedMessage, messageContainer);
    }
    resolvedMessage.classList.remove('hidden');
}

// Hide resolved message
function hideResolvedMessage() {
    const resolvedMessage = document.getElementById('resolved-message');
    if (resolvedMessage) {
        resolvedMessage.classList.add('hidden');
    }
}
</script>
@endpush
@endsection
