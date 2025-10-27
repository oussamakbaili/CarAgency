@extends('layouts.client')

@section('header', 'Détails de la Location')

@section('content')
<!-- Page Header -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Détails de la Location</h1>
                <p class="text-gray-600 mt-1">Informations complètes sur votre réservation</p>
            </div>
            <a href="{{ route('client.rentals.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux locations
            </a>
        </div>
    </div>
</div>

<!-- Status Alert -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        @switch($rental->status)
            @case('pending')
                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-orange-800">
                                Demande en attente
                            </h3>
                            <div class="mt-2 text-sm text-orange-700">
                                <p>Votre demande de location est en cours d'examen par l'agence. Vous recevrez une notification dès qu'elle sera traitée.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break
            
            @case('active')
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Location approuvée
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Félicitations! Votre demande de location a été approuvée. Contactez l'agence pour organiser la prise en charge du véhicule.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break
            
            @case('rejected')
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Location refusée
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Votre demande de location a été refusée par l'agence. Vous pouvez essayer avec d'autres dates ou contacter l'agence pour plus d'informations.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break
            
            @case('completed')
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Location terminée
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Cette location a été complétée avec succès. Merci d'avoir utilisé nos services!</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break
        @endswitch
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Vehicle Information -->
    <div class="space-y-6">
        <!-- Vehicle Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Véhicule
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        @if($rental->car->image)
                            <img src="{{ $rental->car->image_url }}" alt="{{ $rental->car->brand }} {{ $rental->car->model }}" class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }}</h4>
                            <p class="text-sm text-gray-600">{{ $rental->car->year }} • {{ $rental->car->registration_number }}</p>
                            <p class="text-sm text-gray-500">{{ $rental->car->color ?? 'Couleur non spécifiée' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agency Information -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Agence
                </h3>
                <div class="bg-orange-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ $rental->car->agency->agency_name ?? $rental->car->agency->user->name }}</h4>
                    <p class="text-gray-700">{{ $rental->car->agency->address ?? 'Adresse non spécifiée' }}</p>
                    @if($rental->car->agency->user->phone)
                        <p class="text-sm text-gray-600 mt-1">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $rental->car->agency->user->phone }}
                        </p>
                    @endif
                    @if($rental->car->agency->user->email)
                        <p class="text-sm text-gray-600 mt-1">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $rental->car->agency->user->email }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rental Details -->
    <div class="space-y-6">
        <!-- Rental Details Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Détails de la location
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Numéro de réservation</dt>
                            <dd class="text-gray-900 font-semibold">#{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @switch($rental->status)
                                        @case('active')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('pending')
                                            bg-orange-100 text-orange-800
                                            @break
                                        @case('rejected')
                                            bg-red-100 text-red-800
                                            @break
                                        @case('completed')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst($rental->status) }}
                                </span>
                            </dd>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                            <dd class="text-gray-900">{{ $rental->start_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de fin</dt>
                            <dd class="text-gray-900">{{ $rental->end_date->format('d/m/Y') }}</dd>
                        </div>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Durée</dt>
                        <dd class="text-gray-900">{{ $rental->start_date->diffInDays($rental->end_date) + 1 }} jour(s)</dd>
                    </div>

                    <hr class="border-gray-300">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prix par jour</dt>
                            <dd class="text-gray-900">{{ number_format($rental->car->price_per_day, 0) }} MAD</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Prix total</dt>
                            <dd class="text-lg font-bold text-orange-600">{{ number_format($rental->total_price, 0) }} MAD</dd>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de demande</dt>
                        <dd class="text-gray-900">{{ $rental->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Actions
                </h3>
                <div class="space-y-3">
                    @if($rental->status === 'active')
                        <a href="{{ route('client.messages.show', $rental) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Contacter l'agence
                        </a>
                    @endif

                    @if($rental->status === 'pending')
                        <form method="POST" action="{{ route('client.rentals.cancel', $rental) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande de location?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler la demande
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('client.cars.show', $rental->car) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Voir le véhicule
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection