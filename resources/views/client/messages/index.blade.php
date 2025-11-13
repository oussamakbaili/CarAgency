@extends('layouts.client')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto h-screen flex">
        <!-- Left Sidebar - Conversations List -->
        <!-- Mobile: Full width, Desktop: 1/3 width -->
        <div id="conversations-list" class="w-full md:w-1/3 bg-white border-r border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-semibold text-gray-900">Messages</h1>
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
            <div class="flex-1 overflow-y-auto">

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
        <!-- Mobile: Hidden by default, shown when conversation selected. Desktop: Always visible -->
        <div id="chat-view" class="hidden md:flex flex-1 bg-white flex flex-col">
            <!-- Welcome Screen (Default) -->
            <div id="welcome-screen" class="flex-1 flex items-center justify-center bg-gray-50">
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

            <!-- Chat Interface (Hidden by default) -->
            <div id="chat-interface" class="flex-1 flex flex-col hidden" style="min-height: 0; max-height: 100vh; overflow: hidden;">
                <!-- Chat Header -->
                <div id="chat-header" class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <!-- Back Button (Mobile only) -->
                        <button id="back-to-conversations" onclick="backToConversations()" class="md:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors" title="Retour aux conversations">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
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
                            <button onclick="exitConversation()" class="hidden md:block p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors" title="Fermer la conversation (Échap)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-3 md:p-4 bg-gray-50 pb-24 md:pb-4" style="max-height: calc(100vh - 200px); min-height: 0;">
                    <!-- Messages will be loaded here -->
                    <div class="text-center text-gray-500 mt-8">
                        <p>Chargement des messages...</p>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-3 md:p-4 border-t border-gray-200 bg-white fixed bottom-0 left-0 right-0 md:relative md:bottom-auto md:left-auto md:right-auto">
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
                    <div id="file-preview-container" class="hidden flex flex-wrap gap-2 mb-2 px-4"></div>
                    
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
</div>
@endsection

@push('scripts')
<script>
let selectedConversationId = null;
let selectedConversationData = null;

