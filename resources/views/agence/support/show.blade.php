@extends('layouts.agence')

@section('title', 'Ticket de Support #' . $ticket->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ticket #{{ $ticket->id }}</h1>
            <p class="text-gray-600">{{ $ticket->subject }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agence.support.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                ← Retour
            </a>
            @if($ticket->status !== 'closed')
                <button onclick="closeTicket({{ $ticket->id }})" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    Fermer le Ticket
                </button>
            @endif
        </div>
    </div>

    <!-- Ticket Info -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <span class="text-sm text-gray-500">Statut</span>
                        @if($ticket->status == 'open')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Ouvert
                            </span>
                        @elseif($ticket->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Fermé
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Priorité</span>
                        @if($ticket->priority == 'high')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Élevée
                            </span>
                        @elseif($ticket->priority == 'medium')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Moyenne
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Faible
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    Créé le {{ $ticket->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <h3 class="font-medium text-gray-900 mb-3">Message initial</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->message }}</p>
            </div>
        </div>
    </div>

    <!-- Replies -->
    @if($ticket->replies && count($ticket->replies) > 0)
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Conversation</h2>
            </div>
            <div class="p-6 space-y-4">
                @foreach($ticket->replies as $reply)
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                @if($reply['user_type'] === 'agency')
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-medium text-gray-900">
                                    @if($reply['user_type'] === 'agency')
                                        Vous
                                    @else
                                        Support
                                    @endif
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($reply['created_at'])->format('d/m/Y à H:i') }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $reply['message'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Reply Form -->
    @if($ticket->status !== 'closed')
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Répondre au ticket</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('agence.support.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Votre message</label>
                        <textarea name="message" id="message" rows="4" required 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Tapez votre réponse ici..."></textarea>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Envoyer la réponse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Ticket fermé</h3>
            <p class="text-gray-600">Ce ticket a été fermé. Vous ne pouvez plus y répondre.</p>
        </div>
    @endif
</div>

<script>
function closeTicket(ticketId) {
    if (confirm('Êtes-vous sûr de vouloir fermer ce ticket ?')) {
        // Ici vous pourriez ajouter une requête AJAX pour fermer le ticket
        alert('Fonctionnalité de fermeture de ticket bientôt disponible !');
    }
}
</script>
@endsection
