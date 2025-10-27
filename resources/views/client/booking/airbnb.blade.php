@extends('layouts.public')

@section('title', 'Réserver - ' . $car->brand . ' ' . $car->model)

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
                    <h1 class="text-2xl font-bold text-gray-900">Confirmer et payer</h1>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">RC</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Booking Steps -->
            <div class="lg:col-span-2">
                <!-- Step 1: Login/Signup -->
                <div id="step1" class="bg-white rounded-2xl border border-gray-200 p-6 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">1. Se connecter ou s'inscrire</h2>
                            <p class="text-sm text-gray-600 mt-1">Connectez-vous pour continuer votre réservation</p>
                        </div>
                        @if(!auth()->check())
                            <button id="loginBtn" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Continuer
                            </button>
                        @else
                            <div class="flex items-center text-green-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">Connecté</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Step 2: Payment Method -->
                <div id="step2" class="bg-white rounded-2xl border border-gray-200 p-6 mb-4 {{ !auth()->check() ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">2. Ajouter une méthode de paiement</h2>
                            <p class="text-sm text-gray-600 mt-1">Sélectionnez votre mode de paiement</p>
                        </div>
                        @if(auth()->check())
                            <button id="paymentBtn" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Suivant
                            </button>
                        @else
                            <button class="bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed" disabled>
                                Suivant
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Step 3: Review Reservation -->
                <div id="step3" class="bg-white rounded-2xl border border-gray-200 p-6 opacity-50 pointer-events-none">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">3. Examiner votre réservation</h2>
                            <p class="text-sm text-gray-600 mt-1">Vérifiez les détails avant de confirmer</p>
                        </div>
                        <a href="{{ route('booking.review', $car) }}" 
                           class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 hidden">
                            Examiner
                        </a>
                    </div>
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

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-8">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <button id="closeLoginModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-gray-900">Se connecter ou s'inscrire pour réserver</h2>
            </div>

            <!-- Phone Number Input -->
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code pays</label>
                    <div class="relative">
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent appearance-none">
                            <option value="+212">Maroc (+212)</option>
                            <option value="+33">France (+33)</option>
                            <option value="+1">États-Unis (+1)</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <input type="tel" placeholder="Numéro de téléphone" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <p class="text-xs text-gray-500">
                    Nous vous appellerons ou vous enverrons un SMS pour confirmer votre numéro. 
                    <a href="#" class="text-blue-600 underline">Politique de confidentialité</a>
                </p>
            </div>

            <!-- Continue Button -->
            <button id="continuePhone" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold mb-4 transition duration-200">
                Continuer
            </button>

            <!-- Separator -->
            <div class="relative mb-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">ou</span>
                </div>
            </div>

            <!-- Social Login Buttons -->
            <div class="grid grid-cols-3 gap-3 mb-4">
                <button class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </button>
                <button class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                </button>
                <button class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                </button>
            </div>

            <!-- Email Button -->
            <button class="w-full flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-700 font-medium">Continuer avec l'e-mail</span>
            </button>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-8">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <button id="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-gray-900">Ajouter une méthode de paiement</h2>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" class="space-y-4">
                <!-- Card Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de carte</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="cardType" value="credit" class="mr-3" checked>
                            <div class="flex items-center">
                                <span class="mr-3">Carte de crédit ou de débit</span>
                                <div class="flex space-x-2">
                                    <svg class="w-8 h-5" viewBox="0 0 24 16">
                                        <rect width="24" height="16" rx="2" fill="#1A1F71"/>
                                        <text x="12" y="11" text-anchor="middle" fill="white" font-size="8" font-weight="bold">VISA</text>
                                    </svg>
                                    <svg class="w-8 h-5" viewBox="0 0 24 16">
                                        <rect width="24" height="16" rx="2" fill="#EB001B"/>
                                        <circle cx="9" cy="8" r="4" fill="#EB001B"/>
                                        <circle cx="15" cy="8" r="4" fill="#F79E1B"/>
                                    </svg>
                                    <svg class="w-8 h-5" viewBox="0 0 24 16">
                                        <rect width="24" height="16" rx="2" fill="#006FCF"/>
                                        <circle cx="8" cy="8" r="3" fill="#006FCF"/>
                                        <circle cx="16" cy="8" r="3" fill="#006FCF"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Card Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de carte</label>
                    <div class="relative">
                        <input type="text" placeholder="1234 5678 9012 3456" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent pl-12">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>

                <!-- Expiration and CVV -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiration</label>
                        <input type="text" placeholder="MM/AA" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                        <input type="text" placeholder="123" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pays/région</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="MA">Maroc</option>
                        <option value="FR">France</option>
                        <option value="US">États-Unis</option>
                    </select>
                </div>

                <!-- Next Button -->
                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white py-3 rounded-lg font-semibold transition duration-200">
                    Suivant
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Login Modal
    const loginBtn = document.getElementById('loginBtn');
    const loginModal = document.getElementById('loginModal');
    const closeLoginModal = document.getElementById('closeLoginModal');

    if (loginBtn) {
        loginBtn.addEventListener('click', function() {
            loginModal.classList.remove('hidden');
        });
    }

    closeLoginModal.addEventListener('click', function() {
        loginModal.classList.add('hidden');
    });

    // Continue Phone Button
    const continuePhone = document.getElementById('continuePhone');
    if (continuePhone) {
        continuePhone.addEventListener('click', function() {
            // Simulate login success
            loginModal.classList.add('hidden');
            
            // Enable step 2
            const step2 = document.getElementById('step2');
            step2.classList.remove('opacity-50', 'pointer-events-none');
            
            // Mark step 1 as completed
            const step1 = document.getElementById('step1');
            step1.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">1. Se connecter ou s'inscrire</h2>
                            <p class="text-sm text-gray-600 mt-1">Connecté avec succès</p>
                        </div>
                        <svg class="w-6 h-6 text-green-600 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">Connecté</span>
                    </div>
                </div>
            `;
        });
    }

    // Payment Modal
    const paymentBtn = document.getElementById('paymentBtn');
    const paymentModal = document.getElementById('paymentModal');
    const closePaymentModal = document.getElementById('closePaymentModal');

    if (paymentBtn) {
        paymentBtn.addEventListener('click', function() {
            paymentModal.classList.remove('hidden');
        });
    }

    closePaymentModal.addEventListener('click', function() {
        paymentModal.classList.add('hidden');
    });

    // Payment Form Submission
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Simulate payment processing
            alert('Paiement traité avec succès !');
            paymentModal.classList.add('hidden');
            
            // Enable step 3
            const step3 = document.getElementById('step3');
            step3.classList.remove('opacity-50', 'pointer-events-none');
            const reviewBtn = step3.querySelector('a');
            reviewBtn.classList.remove('hidden');
            
            // Mark step 2 as completed
            const step2 = document.getElementById('step2');
            step2.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">2. Ajouter une méthode de paiement</h2>
                            <p class="text-sm text-gray-600 mt-1">Méthode de paiement ajoutée</p>
                        </div>
                        <svg class="w-6 h-6 text-green-600 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center text-green-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">Terminé</span>
                    </div>
                </div>
            `;
        });
    }

    // Close modals when clicking outside
    [loginModal, paymentModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection
