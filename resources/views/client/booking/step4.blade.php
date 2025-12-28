@extends('layouts.public')

@section('title', __('booking.step4.page_title', ['brand' => $car->brand, 'model' => $car->model]))

@push('head')
<script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('booking.step3') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('booking.step4.back') }}
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('booking.step4.title') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('booking.step4.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">{{ __('booking.step4.method_title') }}</h2>

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Payment Gateway Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('booking.step4.gateway_label') }}</label>
                        <div class="space-y-3">
                            <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-red-500">
                                <input type="radio" name="payment_gateway" value="paypal" class="mt-1 payment-gateway-radio" data-gateway="paypal">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ __('booking.step4.gateways.paypal.title') }}</div>
                                    <div class="text-sm text-gray-600">{{ __('booking.step4.gateways.paypal.desc') }}</div>
                                    <div class="text-xs text-gray-500">{{ __('booking.step4.gateways.paypal.hint') }}</div>
                                </div>
                            </label>
                            <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-red-500">
                                <input type="radio" name="payment_gateway" value="stripe" class="mt-1 payment-gateway-radio" data-gateway="stripe" checked>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ __('booking.step4.gateways.stripe.title') }}</div>
                                    <div class="text-sm text-gray-600">{{ __('booking.step4.gateways.stripe.desc') }}</div>
                                    <div class="text-xs text-gray-500">{{ __('booking.step4.gateways.stripe.hint') }}</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Stripe Payment Form (Hidden by default, shown when Stripe is selected) -->
                    <div id="stripe-payment-form" class="payment-form hidden" style="display: none;">
                        <form id="payment-form" action="{{ route('booking.process-payment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_intent_id" id="payment_intent_id" value="">
                            <input type="hidden" name="payment_method" value="card">
                            <input type="hidden" name="payment_gateway" value="stripe">
                            <input type="hidden" name="terms_accepted" value="1" id="terms_accepted">
                            <input type="hidden" name="privacy_policy_accepted" value="1" id="privacy_policy_accepted">

                            <!-- Stripe Elements Container -->
                            <div id="card-element" class="mb-6 p-4 border border-gray-300 rounded-lg">
                                <!-- Stripe Elements will create form elements here -->
                            </div>

                            <!-- Display form errors -->
                            <div id="card-errors" role="alert" class="mb-6 text-red-600 text-sm"></div>

                            <!-- Terms and Conditions -->
                            <div class="mb-6 space-y-4">
                                <label class="flex items-start">
                                    <input type="checkbox" name="terms_checkbox" id="terms_checkbox" class="mt-1 mr-3" required>
                                    <span class="text-sm text-gray-700">
                                        {{ __('booking.step4.terms.accept_terms') }}
                                        <a href="#" class="text-red-600 hover:underline">{{ __('booking.step4.terms.accept_terms_link') }}</a>
                                    </span>
                                </label>
                                <label class="flex items-start">
                                    <input type="checkbox" name="privacy_checkbox" id="privacy_checkbox" class="mt-1 mr-3" required>
                                    <span class="text-sm text-gray-700">
                                        {{ __('booking.step4.terms.accept_privacy') }}
                                        <a href="#" class="text-red-600 hover:underline">{{ __('booking.step4.terms.accept_privacy_link') }}</a>
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="submit-button" class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-lg font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="button-text">{{ __('booking.step4.stripe.pay', ['amount' => number_format($bookingData['total_with_fees'], 2, ',', ' ')]) }}</span>
                                <span id="spinner" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-white inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>

                            <p class="text-xs text-gray-500 mt-4 text-center">
                                🔒 {{ __('booking.step4.stripe.secure') }}
                            </p>
                        </form>
                    </div>

                    <!-- PayPal Payment Form -->
                    <div id="paypal-payment-form" class="payment-form hidden" style="display: none;">
                        <form id="paypal-form" method="POST">
                            @csrf
                            <input type="hidden" name="payment_gateway" value="paypal">
                            <input type="hidden" name="payment_method" value="paypal">

                            <div class="mb-6">
                                    <p class="text-gray-700 mb-4">
                                        {{ __('booking.step4.paypal.redirect') }}
                                    </p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-blue-800">
                                        <strong>{{ __('booking.step4.paypal.amount') }}</strong> {{ number_format($bookingData['total_with_fees'], 2, ',', ' ') }} EUR
                                    </p>
                                    <p class="text-sm text-blue-800 mt-2">
                                        {{ __('booking.step4.gateways.paypal.desc') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-6 space-y-4">
                                <label class="flex items-start">
                                    <input type="checkbox" name="terms_checkbox_paypal" id="terms_checkbox_paypal" class="mt-1 mr-3" required>
                                    <span class="text-sm text-gray-700">
                                        J'accepte les <a href="#" class="text-red-600 hover:underline">conditions générales</a> de location
                                    </span>
                                </label>
                                <label class="flex items-start">
                                    <input type="checkbox" name="privacy_checkbox_paypal" id="privacy_checkbox_paypal" class="mt-1 mr-3" required>
                                    <span class="text-sm text-gray-700">
                                        J'accepte la <a href="#" class="text-red-600 hover:underline">politique de confidentialité</a>
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="paypal-submit-button" class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-lg font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="paypal-button-text">{{ __('booking.step4.paypal.pay', ['amount' => number_format($bookingData['total_with_fees'], 2, ',', ' ')]) }}</span>
                                <span id="paypal-spinner" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-white inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>

                            <!-- Error Display -->
                            <div id="paypal-error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                                <p class="text-red-800 text-sm font-medium" id="paypal-error-message"></p>
                            </div>

                            <p class="text-xs text-gray-500 mt-4 text-center">
                                🔒 {{ __('booking.step4.paypal.secure') }}
                            </p>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Right Column - Booking Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('booking.step4.summary.title') }}</h3>

                    <!-- Car Image -->
                    <div class="relative h-32 bg-gray-100 rounded-xl mb-4 overflow-hidden">
                        @if($car->image_url)
                            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <h4 class="font-semibold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h4>
                    <p class="text-sm text-gray-600 mb-4">{{ $car->agency->agency_name ?? 'Agence' }}</p>

                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('booking.step4.summary.dates') }}</span>
                            <span class="text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($bookingData['start_date'])->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($bookingData['end_date'])->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('booking.step4.summary.duration') }}</span>
                            <span class="text-gray-900 font-medium">{{ trans_choice('booking.step4.summary.days', $bookingData['days'], ['count' => $bookingData['days']]) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('booking.step4.summary.price_per_day') }}</span>
                            <span class="text-gray-900 font-medium">{{ number_format($bookingData['price_per_day'], 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('booking.step4.summary.subtotal') }}</span>
                            <span class="text-gray-900 font-medium">{{ number_format($bookingData['total_price'], 2, ',', ' ') }} €</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-gray-900 font-semibold">{{ __('booking.step4.summary.total') }}</span>
                                <span class="text-gray-900 font-bold text-lg">{{ number_format($bookingData['total_with_fees'], 2, ',', ' ') }} €</span>
                            </div>
                        </div>
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
    function updateGatewayForms() {
        const selected = document.querySelector('.payment-gateway-radio:checked');
        const gateway = selected ? selected.dataset.gateway : 'stripe';

        const stripeForm = document.getElementById('stripe-payment-form');
        const paypalForm = document.getElementById('paypal-payment-form');

        const showStripe = gateway === 'stripe';

        if (stripeForm) {
            stripeForm.classList.toggle('hidden', !showStripe);
            stripeForm.style.display = showStripe ? 'block' : 'none';
            if (showStripe) {
                initStripe();
            }
        }

        if (paypalForm) {
            paypalForm.classList.toggle('hidden', showStripe);
            paypalForm.style.display = showStripe ? 'none' : 'block';
        }
    }

    document.querySelectorAll('.payment-gateway-radio').forEach(radio => {
        radio.addEventListener('change', updateGatewayForms);
    });

    // Initial state
    updateGatewayForms();

    // Initialize Stripe when Stripe option is selected
    function initStripe() {
        if (stripe) return; // Already initialized

        stripe = Stripe('{{ config('services.stripe.key') }}');
        elements = stripe.elements();
        
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });

        cardElement.mount('#card-element');

        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    }

    // Initialize Stripe on page load if Stripe is selected
    if (document.querySelector('input[name="payment_gateway"][value="stripe"]').checked) {
        initStripe();
    }

    // Stripe Form Submission
    const stripeForm = document.getElementById('payment-form');
    if (stripeForm) {
        stripeForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const termsCheckbox = document.getElementById('terms_checkbox');
            const privacyCheckbox = document.getElementById('privacy_checkbox');

            if (!termsCheckbox.checked || !privacyCheckbox.checked) {
                alert('Veuillez accepter les conditions générales et la politique de confidentialité');
                return;
            }

            // Initialize Stripe if not already done
            if (!stripe) {
                initStripe();
            }

            // Get or create PaymentIntent
            if (!clientSecret) {
                const submitBtn = document.getElementById('submit-button');
                const buttonText = document.getElementById('button-text');
                const spinner = document.getElementById('spinner');
                
                submitBtn.disabled = true;
                buttonText.classList.add('hidden');
                spinner.classList.remove('hidden');

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

            // Confirm payment
            const {error, paymentIntent} = await stripe.confirmCardPayment(
                clientSecret,
                {
                    payment_method: {
                        card: cardElement,
                    }
                }
            );

            const submitBtn = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const spinner = document.getElementById('spinner');

            if (error) {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = error.message;
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else {
                document.getElementById('payment_intent_id').value = paymentIntent.id;
                document.getElementById('terms_accepted').value = '1';
                document.getElementById('privacy_policy_accepted').value = '1';
                stripeForm.submit();
            }
        });
    }

    // PayPal Form Submission
    const paypalForm = document.getElementById('paypal-form');
    if (paypalForm) {
        paypalForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const termsCheckbox = document.getElementById('terms_checkbox_paypal');
            const privacyCheckbox = document.getElementById('privacy_checkbox_paypal');

            if (!termsCheckbox.checked || !privacyCheckbox.checked) {
                alert('Veuillez accepter les conditions générales et la politique de confidentialité');
                return;
            }

            const submitBtn = document.getElementById('paypal-submit-button');
            const buttonText = document.getElementById('paypal-button-text');
            const spinner = document.getElementById('paypal-spinner');
            const errorDiv = document.getElementById('paypal-error');
            const errorMessage = document.getElementById('paypal-error-message');
            
            submitBtn.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route('booking.init-paypal-payment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success && data.approve_url) {
                    // Rediriger vers PayPal
                    window.location.href = data.approve_url;
                } else {
                    // Afficher l'erreur dans la page
                    const errorText = data.error || 'Impossible d\'initialiser le paiement PayPal. Veuillez réessayer plus tard.';
                    errorMessage.textContent = errorText;
                    errorDiv.classList.remove('hidden');
                    
                    // Scroll vers l'erreur
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    
                    submitBtn.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            } catch (error) {
                // Afficher l'erreur dans la page
                errorMessage.textContent = 'Erreur de connexion lors de l\'initialisation du paiement PayPal. Veuillez vérifier votre connexion internet et réessayer.';
                errorDiv.classList.remove('hidden');
                
                // Scroll vers l'erreur
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    }

    // CMI Form Submission
    const cmiForm = document.getElementById('cmi-form');
    if (cmiForm) {
        cmiForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const termsCheckbox = document.getElementById('terms_checkbox_cmi');
            const privacyCheckbox = document.getElementById('privacy_checkbox_cmi');

            if (!termsCheckbox.checked || !privacyCheckbox.checked) {
                alert('Veuillez accepter les conditions générales et la politique de confidentialité');
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
                    // Create a form and submit it to CMI
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = data.redirect_url;

                    Object.keys(data.params).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = data.params[key];
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                } else {
                    alert('Erreur: ' + (data.error || 'Impossible d\'initialiser le paiement CMI'));
                    submitBtn.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            } catch (error) {
                alert('Erreur lors de l\'initialisation du paiement CMI');
                submitBtn.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    }
</script>

<style>
    .payment-gateway-option.selected {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
</style>
@endsection
