@extends('layouts.client')

@section('title', 'Examiner votre réservation')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button onclick="window.history.back()" class="mr-4 p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-900">Examiner votre réservation</h1>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">RC</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Review Details -->
            <div class="lg:col-span-2">
                <!-- Completed Steps -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-4">
                    <div class="space-y-4">
                        <!-- Step 1 - Completed -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">1. Se connecter ou s'inscrire</h3>
                                <p class="text-sm text-gray-600">Connecté en tant que {{ auth()->user()->name }}</p>
                            </div>
                        </div>

                        <!-- Step 2 - Completed -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">2. Ajouter une méthode de paiement</h3>
                                <p class="text-sm text-gray-600">Carte de crédit terminée par 4242</p>
                            </div>
                        </div>

                        <!-- Step 3 - Current -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white font-semibold text-sm">3</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">3. Examiner votre réservation</h3>
                                <p class="text-sm text-gray-600">Vérifiez les détails avant de confirmer</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reservation Details -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Détails de votre réservation</h2>
                    
                    <!-- Contact Information -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Informations de contact</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" value="{{ auth()->user()->name }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                                <input type="email" value="{{ auth()->user()->email }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" placeholder="+212 6XX XXX XXX" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Rental Dates -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Dates de location</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                                <input type="date" value="2025-10-16" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                                <input type="date" value="2025-11-02" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Special Requests -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Demandes spéciales (optionnel)</h3>
                        <textarea rows="4" placeholder="Avez-vous des demandes spéciales pour cette location ?" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Conditions et politiques</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" class="mt-1 mr-3" required>
                                <label for="terms" class="text-sm text-gray-600">
                                    J'accepte les <a href="#" class="text-blue-600 underline">conditions d'utilisation</a> 
                                    et la <a href="#" class="text-blue-600 underline">politique de confidentialité</a> de RentCar Platform.
                                </label>
                            </div>
                            <div class="flex items-start">
                                <input type="checkbox" id="cancellation" class="mt-1 mr-3" required>
                                <label for="cancellation" class="text-sm text-gray-600">
                                    J'ai lu et j'accepte les <a href="#" class="text-blue-600 underline">conditions d'annulation</a> 
                                    et les <a href="#" class="text-blue-600 underline">règles de la maison</a>.
                                </label>
                            </div>
                            <div class="flex items-start">
                                <input type="checkbox" id="disclaimer" class="mt-1 mr-3" required>
                                <label for="disclaimer" class="text-sm text-gray-600">
                                    Je comprends que RentCar Platform n'est pas responsable des remboursements en cas de litige 
                                    entre le client et le fournisseur.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Button -->
                    <button id="confirmBooking" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white py-4 rounded-lg font-semibold text-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        Confirmer la réservation
                    </button>
                </div>
            </div>

            <!-- Right Column - Booking Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-8">
                    <!-- Car Image -->
                    <div class="relative h-48 bg-gray-100 rounded-xl mb-4 overflow-hidden">
                        @if($car->image_url)
                            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Car Details -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h3>
                    <div class="flex items-center mb-4">
                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm text-gray-600">4.8 (12 avis)</span>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <p class="text-xs text-gray-500 mb-2">Cette réservation est non remboursable.</p>
                        <p class="text-xs text-blue-600 underline cursor-pointer">Politique complète</p>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-4">
                        <!-- Dates -->
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">Dates</p>
                                <p class="text-sm text-gray-600">Du 16 oct. au 2 nov. 2025</p>
                            </div>
                            <button class="text-gray-600 hover:text-gray-800 text-sm underline">
                                Modifier
                            </button>
                        </div>

                        <!-- Duration -->
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">Durée</p>
                                <p class="text-sm text-gray-600">17 jours</p>
                            </div>
                            <button class="text-gray-600 hover:text-gray-800 text-sm underline">
                                Modifier
                            </button>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="border-t border-gray-200 pt-4 mt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Détails des prix</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">17 jours × {{ number_format($car->price_per_day, 0) }} MAD</span>
                                <span class="text-gray-900">{{ number_format(17 * $car->price_per_day, 0) }} MAD</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Remise séjour longue durée</span>
                                <span>-{{ number_format(17 * $car->price_per_day * 0.1, 0) }} MAD</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-gray-900">Total MAD</span>
                                    <span class="text-gray-900">{{ number_format(17 * $car->price_per_day * 0.9, 0) }} MAD</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 underline cursor-pointer">Répartition des prix</p>
                    </div>

                    <!-- Price Alert -->
                    <div class="mt-4 p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-green-700">Prix plus bas. Vos dates sont 441 MAD moins cher que la moyenne.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Réservation confirmée !</h2>
            <p class="text-gray-600 mb-6">Votre demande de location a été envoyée à l'agence. Vous recevrez une confirmation par e-mail.</p>
            <div class="space-y-3">
                <a href="{{ route('client.dashboard') }}" 
                   class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition duration-200 block">
                    Aller au tableau de bord
                </a>
                <button onclick="window.close()" 
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirmBooking');
    const successModal = document.getElementById('successModal');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    // Enable/disable confirm button based on checkboxes
    function updateConfirmButton() {
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        confirmBtn.disabled = !allChecked;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateConfirmButton);
    });

    // Confirm booking
    confirmBtn.addEventListener('click', function() {
        if (!this.disabled) {
            successModal.classList.remove('hidden');
        }
    });

    // Close success modal when clicking outside
    successModal.addEventListener('click', function(e) {
        if (e.target === successModal) {
            successModal.classList.add('hidden');
        }
    });
});
</script>
@endsection
