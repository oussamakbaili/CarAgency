@extends('layouts.client')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="mt-2 text-lg text-gray-600">Toutes vos conversations en un seul endroit</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Support Button -->
                    <a href="{{ route('client.support.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouveau ticket
                    </a>
                </div>
            </div>
        </div>

        @if($allConversations->count() > 0)
        <!-- Conversations Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($allConversations as $conversation)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-gray-300 transition-all duration-300 group cursor-pointer"
                 onclick="openConversation('{{ $conversation->type }}', '{{ $conversation->id }}')">
                <div class="p-6">
                    <!-- Header with Type Badge -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <!-- Avatar/Icon -->
                            <div class="flex-shrink-0">
                                @if($conversation->type === 'rental')
                                    @if($conversation->image)
                                        <img src="{{ $conversation->image }}" alt="{{ $conversation->title }}" 
                                             class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Type Badge -->
                            <div class="flex flex-col space-y-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $conversation->type === 'rental' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $conversation->type === 'rental' ? 'Réservation' : 'Support' }}
                                </span>
                                @if($conversation->type === 'support')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($conversation->status === 'open') bg-green-100 text-green-800
                                        @elseif($conversation->status === 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($conversation->status === 'resolved') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($conversation->status === 'open') Ouvert
                                        @elseif($conversation->status === 'in_progress') En cours
                                        @elseif($conversation->status === 'resolved') Résolu
                                        @else {{ ucfirst($conversation->status) }} @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Unread Badge -->
                        @if($conversation->unread_count > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white 
                                {{ $conversation->type === 'rental' ? 'bg-orange-600' : 'bg-blue-600' }} rounded-full min-w-[20px]">
                                {{ $conversation->unread_count }}
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors mb-2">
                            {{ Str::limit($conversation->title, 40) }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            {{ $conversation->subtitle }}
                        </p>
                    </div>

                    <!-- Last Message -->
                    @if($conversation->last_message)
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500 truncate flex-1 mr-2">
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
                                    {{ Str::limit($conversation->last_message->message, 50) }}
                                </p>
                                <span class="text-xs text-gray-400 flex-shrink-0">
                                    {{ $conversation->last_message->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-sm text-gray-400 italic">Aucun message encore</p>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Aucune conversation</h3>
                <p class="text-gray-600 mb-8">
                    Vous n'avez pas encore de conversations. Les messages apparaissent ici quand vous avez des réservations actives ou des tickets de support.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('client.cars.index') }}" 
                       class="inline-flex items-center justify-center w-full px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Parcourir les véhicules
                    </a>
                    <a href="{{ route('client.support.create') }}" 
                       class="inline-flex items-center justify-center w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer un ticket support
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Conversation Modal -->
<div id="conversationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
                <button onclick="closeConversation()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="modalContent" class="max-h-96 overflow-y-auto">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Open conversation modal
function openConversation(type, id) {
    const modal = document.getElementById('conversationModal');
    const title = document.getElementById('modalTitle');
    const content = document.getElementById('modalContent');
    
    if (type === 'rental') {
        // Redirect to rental messages
        window.location.href = `/client/messages/${id.replace('rental_', '')}`;
    } else if (type === 'support') {
        // Redirect to support messages
        window.location.href = `/client/support/messages/${id.replace('support_', '')}`;
    }
}

// Close conversation modal
function closeConversation() {
    document.getElementById('conversationModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('conversationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConversation();
    }
});
</script>
@endpush