function selectConversation(type, id, conversationData) {
    console.log('🎯 selectConversation called with:', { type, id, conversationData });
    
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
        
        console.log('✅ Parsed conversation data:', selectedConversationData);
        
        // Effacer les fichiers sélectionnés quand on change de conversation
        clearSelectedFiles();
        
        // Update UI to show selected conversation
        document.querySelectorAll('.bg-orange-50').forEach(el => {
            el.classList.remove('bg-orange-50', 'border-r-orange-500');
        });
        
        // Find and highlight the clicked conversation item
        const conversationElement = document.querySelector(`[data-conversation-id="${type}_${id}"]`);
        console.log('🔍 Looking for element with ID:', `${type}_${id}`);
        console.log('🔍 Found element:', conversationElement);
        
        if (conversationElement) {
            conversationElement.classList.add('bg-orange-50', 'border-r-orange-500');
            console.log('✅ Element highlighted');
        } else {
            console.error('❌ Conversation element not found!');
        }
        
        // Mobile: Hide conversations list and show chat view
        const conversationsList = document.getElementById('conversations-list');
        const chatView = document.getElementById('chat-view');
        const welcomeScreen = document.getElementById('welcome-screen');
        const chatInterface = document.getElementById('chat-interface');
        
        // Check if mobile (screen width < 768px)
        const isMobile = window.innerWidth < 768;
        
        if (isMobile) {
            // Mobile: Hide conversations list, show chat view
            if (conversationsList) conversationsList.classList.add('hidden');
            if (chatView) {
                chatView.classList.remove('hidden');
                chatView.classList.add('flex');
            }
        }
        
        console.log('🔍 Welcome screen element:', welcomeScreen);
        console.log('🔍 Chat interface element:', chatInterface);
        
        if (welcomeScreen && chatInterface) {
            welcomeScreen.classList.add('hidden');
            chatInterface.classList.remove('hidden');
            console.log('✅ UI switched to chat interface');
            
            // Initialiser le clavier après l'affichage du chat
            setTimeout(function() {
                // Réattacher les event listeners au cas où ils ne seraient pas attachés
                document.querySelectorAll('.keyboard-btn').forEach(btn => {
                    // Retirer les anciens listeners pour éviter les doublons
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                    
                    // Attacher le nouveau listener
                    newBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const lang = this.getAttribute('data-keyboard');
                        if (lang) {
                            window.changeKeyboard(lang);
                        }
                    });
                });
                
                // Initialiser la gestion des fichiers (ne sera initialisé qu'une seule fois)
                initFileUpload();
                
                // S'assurer que le bouton d'envoi fonctionne correctement
                const sendButton = document.getElementById('send-message-btn');
                if (sendButton) {
                    // Retirer l'ancien listener en clonant le bouton
                    const newSendBtn = sendButton.cloneNode(true);
                    sendButton.parentNode.replaceChild(newSendBtn, sendButton);
                    
                    // Ajouter un seul event listener (sans onclick dans le HTML)
                    newSendBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('🔘 Bouton d\'envoi cliqué');
                        if (typeof window.sendMessage === 'function') {
                            window.sendMessage();
                        } else {
                            console.error('❌ La fonction sendMessage n\'est pas disponible');
                        }
                    });
                }
                
                // Permettre l'envoi avec la touche Enter dans le champ de message
                const messageInput = document.getElementById('message-input');
                if (messageInput) {
                    // Retirer les anciens listeners en clonant l'input
                    const newMessageInput = messageInput.cloneNode(true);
                    messageInput.parentNode.replaceChild(newMessageInput, messageInput);
                    
                    newMessageInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            if (typeof window.sendMessage === 'function') {
                                window.sendMessage();
                            }
                        }
                    });
                }
                
                // Initialiser avec la langue sauvegardée
                initKeyboard();
            }, 150);
        } else {
            console.error('❌ Welcome screen or chat interface not found!');
        }
        
        // Load conversation data
        loadConversationData(selectedConversationData, type);
        
    } catch (error) {
        console.error('❌ Error in selectConversation:', error);
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
        // Extract rental ID from conversation data (handle both 'rental_123' and just '123' formats)
        let rentalId = conversation.id || conversation.original?.id;
        
        // If ID is in format 'rental_123', extract just the number
        if (typeof rentalId === 'string' && rentalId.startsWith('rental_')) {
            rentalId = rentalId.replace('rental_', '');
        }
        
        if (!rentalId) {
            messagesContainer.innerHTML = `
                <div class="text-center text-gray-500 mt-8">
                    <p>Impossible de charger les messages. ID de réservation manquant.</p>
                </div>
            `;
            return;
        }
        
        // Fetch all messages from rental (using lastMessageId=0 to get all messages)
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
    
    // Trier les messages par date croissante (anciens en premier, nouveaux en bas)
    const sortedMessages = [...messages].sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return dateA - dateB;
    });
    
    sortedMessages.forEach(message => {
        const isFromClient = message.sender_type === 'client';
        const messageAlignment = isFromClient ? 'justify-end' : 'justify-start';
        const messageBgColor = isFromClient ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-900';
        const senderLabel = isFromClient ? 'Vous' : 'Agence';
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${messageAlignment} mb-4`;
        
        // Gérer les pièces jointes
        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = '<div class="mt-2 space-y-2">';
            message.attachments.forEach(attachment => {
                const isImage = attachment.type && attachment.type.startsWith('image/');
                if (isImage) {
                    attachmentsHtml += `
                        <div class="rounded-lg overflow-hidden">
                            <img src="${attachment.url}" alt="${attachment.name}" class="max-w-full h-auto rounded-lg cursor-pointer" onclick="window.open('${attachment.url}', '_blank')">
                        </div>
                    `;
                } else {
                    attachmentsHtml += `
                        <a href="${attachment.url}" target="_blank" class="flex items-center gap-2 p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">${escapeHtml(attachment.name)}</p>
                                <p class="text-xs text-gray-500">${formatFileSize(attachment.size || 0)}</p>
                            </div>
                        </a>
                    `;
                }
            });
            attachmentsHtml += '</div>';
        }
        
        messageDiv.innerHTML = `
            <div class="${messageBgColor} px-4 py-2 rounded-lg max-w-xs lg:max-w-md">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-medium ${isFromClient ? 'text-orange-100' : 'text-gray-600'}">${senderLabel}</span>
                    <span class="ml-2 text-xs ${isFromClient ? 'text-orange-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span>
                </div>
                ${message.message ? `<p class="text-sm whitespace-pre-wrap">${escapeHtml(message.message)}</p>` : ''}
                ${attachmentsHtml}
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    });
    
    // Scroll en bas (les nouveaux messages sont en bas)
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
        // Extract numeric ticket ID from conversation data
        const ticketId = String(conversation.id).replace('support_', '');
        
        // Fetch messages from support ticket
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
    
    // Trier les messages par date croissante (anciens en premier, nouveaux en bas)
    const sortedMessages = [...messages].sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return dateA - dateB;
    });
    
    sortedMessages.forEach(message => {
        const isOwnMessage = message.sender_type === 'App\\Models\\Client';
        const messageAlignment = isOwnMessage ? 'justify-end' : 'justify-start';
        const messageBgColor = isOwnMessage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900';
        const senderLabel = isOwnMessage ? 'Vous' : 'Support';
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${messageAlignment} mb-4`;
        messageDiv.innerHTML = `
            <div class="${messageBgColor} px-4 py-2 rounded-lg max-w-xs">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-medium ${isOwnMessage ? 'text-blue-100' : 'text-gray-600'}">${senderLabel}</span>
                    <span class="ml-2 text-xs ${isOwnMessage ? 'text-blue-200' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</span>
                </div>
                <p class="text-sm whitespace-pre-wrap">${message.message}</p>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    });
    
    // Scroll en bas (les nouveaux messages sont en bas)
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Variables pour gérer les fichiers (globales pour être accessibles partout)
window.selectedFiles = window.selectedFiles || [];
let fileUploadInitialized = false;
let fileInputHandler = null;
let attachBtnHandler = null;
let isSending = false; // Flag pour éviter les doubles envois

