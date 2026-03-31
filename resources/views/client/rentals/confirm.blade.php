@extends('layouts.client')

@section('title', 'Confirmer et payer - ' . $car->brand . ' ' . $car->model)

@push('head')
<script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('client.cars.show', $car) }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Confirmer et payer</h1>
        </div>
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-semibold">Session de réservation</span>
            </div>
            <div class="text-sm">
                Expire dans <span id="booking-countdown" class="font-semibold">19:00</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Booking Steps -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Step 1: Add a payment method -->
                <div id="step1" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">1. Ajouter une méthode de paiement</h2>
                        </div>

                        <!-- Payment Gateway Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Choisir une passerelle de paiement</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Stripe Option -->
                                <div class="payment-gateway-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-orange-500 transition" data-gateway="stripe">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Stripe</h3>
                                            <p class="text-sm text-gray-600 mt-1">Paiement international</p>
                                        </div>
                                        <input type="radio" name="payment_gateway" value="stripe" class="payment-gateway-radio" checked>
                                    </div>
                                </div>

                                <!-- CMI Option -->
                                <div class="payment-gateway-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-orange-500 transition" data-gateway="cmi">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">CMI</h3>
                                            <p class="text-sm text-gray-600 mt-1">Paiement Maroc (MAD)</p>
                                        </div>
                                        <input type="radio" name="payment_gateway" value="cmi" class="payment-gateway-radio">
                                    </div>
                                </div>

                                <!-- PayPal Option -->
                                <div class="payment-gateway-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-orange-500 transition" data-gateway="paypal">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">PayPal</h3>
                                            <p class="text-sm text-gray-600 mt-1">Paiement PayPal (EUR)</p>
                                        </div>
                                        <input type="radio" name="payment_gateway" value="paypal" class="payment-gateway-radio">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stripe Payment Form -->
                        <div id="stripe-payment-form" class="payment-form">
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Carte de crédit ou de débit</h3>
                                    </div>
                                    <input type="radio" name="payment_gateway" value="stripe" class="w-5 h-5 text-orange-600 border-gray-300 focus:ring-orange-500" checked>
                                </div>
                                <div class="flex items-center gap-2 mb-4">
                                    <img src="{{ asset('images/payment-visa-mastercard.png') }}"
                                         alt="Visa and Mastercard"
                                         class="h-8 w-auto object-contain"
                                         loading="lazy">
                                    <!-- AMEX -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 bg-[#006FCF] rounded-md shadow-md flex items-center justify-center border border-[#0052A5] relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#006FCF] to-[#0080FF]"></div>
                                            <span class="text-white text-[10px] font-bold relative z-10">AMEX</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form id="payment-form">
                                @csrf
                                <input type="hidden" name="payment_intent_id" id="payment_intent_id" value="">
                                <input type="hidden" name="payment_method" value="card">
                                <input type="hidden" name="payment_gateway" value="stripe">

                                <div class="space-y-5 mb-6">
                                    <div>
        <div class="flex items-center gap-2 mb-2">
            <label class="block text-sm font-medium text-gray-700">Nom du titulaire</label>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <input type="text" id="cardholder-name" name="cardholder_name" placeholder="John Doe" 
               class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Card number</label>
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <div class="relative">
                                            <div id="card-element" class="px-5 py-4 border border-gray-300 rounded-lg bg-white focus-within:border-gray-400 transition-colors text-base">
                                                <!-- Stripe Elements will create form elements here -->
                                            </div>
                                        </div>
                                        <div id="stripe-card-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                        <div id="card-errors" role="alert" class="text-red-600 text-sm mt-1"></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiration</label>
                                            <div id="card-expiry-element" class="px-5 py-4 border border-gray-300 rounded-lg bg-white focus-within:border-gray-400 transition-colors text-base">
                                                <!-- Stripe Elements will create form elements here -->
                                            </div>
                                            <div id="stripe-expiry-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <div id="card-cvc-element" class="px-5 py-4 border border-gray-300 rounded-lg bg-white focus-within:border-gray-400 transition-colors text-base">
                                                <!-- Stripe Elements will create form elements here -->
                                            </div>
                                            <div id="stripe-cvc-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP code</label>
                                        <input type="text" id="stripe-zip" name="zip_code" placeholder="" 
                                               class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                        <div id="stripe-zip-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Country/region</label>
                                        <div class="relative">
                                            <select id="stripe-country" name="country" 
                                                    class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors appearance-none cursor-pointer text-base">
                                                <option value="MA" selected>Morocco</option>
                                                <option value="FR">France</option>
                                                <option value="US">United States</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="DE">Germany</option>
                                                <option value="ES">Spain</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-900 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div id="stripe-country-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="button" id="submit-button" class="bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span id="button-text">Suivant</span>
                                        <span id="spinner" class="hidden">
                                            <svg class="animate-spin h-5 w-5 text-white inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- PayPal Payment Form -->
                        <div id="paypal-payment-form" class="payment-form hidden">
                            <div class="mb-6">
                                <p class="text-gray-600 text-sm mb-6">
                                    Connectez-vous à votre compte PayPal pour finaliser votre paiement. Vous serez redirigé vers le site PayPal pour vous connecter.
                                </p>
                                
                                <div class="flex justify-center">
                                    <button type="button" id="paypal-submit-button" class="bg-[#0070BA] hover:bg-[#005EA6] text-white px-12 py-4 rounded-lg font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3 shadow-md hover:shadow-lg">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.185zm14.146-14.42a.925.925 0 0 0 .144-.05c-.02-.12-.04-.24-.06-.36-.3-1.87-.6-3.14-1.61-4.29-.9-1.03-2.4-1.48-4.4-1.48H5.998c-.52 0-.97.38-1.05.9L2.47 20.597h4.606l1.12-7.185c.082-.518.526-.9 1.05-.9h4.45c3.75 0 6.75-1.5 7.65-6.05.05-.25.09-.5.12-.75z"/>
                                        </svg>
                                        <span id="paypal-button-text">Payer avec PayPal</span>
                                        <span id="paypal-spinner" class="hidden">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                                
                                <div id="paypal-error" class="text-red-600 text-sm mt-4 text-center hidden"></div>
                            </div>
                        </div>

                        <!-- CMI Payment Form -->
                        <div id="cmi-payment-form" class="payment-form hidden">
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">Carte de crédit ou de débit</h3>
                                    </div>
                                    <input type="radio" name="payment_gateway" value="cmi" class="w-5 h-5 text-orange-600 border-gray-300 focus:ring-orange-500">
                                </div>
                                <div class="flex items-center gap-2 mb-4 flex-wrap">
                                    <img src="{{ asset('images/payment-visa-mastercard.png') }}"
                                         alt="Visa and Mastercard"
                                         class="h-8 w-auto object-contain"
                                         loading="lazy">
                                    <!-- CIH Bank -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 bg-[#C8102E] rounded-md shadow-md flex items-center justify-center border border-[#A00D26] relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#C8102E] to-[#E31837]"></div>
                                            <span class="text-white text-[10px] font-bold relative z-10">CIH</span>
                                        </div>
                                    </div>
                                    <!-- Attijariwafa Bank -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 bg-[#0052A5] rounded-md shadow-md flex items-center justify-center border border-[#003D7A] relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#0052A5] to-[#0066CC]"></div>
                                            <span class="text-white text-[9px] font-bold relative z-10">AWB</span>
                                        </div>
                                    </div>
                                    <!-- BMCE Bank -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 bg-[#00843D] rounded-md shadow-md flex items-center justify-center border border-[#006B32] relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#00843D] to-[#00A651]"></div>
                                            <span class="text-white text-[10px] font-bold relative z-10">BMCE</span>
                                        </div>
                                    </div>
                                    <!-- Bank of Africa -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 bg-[#002244] rounded-md shadow-md flex items-center justify-center border border-[#001122] relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#002244] to-[#003366]"></div>
                                            <span class="text-white text-[9px] font-bold relative z-10">BOA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form id="cmi-form">
                                @csrf
                                <input type="hidden" name="payment_gateway" value="cmi">
                                <input type="hidden" name="payment_method" value="cmi">

                                <div class="space-y-5 mb-6">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Nom du titulaire</label>
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <input type="text" id="cmi-cardholder" name="cardholder" placeholder="John Doe" 
                                               class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                        <div id="cmi-cardholder-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Card number</label>
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" id="cmi-card-number" name="card_number" placeholder="1234 5678 9012 3456" 
                                               maxlength="19"
                                               class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                        <div id="cmi-card-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiration</label>
                                            <input type="text" id="cmi-expiry" name="expiry" placeholder="" 
                                                   maxlength="5"
                                                   class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                            <div id="cmi-expiry-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <input type="text" id="cmi-cvc" name="cvc" placeholder="" 
                                                   maxlength="4"
                                                   class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                            <div id="cmi-cvc-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP code</label>
                                        <input type="text" id="cmi-zip" name="zip_code" placeholder="" 
                                               class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                        <div id="cmi-zip-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Country/region</label>
                                        <div class="relative">
                                            <select id="cmi-country" name="country" 
                                                    class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors appearance-none cursor-pointer text-base">
                                                <option value="MA" selected>Morocco</option>
                                                <option value="FR">France</option>
                                                <option value="US">United States</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="DE">Germany</option>
                                                <option value="ES">Spain</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-900 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div id="cmi-country-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="button" id="cmi-submit-button" class="bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span id="cmi-button-text">Suivant</span>
                                        <span id="cmi-spinner" class="hidden">
                                            <svg class="animate-spin h-5 w-5 text-white inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Review your reservation -->
                <div id="step2" class="bg-white rounded-2xl border border-gray-200 overflow-hidden opacity-50 pointer-events-none">
                    <!-- Collapsible Header -->
                    <div class="p-6 border-b border-gray-200 cursor-pointer" onclick="toggleReviewSection()">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-1">2. Examiner votre réservation</h2>
                                <p class="text-sm text-gray-600">Vérifiez les détails avant de confirmer</p>
                            </div>
                            <svg id="review-arrow" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Reservation Details (Collapsed by default) -->
                    <div id="reviewDetails" class="hidden p-6 space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="font-semibold text-gray-900 mb-3">Détails de la réservation</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Véhicule:</span>
                                    <span class="text-gray-900 font-medium">{{ $car->brand }} {{ $car->model }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Agence:</span>
                                    <span class="text-gray-900 font-medium">{{ $car->agency->agency_name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dates:</span>
                                    <span class="text-gray-900 font-medium">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Durée:</span>
                                    <span class="text-gray-900 font-medium">{{ $days }} jour(s)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Prix par jour:</span>
                                    <span class="text-gray-900 font-medium">{{ number_format($car->client_price_per_day, 0) }} MAD</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-gray-900 font-semibold">Total:</span>
                                    <span class="text-gray-900 font-bold">{{ number_format($totalWithFees, 2, ',', ' ') }} MAD</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <form id="confirmForm" action="{{ route('booking.process-payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_intent_id" id="final_payment_intent_id" value="">
                        <input type="hidden" name="payment_method" id="final_payment_method" value="card">
                        <input type="hidden" name="payment_gateway" id="final_payment_gateway" value="stripe">
                        <input type="hidden" name="terms_accepted" value="1">
                        <input type="hidden" name="privacy_policy_accepted" value="1">
                        
                        <button type="submit" id="reviewButton" class="w-full bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition duration-200 cursor-not-allowed" disabled>
                            Confirmer la réservation
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Reservation Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-8">
                    <!-- Car Image -->
                    <div class="relative h-48 bg-gray-100 rounded-xl mb-4 overflow-hidden">
                        @if($car->image_url)
                            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-orange-200">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <h4 class="font-semibold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h4>
                    <p class="text-sm text-gray-600 mb-4">{{ $car->agency->agency_name ?? 'Agence' }}</p>

                    <!-- Dates -->
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Dates</p>
                                <p class="text-sm text-gray-600">{{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}</p>
                            </div>
                            <a href="{{ route('client.cars.show', $car) }}" class="text-sm text-gray-600 underline">Modifier</a>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $days }} nuit(s) × {{ number_format($car->client_price_per_day, 0) }} MAD</span>
                            <span class="text-gray-900 font-medium">{{ number_format($totalPrice, 2, ',', ' ') }} MAD</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-gray-900 font-semibold">Total MAD</span>
                                <span class="text-gray-900 font-bold text-lg">{{ number_format($totalWithFees, 2, ',', ' ') }} MAD</span>
                            </div>
                        </div>
                    </div>

                    <!-- Disclaimer Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Clause de non-responsabilité</h4>
                                    <p class="text-sm text-gray-700">
                                        En confirmant cette réservation, vous acceptez que <strong class="text-orange-600">RentCar Platform</strong> n'est pas responsable des remboursements en cas de litige entre le client et le fournisseur. Tous les litiges doivent être résolus directement entre les parties concernées.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" id="disclaimer-checkbox" name="disclaimer_accepted" 
                                   class="mt-1 mr-3 w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500" required>
                            <div>
                                <span class="text-sm font-medium text-gray-900 block">J'accepte les conditions de non-responsabilité</span>
                                <span class="text-xs text-gray-600 block mt-1">Je comprends que RentCar Platform n'est pas responsable des remboursements en cas de litige.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let stripe = null;
    let elements = null;
    let cardElement = null;
    let clientSecret = null;

    // Payment Gateway Selection
    document.querySelectorAll('.payment-gateway-option').forEach(option => {
        option.addEventListener('click', function() {
            const gateway = this.dataset.gateway;
            const radio = this.querySelector('.payment-gateway-radio');
            radio.checked = true;

            document.querySelectorAll('.payment-gateway-option').forEach(opt => {
                opt.classList.remove('border-orange-500');
                opt.classList.add('border-gray-200');
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-orange-500');

            selectedPaymentGateway = gateway;
            
            if (gateway === 'stripe') {
                document.getElementById('stripe-payment-form').classList.remove('hidden');
                document.getElementById('cmi-payment-form').classList.add('hidden');
                document.getElementById('paypal-payment-form').classList.add('hidden');
                initStripe();
            } else if (gateway === 'cmi') {
                document.getElementById('stripe-payment-form').classList.add('hidden');
                document.getElementById('cmi-payment-form').classList.remove('hidden');
                document.getElementById('paypal-payment-form').classList.add('hidden');
            } else if (gateway === 'paypal') {
                document.getElementById('stripe-payment-form').classList.add('hidden');
                document.getElementById('cmi-payment-form').classList.add('hidden');
                document.getElementById('paypal-payment-form').classList.remove('hidden');
            }
        });
    });

    let cardExpiryElement = null;
    let cardCvcElement = null;

    function initStripe() {
        // Initialize Stripe if not already done
        if (!stripe) {
            const stripeKey = '{{ config('services.stripe.key') }}';
            if (!stripeKey) {
                console.error('Stripe key is not configured');
                return;
            }
            stripe = Stripe(stripeKey);
            elements = stripe.elements();
        }

        // Unmount existing elements if they exist
        if (cardElement) {
            try {
                cardElement.unmount();
            } catch(e) {}
        }
        if (cardExpiryElement) {
            try {
                cardExpiryElement.unmount();
            } catch(e) {}
        }
        if (cardCvcElement) {
            try {
                cardCvcElement.unmount();
            } catch(e) {}
        }
        
        // Wait a bit to ensure the form is visible
        setTimeout(() => {
            // Card number element
            cardElement = elements.create('cardNumber', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        fontFamily: 'system-ui, -apple-system, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                },
            });
            cardElement.mount('#card-element');

            // Card expiry element
            cardExpiryElement = elements.create('cardExpiry', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        fontFamily: 'system-ui, -apple-system, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                },
            });
            cardExpiryElement.mount('#card-expiry-element');

            // Card CVC element
            cardCvcElement = elements.create('cardCvc', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        fontFamily: 'system-ui, -apple-system, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                },
            });
            cardCvcElement.mount('#card-cvc-element');

            // Handle errors
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            cardExpiryElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            cardCvcElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        }, 100);
    }

    if (document.querySelector('input[name="payment_gateway"][value="stripe"]').checked) {
        initStripe();
    }

    let selectedPaymentGateway = 'stripe';
    let paymentIntentId = '';

    // Vérifier si le paiement PayPal a réussi et ouvrir automatiquement l'étape 2
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const paymentSuccess = urlParams.get('payment_success');
        
        if (paymentSuccess === 'paypal') {
            console.log('PayPal payment success detected, opening step 2...');
            
            // Le paiement PayPal a réussi
            // Récupérer le payment_intent_id depuis la session si disponible
            const paymentIntentIdFromSession = '{{ session("booking_data.payment_intent_id") }}';
            if (paymentIntentIdFromSession && paymentIntentIdFromSession !== '') {
                paymentIntentId = paymentIntentIdFromSession;
            }
            selectedPaymentGateway = 'paypal';
            
            // Attendre un peu pour s'assurer que tous les éléments sont chargés
            setTimeout(() => {
                // Activer l'étape 2
                enableStep2();
                
                // Scroll vers l'étape 2 après un court délai
                setTimeout(() => {
                    const step2 = document.getElementById('step2');
                    if (step2) {
                        step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 500);
            }, 100);
        }
    });

    // Écouter les messages de succès PayPal depuis la fenêtre popup
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'paypal_payment_success') {
            // Le paiement PayPal a réussi
            if (event.data.rental_id) {
                // Rediriger vers la page de réservation
                window.location.href = '{{ url("/client/rentals") }}/' + event.data.rental_id;
            } else {
                // Recharger la page pour mettre à jour le statut
                window.location.reload();
            }
        }
    });

    // Toggle review section
    function toggleReviewSection() {
        const reviewDetails = document.getElementById('reviewDetails');
        const reviewArrow = document.getElementById('review-arrow');
        
        if (reviewDetails.classList.contains('hidden')) {
            reviewDetails.classList.remove('hidden');
            reviewArrow.classList.add('rotate-180');
        } else {
            reviewDetails.classList.add('hidden');
            reviewArrow.classList.remove('rotate-180');
        }
    }

    // Enable step 2 when payment method is selected and form is valid
    function enableStep2() {
        const step2 = document.getElementById('step2');
        const reviewButton = document.getElementById('reviewButton');
        const reviewDetails = document.getElementById('reviewDetails');
        const reviewArrow = document.getElementById('review-arrow');
        const disclaimerCheckbox = document.getElementById('disclaimer-checkbox');
        
        step2.classList.remove('opacity-50', 'pointer-events-none');
        reviewButton.classList.remove('cursor-not-allowed');
        reviewButton.classList.add('hover:bg-gray-800');
        
        // Ouvrir automatiquement la section de détails
        if (reviewDetails && reviewDetails.classList.contains('hidden')) {
            reviewDetails.classList.remove('hidden');
            if (reviewArrow) {
                reviewArrow.classList.add('rotate-180');
            }
        }
        
        // Update final form with payment details
        document.getElementById('final_payment_intent_id').value = paymentIntentId;
        document.getElementById('final_payment_method').value = selectedPaymentGateway === 'stripe' ? 'card' : selectedPaymentGateway;
        document.getElementById('final_payment_gateway').value = selectedPaymentGateway;
        
        // Button remains disabled until disclaimer is checked
        if (disclaimerCheckbox && disclaimerCheckbox.checked) {
            reviewButton.disabled = false;
        } else {
            reviewButton.disabled = true;
        }
        
        // Scroll to step 2
        step2.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Helper function to show error message
    function showError(fieldId, message) {
        const errorElement = document.getElementById(fieldId + '-error');
        const inputElement = document.getElementById(fieldId);
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        
        if (inputElement) {
            inputElement.classList.add('border-red-500');
            inputElement.classList.remove('border-gray-300');
        }
    }

    // Helper function to clear error message
    function clearError(fieldId) {
        const errorElement = document.getElementById(fieldId + '-error');
        const inputElement = document.getElementById(fieldId);
        
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
        
        if (inputElement) {
            inputElement.classList.remove('border-red-500');
            inputElement.classList.add('border-gray-300');
        }
    }

    // Clear all errors for a gateway
    function clearAllErrors(gateway) {
        const fields = ['cardholder', 'card', 'expiry', 'cvc', 'zip', 'country'];
        fields.forEach(field => {
            clearError(gateway + '-' + field);
        });
    }

    function isValidExpiry(expiry) {
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            return false;
        }
        const [mm, yy] = expiry.split('/').map(v => parseInt(v, 10));
        if (mm < 1 || mm > 12) return false;

        const now = new Date();
        const currentYY = parseInt(now.getFullYear().toString().slice(-2), 10);
        const currentMM = now.getMonth() + 1;

        if (yy < currentYY) return false;
        if (yy === currentYY && mm < currentMM) return false;
        return true;
    }

    // Validate payment form based on gateway
    function validatePaymentForm() {
        let isValid = true;
        
        if (selectedPaymentGateway === 'stripe') {
            clearAllErrors('stripe');
            
            const zip = document.getElementById('stripe-zip').value.trim();
            const country = document.getElementById('stripe-country').value;
            
            if (!zip) {
                showError('stripe-zip', 'Veuillez remplir le code postal');
                isValid = false;
            }
            
            if (!country) {
                showError('stripe-country', 'Veuillez sélectionner un pays');
                isValid = false;
            }
            
            // Stripe card validation is handled by Stripe Elements
            // Check if card element has errors
            const cardErrors = document.getElementById('card-errors');
            if (cardErrors && cardErrors.textContent.trim()) {
                isValid = false;
            }
            
            return isValid;
        } else if (selectedPaymentGateway === 'paypal') {
            // PayPal ne nécessite pas de validation de formulaire
            // L'utilisateur sera redirigé vers PayPal pour se connecter
            return true;
        } else if (selectedPaymentGateway === 'cmi') {
            clearAllErrors('cmi');
            
            const cardholder = document.getElementById('cmi-cardholder').value.trim();
            const cardNumber = document.getElementById('cmi-card-number').value.replace(/\s/g, '');
            const expiry = document.getElementById('cmi-expiry').value.trim();
            const cvc = document.getElementById('cmi-cvc').value.trim();
            const zip = document.getElementById('cmi-zip').value.trim();
            const country = document.getElementById('cmi-country').value;
            
            if (!cardholder) {
                showError('cmi-cardholder', 'Veuillez renseigner le nom du titulaire');
                isValid = false;
            }

            if (!cardNumber) {
                showError('cmi-card', 'Veuillez remplir le numéro de carte');
                isValid = false;
            } else if (cardNumber.length < 13 || cardNumber.length > 19) {
                showError('cmi-card', 'Le numéro de carte doit contenir entre 13 et 19 chiffres');
                isValid = false;
            }
            
            if (!expiry) {
                showError('cmi-expiry', 'Veuillez remplir la date d\'expiration');
                isValid = false;
            } else if (!isValidExpiry(expiry)) {
                showError('cmi-expiry', 'La date d\'expiration doit être au format MM/AA et future');
                isValid = false;
            }
            
            if (!cvc) {
                showError('cmi-cvc', 'Veuillez remplir le CVV');
                isValid = false;
            } else if (cvc.length < 3 || cvc.length > 4) {
                showError('cmi-cvc', 'Le CVV doit contenir 3 ou 4 chiffres');
                isValid = false;
            }
            
            if (!zip) {
                showError('cmi-zip', 'Veuillez remplir le code postal');
                isValid = false;
            }
            
            if (!country) {
                showError('cmi-country', 'Veuillez sélectionner un pays');
                isValid = false;
            }
            
            return isValid;
        }
        return false;
    }

    // Stripe Form Submission
    const stripeSubmitButton = document.getElementById('submit-button');
    if (stripeSubmitButton) {
        stripeSubmitButton.addEventListener('click', async function(event) {
            event.preventDefault();

            const cardholderName = document.getElementById('cardholder-name').value;
            if (!cardholderName) {
                alert('Veuillez entrer le nom sur la carte');
                return;
            }

            if (!stripe) {
                initStripe();
            }

            const submitBtn = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const spinner = document.getElementById('spinner');
            
            submitBtn.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            // Initialize payment intent
            if (!clientSecret) {
                try {
                    const response = await fetch('{{ route('booking.init-stripe-payment') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        clientSecret = data.client_secret;
                        document.getElementById('payment_intent_id').value = data.payment_intent_id;
                    } else {
                        alert('Erreur: ' + (data.error || 'Impossible d\'initialiser le paiement'));
                        submitBtn.disabled = false;
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                        return;
                    }
                } catch (error) {
                    alert('Erreur lors de l\'initialisation du paiement');
                    submitBtn.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                    return;
                }
            }

            // Validate form first
            if (!validatePaymentForm()) {
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
                return;
            }

            // Get ZIP and country
            const zip = document.getElementById('stripe-zip').value;
            const country = document.getElementById('stripe-country').value;

            // Confirm payment
            const {error, paymentIntent} = await stripe.confirmCardPayment(
                clientSecret,
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            address: {
                                postal_code: zip,
                                country: country,
                            }
                        }
                    }
                }
            );

            if (error) {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = error.message;
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else {
                paymentIntentId = paymentIntent.id;
                document.getElementById('payment_intent_id').value = paymentIntent.id;
                
                // Enable step 2
                enableStep2();
                
                // Show success message
                buttonText.textContent = 'Méthode de paiement ajoutée';
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
                submitBtn.disabled = true;
            }
        });
    }

    // PayPal Form Submission
    const paypalSubmitButton = document.getElementById('paypal-submit-button');
    if (paypalSubmitButton) {
        paypalSubmitButton.addEventListener('click', async function(event) {
            event.preventDefault();

            const submitBtn = document.getElementById('paypal-submit-button');
            const buttonText = document.getElementById('paypal-button-text');
            const spinner = document.getElementById('paypal-spinner');
            const errorDiv = document.getElementById('paypal-error');
            
            submitBtn.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
            
            if (errorDiv) {
                errorDiv.classList.add('hidden');
            }

            try {
                const response = await fetch('{{ route('booking.init-paypal-payment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                // Vérifier si la réponse est OK
                if (!response.ok) {
                    let errorMessage = 'Erreur lors de l\'initialisation du paiement PayPal';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.error || errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `Erreur HTTP ${response.status}: ${response.statusText}`;
                    }
                    
                    if (errorDiv) {
                        errorDiv.textContent = errorMessage;
                        errorDiv.classList.remove('hidden');
                    }
                    submitBtn.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                    return;
                }

                const data = await response.json();

                if (data.success && data.approve_url) {
                    // Ouvrir PayPal dans une nouvelle fenêtre
                    window.open(data.approve_url, '_blank', 'noopener,noreferrer');
                    
                    // Réinitialiser le bouton après un court délai
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                    }, 1000);
                } else {
                    if (errorDiv) {
                        errorDiv.textContent = data.error || data.message || 'Impossible d\'initialiser le paiement PayPal';
                        errorDiv.classList.remove('hidden');
                    }
                    submitBtn.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            } catch (error) {
                console.error('PayPal initialization error:', error);
                if (errorDiv) {
                    let errorMessage = 'Erreur lors de l\'initialisation du paiement PayPal';
                    if (error.message) {
                        errorMessage += ': ' + error.message;
                    }
                    errorDiv.textContent = errorMessage;
                    errorDiv.classList.remove('hidden');
                }
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    }

    // CMI Form Submission
    const cmiSubmitButton = document.getElementById('cmi-submit-button');
    if (cmiSubmitButton) {
        cmiSubmitButton.addEventListener('click', async function(event) {
            event.preventDefault();

            if (!validatePaymentForm()) {
                return;
            }

            // Validate card number via API
            const cardNumberRaw = document.getElementById('cmi-card-number').value;
            try {
                const validateResponse = await fetch('{{ route('client.cards.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ card_number: cardNumberRaw })
                });

                if (!validateResponse.ok) {
                    const data = await validateResponse.json();
                    showError('cmi-card', data.message || 'Your card is not valid');
                    return;
                } else {
                    clearError('cmi-card');
                }
            } catch (err) {
                showError('cmi-card', 'Votre carte n\'est pas valide');
                return;
            }

            const submitBtn = document.getElementById('cmi-submit-button');
            const buttonText = document.getElementById('cmi-button-text');
            const spinner = document.getElementById('cmi-spinner');
            
            submitBtn.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            try {
                const response = await fetch('{{ route('booking.init-cmi-payment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    paymentIntentId = data.order_id;
                    enableStep2();
                    buttonText.textContent = 'Méthode de paiement ajoutée';
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                    submitBtn.disabled = true;
                } else {
                    const errorMsg = document.getElementById('cmi-card-error');
                    if (errorMsg) {
                        errorMsg.textContent = data.error || 'Impossible d\'initialiser le paiement CMI';
                        errorMsg.classList.remove('hidden');
                    }
                    submitBtn.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            } catch (error) {
                const errorMsg = document.getElementById('cmi-card-error');
                if (errorMsg) {
                    errorMsg.textContent = 'Erreur lors de l\'initialisation du paiement CMI';
                    errorMsg.classList.remove('hidden');
                }
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    }

    // Clear CMI cardholder error on input
    const cmiCardholder = document.getElementById('cmi-cardholder');
    if (cmiCardholder) {
        cmiCardholder.addEventListener('input', function() {
            clearError('cmi-cardholder');
        });
    }

    // Format CMI card number input
    const cmiCardNumber = document.getElementById('cmi-card-number');
    if (cmiCardNumber) {
        cmiCardNumber.addEventListener('input', function(e) {
            clearError('cmi-card');
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/\D/g, '').slice(0, 19);
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Format CMI expiry input
    const cmiExpiry = document.getElementById('cmi-expiry');
    if (cmiExpiry) {
        cmiExpiry.addEventListener('input', function(e) {
            clearError('cmi-expiry');
            let value = e.target.value.replace(/\D/g, '').slice(0, 4);
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // Format CMI CVC input (numbers only)
    const cmiCvc = document.getElementById('cmi-cvc');
    if (cmiCvc) {
        cmiCvc.addEventListener('input', function(e) {
            clearError('cmi-cvc');
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    // Format CMI ZIP input
    const cmiZip = document.getElementById('cmi-zip');
    if (cmiZip) {
        cmiZip.addEventListener('input', function(e) {
            clearError('cmi-zip');
        });
    }

    // Format CMI Country input
    const cmiCountry = document.getElementById('cmi-country');
    if (cmiCountry) {
        cmiCountry.addEventListener('change', function(e) {
            clearError('cmi-country');
        });
    }


    // Format Stripe ZIP input
    const stripeZip = document.getElementById('stripe-zip');
    if (stripeZip) {
        stripeZip.addEventListener('input', function(e) {
            clearError('stripe-zip');
        });
    }

    // Format Stripe Country input
    const stripeCountry = document.getElementById('stripe-country');
    if (stripeCountry) {
        stripeCountry.addEventListener('change', function(e) {
            clearError('stripe-country');
        });
    }

    // Validate disclaimer checkbox before confirming reservation
    const confirmForm = document.getElementById('confirmForm');
    const disclaimerCheckbox = document.getElementById('disclaimer-checkbox');
    
    if (confirmForm && disclaimerCheckbox) {
        confirmForm.addEventListener('submit', function(e) {
            if (!disclaimerCheckbox.checked) {
                e.preventDefault();
                alert('Veuillez accepter les conditions de non-responsabilité pour confirmer la réservation');
                disclaimerCheckbox.focus();
                return false;
            }
        });

        // Enable/disable confirm button based on disclaimer checkbox
        disclaimerCheckbox.addEventListener('change', function() {
            const reviewButton = document.getElementById('reviewButton');
            if (this.checked) {
                reviewButton.disabled = false;
                reviewButton.classList.remove('cursor-not-allowed');
            } else {
                reviewButton.disabled = true;
                reviewButton.classList.add('cursor-not-allowed');
            }
        });
    }

    // Booking session countdown (19 minutes)
    const countdownEl = document.getElementById('booking-countdown');
    if (countdownEl) {
        const SESSION_MS = 19 * 60 * 1000;
        const expireAt = Date.now() + SESSION_MS;

        const timer = setInterval(async () => {
            const remaining = expireAt - Date.now();
            if (remaining <= 0) {
                clearInterval(timer);
                countdownEl.textContent = '00:00';
                handleSessionExpired();
                return;
            }
            const minutes = Math.floor(remaining / 60000);
            const seconds = Math.floor((remaining % 60000) / 1000);
            countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }, 1000);
    }

    async function handleSessionExpired() {
        // Disable all payment buttons and inputs
        ['submit-button', 'paypal-submit-button', 'cmi-submit-button', 'reviewButton'].forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = true;
        });

        try {
            await fetch('{{ route('client.booking.session.expire') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        } catch (e) {}

        alert('Votre session de réservation a expiré. La voiture est à nouveau disponible.');
        window.location.href = '{{ route('client.cars.show', $car) }}';
    }
</script>
@endsection

