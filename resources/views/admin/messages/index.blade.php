@extends('layouts.admin')

@section('header', 'Messages')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto h-screen flex">
        <!-- Left Sidebar - Conversations List -->
        <div class="w-1/3 bg-white border-r border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-semibold text-gray-900">Messages</h1>
                    <div class="flex items-center space-x-2">
                        <!-- Filter Dropdown -->
                        <div class="relative">
                            <select id="conversation-filter" onchange="filterConversations(this.value)" class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-1.5 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="all">Tous les tickets</option>
                                <option value="open" {{ request('filter') === 'open' ? 'selected' : '' }}>Ouverts</option>
                                <option value="in_progress" {{ request('filter') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="resolved" {{ request('filter') === 'resolved' ? 'selected' : '' }}>Résolus</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           placeholder="Rechercher une conversation..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto">
                @if($allConversations->count() > 0)
                    @foreach($allConversations as $conversation)
                    <div class="conversation-item border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
                         data-type="{{ $conversation->type }}"
                         data-status="{{ $conversation->type === 'support' ? ($conversation->status ?? 'unknown') : 'all' }}"
                         data-conversation-id="{{ $conversation->type }}_{{ $conversation->id }}"
                         data-conversation-data="{{ htmlspecialchars(json_encode($conversation), ENT_QUOTES, 'UTF-8') }}"
                         onclick="selectConversation('{{ $conversation->type }}', '{{ $conversation->id }}', this.getAttribute('data-conversation-data'))">
                        <div class="p-4">
                            <div class="flex items-center space-x-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($conversation->type === 'rental')
                                        @if($conversation->image)
                                            <img src="{{ $conversation->image }}" alt="{{ $conversation->title }}" 
                                                 class="w-12 h-12 object-cover rounded-full">
                                        @else
                                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Conversation Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $conversation->type === 'rental' ? $conversation->title : Str::limit($conversation->title, 20) }}
                                            </h3>
                                            @if($conversation->type === 'support')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($conversation->status === 'open') bg-green-100 text-green-800
                                                    @elseif($conversation->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                    @elseif($conversation->status === 'resolved') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @if($conversation->status === 'open') Ouvert
                                                    @elseif($conversation->status === 'in_progress') En cours
                                                    @elseif($conversation->status === 'resolved') Résolu
                                                    @else Fermé @endif
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            @if($conversation->unread_count > 0)
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white 
                                                    {{ $conversation->type === 'rental' ? 'bg-orange-600' : 'bg-blue-600' }} rounded-full min-w-[18px] h-5">
                                                    {{ $conversation->unread_count }}
                                                </span>
                                            @endif
                                            @if($conversation->type === 'support')
                                                <span class="text-xs font-medium text-gray-600">
                                                    @if($conversation->status === 'open') Ouvert
                                                    @elseif($conversation->status === 'in_progress') En cours
                                                    @elseif($conversation->status === 'resolved') Résolu
                                                    @else Fermé @endif
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500">
                                                    {{ $conversation->last_message ? $conversation->last_message->created_at->diffForHumans() : $conversation->last_activity->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $conversation->subtitle }}
                                    </p>
                                    
                                    @if($conversation->last_message)
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm text-gray-600 truncate flex-1">
                                                @if($conversation->type === 'rental')
                                                    @if($conversation->last_message->sender_type === 'agency')
                                                        <span class="font-medium text-orange-600">Agence:</span>
                                                    @else
                                                        <span class="font-medium text-blue-600">Client:</span>
                                                    @endif
                                                @else
                                                    @if($conversation->last_message->sender_type === 'App\Models\User')
                                                        <span class="font-medium text-orange-600">Admin:</span>
                                                    @else
                                                        <span class="font-medium text-blue-600">{{ ucfirst($conversation->last_message->sender_type) }}:</span>
                                                    @endif
                                                @endif
                                                {{ Str::limit($conversation->last_message->message, 35) }}
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400 italic">Aucun message encore</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">Aucune conversation</h3>
                            <p class="text-xs text-gray-500">Vos conversations apparaîtront ici</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Content Area - Chat View -->
        <div class="flex-1 bg-white flex flex-col">
            <!-- Welcome Screen (Default) -->
            <div id="welcome-screen" class="flex-1 flex items-center justify-center bg-gray-50">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">TOUBCAR Support Admin</h2>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Sélectionnez un ticket de support pour répondre aux demandes des clients et agences.
                    </p>
                </div>
            </div>

            <!-- Chat Interface (Hidden by default) -->
            <div id="chat-interface" class="flex-1 flex flex-col hidden">
                <!-- Chat Header -->
                <div id="chat-header" class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <!-- Avatar -->
                        <div id="chat-avatar" class="flex-shrink-0">
                            <!-- Avatar will be loaded here -->
                        </div>

                        <!-- Chat Info -->
                        <div class="flex-1 min-w-0">
                            <h2 id="chat-title" class="text-lg font-semibold text-gray-900 truncate">
                                <!-- Title will be loaded here -->
                            </h2>
                            <p id="chat-subtitle" class="text-sm text-gray-500">
                                <!-- Subtitle will be loaded here -->
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <button onclick="exitConversation()" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors" title="Fermer la conversation (Échap)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </button>
                            <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 bg-gray-50">
                    <!-- Messages will be loaded here -->
                    <div class="text-center text-gray-500 mt-8">
                        <p>Chargement des messages...</p>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <div class="flex items-center space-x-3">
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </button>
                        <div class="flex-1">
                            <textarea id="message-input" placeholder="Tapez votre message..." 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-full resize-none focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                      rows="1"></textarea>
                        </div>
                        <button onclick="sendMessage()" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedConversationId = null;
let selectedConversationData = null;

function selectConversation(type, id, conversationData) {
    console.log('Selecting conversation:', type, id, conversationData);
    
    try {
        selectedConversationId = `${type}_${id}`;
        
        // Parse conversation data safely
        if (typeof conversationData === 'string') {
            // Decode HTML entities first
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = conversationData;
            const decoded = tempDiv.textContent || tempDiv.innerText || conversationData;
            
            // Try to parse as JSON
            try {
                selectedConversationData = JSON.parse(decoded);
            } catch (e) {
                // If parsing still fails, try with manual unescaping
                try {
                    const unescaped = decoded.replace(/&quot;/g, '"').replace(/&#039;/g, "'").replace(/&#39;/g, "'").replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
                    selectedConversationData = JSON.parse(unescaped);
                } catch (e2) {
                    console.error('Error parsing conversation data:', e2);
                    console.error('Raw data:', conversationData);
                    console.error('Decoded data:', decoded);
                    alert('Erreur lors de l\'ouverture de la conversation: Données invalides.');
                    return;
                }
            }
        } else if (typeof conversationData === 'object') {
            // Already an object
            selectedConversationData = conversationData;
        } else {
            console.error('Invalid conversation data type:', typeof conversationData);
            alert('Erreur lors de l\'ouverture de la conversation: Format de données invalide.');
            return;
        }
        
        console.log('Parsed conversation data:', selectedConversationData);
        
        // Update UI to show selected conversation
        document.querySelectorAll('.bg-orange-50').forEach(el => {
            el.classList.remove('bg-orange-50', 'border-r-orange-500');
        });
        
        // Find and highlight the clicked conversation item
        const conversationElement = document.querySelector(`[data-conversation-id="${type}_${id}"]`);
        if (conversationElement) {
            conversationElement.classList.add('bg-orange-50', 'border-r-orange-500');
        }
        
        // Show chat interface and hide welcome screen
        document.getElementById('welcome-screen').classList.add('hidden');
        document.getElementById('chat-interface').classList.remove('hidden');
        
        // Load conversation data
        loadConversationData(selectedConversationData, type);
    } catch (error) {
        console.error('Error in selectConversation:', error);
        alert('Erreur lors de l\'ouverture de la conversation: ' + error.message);
    }
}

function loadConversationData(conversation, type) {
    // Update chat header
    document.getElementById('chat-title').textContent = conversation.title;
    document.getElementById('chat-subtitle').textContent = conversation.subtitle;
    
    // Update avatar
    const avatarContainer = document.getElementById('chat-avatar');
    if (type === 'rental') {
        if (conversation.image) {
            avatarContainer.innerHTML = `<img src="${conversation.image}" alt="${conversation.title}" class="w-10 h-10 object-cover rounded-full">`;
        } else {
            avatarContainer.innerHTML = `
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                </div>
            `;
        }
    } else {
        avatarContainer.innerHTML = `
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                </svg>
            </div>
        `;
    }
    
    // Load messages based on conversation type
    if (type === 'support') {
        loadSupportMessages(conversation);
    } else {
        loadMessages(conversation);
    }
}

// Load rental messages for rental conversations
async function loadMessages(conversation) {
    const messagesContainer = document.getElementById('messages-container');
    
    // Show loading state
    messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Chargement des messages...</p></div>';
    
    try {
        // Extract rental ID from conversation data
        const rentalId = conversation.id || conversation.original?.id;
        
        if (!rentalId) {
            messagesContainer.innerHTML = `
                <div class="text-center text-gray-500 mt-8">
                    <p>Impossible de charger les messages. ID de réservation manquant.</p>
                </div>
            `;
            return;
        }
        
        // Fetch all messages from rental (using lastMessageId=0 to get all messages)
        const response = await fetch(`/admin/messages/${rentalId}/new?last_message_id=0`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.messages && data.messages.length > 0) {
            displayRentalMessages(data.messages);
        } else {
            messagesContainer.innerHTML = `
                <div class="text-center text-gray-500 mt-8">
                    <p>Aucun message dans cette conversation</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading rental messages:', error);
        messagesContainer.innerHTML = `
            <div class="text-center text-red-500 mt-8">
                <p>Erreur lors du chargement des messages</p>
            </div>
        `;
    }
}

// Display rental messages
function displayRentalMessages(messages) {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = `
            <div class="text-center text-gray-500 mt-8">
                <p>Aucun message dans cette conversation</p>
            </div>
        `;
        return;
    }
    
    messages.forEach(message => {
        const isFromAdmin = message.sender_type === 'App\\Models\\User';
        const messageAlignment = isFromAdmin ? 'justify-end' : 'justify-start';
        const messageBgColor = isFromAdmin ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-900';
        const senderLabel = isFromAdmin ? 'Admin' : (message.sender_type === 'agency' ? 'Agence' : 'Client');
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${messageAlignment} mb-4`;
        messageDiv.innerHTML = `
            <div class="${messageBgColor} px-4 py-2 rounded-lg max-w-xs">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-medium ${isFromAdmin ? 'text-orange-100' : 'text-gray-600'}">${senderLabel}</span>
                    <span class="ml-2 text-xs ${isFromAdmin ? 'text-orange-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span>
                </div>
                <p class="text-sm whitespace-pre-wrap">${escapeHtml(message.message)}</p>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load support messages for support conversations
async function loadSupportMessages(conversation) {
    const messagesContainer = document.getElementById('messages-container');
    
    // Show loading state
    messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Chargement des messages...</p></div>';
    
    try {
        // Extract ticket ID from conversation data
        const ticketId = conversation.id;
        
        // Fetch messages from support ticket
        const response = await fetch(`/admin/support/messages/${ticketId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.messages) {
            displaySupportMessages(data.messages);
        } else {
            messagesContainer.innerHTML = `
                <div class="text-center text-gray-500 mt-8">
                    <p>Aucun message dans ce ticket de support</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading support messages:', error);
        messagesContainer.innerHTML = `
            <div class="text-center text-red-500 mt-8">
                <p>Erreur lors du chargement des messages</p>
            </div>
        `;
    }
}

// Display support messages
function displaySupportMessages(messages) {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = `
            <div class="text-center text-gray-500 mt-8">
                <p>Aucun message dans ce ticket</p>
            </div>
        `;
        return;
    }
    
    messages.forEach(message => {
        const isFromAdmin = message.sender_type === 'App\\Models\\User';
        const messageAlignment = isFromAdmin ? 'justify-end' : 'justify-start';
        const messageBgColor = isFromAdmin ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-900';
        const senderLabel = isFromAdmin ? 'Admin' : (message.sender_type === 'App\\Models\\Client' ? 'Client' : 'Agence');
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${messageAlignment} mb-4`;
        messageDiv.innerHTML = `
            <div class="${messageBgColor} px-4 py-2 rounded-lg max-w-xs">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-medium ${isFromAdmin ? 'text-orange-100' : 'text-gray-600'}">${senderLabel}</span>
                    <span class="ml-2 text-xs ${isFromAdmin ? 'text-orange-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span>
                </div>
                <p class="text-sm whitespace-pre-wrap">${message.message}</p>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

async function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value.trim();
    
    if (message && selectedConversationData) {
        // Determine conversation type
        const conversationType = selectedConversationId.split('_')[0];
        
        if (conversationType === 'support') {
            // Send support message
            await sendSupportMessage(message);
        } else {
            // Send rental message (existing logic)
            await sendRentalMessage(message);
        }
        
        // Clear input
        messageInput.value = '';
        messageInput.style.height = 'auto';
    }
}

// Send message to support ticket
async function sendSupportMessage(message) {
    try {
        const ticketId = selectedConversationData.id;
        
        const response = await fetch(`/admin/support/messages/${ticketId}/send`, {
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
            // Reload support messages
            await loadSupportMessages(selectedConversationData);
        } else {
            alert('Erreur lors de l\'envoi du message');
        }
    } catch (error) {
        console.error('Error sending support message:', error);
        alert('Erreur lors de l\'envoi du message');
    }
}

// Send message to rental conversation
async function sendRentalMessage(message) {
    // Add message to UI immediately
    const messagesContainer = document.getElementById('messages-container');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex justify-end mb-4';
    messageDiv.innerHTML = `
        <div class="bg-orange-600 text-white px-4 py-2 rounded-lg max-w-xs">
            <p class="text-sm">${message}</p>
            <p class="text-xs opacity-75 mt-1">Maintenant</p>
        </div>
    `;
    messagesContainer.appendChild(messageDiv);
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // In real app, send message via AJAX
    console.log('Sending rental message:', message, 'to conversation:', selectedConversationData.id);
}

// Function to exit conversation (like WhatsApp)
function exitConversation() {
    // Clear selection
    selectedConversationId = null;
    selectedConversationData = null;
    
    // Remove selection styling
    document.querySelectorAll('.bg-orange-50').forEach(el => {
        el.classList.remove('bg-orange-50', 'border-r-orange-500');
    });
    
    // Show welcome screen and hide chat interface
    document.getElementById('welcome-screen').classList.remove('hidden');
    document.getElementById('chat-interface').classList.add('hidden');
    
    // Clear messages container
    document.getElementById('messages-container').innerHTML = '';
}

// Filter conversations by status
function filterConversations(filterStatus) {
    const conversations = document.querySelectorAll('.conversation-item');
    
    conversations.forEach(conversation => {
        const conversationStatus = conversation.dataset.status || 'all';
        const shouldShow = filterStatus === 'all' || conversationStatus === filterStatus;
        conversation.style.display = shouldShow ? 'block' : 'none';
    });
    
    // Update selected conversation if it's hidden
    if (selectedConversationId) {
        const selectedElement = document.querySelector(`[data-conversation-id="${selectedConversationId}"]`);
        if (selectedElement && selectedElement.style.display === 'none') {
            // Clear selection if current conversation is filtered out
            selectedConversationId = null;
            selectedConversationData = null;
            
            // Show welcome screen
            document.getElementById('welcome-screen').classList.remove('hidden');
            document.getElementById('chat-interface').classList.add('hidden');
            
            // Remove selection styling
            document.querySelectorAll('.bg-orange-50').forEach(el => {
                el.classList.remove('bg-orange-50', 'border-r-orange-500');
            });
        }
    }
}

// Auto-resize textarea and apply initial filter
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message-input');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Send message on Enter key
        textarea.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // Apply initial filter from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const filterParam = urlParams.get('filter');
    if (filterParam && ['all','open','in_progress','resolved'].includes(filterParam)) {
        const select = document.getElementById('conversation-filter');
        if (select) select.value = filterParam;
        filterConversations(filterParam);
    } else {
        filterConversations('all');
    }
});

// Handle Escape key to exit conversation (like WhatsApp)
document.addEventListener('keydown', function(e) {
    // Only handle Escape key when chat interface is visible
    if (e.key === 'Escape' && !document.getElementById('chat-interface').classList.contains('hidden')) {
        exitConversation();
    }
});
</script>
@endpush
