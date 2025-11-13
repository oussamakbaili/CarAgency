@extends('layouts.public')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Messages</h1>
            <p class="text-gray-600">Toutes vos conversations en un seul endroit</p>
        </div>

        <!-- Conversations Section (No Sidebar) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <!-- Header with Filter -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Conversations</h2>
                    <div class="flex items-center space-x-2">
                        <!-- Filter Dropdown -->
                        <div class="relative">
                            <select id="conversation-filter" onchange="filterConversations(this.value)" class="appearance-none bg-white border border-gray-300 rounded-lg px-3 py-1.5 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="all">Tous</option>
                                <option value="support" {{ request('filter') === 'support' ? 'selected' : '' }}>Message Support</option>
                                <option value="rental" {{ request('filter') === 'rental' ? 'selected' : '' }}>Message Agence</option>
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
            <div class="max-h-64 overflow-y-auto">
                @if($allConversations->count() > 0)
                    @foreach($allConversations as $conversation)
                    <div class="conversation-item border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
                         data-type="{{ $conversation->type }}"
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
                                                    @if($conversation->last_message->sender_type === 'client')
                                                        <span class="font-medium text-orange-600">Vous:</span>
                                                    @else
                                                        <span class="font-medium text-blue-600">Agence:</span>
                                                    @endif
                                                @else
                                                    @if($conversation->last_message->sender_type === 'App\Models\Client')
                                                        <span class="font-medium text-orange-600">Vous:</span>
                                                    @else
                                                        <span class="font-medium text-blue-600">Support:</span>
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
                    <div class="flex items-center justify-center py-12">
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

        <!-- Chat Interface (Hidden by default) -->
        <div id="chat-interface" class="bg-white rounded-lg shadow-sm border border-gray-200 hidden">
            <!-- Chat Header -->
            <div id="chat-header" class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
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
                    </div>
                </div>
            </div>

            <!-- Welcome Screen (Default) -->
            <div id="welcome-screen" class="flex items-center justify-center bg-gray-50 py-20">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">TOUBCAR Messages</h2>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Sélectionnez une conversation pour commencer à échanger avec nos agences ou notre équipe de support.
                    </p>
                </div>
            </div>

            <!-- Messages Area (Hidden by default) -->
            <div id="messages-container" class="hidden flex-1 overflow-y-auto p-4 bg-gray-50" style="max-height: 500px; min-height: 300px;">
                <!-- Messages will be loaded here -->
                <div class="text-center text-gray-500 mt-8">
                    <p>Chargement des messages...</p>
                </div>
            </div>

            <!-- Message Input (Hidden by default) -->
            <div id="message-input-section" class="hidden p-4 border-t border-gray-200 bg-white rounded-b-lg">
                <!-- Keyboard Language Selector -->
                <div class="flex items-center justify-end mb-2 space-x-2">
                    <span class="text-xs text-gray-500">Clavier:</span>
                    <div class="flex items-center space-x-1 bg-gray-100 rounded-lg p-1">
                        <button id="keyboard-fr" data-keyboard="fr" class="keyboard-btn px-2 py-1 text-xs font-medium rounded transition-colors bg-white text-gray-700 shadow-sm" title="Français">
                            FR
                        </button>
                        <button id="keyboard-ar" data-keyboard="ar" class="keyboard-btn px-2 py-1 text-xs font-medium rounded transition-colors text-gray-500 hover:bg-gray-200" title="العربية">
                            AR
                        </button>
                        <button id="keyboard-en" data-keyboard="en" class="keyboard-btn px-2 py-1 text-xs font-medium rounded transition-colors text-gray-500 hover:bg-gray-200" title="English">
                            EN
                        </button>
                    </div>
                </div>
                <!-- Preview des fichiers sélectionnés -->
                <div id="file-preview-container" class="hidden flex flex-wrap gap-2 mb-2"></div>
                
                <div class="flex items-center space-x-3">
                    <!-- File Input (caché) -->
                    <input type="file" id="file-input" class="hidden" accept="image/*,application/pdf,.doc,.docx,.txt" multiple>
                    
                    <!-- Attachment Button -->
                    <button type="button" id="attach-file-btn" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors" title="Joindre un fichier">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </button>
                    
                    <div class="flex-1">
                        <textarea id="message-input" placeholder="Tapez votre message..." 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-full resize-none focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                  rows="1" dir="ltr"></textarea>
                    </div>
                    <button type="button" id="send-message-btn" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.mobile-bottom-nav')