// Initialiser la gestion des fichiers (une seule fois)
function initFileUpload() {
    const fileInput = document.getElementById('file-input');
    const attachBtn = document.getElementById('attach-file-btn');
    
    if (!fileInput || !attachBtn) return;
    
    // Si déjà initialisé, ne pas réinitialiser
    if (fileUploadInitialized) {
        return;
    }
    
    // Supprimer les anciens listeners s'ils existent
    if (attachBtnHandler) {
        attachBtn.removeEventListener('click', attachBtnHandler);
    }
    if (fileInputHandler) {
        fileInput.removeEventListener('change', fileInputHandler);
    }
    
    // Créer les nouveaux handlers
    attachBtnHandler = function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.click();
    };
    
    fileInputHandler = function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const files = Array.from(e.target.files);
        
        if (files.length > 0) {
            // Vérifier la taille des fichiers (max 10MB par fichier)
            const maxSize = 10 * 1024 * 1024; // 10MB
            const validFiles = [];
            const invalidFiles = [];
            const currentFiles = window.selectedFiles || [];
            
            files.forEach(file => {
                // Vérifier si le fichier n'est pas déjà dans la liste (éviter les doublons)
                const isDuplicate = currentFiles.some(existingFile => 
                    existingFile.name === file.name && 
                    existingFile.size === file.size && 
                    existingFile.lastModified === file.lastModified
                );
                
                if (!isDuplicate) {
                    if (file.size > maxSize) {
                        invalidFiles.push(file.name);
                    } else {
                        validFiles.push(file);
                    }
                }
            });
            
            if (invalidFiles.length > 0) {
                alert(`Les fichiers suivants sont trop volumineux (max 10MB): ${invalidFiles.join(', ')}`);
            }
            
            if (validFiles.length > 0) {
                window.selectedFiles = [...currentFiles, ...validFiles];
                console.log('📎 Fichiers sélectionnés:', window.selectedFiles.map(f => f.name));
                updateFilePreview();
            }
        }
        
        // Réinitialiser l'input pour permettre de sélectionner le même fichier à nouveau
        e.target.value = '';
    };
    
    // Attacher les listeners
    attachBtn.addEventListener('click', attachBtnHandler);
    fileInput.addEventListener('change', fileInputHandler);
    
    fileUploadInitialized = true;
}

