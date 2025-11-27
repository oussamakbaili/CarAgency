@extends('layouts.client')

@section('title', 'Messages - ' . $rental->car->brand . ' ' . $rental->car->model)

@section('content')
<div class="min-h-screen bg-gray-100" style="height: 100vh; overflow: hidden;">
    <div class="max-w-7xl mx-auto h-full flex flex-col md:flex-row">
        <!-- Left Sidebar - Conversations List -->
        <div id="conversations-list" class="w-full md:w-1/3 bg-white border-r border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-semibold text-gray-900">Messages</h1>
                    <a href="{{ route('client.rentals.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors" title="Retour aux locations">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
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
                <a href="{{ route('client.messages.index') }}" class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-sm font-semibold text-gray-900">Voir toutes les conversations</h3>
                            </div>
                            <p class="text-sm text-gray-500">Retour à la liste complète</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Right Content Area - Chat View -->
        <div id="chat-view" class="flex-1 bg-white flex flex-col">
            <!-- Chat Interface -->
            <div id="chat-interface" class="flex-1 flex flex-col relative" style="min-height: 0; max-height: 100vh; overflow: hidden;">
                <!-- Chat Header -->
                <div id="chat-header" class="p-4 border-b border-gray-200 bg-gray-50 flex-shrink-0 z-10">
                    <div class="flex items-center space-x-3">
                        <!-- Back Button (Mobile only) -->
                        <a href="{{ route('client.messages.index') }}" class="md:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full transition-colors" title="Retour aux conversations">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if($rental->car->image)
                                <img src="{{ $rental->car->image_url }}" 
                                     alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                                     class="w-10 h-10 object-cover rounded-full">
                            @else
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Chat Info -->
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-semibold text-gray-900 truncate">
                                {{ $rental->car->brand }} {{ $rental->car->model }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                {{ $rental->agency->agency_name ?? 'Agence' }} • 
                                {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <div class="text-right mr-4">
                                <p class="text-sm font-semibold text-orange-600">
                                    {{ number_format($rental->total_price, 0) }} MAD
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $rental->start_date->diffInDays($rental->end_date) + 1 }} jour(s)
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                {{ ucfirst($rental->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Messages Area - Scrollable -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-3 md:p-4 bg-gray-50" style="min-height: 0; padding-bottom: 180px;">
                    @if($rental->messages->count() > 0)
                        @foreach($rental->messages->sortBy('created_at') as $message)
                            <div class="flex {{ $message->sender_type === 'client' ? 'justify-end' : 'justify-start' }} mb-4">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="flex items-end space-x-2 {{ $message->sender_type === 'client' ? 'flex-row-reverse space-x-reverse' : '' }}">
                                        <!-- Avatar -->
                                        <div class="flex-shrink-0">
                                            @if($message->sender_type === 'client')
                                                <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-sm font-medium">{{ substr($message->sender->name ?? 'C', 0, 1) }}</span>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-sm font-medium">{{ substr($rental->agency->agency_name ?? 'A', 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Message Bubble -->
                                        <div class="px-4 py-2 rounded-lg {{ $message->sender_type === 'client' ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-900' }}">
                                            <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                                            
                                            <!-- Attachments -->
                                            @if($message->attachments && count($message->attachments) > 0)
                                                <div class="mt-2 space-y-2">
                                                    @foreach($message->attachments as $attachment)
                                                        @if(str_starts_with($attachment['type'] ?? '', 'image/'))
                                                            <div class="rounded-lg overflow-hidden">
                                                                <img src="{{ $attachment['url'] }}" alt="{{ $attachment['name'] }}" class="max-w-full h-auto rounded-lg cursor-pointer" onclick="window.open('{{ $attachment['url'] }}', '_blank')">
                                                            </div>
                                                        @else
                                                            <a href="{{ $attachment['url'] }}" target="_blank" class="flex items-center gap-2 p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                </svg>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-medium truncate">{{ $attachment['name'] }}</p>
                                                                    <p class="text-xs text-gray-500">{{ number_format(($attachment['size'] ?? 0) / 1024, 2) }} KB</p>
                                                                </div>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Timestamp -->
                                    <div class="mt-1 {{ $message->sender_type === 'client' ? 'text-right' : 'text-left' }}">
                                        <span class="text-xs text-gray-500">
                                            {{ $message->created_at->format('H:i') }}
                                            @if($message->sender_type === 'client' && $message->is_read)
                                                <svg class="inline w-3 h-3 text-blue-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <svg class="inline w-3 h-3 text-blue-500 -ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($message->sender_type === 'client')
                                                <svg class="inline w-3 h-3 text-gray-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 mt-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p>Aucun message encore. Commencez la conversation !</p>
                        </div>
                    @endif
                </div>

                <!-- Message Input - Fixed at bottom -->
                <div id="message-input-container" class="fixed md:relative bottom-0 left-0 right-0 p-3 md:p-4 border-t border-gray-200 bg-white z-20">
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
</div>

@push('scripts')
<script>
let selectedFiles = [];
let lastMessageId = {{ $rental->messages->max('id') ?? 0 }};
let isSending = false;

// Initialize file upload
function initFileUpload() {
    const fileInput = document.getElementById('file-input');
    const attachBtn = document.getElementById('attach-file-btn');
    const filePreviewContainer = document.getElementById('file-preview-container');
    
    if (attachBtn && fileInput) {
        attachBtn.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });
            updateFilePreview();
            fileInput.value = '';
        });
    }
}

// Update file preview
function updateFilePreview() {
    const container = document.getElementById('file-preview-container');
    if (!container) return;
    
    if (selectedFiles.length === 0) {
        container.classList.add('hidden');
        container.innerHTML = '';
        return;
    }
    
    container.classList.remove('hidden');
    container.innerHTML = selectedFiles.map((file, index) => {
        const isImage = file.type.startsWith('image/');
        const fileSize = (file.size / 1024).toFixed(2) + ' KB';
        
        return `
            <div class="relative inline-block">
                ${isImage ? 
                    `<img src="${URL.createObjectURL(file)}" alt="${file.name}" class="h-20 w-20 object-cover rounded-lg">` :
                    `<div class="h-20 w-20 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>`
                }
                <button onclick="removeFile(${index})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                    ×
                </button>
                <p class="text-xs text-gray-600 mt-1 truncate w-20">${file.name}</p>
            </div>
        `;
    }).join('');
}

// Remove file
function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFilePreview();
}

// Send message
window.sendMessage = async function() {
    if (isSending) return false;
    
    const messageInput = document.getElementById('message-input');
    const message = messageInput ? messageInput.value.trim() : '';
    const files = selectedFiles.length > 0 ? selectedFiles : null;
    
    if (!message && !files) {
        return false;
    }
    
    isSending = true;
    const sendBtn = document.getElementById('send-message-btn');
    if (sendBtn) sendBtn.disabled = true;
    
    try {
        const formData = new FormData();
        formData.append('message', message || '');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }
        
        if (files && files.length > 0) {
            files.forEach((file, index) => {
                formData.append(`attachments[${index}]`, file);
            });
        }
        
        const response = await fetch(`{{ route('client.messages.store', $rental) }}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Erreur HTTP:', response.status, errorText);
            alert(`Erreur ${response.status}: ${errorText || 'Erreur lors de l\'envoi du message'}`);
            return false;
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Clear input and files
            if (messageInput) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
            }
            selectedFiles = [];
            updateFilePreview();
            
            // Reload messages
            loadMessages();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Erreur lors de l\'envoi du message');
    } finally {
        isSending = false;
        if (sendBtn) sendBtn.disabled = false;
        if (messageInput) messageInput.focus();
    }
    
    return false;
};

