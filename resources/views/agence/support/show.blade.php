@extends('layouts.agence')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('agence.support.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-2 inline-flex items-center">
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
                        <form method="POST" action="{{ route('agence.support.resolve', $ticket->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Marquer comme résolu
                            </button>
                        </form>
                    @elseif($ticket->status === 'resolved' || $ticket->status === 'closed')
                        <form method="POST" action="{{ route('agence.support.reopen', $ticket->id) }}">
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $ticket->category_icon }} {{ $ticket->category_label }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Message initial</h3>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $ticket->message }}</p>
                    </div>

                    @if($ticket->rental)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">
                                <span class="font-medium">Réservation concernée:</span> 
                                Réservation #{{ $ticket->rental->id }} - {{ $ticket->rental->car->marque }} {{ $ticket->rental->car->modele }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Conversation -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Conversation avec l'Administration</h2>
                        <p class="text-sm text-gray-500">Échanges avec l'équipe administrative</p>
                    </div>

                    <div class="px-6 py-4 space-y-4 max-h-[500px] overflow-y-auto">
                        @if(!empty($ticket->replies) && count($ticket->replies) > 0)
                            @foreach($ticket->replies as $reply)
                                <div class="flex gap-3 {{ $reply['user_type'] === 'agency' ? '' : 'flex-row-reverse' }}">
                                    <div class="flex-shrink-0">
                                        @if($reply['user_type'] === 'admin')
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500 text-white text-sm font-medium">
                                                A
                                            </span>
                                        @elseif($reply['user_type'] === 'agency')
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-purple-500 text-white text-sm font-medium">
                                                V
                                            </span>
                                        @else
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500 text-white text-sm font-medium">
                                                •
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-1 {{ $reply['user_type'] === 'agency' ? '' : 'text-right' }}">
                                        <div class="inline-block {{ ($reply['user_type'] === 'admin' ? 'bg-indigo-50' : ($reply['user_type'] === 'system' ? 'bg-gray-100' : 'bg-purple-50')) }} rounded-lg px-4 py-3 max-w-lg">
                                            @if($reply['user_type'] === 'system')
                                                <p class="text-xs text-gray-600 italic">{{ $reply['message'] }}</p>
                                            @else
                                                <div class="mb-1">
                                                    <span class="text-xs font-medium {{ $reply['user_type'] === 'admin' ? 'text-indigo-700' : 'text-purple-700' }}">
                                                        {{ $reply['user_type'] === 'admin' ? 'Administration' : 'Vous' }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $reply['message'] }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($reply['created_at'])->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aucune réponse pour le moment</p>
                                <p class="text-xs text-gray-400 mt-1">L'administration vous répondra bientôt</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reply Form -->
                    @if(!in_array($ticket->status, ['closed']))
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <form method="POST" action="{{ route('agence.support.reply', $ticket->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Ajouter une réponse</label>
                                    <textarea 
                                        name="message" 
                                        id="message" 
                                        rows="4" 
                                        required
                                        maxlength="2000"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Écrivez votre message ici..."
                                    ></textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="px-6 py-4 border-t border-gray-200 bg-yellow-50">
                            <p class="text-sm text-yellow-800 text-center">
                                Ce ticket est fermé. Vous pouvez le rouvrir si vous avez besoin d'aide supplémentaire.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Statut actuel</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ticket->status_badge }}">
                                {{ $ticket->status_label }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Priorité</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $ticket->priority_badge }}">
                                {{ $ticket->priority_label }}
                            </span>
                        </div>

                        @if($ticket->assignedTo)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Traité par</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $ticket->assignedTo->name }}</p>
                                <p class="text-xs text-gray-500">Administrateur</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Créé</p>
                            <p class="text-sm text-gray-900">{{ $ticket->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>

                        @if($ticket->last_reply_at)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Dernière réponse</p>
                                <p class="text-sm text-gray-900">{{ $ticket->last_reply_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $ticket->last_reply_at->diffForHumans() }}</p>
                            </div>
                        @endif

                        @if($ticket->resolved_at)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Résolu le</p>
                                <p class="text-sm text-gray-900">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-purple-800">Assistance</h3>
                            <div class="mt-2 text-sm text-purple-700">
                                <p>L'administration vérifie régulièrement les tickets et vous répondra dès que possible.</p>
                                <p class="mt-2">Temps de réponse moyen : <strong>1-3 heures</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div id="success-toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('success-toast').style.display = 'none';
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div id="error-toast" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('error-toast').style.display = 'none';
        }, 3000);
    </script>
@endif

<script>
    // Auto-scroll to bottom of conversation
    document.addEventListener('DOMContentLoaded', function() {
        const conversation = document.querySelector('.overflow-y-auto');
        if (conversation) {
            conversation.scrollTop = conversation.scrollHeight;
        }
    });
</script>
@endsection
