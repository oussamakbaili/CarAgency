@extends('layouts.agence')

@section('header', 'Messages')

@section('content')
<!-- Page Header -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Messages</h1>
                <p class="text-gray-600 mt-1">Communiquez avec vos clients pour les réservations approuvées</p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="text-sm font-medium text-gray-600">{{ $rentals->count() }} conversation(s)</span>
            </div>
        </div>
    </div>
</div>

@if($rentals->count() > 0)
<!-- Conversations List -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <div class="space-y-4">
            @foreach($rentals as $rental)
            <a href="{{ route('agence.messages.show', $rental) }}" 
               class="block p-4 border border-gray-200 rounded-lg hover:border-orange-300 hover:shadow-md transition-all duration-300 group">
                <div class="flex items-center space-x-4">
                    <!-- Car Image -->
                    <div class="flex-shrink-0">
                        @if($rental->car->image)
                            <img src="{{ $rental->car->image_url }}" 
                                 alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Conversation Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">
                                    {{ $rental->car->brand }} {{ $rental->car->model }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $rental->user->name }} • 
                                    {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($rental->unread_count > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-orange-600 rounded-full min-w-[20px]">
                                        {{ $rental->unread_count }}
                                    </span>
                                @endif
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>

                        @if($rental->last_message)
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-sm text-gray-500 truncate">
                                    @if($rental->last_message->sender_type === 'agency')
                                        <span class="text-orange-600 font-medium">Vous:</span>
                                    @else
                                        <span class="text-blue-600 font-medium">{{ $rental->user->name }}:</span>
                                    @endif
                                    {{ Str::limit($rental->last_message->message, 60) }}
                                </p>
                                <span class="text-xs text-gray-400 ml-2">
                                    {{ $rental->last_message->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @else
                            <div class="mt-2">
                                <p class="text-sm text-gray-400 italic">Aucun message encore</p>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@else
<!-- No Conversations -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune conversation</h3>
        <p class="text-gray-600 mb-6">
            Vous n'avez pas encore de conversations actives. La messagerie s'active automatiquement 
            lorsque vous approuvez les réservations de vos clients.
        </p>
        <div class="space-x-4">
            <a href="{{ route('agence.bookings.pending') }}" 
               class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Voir les réservations en attente
            </a>
            <a href="{{ route('agence.cars.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Gérer ma flotte
            </a>
        </div>
    </div>
</div>
@endif
@endsection