// Load messages
async function loadMessages() {
    try {
        const response = await fetch(`{{ route('client.messages.new', $rental) }}?last_message_id=0`);
        const data = await response.json();
        
        if (data.success && data.messages) {
            displayMessages(data.messages);
            lastMessageId = Math.max(...data.messages.map(m => m.id), lastMessageId);
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

// Display messages
function displayMessages(messages) {
    const container = document.getElementById('messages-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (messages.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 mt-8">
                <p>Aucun message dans cette conversation</p>
            </div>
        `;
        return;
    }
    
    messages.forEach(message => {
        const isFromClient = message.sender_type === 'client';
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isFromClient ? 'justify-end' : 'justify-start'} mb-4`;
        
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
                                <p class="text-xs text-gray-500">${(attachment.size / 1024).toFixed(2)} KB</p>
                            </div>
                        </a>
                    `;
                }
            });
            attachmentsHtml += '</div>';
        }
        
        const time = new Date(message.created_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        const readIcon = isFromClient && message.is_read ? 
            '<svg class="inline w-3 h-3 text-blue-500 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><svg class="inline w-3 h-3 text-blue-500 -ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' :
            (isFromClient ? '<svg class="inline w-3 h-3 text-gray-400 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' : '');
        
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="flex items-end space-x-2 ${isFromClient ? 'flex-row-reverse space-x-reverse' : ''}">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 ${isFromClient ? 'bg-orange-600' : 'bg-blue-600'} rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">${isFromClient ? '{{ substr(Auth::user()->name, 0, 1) }}' : '{{ substr($rental->agency->agency_name ?? "A", 0, 1) }}'}</span>
                        </div>
                    </div>
                    <div class="px-4 py-2 rounded-lg ${isFromClient ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-900'}">
                        <p class="text-sm whitespace-pre-wrap">${escapeHtml(message.message)}</p>
                        ${attachmentsHtml}
                    </div>
                </div>
                <div class="mt-1 ${isFromClient ? 'text-right' : 'text-left'}">
                    <span class="text-xs text-gray-500">${time}${readIcon}</span>
                </div>
            </div>
        `;
        
        container.appendChild(messageDiv);
    });
    
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function scrollToBottom() {
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    initFileUpload();
    
    const sendBtn = document.getElementById('send-message-btn');
    const messageInput = document.getElementById('message-input');
    
    if (sendBtn) {
        sendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.sendMessage();
        });
    }
    
    if (messageInput) {
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                window.sendMessage();
            }
        });
        
        // Auto-resize
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }
    
    // Poll for new messages
    setInterval(async () => {
        try {
            const response = await fetch(`{{ route('client.messages.new', $rental) }}?last_message_id=${lastMessageId}`);
            const data = await response.json();
            
            if (data.success && data.messages && data.messages.length > 0) {
                displayMessages(data.messages);
                lastMessageId = Math.max(...data.messages.map(m => m.id), lastMessageId);
            }
        } catch (error) {
            console.error('Error polling messages:', error);
        }
    }, 3000);
    
    scrollToBottom();
});
</script>
@endpush
@endsection
