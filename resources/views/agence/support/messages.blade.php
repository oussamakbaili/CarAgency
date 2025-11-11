@extends('layouts.agence')

@section('title', 'Messages Support')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('agence.support.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-2 inline-flex items-center">
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
                                          class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Tapez votre message..."></textarea>
                                <button type="submit" id="send-button"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
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
        const response = await fetch('/agence/support/tickets', {
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
    ticketDiv.className = `p-3 rounded-lg border cursor-pointer transition-colors hover:bg-gray-50 ${currentTicketId === ticket.id ? 'bg-indigo-50 border-indigo-200' : 'border-gray-200'}`;
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
            ${unreadCount > 0 ? `<span class="ml-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">${unreadCount}</span>` : ''}
        </div>
    `;
    
    return ticketDiv;
}

// Select a ticket
async function selectTicket(ticket) {
    currentTicketId = ticket.id;
    
    // Update UI
    document.querySelectorAll('#tickets-list > div').forEach(el => {
        el.className = el.className.replace('bg-indigo-50 border-indigo-200', 'border-gray-200');
    });
    
    event.currentTarget.className = event.currentTarget.className.replace('border-gray-200', 'bg-indigo-50 border-indigo-200');
    
    // Show ticket info
    document.getElementById('messages-header').classList.remove('hidden');
    document.getElementById('ticket-subject').textContent = ticket.subject;
    document.getElementById('ticket-info').textContent = `Ticket #${ticket.ticket_number} • Créé le ${new Date(ticket.created_at).toLocaleDateString('fr-FR')}`;
    
    // Update status
    const statusContainer = document.getElementById('ticket-status');
    statusContainer.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(ticket.status)}">${getStatusLabel(ticket.status)}</span>`;
    
    // Show message form
    document.getElementById('message-form-container').classList.remove('hidden');
    
    // Initialiser la détection de numéro de téléphone
    setTimeout(initPhoneDetection, 100);
    
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
        const response = await fetch(`/agence/support/messages/${currentTicketId}`, {
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
    
    // Trier les messages par date croissante (anciens en premier, nouveaux en bas)
    const sortedMessages = [...messages].sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return dateA - dateB; // Croissant : anciens en premier, nouveaux en bas
    });
    
    sortedMessages.forEach(message => {
        const messageDiv = createMessageElement(message);
        container.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    container.scrollTop = container.scrollHeight;
}

// Create message element
function createMessageElement(message) {
    const isOwnMessage = message.sender_type === 'App\\Models\\Agency';
    const senderInfo = getSenderInfo(message.sender_type);
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'} mb-4`;
    
    messageDiv.innerHTML = `
        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${isOwnMessage ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900'}">
            <div class="flex items-center mb-1">
                <span class="text-xs font-medium ${isOwnMessage ? 'text-indigo-100' : 'text-gray-600'}">${message.sender.name || message.sender.nom || 'Utilisateur'}</span>
                <span class="ml-2 text-xs ${isOwnMessage ? 'text-indigo-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span>
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
    
    const message = messageInput.value.trim();
    if (!message) return;
    
    // Vérifier si le message contient un numéro de téléphone
    const phoneNumber = detectPhoneNumber(message);
    if (phoneNumber) {
        // Bloquer l'envoi et afficher une alerte d'erreur
        showPhoneNumberAlert(phoneNumber, true);
        // Mettre le focus sur le champ de saisie
        messageInput.focus();
        // Sélectionner le texte pour faciliter la suppression
        messageInput.select();
        return;
    }
    
    // Disable form
    sendButton.disabled = true;
    
    try {
        const response = await fetch(`/agence/support/messages/${currentTicketId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message
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
        await fetch(`/agence/support/messages/${currentTicketId}/mark-read`, {
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

// Fonction pour convertir les lettres en chiffres selon le clavier T9
function convertT9ToDigits(text) {
    const t9Map = {
        'a': '2', 'b': '2', 'c': '2',
        'd': '3', 'e': '3', 'f': '3',
        'g': '4', 'h': '4', 'i': '4',
        'j': '5', 'k': '5', 'l': '5',
        'm': '6', 'n': '6', 'o': '6',
        'p': '7', 'q': '7', 'r': '7', 's': '7',
        't': '8', 'u': '8', 'v': '8',
        'w': '9', 'x': '9', 'y': '9', 'z': '9'
    };
    
    return text.toLowerCase().split('').map(char => {
        return t9Map[char] || char;
    }).join('');
}

// Fonction pour normaliser un texte (convertir lettres en chiffres T9 et garder chiffres)
function normalizePhoneText(text) {
    // Convertir toutes les lettres en chiffres T9
    const normalized = convertT9ToDigits(text);
    // Garder seulement les chiffres et quelques séparateurs courants
    return normalized.replace(/[^\d+]/g, '');
}

// Fonction pour extraire les séquences qui pourraient être des numéros
function extractPhoneSequences(text) {
    const sequences = [];
    
    // Normaliser le texte (convertir lettres en chiffres T9)
    const normalized = normalizePhoneText(text);
    
    // Extraire les séquences de chiffres (8-15 chiffres)
    const digitSequences = normalized.match(/\d{8,15}/g);
    if (digitSequences) {
        sequences.push(...digitSequences);
    }
    
    // Chercher aussi dans le texte original avec lettres mélangées
    // Pattern: mix de chiffres et lettres (au moins 8 caractères)
    const mixedPattern = /[0-9a-z]{8,15}/gi;
    const mixedMatches = text.match(mixedPattern);
    
    if (mixedMatches) {
        mixedMatches.forEach(match => {
            const normalizedMatch = normalizePhoneText(match);
            if (normalizedMatch.length >= 8 && normalizedMatch.length <= 15) {
                sequences.push(normalizedMatch);
            }
        });
    }
    
    // Chercher les formats français avec séparateurs variés
    const frenchPatterns = [
        /(?:0|\+33|0033)[\s.\-]?[1-9][\s.\-a-z]?[\d\s.\-a-z]{8,}/gi,  // Format français flexible
        /[0][1-9][\s.\-a-z]?[\d\s.\-a-z]{8,}/gi  // Format 0X avec séparateurs
    ];
    
    frenchPatterns.forEach(pattern => {
        const matches = text.match(pattern);
        if (matches) {
            matches.forEach(match => {
                const normalizedMatch = normalizePhoneText(match);
                if (normalizedMatch.length >= 10 && normalizedMatch.length <= 15) {
                    sequences.push(normalizedMatch);
                }
            });
        }
    });
    
    return [...new Set(sequences)]; // Retourner les séquences uniques
}

// Fonction pour valider si une séquence est un vrai numéro de téléphone
function isValidPhoneNumber(sequence) {
    // Un numéro valide doit avoir entre 8 et 15 chiffres
    if (sequence.length < 8 || sequence.length > 15) {
        return false;
    }
    
    // Formats français: commence par 0 suivi de 1-9, puis 8 chiffres (total 10)
    if (sequence.match(/^0[1-9]\d{8}$/)) {
        return true;
    }
    
    // Formats internationaux français: +33 suivi de 9 chiffres
    if (sequence.match(/^\+?33[1-9]\d{8}$/)) {
        return true;
    }
    
    // Formats internationaux: commence par + suivi de 1-3 chiffres (code pays)
    if (sequence.match(/^\+?\d{1,3}\d{7,14}$/)) {
        return true;
    }
    
    // Numéros de 8 à 15 chiffres consécutifs (pour détecter les tentatives de contournement)
    if (sequence.length >= 8 && sequence.length <= 15 && /^\d+$/.test(sequence)) {
        // Vérifier qu'il ne s'agit pas d'une date ou d'un code postal
        // Exclure les dates (format YYYYMMDD ou similaire)
        if (sequence.length === 8 && sequence.match(/^(19|20)\d{6}$/)) {
            return false; // Probablement une date
        }
        return true;
    }
    
    return false;
}

// Fonction principale pour détecter les numéros de téléphone (version avancée)
function detectPhoneNumber(text) {
    if (!text || typeof text !== 'string') {
        return null;
    }
    
    // Extraire toutes les séquences potentielles
    const sequences = extractPhoneSequences(text);
    
    // Valider chaque séquence
    for (const sequence of sequences) {
        if (isValidPhoneNumber(sequence)) {
            // Retrouver la séquence originale dans le texte pour l'afficher
            const originalMatch = findOriginalMatch(text, sequence);
            return originalMatch || sequence;
        }
    }
    
    return null;
}

// Fonction pour retrouver la correspondance originale dans le texte
function findOriginalMatch(text, normalizedSequence) {
    // Chercher le pattern original qui correspond à la séquence normalisée
    const patterns = [
        // Pattern avec lettres mélangées (ex: 06aa22aa25)
        /[0-9a-z\s.\-]{8,20}/gi,
        // Pattern français standard
        /(?:0|\+33|0033)[\s.\-]?[1-9][\s.\-]?[\d\s.\-]{8,}/gi,
        // Pattern avec séparateurs variés
        /[0-9][\s.\-a-z]?[0-9a-z\s.\-]{7,}/gi
    ];
    
    for (const pattern of patterns) {
        const matches = text.match(pattern);
        if (matches) {
            for (const match of matches) {
                const normalized = normalizePhoneText(match);
                if (normalized === normalizedSequence || 
                    normalized.includes(normalizedSequence) || 
                    normalizedSequence.includes(normalized)) {
                    return match.trim();
                }
            }
        }
    }
    
    return normalizedSequence;
}

// Fonction pour afficher l'alerte de numéro de téléphone
function showPhoneNumberAlert(phoneNumber, isError = false) {
    // Vérifier si une alerte existe déjà
    let alertDiv = document.getElementById('phone-number-alert');
    
    // Supprimer l'ancienne alerte si elle existe
    if (alertDiv) {
        alertDiv.remove();
    }
    
    // Créer l'élément d'alerte
    alertDiv = document.createElement('div');
    alertDiv.id = 'phone-number-alert';
    
    if (isError) {
        // Alerte d'erreur (rouge) pour bloquer l'envoi
        alertDiv.className = 'fixed top-4 right-4 bg-red-50 border-l-4 border-red-500 p-4 shadow-lg rounded-md z-50 max-w-md';
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        Envoi bloqué - Numéro de téléphone détecté
                    </p>
                    <p class="mt-1 text-sm text-red-700">
                        Le message contient un numéro de téléphone: <strong>${phoneNumber}</strong><br>
                        <strong>Pour votre sécurité, les numéros de téléphone ne sont pas autorisés dans les messages.</strong><br>
                        Veuillez supprimer le numéro de téléphone avant d'envoyer le message.
                    </p>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-2 text-sm text-red-800 hover:text-red-900 underline font-medium">
                        Fermer
                    </button>
                </div>
            </div>
        `;
    } else {
        // Alerte d'avertissement (jaune) pour prévenir
        alertDiv.className = 'fixed top-4 right-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 shadow-lg rounded-md z-50 max-w-md';
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        Numéro de téléphone détecté
                    </p>
                    <p class="mt-1 text-sm text-yellow-700">
                        Vous avez saisi un numéro de téléphone: <strong>${phoneNumber}</strong><br>
                        <strong>Les numéros de téléphone ne sont pas autorisés dans les messages.</strong><br>
                        Le message ne pourra pas être envoyé tant que le numéro est présent.
                    </p>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-2 text-sm text-yellow-800 hover:text-yellow-900 underline">
                        Fermer
                    </button>
                </div>
            </div>
        `;
    }
    
    document.body.appendChild(alertDiv);
    
    // Auto-fermer après 8 secondes pour les erreurs, 10 secondes pour les avertissements
    setTimeout(() => {
        if (alertDiv && alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, isError ? 8000 : 10000);
}

// Initialiser la détection de numéro de téléphone sur le textarea
function initPhoneDetection() {
    const messageInput = document.getElementById('message-input');
    if (messageInput && !messageInput.hasAttribute('data-phone-detection-initialized')) {
        messageInput.setAttribute('data-phone-detection-initialized', 'true');
        let lastDetectedPhone = null;
        
        messageInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
            
            // Détecter les numéros de téléphone
            const text = this.value;
            const phoneNumber = detectPhoneNumber(text);
            
            if (phoneNumber && phoneNumber !== lastDetectedPhone) {
                lastDetectedPhone = phoneNumber;
                showPhoneNumberAlert(phoneNumber);
            }
        });
    }
}

// Initialiser au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    initPhoneDetection();
});
</script>
@endpush
@endsection

@endsection


