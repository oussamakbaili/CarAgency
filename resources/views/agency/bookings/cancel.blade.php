@extends('layouts.agency')

@section('title', 'Annuler une réservation')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Annuler une réservation</h1>
            <a href="{{ route('agence.bookings.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Warning Message -->
        @if($stats['warning_message'])
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 font-medium">
                        {{ $stats['warning_message'] }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Suspension Alert -->
        @if($stats['is_suspended'])
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 002.828 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">
                        Votre compte est suspendu. Vous ne pouvez pas annuler de réservations.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Rental Details -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails de la réservation</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Client</label>
                    <p class="text-gray-900">{{ $rental->client->user->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Véhicule</label>
                    <p class="text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Date de début</label>
                    <p class="text-gray-900">{{ $rental->start_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Date de fin</label>
                    <p class="text-gray-900">{{ $rental->end_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Prix total</label>
                    <p class="text-gray-900 font-semibold">{{ number_format($rental->total_price, 2) }} MAD</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Statut</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $rental->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Cancellation Form -->
        @if(!$stats['is_suspended'])
        <form action="{{ route('agence.bookings.cancel', $rental) }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Raison de l'annulation <span class="text-red-500">*</span>
                </label>
                <select name="cancellation_reason" id="cancellation_reason" class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Sélectionnez une raison</option>
                    <option value="vehicle_unavailable">Véhicule non disponible</option>
                    <option value="maintenance_required">Maintenance requise</option>
                    <option value="emergency">Urgence personnelle</option>
                    <option value="customer_request">Demande du client</option>
                    <option value="weather_conditions">Conditions météorologiques</option>
                    <option value="other">Autre</option>
                </select>
                @error('cancellation_reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes supplémentaires (optionnel)
                </label>
                <textarea name="notes" id="notes" rows="4" class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ajoutez des détails sur l'annulation..."></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cancellation Stats -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Statistiques d'annulation</h4>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['cancellation_count'] }}</p>
                        <p class="text-xs text-blue-700">Annulations</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['max_cancellations'] }}</p>
                        <p class="text-xs text-blue-700">Limite</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold {{ $stats['remaining_cancellations'] <= 1 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $stats['remaining_cancellations'] }}
                        </p>
                        <p class="text-xs text-blue-700">Restantes</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('agence.bookings.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Confirmer l'annulation
                </button>
            </div>
        </form>
        @else
        <div class="text-center py-8">
            <p class="text-gray-500">Vous ne pouvez pas annuler de réservations car votre compte est suspendu.</p>
            <a href="mailto:support@rentcarplatform.com" class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-500">
                Contacter le support
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
