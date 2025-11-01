@extends('layouts.agence')

@section('title', 'Créer un Ticket de Support')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            @if($agency->status === 'approved')
                <a href="{{ route('agence.support.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à mes tickets
                </a>
            @else
                <a href="{{ route('agence.pending') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à la page d'attente
                </a>
            @endif
            <h1 class="text-3xl font-bold text-gray-900">Contacter l'Administration</h1>
            <p class="mt-2 text-sm text-gray-600">Créez un ticket de support pour obtenir de l'aide</p>
            @if($agency->status !== 'approved')
                <div class="mt-4 bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-800">
                                <strong>Votre compte est en attente d'approbation.</strong> Vous pouvez utiliser ce formulaire pour poser des questions sur votre dossier, fournir des documents supplémentaires, ou demander de l'aide.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow">
            <form method="POST" action="{{ route('agence.support.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="category" 
                        id="category" 
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Problème Technique</option>
                        <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Facturation / Paiements</option>
                        <option value="booking" {{ old('category') == 'booking' ? 'selected' : '' }}>Gestion des Réservations</option>
                        @if($agency->status !== 'approved')
                            <option value="account" {{ old('category') == 'account' || !old('category') ? 'selected' : '' }}>Mon Compte / Statut d'Approbation</option>
                        @else
                            <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Mon Compte Agence</option>
                        @endif
                        <option value="complaint" {{ old('category') == 'complaint' ? 'selected' : '' }}>Plainte / Réclamation</option>
                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>Question Générale</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priorité <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="priority" 
                        id="priority" 
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse - Question simple</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Moyenne - Besoin d'assistance</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute - Problème important</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente - Besoin d'aide immédiate</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rental (Optional) -->
                @if($rentals->count() > 0)
                    <div>
                        <label for="rental_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Réservation concernée (optionnel)
                        </label>
                        <select 
                            name="rental_id" 
                            id="rental_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Aucune - Question générale</option>
                            @foreach($rentals as $rental)
                                <option value="{{ $rental->id }}" {{ old('rental_id') == $rental->id ? 'selected' : '' }}>
                                    Réservation #{{ $rental->id }} - {{ $rental->car->brand }} {{ $rental->car->model }} 
                                    @if($rental->start_date)
                                        ({{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Sélectionnez une réservation si votre question la concerne</p>
                    </div>
                @endif

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Sujet <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="subject" 
                        id="subject" 
                        required
                        maxlength="255"
                        value="{{ old('subject') }}"
                        placeholder="Ex: Question sur les commissions"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Description détaillée <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="message" 
                        id="message" 
                        rows="6" 
                        required
                        maxlength="2000"
                        placeholder="Décrivez votre problème ou votre question en détail..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('message') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Maximum 2000 caractères</p>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Help Tips -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-purple-800">Conseils pour un support rapide</h3>
                            <div class="mt-2 text-sm text-purple-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Soyez aussi détaillé que possible</li>
                                    <li>Incluez le numéro de réservation si applicable</li>
                                    <li>Joignez des captures d'écran si nécessaire</li>
                                    <li>L'administration répondra dans les 1-3 heures</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    @if($agency->status === 'approved')
                        <a href="{{ route('agence.support.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                    @else
                        <a href="{{ route('agence.pending') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                    @endif
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Créer le ticket
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

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
@endsection

