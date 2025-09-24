@extends('layouts.admin')

@section('header', 'Détails de la Réservation')

@section('content')
<div class="py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Booking Details Card -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    Réservation #{{ $booking->id }}
                </h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $booking->status === 'active' ? 'bg-green-100 text-green-800' : 
                       ($booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                       ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                    {{ $booking->status === 'active' ? 'Active' : 
                       ($booking->status === 'completed' ? 'Terminée' : 
                       ($booking->status === 'pending' ? 'En attente' : 'Annulée')) }}
                </span>
            </div>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Client Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Client</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Nom:</span> {{ $booking->user->name }}</p>
                        <p class="text-sm"><span class="font-medium">Email:</span> {{ $booking->user->email }}</p>
                        <p class="text-sm"><span class="font-medium">Téléphone:</span> {{ $booking->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Véhicule</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Marque:</span> {{ $booking->car->brand }}</p>
                        <p class="text-sm"><span class="font-medium">Modèle:</span> {{ $booking->car->model }}</p>
                        <p class="text-sm"><span class="font-medium">Année:</span> {{ $booking->car->year }}</p>
                        <p class="text-sm"><span class="font-medium">Immatriculation:</span> {{ $booking->car->registration_number }}</p>
                    </div>
                </div>

                <!-- Agency Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Agence</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Nom:</span> {{ $booking->agency->agency_name ?? 'N/A' }}</p>
                        <p class="text-sm"><span class="font-medium">Ville:</span> {{ $booking->agency->city ?? 'N/A' }}</p>
                        <p class="text-sm"><span class="font-medium">Adresse:</span> {{ $booking->agency->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Rental Period -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Période de Location</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Début:</span> {{ $booking->start_date->format('d/m/Y') }}</p>
                        <p class="text-sm"><span class="font-medium">Fin:</span> {{ $booking->end_date->format('d/m/Y') }}</p>
                        <p class="text-sm"><span class="font-medium">Durée:</span> {{ $booking->start_date->diffInDays($booking->end_date) }} jours</p>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Tarification</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Prix par jour:</span> {{ number_format($booking->daily_price, 0, ',', ' ') }} MAD</p>
                        <p class="text-sm"><span class="font-medium">Prix total:</span> {{ number_format($booking->total_price, 0, ',', ' ') }} MAD</p>
                        <p class="text-sm"><span class="font-medium">Caution:</span> {{ number_format($booking->deposit ?? 0, 0, ',', ' ') }} MAD</p>
                    </div>
                </div>

                <!-- Booking Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Informations</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Créée le:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-sm"><span class="font-medium">Modifiée le:</span> {{ $booking->updated_at->format('d/m/Y H:i') }}</p>
                        <p class="text-sm"><span class="font-medium">ID:</span> #{{ $booking->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Timeline de la Réservation</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($timeline as $index => $event)
                    <li>
                        <div class="relative pb-8">
                            @if($index < count($timeline) - 1)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                        {{ $event['status'] === 'completed' ? 'bg-green-500' : 'bg-gray-400' }}">
                                        @if($event['status'] === 'completed')
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        @else
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ $event['event'] }}</p>
                                        <p class="text-sm text-gray-900">{{ $event['description'] }}</p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        {{ $event['date']->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Bookings -->
    @if($relatedBookings->count() > 0)
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Autres Réservations du Client</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($relatedBookings as $relatedBooking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $relatedBooking->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $relatedBooking->car->brand }} {{ $relatedBooking->car->model }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $relatedBooking->start_date->format('d/m/Y') }} - {{ $relatedBooking->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($relatedBooking->total_price, 0, ',', ' ') }} MAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $relatedBooking->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($relatedBooking->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                   ($relatedBooking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $relatedBooking->status === 'active' ? 'Active' : 
                                   ($relatedBooking->status === 'completed' ? 'Terminée' : 
                                   ($relatedBooking->status === 'pending' ? 'En attente' : 'Annulée')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.bookings.show', $relatedBooking) }}" 
                               class="text-blue-600 hover:text-blue-900">Voir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