// Mettre à jour l'aperçu des fichiers
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
        fileDiv.setAttribute('data-file-index', index);
        
        const isImage = file.type.startsWith('image/');
        const fileIcon = isImage 
            ? '<svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
            : '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
        
        fileDiv.innerHTML = `
            ${fileIcon}
            <span class="flex-1 truncate max-w-xs">${escapeHtml(file.name)}</span>
            <span class="text-xs text-gray-500">${formatFileSize(file.size)}</span>
            <button type="button" class="remove-file-btn ml-2 text-red-500 hover:text-red-700" data-file-index="${index}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        // Attacher l'événement de suppression
        const removeBtn = fileDiv.querySelector('.remove-file-btn');
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const fileIndex = parseInt(this.getAttribute('data-file-index'));
            removeFile(fileIndex);
        });
        
        filePreviewContainer.appendChild(fileDiv);
    });
}

// Formater la taille du fichier
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Supprimer un fichier de la sélection (fonction globale)
window.removeFile = function(index) {
    const files = window.selectedFiles || [];
    if (index >= 0 && index < files.length) {
        window.selectedFiles.splice(index, 1);
        updateFilePreview();
        
        // Réinitialiser l'input file si plus de fichiers
        if (window.selectedFiles.length === 0) {
            const fileInput = document.getElementById('file-input');
            if (fileInput) {
                fileInput.value = '';
            }
        }
    }
};

// Fonction pour effacer tous les fichiers sélectionnés
function clearSelectedFiles() {
    window.selectedFiles = [];
    updateFilePreview();
    const fileInput = document.getElementById('file-input');
    if (fileInput) {
        fileInput.value = '';
    }
}

// Fonction globale pour envoyer un message
window.sendMessage = async function sendMessage() {
    // Empêcher les doubles envois
    if (isSending) {
        console.log('⏳ Un envoi est déjà en cours, ignore...');
        return;
    }
    
    isSending = true;
    
    try {
        const messageInput = document.getElementById('message-input');
        const message = messageInput ? messageInput.value.trim() : '';
        
        // Vérifier qu'il y a au moins un message ou un fichier
        const hasMessage = message && message.length > 0;
        const files = window.selectedFiles || [];
        const hasFiles = Array.isArray(files) && files.length > 0;
        
        console.log('📤 Tentative d\'envoi:', { 
            hasMessage, 
            hasFiles, 
            filesCount: files.length, 
            selectedConversationData: !!selectedConversationData,
            files: files.map(f => f.name)
        });
        
        if ((!hasMessage && !hasFiles) || !selectedConversationData) {
            console.log('❌ Envoi bloqué: pas de message ni de fichier, ou pas de conversation sélectionnée');
            if (!hasMessage && !hasFiles) {
                alert('Veuillez saisir un message ou sélectionner un fichier');
            }
            return;
        }
        
        // Vérifier si le message contient un numéro de téléphone (seulement si il y a un message)
        if (hasMessage) {
            const phoneNumber = detectPhoneNumber(message);
            if (phoneNumber) {
                // Bloquer l'envoi et afficher une alerte d'erreur
                showPhoneNumberAlert(phoneNumber, true);
                // Mettre le focus sur le champ de saisie
                if (messageInput) {
                    messageInput.focus();
                    messageInput.select();
                }
                return;
            }
        }
        
        // Sauvegarder les fichiers avant l'envoi (au cas où ils seraient effacés)
        const filesToSend = hasFiles ? [...files] : [];
        
        console.log('✅ Envoi autorisé:', { message: hasMessage ? 'Oui' : 'Non', files: filesToSend.length });
        
        // Determine conversation type
        const conversationType = selectedConversationId ? selectedConversationId.split('_')[0] : null;
        
        if (!conversationType) {
            console.error('❌ Type de conversation non trouvé');
            alert('Erreur: Type de conversation non trouvé');
            return;
        }
        
        let sendSuccess = false;
        
        if (conversationType === 'support') {
            // Send support message
            sendSuccess = await sendSupportMessage(message || '', filesToSend);
        } else {
            // Send rental message (existing logic)
            sendSuccess = await sendRentalMessage(message || '', filesToSend);
        }
        
        // Clear input and files seulement si l'envoi a réussi
        if (sendSuccess) {
            if (messageInput) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }
            window.selectedFiles = [];
            updateFilePreview();
            
            // Réinitialiser l'input file
            const fileInput = document.getElementById('file-input');
            if (fileInput) {
                fileInput.value = '';
            }
        }
    } finally {
        // Réinitialiser le flag après un court délai
        setTimeout(() => {
            isSending = false;
        }, 1000);
    }
};

// Send message to support ticket
async function sendSupportMessage(message, filesToSend = null) {
    try {
        const ticketId = String(selectedConversationData.id).replace('support_', '');
        
        // Pour le support, on utilise FormData si on a des fichiers, sinon JSON
        const files = filesToSend || (window.selectedFiles || []);
        let response;
        
        if (files && files.length > 0) {
            // Utiliser FormData pour les fichiers
            const formData = new FormData();
            formData.append('message', message || '');
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.getAttribute('content'));
            }
            
            files.forEach((file, index) => {
                if (file instanceof File) {
                    formData.append(`attachments[${index}]`, file);
                }
            });
            
            response = await fetch(`/client/support/messages/${ticketId}/send`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
        } else {
            // Utiliser JSON si pas de fichiers
            response = await fetch(`/client/support/messages/${ticketId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message
                })
            });
        }
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Erreur HTTP:', response.status, errorText);
            alert(`Erreur ${response.status}: ${errorText || 'Erreur lors de l\'envoi du message'}`);
            return false;
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Reload support messages
            await loadSupportMessages(selectedConversationData);
            return true;
        } else {
            alert(data.message || 'Erreur lors de l\'envoi du message');
            return false;
        }
    } catch (error) {
        console.error('❌ Erreur lors de l\'envoi du message support:', error);
        alert('Erreur lors de l\'envoi du message: ' + error.message);
        return false;
    }
}

// Send message to rental conversation
async function sendRentalMessage(message, filesToSend = null) {
    try {
        // Utiliser les fichiers passés en paramètre ou ceux dans window.selectedFiles
        const files = filesToSend || (window.selectedFiles || []);
        
        let rentalId = selectedConversationData.id || selectedConversationData.original?.id;
        
        // If ID is in format 'rental_123', extract just the number
        if (typeof rentalId === 'string' && rentalId.startsWith('rental_')) {
            rentalId = rentalId.replace('rental_', '');
        }
        
        if (!rentalId) {
            alert('Impossible d\'envoyer le message. ID de réservation manquant.');
            return false;
        }
        
        // Préparer FormData pour l'envoi avec fichiers
        const formData = new FormData();
        formData.append('message', message || '');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }
        
        // Ajouter les fichiers
        if (files && files.length > 0) {
            console.log(`📎 Préparation de ${files.length} fichier(s) pour l'envoi:`, files.map(f => ({name: f.name, size: f.size, type: f.type})));
            
            // Vérifier que tous les fichiers sont valides
            const validFiles = files.filter(file => file instanceof File);
            if (validFiles.length !== files.length) {
                console.error('❌ Certains fichiers ne sont pas valides');
                alert('Erreur: Certains fichiers ne sont pas valides');
                return false;
            }
            
            // Ajouter chaque fichier au FormData
            validFiles.forEach((file, index) => {
                formData.append(`attachments[${index}]`, file);
                console.log(`✅ Fichier ${index + 1}/${validFiles.length} ajouté:`, file.name, `(${file.size} bytes)`);
            });
            
            // Vérifier le contenu du FormData (pour debug)
            console.log('📦 FormData préparé avec', validFiles.length, 'fichier(s)');
        } else {
            console.log('ℹ️ Aucun fichier à envoyer');
        }
        
        const response = await fetch(`/client/messages/${rentalId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
                // Ne pas mettre Content-Type pour FormData, le navigateur le fait automatiquement
            },
            body: formData
        });
        
        // Vérifier si la réponse est OK
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Erreur HTTP:', response.status, errorText);
            alert(`Erreur ${response.status}: ${errorText || 'Erreur lors de l\'envoi du message'}`);
            return false;
        }
        
        const data = await response.json();
        
        if (data.success) {
            console.log('✅ Message envoyé avec succès');
            // Reload messages to show the new one
            await loadMessages(selectedConversationData);
            return true;
        } else {
            console.error('❌ Erreur du serveur:', data);
            alert(data.message || 'Erreur lors de l\'envoi du message');
            return false;
        }
    } catch (error) {
        console.error('❌ Erreur lors de l\'envoi du message:', error);
        alert('Erreur lors de l\'envoi du message: ' + error.message);
        return false;
    }
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
    
    // Check if mobile
    const isMobile = window.innerWidth < 768;
    
    if (isMobile) {
        // Mobile: Show conversations list, hide chat view
        const conversationsList = document.getElementById('conversations-list');
        const chatView = document.getElementById('chat-view');
        if (conversationsList) conversationsList.classList.remove('hidden');
        if (chatView) {
            chatView.classList.add('hidden');
            chatView.classList.remove('flex');
        }
    } else {
        // Desktop: Show welcome screen and hide chat interface
        document.getElementById('welcome-screen').classList.remove('hidden');
        document.getElementById('chat-interface').classList.add('hidden');
    }
    
    // Clear messages container
    document.getElementById('messages-container').innerHTML = '';
}

// Function to go back to conversations list (Mobile only)
function backToConversations() {
    exitConversation();
}

// Filter conversations by type
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

// Fonction pour convertir les chiffres arabes en chiffres latins
function convertArabicDigitsToLatin(text) {
    const arabicToLatin = {
        '٠': '0', '١': '1', '٢': '2', '٣': '3', '٤': '4',
        '٥': '5', '٦': '6', '٧': '7', '٨': '8', '٩': '9'
    };
    
    return text.split('').map(char => {
        return arabicToLatin[char] || char;
    }).join('');
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
    // D'abord convertir les chiffres arabes en chiffres latins
    let normalized = convertArabicDigitsToLatin(text);
    // Ensuite convertir les lettres en chiffres T9
    normalized = convertT9ToDigits(normalized);
    // Garder seulement les chiffres et le signe +
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
    
    // Chercher les formats marocains (+212) avec séparateurs variés et chiffres arabes
    const moroccanPatterns = [
        /(?:\+212|00212|212)[\s.\-]?[5-7][\s.\-]?[\d\s.\-a-z٠-٩]{8,}/gi,  // Format marocain avec +212
        /[0][5-7][\s.\-a-z٠-٩]?[\d\s.\-a-z٠-٩]{8,}/gi,  // Format marocain local 05X ou 06X ou 07X
        /[5-7][\s.\-a-z٠-٩]?[\d\s.\-a-z٠-٩]{8,}/gi  // Format marocain sans indicatif
    ];
    
    moroccanPatterns.forEach(pattern => {
        const matches = text.match(pattern);
        if (matches) {
            matches.forEach(match => {
                const normalizedMatch = normalizePhoneText(match);
                // Numéros marocains: 10 chiffres (avec 0) ou 9 chiffres (sans 0) ou 13 chiffres (avec +212)
                if ((normalizedMatch.length >= 9 && normalizedMatch.length <= 13) || 
                    (normalizedMatch.startsWith('212') && normalizedMatch.length >= 12)) {
                    sequences.push(normalizedMatch);
                }
            });
        }
    });
    
    // Chercher aussi les chiffres arabes mélangés
    const arabicPattern = /[٠-٩0-9\s.\-a-z]{8,15}/gi;
    const arabicMatches = text.match(arabicPattern);
    if (arabicMatches) {
        arabicMatches.forEach(match => {
            const normalizedMatch = normalizePhoneText(match);
            if (normalizedMatch.length >= 8 && normalizedMatch.length <= 15) {
                sequences.push(normalizedMatch);
            }
        });
    }
    
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
    
    // Formats marocains: +212 suivi de 9 chiffres (commence par 5, 6 ou 7)
    if (sequence.match(/^\+?212[5-7]\d{8}$/)) {
        return true;
    }
    
    // Formats marocains locaux: commence par 05, 06 ou 07 suivi de 8 chiffres
    if (sequence.match(/^0[5-7]\d{8}$/)) {
        return true;
    }
    
    // Formats marocains sans indicatif: commence par 5, 6 ou 7 suivi de 8 chiffres
    if (sequence.match(/^[5-7]\d{8}$/)) {
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
        // Pattern marocain standard
        /(?:\+212|00212|212|0[5-7])[\s.\-]?[\d\s.\-a-z٠-٩]{8,}/gi,
        // Pattern avec chiffres arabes
        /[٠-٩0-9\s.\-a-z]{8,20}/gi,
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

// Auto-resize textarea and apply initial filter
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM Content Loaded - Messages page');
    
    // Test if conversation items are clickable
    const conversationItems = document.querySelectorAll('.conversation-item');
    console.log('🔍 Found conversation items:', conversationItems.length);
    
    conversationItems.forEach((item, index) => {
        console.log(`📱 Conversation item ${index}:`, item);
        console.log(`📱 Data attributes:`, {
            type: item.dataset.type,
            conversationId: item.dataset.conversationId,
            onclick: item.onclick
        });
        
        // Add a test click listener
        item.addEventListener('click', function(e) {
            console.log('🎯 Click detected on conversation item:', e.target);
            console.log('🎯 Event details:', {
                type: e.type,
                target: e.target,
                currentTarget: e.currentTarget,
                bubbles: e.bubbles,
                cancelable: e.cancelable
            });
        });
    });
    
    const textarea = document.getElementById('message-input');
    if (textarea) {
        let lastDetectedPhone = null;
        
        textarea.addEventListener('input', function() {
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
    if (filterParam && (filterParam === 'support' || filterParam === 'rental')) {
        // Apply the filter immediately
        filterConversations(filterParam);
    }
});

// Handle Escape key to exit conversation (like WhatsApp)
document.addEventListener('keydown', function(e) {
    // Only handle Escape key when chat interface is visible
    if (e.key === 'Escape' && !document.getElementById('chat-interface').classList.contains('hidden')) {
        exitConversation();
    }
});

// Fonction pour changer le clavier (FR/AR/EN) - Version globale
let currentKeyboard = 'fr';
window.changeKeyboard = function(lang) {
    currentKeyboard = lang;
    const messageInput = document.getElementById('message-input');
    
    if (!messageInput) {
        console.warn('Message input not found');
        return;
    }
    
    // Mettre à jour les boutons
    document.querySelectorAll('.keyboard-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-gray-700', 'shadow-sm');
        btn.classList.add('text-gray-500', 'hover:bg-gray-200');
    });
    
    const activeBtn = document.getElementById(`keyboard-${lang}`);
    if (activeBtn) {
        activeBtn.classList.add('bg-white', 'text-gray-700', 'shadow-sm');
        activeBtn.classList.remove('text-gray-500', 'hover:bg-gray-200');
    }
    
    // Changer la direction du texte et le placeholder
    if (lang === 'ar') {
        messageInput.dir = 'rtl';
        messageInput.style.textAlign = 'right';
        messageInput.style.direction = 'rtl';
        messageInput.placeholder = 'اكتب رسالتك...';
        messageInput.setAttribute('lang', 'ar');
        messageInput.setAttribute('inputmode', 'text');
    } else {
        messageInput.dir = 'ltr';
        messageInput.style.textAlign = 'left';
        messageInput.style.direction = 'ltr';
        if (lang === 'en') {
            messageInput.placeholder = 'Type your message...';
            messageInput.setAttribute('lang', 'en');
        } else {
            messageInput.placeholder = 'Tapez votre message...';
            messageInput.setAttribute('lang', 'fr');
        }
        messageInput.setAttribute('inputmode', 'text');
    }
    
    // Sauvegarder la préférence
    localStorage.setItem('preferredKeyboard', lang);
    
    // Mettre le focus sur le champ si visible
    if (messageInput.offsetParent !== null) {
        messageInput.focus();
    }
};

// Fonction pour initialiser le clavier (appelée quand le champ est disponible)
function initKeyboard() {
    const messageInput = document.getElementById('message-input');
    if (!messageInput) {
        return;
    }
    
    // Attacher les event listeners aux boutons de clavier
    document.querySelectorAll('.keyboard-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-keyboard');
            if (lang) {
                window.changeKeyboard(lang);
            }
        });
    });
    
    // Initialiser avec la langue sauvegardée
    const savedKeyboard = localStorage.getItem('preferredKeyboard') || 'fr';
    window.changeKeyboard(savedKeyboard);
}

// Initialiser le clavier au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Attacher les event listeners immédiatement
    document.querySelectorAll('.keyboard-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-keyboard');
            if (lang) {
                window.changeKeyboard(lang);
            }
        });
    });
    
    // Initialiser la gestion des fichiers
    initFileUpload();
    
    // S'assurer que le bouton d'envoi fonctionne correctement
    const sendButton = document.getElementById('send-message-btn');
    if (sendButton) {
        // Retirer l'ancien listener en clonant le bouton
        const newSendBtn = sendButton.cloneNode(true);
        sendButton.parentNode.replaceChild(newSendBtn, sendButton);
        
        // Ajouter un seul event listener (sans onclick dans le HTML)
        newSendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🔘 Bouton d\'envoi cliqué');
            if (typeof window.sendMessage === 'function') {
                window.sendMessage();
            } else {
                console.error('❌ La fonction sendMessage n\'est pas disponible');
            }
        });
    }
    
    // Permettre l'envoi avec la touche Enter dans le champ de message
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
    
    // Essayer d'initialiser immédiatement
    setTimeout(initKeyboard, 100);
});
</script>
@endpush