@endsection

@push('scripts')
<script>
// Same JavaScript as client/messages but adapted for public layout without sidebar
let selectedConversationId = null;
let selectedConversationData = null;

function selectConversation(type, id, conversationData) {
    try {
        selectedConversationId = `${type}_${id}`;
        
        // Parse conversation data safely
        if (typeof conversationData === 'string') {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = conversationData;
            const decoded = tempDiv.textContent || tempDiv.innerText || conversationData;
            
            try {
                selectedConversationData = JSON.parse(decoded);
            } catch (e) {
                try {
                    const unescaped = decoded.replace(/&quot;/g, '"').replace(/&#039;/g, "'").replace(/&#39;/g, "'").replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
                    selectedConversationData = JSON.parse(unescaped);
                } catch (e2) {
                    console.error('Error parsing conversation data:', e2);
                    alert('Erreur lors de l\'ouverture de la conversation: Données invalides.');
                    return;
                }
            }
        } else if (typeof conversationData === 'object') {
            selectedConversationData = conversationData;
        } else {
            console.error('Invalid conversation data type:', typeof conversationData);
            alert('Erreur lors de l\'ouverture de la conversation: Format de données invalide.');
            return;
        }
        
        // Clear selected files
        clearSelectedFiles();
        
        // Update UI to show selected conversation
        document.querySelectorAll('.bg-orange-50').forEach(el => {
            el.classList.remove('bg-orange-50', 'border-r-orange-500');
        });
        
        const conversationElement = document.querySelector(`[data-conversation-id="${type}_${id}"]`);
        if (conversationElement) {
            conversationElement.classList.add('bg-orange-50', 'border-r-orange-500');
        }
        
        // Show chat interface and hide welcome screen
        const welcomeScreen = document.getElementById('welcome-screen');
        const chatInterface = document.getElementById('chat-interface');
        const messagesContainer = document.getElementById('messages-container');
        const messageInputSection = document.getElementById('message-input-section');
        
        if (chatInterface) {
            chatInterface.classList.remove('hidden');
            if (welcomeScreen) welcomeScreen.classList.add('hidden');
            if (messagesContainer) messagesContainer.classList.remove('hidden');
            if (messageInputSection) messageInputSection.classList.remove('hidden');
            
            // Initialize after showing
            setTimeout(function() {
                initFileUpload();
                initKeyboard();
                
                const sendButton = document.getElementById('send-message-btn');
                if (sendButton) {
                    sendButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (typeof window.sendMessage === 'function') {
                            window.sendMessage();
                        }
                    });
                }
                
                const messageInput = document.getElementById('message-input');
                if (messageInput) {
                    messageInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            if (typeof window.sendMessage === 'function') {
                                window.sendMessage();
                            }
                        }
                    });
                }
            }, 150);
        }
        
        // Load conversation data
        loadConversationData(selectedConversationData, type);
        
    } catch (error) {
        console.error('❌ Error in selectConversation:', error);
        alert('Erreur lors de l\'ouverture de la conversation: ' + error.message);
    }
}

function loadConversationData(conversation, type) {
    document.getElementById('chat-title').textContent = conversation.title;
    document.getElementById('chat-subtitle').textContent = conversation.subtitle;
    
    const avatarContainer = document.getElementById('chat-avatar');
    if (type === 'rental') {
        if (conversation.image) {
            avatarContainer.innerHTML = `<img src="${conversation.image}" alt="${conversation.title}" class="w-10 h-10 object-cover rounded-full">`;
        } else {
            avatarContainer.innerHTML = `<div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>`;
        }
    } else {
        avatarContainer.innerHTML = `<div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/></svg></div>`;
    }
    
    if (type === 'support') {
        loadSupportMessages(conversation);
    } else {
        loadMessages(conversation);
    }
}

