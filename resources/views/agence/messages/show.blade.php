@extends('layouts.agence')

@section('header', 'Messages - ' . $rental->car->brand . ' ' . $rental->car->model)

@section('content')
<!-- Chat Header -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('agence.messages.index') }}" 
                   class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                
                <!-- Car Image -->
                <div class="flex-shrink-0">
                    @if($rental->car->image)
                        <img src="{{ $rental->car->image_url }}" 
                             alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                             class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div>
                    <h1 class="text-xl font-semibold text-gray-900">
                        {{ $rental->car->brand }} {{ $rental->car->model }}
                    </h1>
                    <p class="text-sm text-gray-600">
                        {{ $rental->user->name }} • 
                        {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-lg font-semibold text-orange-600">
                        {{ number_format($rental->total_price, 0) }} MAD
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $rental->start_date->diffInDays($rental->end_date) + 1 }} jour(s)
                    </p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                    {{ ucfirst($rental->status) }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Chat Interface -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="flex flex-col h-96">
        <!-- Messages Area -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
            @if($rental->messages->count() > 0)
                @foreach($rental->messages as $message)
                    <div class="flex {{ $message->sender_type === 'agency' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-end space-x-2 {{ $message->sender_type === 'agency' ? 'flex-row-reverse space-x-reverse' : '' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($message->sender_type === 'agency')
                                        <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr($rental->user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Message Bubble -->
                                <div class="px-4 py-2 rounded-lg {{ $message->sender_type === 'agency' ? 'bg-orange-600 text-white' : 'bg-white text-gray-900 border border-gray-200' }}">
                                    <p class="text-sm">{{ $message->message }}</p>
                                </div>
                            </div>

                            <!-- Timestamp -->
                            <div class="mt-1 {{ $message->sender_type === 'agency' ? 'text-right' : 'text-left' }}">
                                <span class="text-xs text-gray-500">
                                    {{ $message->created_at->format('H:i') }}
                                    @if($message->sender_type === 'agency')
                                        @switch($message->read_status)
                                            @case('sent')
                                                <!-- Pas de coche = Message envoyé, destinataire hors ligne -->
                                                @break
                                            @case('delivered')
                                                <!-- Une coche grise = Message envoyé, destinataire en ligne mais pas lu -->
                                                <svg class="inline w-3 h-3 text-gray-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                @break
                                            @case('read')
                                                <!-- Deux coches bleues = Message lu par le destinataire -->
                                                <svg class="inline w-3 h-3 text-blue-500 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <svg class="inline w-3 h-3 text-blue-500 -ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                @break
                                        @endswitch
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-gray-500">Aucun message encore. Commencez la conversation !</p>
                </div>
            @endif
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <form id="message-form" class="flex items-center space-x-3">
                @csrf
                <div class="flex-1">
                    <input type="text" 
                           id="message-input" 
                           name="message" 
                           placeholder="Tapez votre message..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                           maxlength="1000"
                           required>
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Envoyer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('messages-container');
    let lastMessageId = {{ $rental->messages->max('id') ?? 0 }};

    // Auto-scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Send message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Disable form
        messageForm.querySelector('button').disabled = true;
        
        fetch(`{{ route('agence.messages.store', $rental) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message,
                message_type: 'text'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear input
                messageInput.value = '';
                
                // Add message to UI immediately
                addMessageToUI(message, 'agency', new Date());
                
                // Update last message ID
                lastMessageId++;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'envoi du message');
        })
        .finally(() => {
            messageForm.querySelector('button').disabled = false;
            messageInput.focus();
        });
    });

    // Add message to UI
    function addMessageToUI(message, senderType, timestamp) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${senderType === 'agency' ? 'justify-end' : 'justify-start'}`;
        
        const time = timestamp.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="flex items-end space-x-2 ${senderType === 'agency' ? 'flex-row-reverse space-x-reverse' : ''}">
                    <div class="flex-shrink-0">
                        ${senderType === 'agency' ? 
                            '<div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center"><span class="text-white text-sm font-medium">' + '{{ substr(Auth::user()->name, 0, 1) }}' + '</span></div>' :
                            '<div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center"><span class="text-white text-sm font-medium">' + '{{ substr($rental->user->name, 0, 1) }}' + '</span></div>'
                        }
                    </div>
                    <div class="px-4 py-2 rounded-lg ${senderType === 'agency' ? 'bg-orange-600 text-white' : 'bg-white text-gray-900 border border-gray-200'}">
                        <p class="text-sm">${message}</p>
                    </div>
                </div>
                <div class="mt-1 ${senderType === 'agency' ? 'text-right' : 'text-left'}">
                    <span class="text-xs text-gray-500">${time}</span>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // Poll for new messages
    function pollForNewMessages() {
        fetch(`{{ route('agence.messages.new', $rental) }}?last_message_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        addMessageToUI(message.message, message.sender_type, new Date(message.created_at));
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                }
            })
            .catch(error => {
                console.error('Error polling messages:', error);
            });
    }

    // Start polling for new messages
    setInterval(pollForNewMessages, 3000); // Poll every 3 seconds

    // Initial scroll to bottom
    scrollToBottom();
});
</script>
@endsection