async function loadMessages(conversation) {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Chargement des messages...</p></div>';
    
    try {
        let rentalId = conversation.id || conversation.original?.id;
        if (typeof rentalId === 'string' && rentalId.startsWith('rental_')) {
            rentalId = rentalId.replace('rental_', '');
        }
        
        if (!rentalId) {
            messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Impossible de charger les messages. ID de réservation manquant.</p></div>';
            return;
        }
        
        const response = await fetch(`/client/messages/${rentalId}/new?last_message_id=0`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.messages && data.messages.length > 0) {
            displayRentalMessages(data.messages);
        } else {
            messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Aucun message dans cette conversation</p></div>';
        }
    } catch (error) {
        console.error('Error loading rental messages:', error);
        messagesContainer.innerHTML = '<div class="text-center text-red-500 mt-8"><p>Erreur lors du chargement des messages</p></div>';
    }
}

function displayRentalMessages(messages) {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Aucun message dans cette conversation</p></div>';
        return;
    }
    
    const sortedMessages = [...messages].sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
    });
    
    sortedMessages.forEach(message => {
        const isFromClient = message.sender_type === 'client';
        const messageAlignment = isFromClient ? 'justify-end' : 'justify-start';
        const messageBgColor = isFromClient ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-900';
        const senderLabel = isFromClient ? 'Vous' : 'Agence';
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${messageAlignment} mb-4`;
        
        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = '<div class="mt-2 space-y-2">';
            message.attachments.forEach(attachment => {
                const isImage = attachment.type && attachment.type.startsWith('image/');
                if (isImage) {
                    attachmentsHtml += `<div class="rounded-lg overflow-hidden"><img src="${attachment.url}" alt="${attachment.name}" class="max-w-full h-auto rounded-lg cursor-pointer" onclick="window.open('${attachment.url}', '_blank')"></div>`;
                } else {
                    attachmentsHtml += `<a href="${attachment.url}" target="_blank" class="flex items-center gap-2 p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><div class="flex-1 min-w-0"><p class="text-sm font-medium truncate">${escapeHtml(attachment.name)}</p><p class="text-xs text-gray-500">${formatFileSize(attachment.size || 0)}</p></div></a>`;
                }
            });
            attachmentsHtml += '</div>';
        }
        
        messageDiv.innerHTML = `<div class="${messageBgColor} px-4 py-2 rounded-lg max-w-xs lg:max-w-md"><div class="flex items-center mb-1"><span class="text-xs font-medium ${isFromClient ? 'text-orange-100' : 'text-gray-600'}">${senderLabel}</span><span class="ml-2 text-xs ${isFromClient ? 'text-orange-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span></div>${message.message ? `<p class="text-sm whitespace-pre-wrap">${escapeHtml(message.message)}</p>` : ''}${attachmentsHtml}</div>`;
        
        messagesContainer.appendChild(messageDiv);
    });
    
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function loadSupportMessages(conversation) {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Chargement des messages...</p></div>';
    
    try {
        const ticketId = String(conversation.id).replace('support_', '');
        const response = await fetch(`/client/support/messages/${ticketId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.messages) {
            displaySupportMessages(data.messages);
        } else {
            messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Aucun message dans ce ticket de support</p></div>';
        }
    } catch (error) {
        console.error('Error loading support messages:', error);
        messagesContainer.innerHTML = '<div class="text-center text-red-500 mt-8"><p>Erreur lors du chargement des messages</p></div>';
    }
}

function displaySupportMessages(messages) {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = '<div class="text-center text-gray-500 mt-8"><p>Aucun message dans ce ticket</p></div>';
        return;
    }
    
    const sortedMessages = [...messages].sort((a, b) => {
        return new Date(a.created_at) - new Date(b.created_at);
    });
    
    sortedMessages.forEach(message => {
        const isOwnMessage = message.sender_type === 'App\\Models\\Client';
        const messageAlignment = isOwnMessage ? 'justify-end' : 'justify-start';
        const messageBgColor = isOwnMessage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900';
        const senderLabel = isOwnMessage ? 'Vous' : 'Support';
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${messageAlignment} mb-4`;
        messageDiv.innerHTML = `<div class="${messageBgColor} px-4 py-2 rounded-lg max-w-xs"><div class="flex items-center mb-1"><span class="text-xs font-medium ${isOwnMessage ? 'text-blue-100' : 'text-gray-600'}">${senderLabel}</span><span class="ml-2 text-xs ${isOwnMessage ? 'text-blue-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span></div><p class="text-sm whitespace-pre-wrap">${message.message}</p></div>`;
        
        messagesContainer.appendChild(messageDiv);
    });
    
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

window.selectedFiles = window.selectedFiles || [];
let fileUploadInitialized = false;

function initFileUpload() {
    const fileInput = document.getElementById('file-input');
    const attachBtn = document.getElementById('attach-file-btn');
    
    if (!fileInput || !attachBtn || fileUploadInitialized) return;
    
    attachBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.click();
    });
    
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            const maxSize = 10 * 1024 * 1024;
            const validFiles = files.filter(file => file.size <= maxSize);
            window.selectedFiles = [...(window.selectedFiles || []), ...validFiles];
            updateFilePreview();
        }
        e.target.value = '';
    });
    
    fileUploadInitialized = true;
}

function updateFilePreview() {
    const filePreviewContainer = document.getElementById('file-preview-container');
    if (!filePreviewContainer) return;
    
    const files = window.selectedFiles || [];
    if (files.length === 0) {
        filePreviewContainer.classList.add('hidden');
        filePreviewContainer.innerHTML = '';
        return;
    }
    
    filePreviewContainer.classList.remove('hidden');
    filePreviewContainer.innerHTML = '';
    
    files.forEach((file, index) => {
        const fileDiv = document.createElement('div');
        fileDiv.className = 'flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 text-sm';
        fileDiv.innerHTML = `<span class="flex-1 truncate">${escapeHtml(file.name)}</span><span class="text-xs text-gray-500">${formatFileSize(file.size)}</span><button type="button" class="text-red-500 hover:text-red-700" onclick="removeFile(${index})">×</button>`;
        filePreviewContainer.appendChild(fileDiv);
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

window.removeFile = function(index) {
    const files = window.selectedFiles || [];
    if (index >= 0 && index < files.length) {
        window.selectedFiles.splice(index, 1);
        updateFilePreview();
    }
};

function clearSelectedFiles() {
    window.selectedFiles = [];
    updateFilePreview();
    const fileInput = document.getElementById('file-input');
    if (fileInput) fileInput.value = '';
}

let isSending = false;

window.sendMessage = async function sendMessage() {
    if (isSending) return;
    isSending = true;
    
    try {
        const messageInput = document.getElementById('message-input');
        const message = messageInput ? messageInput.value.trim() : '';
        const files = window.selectedFiles || [];
        const hasMessage = message && message.length > 0;
        const hasFiles = files.length > 0;
        
        if ((!hasMessage && !hasFiles) || !selectedConversationData) {
            if (!hasMessage && !hasFiles) {
                alert('Veuillez saisir un message ou sélectionner un fichier');
            }
            return;
        }
        
        const conversationType = selectedConversationId ? selectedConversationId.split('_')[0] : null;
        if (!conversationType) {
            alert('Erreur: Type de conversation non trouvé');
            return;
        }
        
        let sendSuccess = false;
        
        if (conversationType === 'support') {
            sendSuccess = await sendSupportMessage(message || '', files);
        } else {
            sendSuccess = await sendRentalMessage(message || '', files);
        }
        
        if (sendSuccess) {
            if (messageInput) messageInput.value = '';
            window.selectedFiles = [];
            updateFilePreview();
            const fileInput = document.getElementById('file-input');
            if (fileInput) fileInput.value = '';
        }
    } finally {
        setTimeout(() => { isSending = false; }, 1000);
    }
};

async function sendSupportMessage(message, filesToSend) {
    try {
        const ticketId = String(selectedConversationData.id).replace('support_', '');
        const formData = new FormData();
        formData.append('message', message || '');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        if (filesToSend && filesToSend.length > 0) {
            filesToSend.forEach((file, index) => {
                if (file instanceof File) {
                    formData.append(`attachments[${index}]`, file);
                }
            });
        }
        
        const response = await fetch(`/client/support/messages/${ticketId}/send`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        
        if (!response.ok) {
            alert('Erreur lors de l\'envoi du message');
            return false;
        }
        
        const data = await response.json();
        if (data.success) {
            await loadSupportMessages(selectedConversationData);
            return true;
        } else {
            alert(data.message || 'Erreur lors de l\'envoi du message');
            return false;
        }
    } catch (error) {
        console.error('Error sending support message:', error);
        alert('Erreur lors de l\'envoi du message: ' + error.message);
        return false;
    }
}

async function sendRentalMessage(message, filesToSend) {
    try {
        let rentalId = selectedConversationData.id || selectedConversationData.original?.id;
        if (typeof rentalId === 'string' && rentalId.startsWith('rental_')) {
            rentalId = rentalId.replace('rental_', '');
        }
        
        if (!rentalId) {
            alert('Impossible d\'envoyer le message. ID de réservation manquant.');
            return false;
        }
        
        const formData = new FormData();
        formData.append('message', message || '');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        if (filesToSend && filesToSend.length > 0) {
            filesToSend.forEach((file, index) => {
                if (file instanceof File) {
                    formData.append(`attachments[${index}]`, file);
                }
            });
        }
        
        const response = await fetch(`/client/messages/${rentalId}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        
        if (!response.ok) {
            alert('Erreur lors de l\'envoi du message');
            return false;
        }
        
        const data = await response.json();
        if (data.success) {
            await loadMessages(selectedConversationData);
            return true;
        } else {
            alert(data.message || 'Erreur lors de l\'envoi du message');
            return false;
        }
    } catch (error) {
        console.error('Error sending rental message:', error);
        alert('Erreur lors de l\'envoi du message: ' + error.message);
        return false;
    }
}

function exitConversation() {
    selectedConversationId = null;
    selectedConversationData = null;
    
    document.querySelectorAll('.bg-orange-50').forEach(el => {
        el.classList.remove('bg-orange-50', 'border-r-orange-500');
    });
    
    const welcomeScreen = document.getElementById('welcome-screen');
    const chatInterface = document.getElementById('chat-interface');
    const messagesContainer = document.getElementById('messages-container');
    const messageInputSection = document.getElementById('message-input-section');
    
    if (welcomeScreen) welcomeScreen.classList.remove('hidden');
    if (chatInterface) chatInterface.classList.add('hidden');
    if (messagesContainer) {
        messagesContainer.classList.add('hidden');
        messagesContainer.innerHTML = '';
    }
    if (messageInputSection) messageInputSection.classList.add('hidden');
}

function filterConversations(filterType) {
    const conversations = document.querySelectorAll('.conversation-item');
    conversations.forEach(conversation => {
        const conversationType = conversation.getAttribute('data-type');
        if (filterType === 'all' || conversationType === filterType) {
            conversation.style.display = 'block';
        } else {
            conversation.style.display = 'none';
        }
    });
}

let currentKeyboard = 'fr';
window.changeKeyboard = function(lang) {
    currentKeyboard = lang;
    const messageInput = document.getElementById('message-input');
    if (!messageInput) return;
    
    document.querySelectorAll('.keyboard-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-gray-700', 'shadow-sm');
        btn.classList.add('text-gray-500', 'hover:bg-gray-200');
    });
    
    const activeBtn = document.getElementById(`keyboard-${lang}`);
    if (activeBtn) {
        activeBtn.classList.add('bg-white', 'text-gray-700', 'shadow-sm');
        activeBtn.classList.remove('text-gray-500', 'hover:bg-gray-200');
    }
    
    if (lang === 'ar') {
        messageInput.dir = 'rtl';
        messageInput.style.textAlign = 'right';
        messageInput.placeholder = 'اكتب رسالتك...';
    } else {
        messageInput.dir = 'ltr';
        messageInput.style.textAlign = 'left';
        messageInput.placeholder = lang === 'en' ? 'Type your message...' : 'Tapez votre message...';
    }
    
    localStorage.setItem('preferredKeyboard', lang);
};

function initKeyboard() {
    const messageInput = document.getElementById('message-input');
    if (!messageInput) return;
    
    document.querySelectorAll('.keyboard-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-keyboard');
            if (lang) window.changeKeyboard(lang);
        });
    });
    
    const savedKeyboard = localStorage.getItem('preferredKeyboard') || 'fr';
    window.changeKeyboard(savedKeyboard);
}

document.addEventListener('DOMContentLoaded', function() {
    initFileUpload();
    initKeyboard();
    
    const textarea = document.getElementById('message-input');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const chatInterface = document.getElementById('chat-interface');
            if (chatInterface && !chatInterface.classList.contains('hidden')) {
                exitConversation();
            }
        }
    });
});
</script>
@endpush

