@extends('layouts.public')

@section('title', __('booking.main.page_title', ['brand' => $car->brand, 'model' => $car->model]))

@push('scripts')
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="mr-4 p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('booking.main.header', ['brand' => $car->brand, 'model' => $car->model]) }}</h1>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-[#C2410C] rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">TC</span>
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
                            <h2 class="text-lg font-semibold text-gray-900">1. {{ __('booking.main.steps.login_title') }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ __('booking.main.steps.login_sub') }}</p>
                        </div>
                        @if(!auth()->check())
                            <a href="{{ route('login', ['return_url' => url()->current()]) }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 inline-block text-center">
                                {{ __('booking.main.buttons.continue') }}
                            </a>
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
                <div id="step2" class="bg-white rounded-2xl border border-gray-200 p-6 mb-4 {{ auth()->check() ? '' : 'opacity-50 pointer-events-none' }}">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">2. {{ __('booking.main.steps.payment_title') }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ __('booking.main.steps.payment_sub') }}</p>
                        </div>
                        <button id="paymentBtn" type="button" class="{{ auth()->check() ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }} px-6 py-3 rounded-lg font-semibold transition duration-200" {{ auth()->check() ? '' : 'disabled' }}>
                            {{ __('booking.main.buttons.next') }}
                        </button>
                    </div>
                    
                    <!-- Payment Methods Content (Hidden by default) -->
                    <div id="paymentMethodsContent" class="hidden mt-6">
                        <!-- PayPal Payment Form -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('booking.step4.method_title') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('booking.step4.paypal.redirect') }}</p>
                        </div>

                        <!-- PayPal Payment Form (Only Payment Method) -->
                        <div id="paypal-payment-form" class="payment-form">
                            <div class="mb-6">
                                <p class="text-gray-600 text-sm mb-6">
                                    Connectez-vous à votre compte PayPal pour finaliser votre paiement. Vous serez redirigé vers le site PayPal pour vous connecter.
                                </p>
                                
                                <div class="flex justify-center">
                                    <button type="button" id="paypal-submit-button" class="bg-[#0070BA] hover:bg-[#005EA6] text-white px-12 py-4 rounded-lg font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3 shadow-md hover:shadow-lg">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.185zm14.146-14.42a.925.925 0 0 0 .144-.05c-.02-.12-.04-.24-.06-.36-.3-1.87-.6-3.14-1.61-4.29-.9-1.03-2.4-1.48-4.4-1.48H5.998c-.52 0-.97.38-1.05.9L2.47 20.597h4.606l1.12-7.185c.082-.518.526-.9 1.05-.9h4.45c3.75 0 6.75-1.5 7.65-6.05.05-.25.09-.5.12-.75z"/>
                                        </svg>
                                        <span id="paypal-button-text">{{ __('booking.step4.paypal.pay', ['amount' => '']) }}</span>
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

                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <h3 class="font-semibold text-gray-900">{{ __('booking.main.payment_modal.card_label') }}</h3>
                                    </div>
                                    <input type="radio" name="payment_gateway" value="cmi" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                                </div>
                                <div class="flex items-center gap-2 mb-4 flex-wrap">
                                    <!-- VISA -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 bg-[#1A1F71] rounded-md shadow-md flex items-center justify-center border border-[#0F2A5C] relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-[#1A1F71] to-[#1434A4] opacity-80"></div>
                                            <span class="text-white text-[11px] font-bold tracking-wider relative z-10">VISA</span>
                                        </div>
                                    </div>
                                    <!-- Mastercard -->
                                    <div class="flex items-center">
                                        <div class="w-14 h-8 rounded-full shadow-md flex items-center justify-center relative overflow-hidden">
                                            <div class="absolute left-0 top-0 w-1/2 h-full bg-[#EB001B]"></div>
                                            <div class="absolute right-0 top-0 w-1/2 h-full bg-[#F79E1B]"></div>
                                            <div class="absolute left-1/2 top-0 w-px h-full bg-white opacity-30 transform -translate-x-1/2"></div>
                                            <span class="text-white text-[10px] font-bold relative z-10 drop-shadow-md">MC</span>
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
                                        <input type="text" id="cmi-card-number" name="card_number" placeholder="4444 4444 4444 4444" 
                                               maxlength="23" inputmode="numeric" autocomplete="cc-number"
                                               class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                        <div id="cmi-card-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiration</label>
                                            <input type="text" id="cmi-expiry" name="expiry" placeholder="MM/AA" 
                                                   maxlength="5" inputmode="numeric" autocomplete="cc-exp"
                                                   class="w-full px-5 py-4 border border-gray-300 rounded-lg bg-white focus:border-gray-400 focus:outline-none transition-colors text-base">
                                            <div id="cmi-expiry-error" class="text-red-600 text-sm mt-1 hidden"></div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <input type="text" id="cmi-cvc" name="cvc" placeholder="123" 
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
                                        <span id="cmi-button-text">{{ __('booking.main.buttons.next') }}</span>
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

                <!-- Step 3: Review Reservation -->
                <div id="step3" class="bg-white rounded-2xl border border-gray-200 p-6 opacity-50 pointer-events-none">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">3. {{ __('booking.main.steps.review_title') }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ __('booking.main.steps.review_sub') }}</p>
                        </div>
                        <a href="{{ route('booking.review', $car) }}" 
                           class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 hidden">
                            {{ __('booking.main.steps.review_title') }}
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

                    @php
                        $bookingData = session('booking_data');
                        $startDate = isset($bookingData['start_date']) ? \Carbon\Carbon::parse($bookingData['start_date']) : null;
                        $endDate = isset($bookingData['end_date']) ? \Carbon\Carbon::parse($bookingData['end_date']) : null;
                        $days = $bookingData['days'] ?? 1;
                        $pricePerDay = $bookingData['price_per_day'] ?? $car->client_price_per_day;
                        $totalPrice = $bookingData['total_price'] ?? ($days * $pricePerDay);
                        $longStayDiscount = $days >= 7 ? $totalPrice * 0.1 : 0;
                        $totalAfterDiscount = $totalPrice - $longStayDiscount;
                    @endphp

                    <!-- Car Details -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $car->brand }} {{ $car->model }}</h3>
                    
                    <!-- Agency Name -->
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <a href="{{ route('public.car.show', [$car->agency, $car]) }}" class="text-sm text-blue-600 underline">
                            {{ $car->agency->agency_name }}
                        </a>
                        @if($car->agency->featured)
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-medium rounded">{{ __('booking.main.summary.top_partner') }}</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center mb-4 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>{{ __('booking.main.summary.reviews', ['rating' => number_format($car->getAverageRating(), 1), 'count' => $car->getReviewsCount()]) }}</span>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <p class="text-xs text-gray-500 mb-2">{{ __('booking.main.summary.non_refundable') }}</p>
                        <a href="{{ route('public.policies.non-refundable') }}" class="text-xs text-blue-600 underline cursor-pointer">
                            {{ __('booking.main.summary.full_policy') }}
                        </a>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-4">
                        <!-- Dates -->
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('booking.main.summary.dates') }}</p>
                                <p class="text-sm text-gray-600" id="selected-dates">
                                    @if($startDate && $endDate)
                                        Du {{ $startDate->translatedFormat('d M Y') }} au {{ $endDate->translatedFormat('d M Y') }}
                                    @else
                                        --
                                    @endif
                                </p>
                            </div>
                                <button id="editDatesBtn" class="text-gray-600 hover:text-gray-800 text-sm underline cursor-pointer">
                                {{ __('booking.main.buttons.edit') }}
                            </button>
                        </div>

                        <!-- Duration -->
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900">{{ __('booking.main.summary.duration') }}</p>
                                <p class="text-sm text-gray-600" id="selected-duration">
                                    {{ trans_choice('booking.main.summary.days', $days, ['count' => $days]) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="border-t border-gray-200 pt-4 mt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">{{ __('booking.main.summary.price_details') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600" id="price-breakdown">
                                    {{ $days }} {{ trans_choice('booking.main.summary.days', $days, ['count' => $days]) }} × {{ number_format($pricePerDay, 0, ',', ' ') }} MAD
                                </span>
                                <span class="text-gray-900" id="subtotal-price">{{ number_format($totalPrice, 0, ',', ' ') }} MAD</span>
                            </div>
                            <div class="flex justify-between text-green-600" id="discount-row" style="{{ $longStayDiscount > 0 ? '' : 'display:none;' }}">
                                <span>{{ __('booking.main.summary.long_stay_discount') }}</span>
                                <span id="discount-price">-{{ number_format($longStayDiscount, 0, ',', ' ') }} MAD</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-gray-900">{{ __('booking.main.summary.total_mad') }}</span>
                                    <span class="text-gray-900" id="total-price">{{ number_format($totalAfterDiscount, 0, ',', ' ') }} MAD</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 underline cursor-pointer">{{ __('booking.main.summary.price_breakdown') }}</p>
                    </div>

                    <!-- Price Alert -->
                    <div class="mt-4 p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-green-700">{{ __('booking.main.summary.lower_price', ['amount' => number_format($longStayDiscount > 0 ? $longStayDiscount : 0, 0, ',', ' ')]) }}</p>
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
                <h2 class="text-xl font-bold text-gray-900">{{ __('booking.main.login_modal.title') }}</h2>
            </div>

            <!-- Phone Number Input -->
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.main.login_modal.country_code') }}</label>
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
                    <input type="tel" placeholder="{{ __('booking.main.login_modal.phone_placeholder') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <p class="text-xs text-gray-500">
                    Nous vous appellerons ou vous enverrons un SMS pour confirmer votre numéro. 
                    <a href="#" class="text-blue-600 underline">{{ __('booking.main.login_modal.privacy') }}</a>
                </p>
            </div>

            <!-- Continue Button -->
            <button id="continuePhone" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold mb-4 transition duration-200">
                {{ __('booking.main.login_modal.continue') }}
            </button>

            <!-- Separator -->
            <div class="relative mb-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">{{ __('booking.main.login_modal.or') }}</span>
                </div>
            </div>

            <!-- Social Login Buttons -->
            <div class="grid grid-cols-3 gap-3 mb-4">
                <button type="button" id="facebookLogin" class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </button>
                <button type="button" id="googleLogin" class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                </button>
                <button type="button" id="appleLogin" class="flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                </button>
            </div>

            <!-- Email Button -->
            <button type="button" id="emailLogin" class="w-full flex items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-700 font-medium">{{ __('booking.main.login_modal.social_email') }}</span>
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
                <h2 class="text-xl font-bold text-gray-900">{{ __('booking.main.payment_modal.title') }}</h2>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" class="space-y-4">
                <!-- Card Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.main.payment_modal.card_type') }}</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="cardType" value="credit" class="mr-3" checked>
                            <div class="flex items-center">
                                <span class="mr-3">{{ __('booking.main.payment_modal.card_label') }}</span>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.main.payment_modal.card_number') }}</label>
                    <div class="relative">
                        <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent pl-12" 
                               maxlength="19" inputmode="numeric" autocomplete="cc-number">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div id="cardNumberError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Expiration and CVV -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.main.payment_modal.exp') }}</label>
                        <input type="text" id="expirationDate" placeholder="MM/AA" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                               maxlength="5" inputmode="numeric" autocomplete="cc-exp">
                        <div id="expirationError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.main.payment_modal.cvv') }}</label>
                        <input type="text" id="cvv" placeholder="123" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                               maxlength="4">
                        <div id="cvvError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.main.payment_modal.country') }}</label>
                    <select id="country" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="MA">Maroc</option>
                        <option value="FR">France</option>
                        <option value="US">États-Unis</option>
                    </select>
                </div>

                <!-- Next Button -->
                <button type="submit" id="paymentSubmitBtn" class="w-full bg-black hover:bg-gray-800 text-white py-3 rounded-lg font-semibold transition duration-200">
                    {{ __('booking.main.payment_modal.next') }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Edit Dates Modal -->
<div id="editDatesModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-8">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <button id="closeEditDatesModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-gray-900">Modifier les dates</h2>
            </div>

            <!-- Date Selection -->
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                    <input type="date" id="startDateInput" value="{{ session('booking_data.start_date') ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" 
                           min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" id="endDateInput" value="{{ session('booking_data.end_date') ?? \Carbon\Carbon::tomorrow()->addDays(7)->format('Y-m-d') }}" 
                           min="{{ \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-blue-700">Durée sélectionnée : <span id="calculated-duration">17 jours</span></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button id="cancelDatesBtn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                    Annuler
                </button>
                <button id="saveDatesBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition duration-200">
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Duration Modal -->
<div id="editDurationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-8">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <button id="closeEditDurationModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-gray-900">Modifier la durée</h2>
            </div>

            <!-- Duration Selection -->
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de jours</label>
                    <div class="flex items-center space-x-4">
                        <button id="decreaseDays" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input type="number" id="durationInput" value="17" min="1" max="30" 
                               class="w-20 text-center px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <button id="increaseDays" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-blue-700">Nouveau total estimé : <span id="new-total-price">7,650 MAD</span></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button id="cancelDurationBtn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                    Annuler
                </button>
                <button id="saveDurationBtn" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition duration-200">
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activer l'étape 2 si l'utilisateur est déjà connecté
    @if(auth()->check())
        const step2 = document.getElementById('step2');
        const paymentBtnInit = document.getElementById('paymentBtn');
        
        if (step2 && paymentBtnInit) {
            // Activer l'étape 2
            step2.classList.remove('opacity-50', 'pointer-events-none');
            
            // Activer le bouton de paiement
            paymentBtnInit.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            paymentBtnInit.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white');
            paymentBtnInit.disabled = false;
            paymentBtnInit.textContent = 'Suivant';
        }
    @endif

    // Login Modal (pas utilisé si on redirige vers la page de login)
    const loginBtn = document.getElementById('loginBtn');
    const loginModal = document.getElementById('loginModal');
    const closeLoginModal = document.getElementById('closeLoginModal');

    // Le bouton loginBtn n'existe plus, on utilise maintenant un lien direct vers la page de login
    // Ce code est conservé pour la compatibilité avec d'autres parties de la page qui pourraient utiliser le modal

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

    // Payment Methods Display
    const paymentBtnHandler = document.getElementById('paymentBtn');
    const paymentMethodsContent = document.getElementById('paymentMethodsContent');

    if (paymentBtnHandler && paymentMethodsContent) {
        console.log('Payment button and content found, attaching event listener');
        paymentBtnHandler.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Payment button clicked');
            
            // Afficher le contenu des méthodes de paiement
            paymentMethodsContent.classList.remove('hidden');
            console.log('Payment methods content shown');
            
            // Masquer le bouton "Suivant"
            paymentBtnHandler.style.display = 'none';
            
            // Scroll vers le contenu des méthodes de paiement
            setTimeout(() => {
                paymentMethodsContent.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
            
            // Initialiser Stripe si Stripe est sélectionné par défaut
            const stripeRadio = document.querySelector('input[name="payment_gateway"][value="stripe"]');
            if (stripeRadio && stripeRadio.checked) {
                setTimeout(() => {
                    if (typeof initStripe === 'function') {
                        initStripe();
                    }
                }, 300);
            }
        });
    } else {
        console.error('Payment button or content not found:', {
            button: !!paymentBtnHandler,
            content: !!paymentMethodsContent
        });
    }

    // Payment Modal (si existe)
    const paymentModal = document.getElementById('paymentModal');
    const closePaymentModal = document.getElementById('closePaymentModal');
    if (closePaymentModal && paymentModal) {
        closePaymentModal.addEventListener('click', function() {
            paymentModal.classList.add('hidden');
        });
    }

    // Payment Form Validation and Submission
    const paymentForm = document.getElementById('paymentForm');
    const cardNumberInput = document.getElementById('cardNumber');
    const expirationInput = document.getElementById('expirationDate');
    const cvvInput = document.getElementById('cvv');
    const countrySelect = document.getElementById('country');

    if (paymentForm) {
        // Format card number with spaces
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function(e) {
                e.target.value = formatCardNumber(e.target.value);
            });
        }

        // Format expiration date
        if (expirationInput) {
            expirationInput.addEventListener('input', function(e) {
                e.target.value = formatExpiry(e.target.value);
            });
        }

        // Only allow numbers in CVV
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
            });
        }

        // Form submission with validation
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearPaymentErrors();
            
            // Validate all fields
            const isValid = validatePaymentForm();
            
            if (isValid) {
                // Simulate payment processing
                const submitBtn = document.getElementById('paymentSubmitBtn');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Traitement...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    showNotification('Méthode de paiement ajoutée avec succès !', 'success');
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
                    
                    // Reset button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            }
        });
    }

    // Payment validation functions
    function validatePaymentForm() {
        let isValid = true;
        
        // Validate card number
        const cardNumber = cardNumberInput.value.replace(/\s/g, '');
        if (!cardNumber) {
            showFieldError('cardNumberError', 'Le numéro de carte est requis');
            isValid = false;
        } else if (!validateCardNumber(cardNumber)) {
            showFieldError('cardNumberError', 'Numéro de carte invalide');
            isValid = false;
        } else {
            hideFieldError('cardNumberError');
        }
        
        // Validate expiration date
        const expiration = expirationInput.value;
        if (!expiration) {
            showFieldError('expirationError', 'La date d\'expiration est requise');
            isValid = false;
        } else if (!validateExpirationDate(expiration)) {
            showFieldError('expirationError', 'Date d\'expiration invalide');
            isValid = false;
        } else {
            hideFieldError('expirationError');
        }
        
        // Validate CVV
        const cvv = cvvInput.value;
        if (!cvv) {
            showFieldError('cvvError', 'Le CVV est requis');
            isValid = false;
        } else if (!validateCVV(cvv)) {
            showFieldError('cvvError', 'CVV invalide (3 ou 4 chiffres)');
            isValid = false;
        } else {
            hideFieldError('cvvError');
        }
        
        return isValid;
    }

    function validateCardNumber(cardNumber) {
        // Remove spaces and check if it's all digits
        const cleanNumber = cardNumber.replace(/\s/g, '');
        if (!/^\d+$/.test(cleanNumber)) {
            return false;
        }
        
        // Check length (13-19 digits)
        if (cleanNumber.length < 13 || cleanNumber.length > 19) {
            return false;
        }
        
        // Luhn algorithm validation
        let sum = 0;
        let isEven = false;
        
        for (let i = cleanNumber.length - 1; i >= 0; i--) {
            let digit = parseInt(cleanNumber.charAt(i));
            
            if (isEven) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }
            
            sum += digit;
            isEven = !isEven;
        }
        
        return sum % 10 === 0;
    }

    function validateExpirationDate(expiration) {
        const regex = /^(0[1-9]|1[0-2])\/\d{2}$/;
        if (!regex.test(expiration)) {
            return false;
        }
        
        const [month, year] = expiration.split('/');
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear() % 100;
        const currentMonth = currentDate.getMonth() + 1;
        
        const expYear = parseInt(year);
        const expMonth = parseInt(month);
        
        if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
            return false;
        }
        
        return true;
    }

    function validateCVV(cvv) {
        return /^\d{3,4}$/.test(cvv);
    }

    function showFieldError(errorId, message) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    function hideFieldError(errorId) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.classList.add('hidden');
        }
    }

    function clearPaymentErrors() {
        hideFieldError('cardNumberError');
        hideFieldError('expirationError');
        hideFieldError('cvvError');
    }

    // Close modals when clicking outside
    [loginModal, paymentModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // Edit Dates Functionality
    const editDatesBtn = document.getElementById('editDatesBtn');
    const editDatesModal = document.getElementById('editDatesModal');
    const closeEditDatesModal = document.getElementById('closeEditDatesModal');
    const cancelDatesBtn = document.getElementById('cancelDatesBtn');
    const saveDatesBtn = document.getElementById('saveDatesBtn');
    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');
    const calculatedDuration = document.getElementById('calculated-duration');

    // Open dates modal
    editDatesBtn.addEventListener('click', function() {
        editDatesModal.classList.remove('hidden');
    });

    // Close dates modal
    [closeEditDatesModal, cancelDatesBtn].forEach(btn => {
        btn.addEventListener('click', function() {
            editDatesModal.classList.add('hidden');
        });
    });

    // Calculate duration when dates change
    function calculateDuration() {
        if (!startDateInput || !endDateInput) {
            return;
        }

        if (!startDateInput.value || !endDateInput.value) {
            return;
        }

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
            return;
        }

        const diffTime = endDate - startDate;
        if (diffTime <= 0) {
            calculatedDuration.textContent = '0 jour';
            document.getElementById('selected-duration').textContent = '0 jour';
            return;
        }

        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // Mettre à jour la durée dans le modal
        calculatedDuration.textContent = diffDays + ' jours';

        // Mettre à jour la durée dans le récapitulatif à droite
        const selectedDurationEl = document.getElementById('selected-duration');
        if (selectedDurationEl) {
            selectedDurationEl.textContent = diffDays + ' jours';
        }

        // Mettre à jour le texte des dates dans le récapitulatif
        const selectedDatesEl = document.getElementById('selected-dates');
        if (selectedDatesEl) {
            const options = { day: 'numeric', month: 'short' };
            const startFormatted = startDate.toLocaleDateString('fr-FR', options);
            const endFormatted = endDate.toLocaleDateString('fr-FR', options);
            const year = startDate.getFullYear();
            selectedDatesEl.textContent = `Du ${startFormatted} au ${endFormatted} ${year}`;
        }

        // Mettre à jour le prix dans le récapitulatif
        const pricePerDay = {{ $car->client_price_per_day }};
        const subtotal = diffDays * pricePerDay;
        const discount = diffDays >= 7 ? subtotal * 0.1 : 0;
        const total = subtotal - discount;

        const priceBreakdownEl = document.getElementById('price-breakdown');
        const subtotalEl = document.getElementById('subtotal-price');
        const discountEl = document.getElementById('discount-price');
        const discountRow = document.getElementById('discount-row');
        const totalEl = document.getElementById('total-price');

        if (priceBreakdownEl) {
            priceBreakdownEl.textContent = `${diffDays} ${diffDays > 1 ? 'jours' : 'jour'} × ${pricePerDay.toLocaleString('fr-FR')} MAD`;
        }
        if (subtotalEl) {
            subtotalEl.textContent = subtotal.toLocaleString('fr-FR') + ' MAD';
        }
        if (discountEl && discountRow) {
            if (discount > 0) {
                discountRow.style.display = '';
                discountEl.textContent = '-' + discount.toLocaleString('fr-FR') + ' MAD';
            } else {
                discountRow.style.display = 'none';
            }
        }
        if (totalEl) {
            totalEl.textContent = total.toLocaleString('fr-FR') + ' MAD';
        }
    }

    [startDateInput, endDateInput].forEach(input => {
        if (input) {
            input.addEventListener('change', calculateDuration);
        }
    });

    // Calcul initial au chargement de la page
    calculateDuration();

    // Save dates
    saveDatesBtn.addEventListener('click', function() {
        console.log('Save dates button clicked'); // Debug
        
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        // Format dates for display
        const options = { day: 'numeric', month: 'short' };
        const startFormatted = startDate.toLocaleDateString('fr-FR', options);
        const endFormatted = endDate.toLocaleDateString('fr-FR', options);
        const year = startDate.getFullYear();
        
        // Update display
        document.getElementById('selected-dates').textContent = `Du ${startFormatted} au ${endFormatted} ${year}`;
        
        // Calculate and update duration
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        document.getElementById('selected-duration').textContent = diffDays + ' jours';
        
        // Update price calculation
        const pricePerDay = {{ $car->client_price_per_day }};
        const subtotal = diffDays * pricePerDay;
        const discount = diffDays >= 7 ? subtotal * 0.1 : 0;
        const total = subtotal - discount;
        
        // Update price display using IDs
        document.getElementById('price-breakdown').textContent = `${diffDays} ${diffDays > 1 ? 'jours' : 'jour'} × ${pricePerDay.toLocaleString('fr-FR')} MAD`;
        document.getElementById('subtotal-price').textContent = `${subtotal.toLocaleString('fr-FR')} MAD`;
        const discountRow = document.getElementById('discount-row');
        const discountPrice = document.getElementById('discount-price');
        if (discount > 0) {
            discountRow.style.display = '';
            discountPrice.textContent = `-${discount.toLocaleString('fr-FR')} MAD`;
        } else {
            discountRow.style.display = 'none';
        }
        document.getElementById('total-price').textContent = `${total.toLocaleString('fr-FR')} MAD`;
        
        editDatesModal.classList.add('hidden');
    });

    // Edit Duration Functionality
    const editDurationBtn = document.getElementById('editDurationBtn');
    const editDurationModal = document.getElementById('editDurationModal');
    const closeEditDurationModal = document.getElementById('closeEditDurationModal');
    const cancelDurationBtn = document.getElementById('cancelDurationBtn');
    const saveDurationBtn = document.getElementById('saveDurationBtn');
    const durationInput = document.getElementById('durationInput');
    const decreaseDays = document.getElementById('decreaseDays');
    const increaseDays = document.getElementById('increaseDays');
    const newTotalPrice = document.getElementById('new-total-price');

    // Open duration modal
    editDurationBtn.addEventListener('click', function() {
        // Set current duration value
        const currentDuration = parseInt(document.getElementById('selected-duration').textContent);
        durationInput.value = currentDuration;
        updateDurationPrice();
        editDurationModal.classList.remove('hidden');
    });

    // Close duration modal
    [closeEditDurationModal, cancelDurationBtn].forEach(btn => {
        btn.addEventListener('click', function() {
            editDurationModal.classList.add('hidden');
        });
    });

    // Duration controls
    decreaseDays.addEventListener('click', function() {
        if (durationInput.value > 1) {
            durationInput.value = parseInt(durationInput.value) - 1;
            updateDurationPrice();
        }
    });

    increaseDays.addEventListener('click', function() {
        if (durationInput.value < 30) {
            durationInput.value = parseInt(durationInput.value) + 1;
            updateDurationPrice();
        }
    });

    durationInput.addEventListener('input', updateDurationPrice);

    function updateDurationPrice() {
        const days = parseInt(durationInput.value);
        const pricePerDay = {{ $car->client_price_per_day }};
        const subtotal = days * pricePerDay;
        const discount = subtotal * 0.1;
        const total = subtotal - discount;
        newTotalPrice.textContent = `${total.toLocaleString()} MAD`;
    }

    // Save duration
    saveDurationBtn.addEventListener('click', function() {
        console.log('Save duration button clicked'); // Debug
        
        const newDuration = parseInt(durationInput.value);
        
        // Update display
        document.getElementById('selected-duration').textContent = newDuration + ' jours';
        
        // Update price calculation
        const pricePerDay = {{ $car->client_price_per_day }};
        const subtotal = newDuration * pricePerDay;
        const discount = subtotal * 0.1;
        const total = subtotal - discount;
        
        // Update price display using IDs
        document.getElementById('price-breakdown').textContent = `${newDuration} jours × ${pricePerDay.toLocaleString()} MAD`;
        document.getElementById('subtotal-price').textContent = `${subtotal.toLocaleString()} MAD`;
        document.getElementById('discount-price').textContent = `-${discount.toLocaleString()} MAD`;
        document.getElementById('total-price').textContent = `${total.toLocaleString()} MAD`;
        
        editDurationModal.classList.add('hidden');
    });

    // Close modals when clicking outside (including new modals)
    [editDatesModal, editDurationModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // Social Login Buttons Functionality
    const facebookLogin = document.getElementById('facebookLogin');
    const googleLogin = document.getElementById('googleLogin');
    const appleLogin = document.getElementById('appleLogin');
    const emailLogin = document.getElementById('emailLogin');

    console.log('Social login buttons found:', {
        facebook: !!facebookLogin,
        google: !!googleLogin,
        apple: !!appleLogin,
        email: !!emailLogin
    });

    // Facebook Login
    if (facebookLogin) {
        facebookLogin.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Facebook login clicked');
            // Handle Facebook login
            handleLoginMethod('Facebook', this);
        });
    }

    // Google Login
    if (googleLogin) {
        googleLogin.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Google login clicked');
            // Handle Google login
            handleLoginMethod('Google', this);
        });
    }

    // Apple Login
    if (appleLogin) {
        appleLogin.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Apple login clicked');
            // Handle Apple login
            handleLoginMethod('Apple', this);
        });
    }

    // Email Login
    if (emailLogin) {
        emailLogin.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Email login clicked');
            // Redirect to Laravel's register page
            window.location.href = '{{ route("register") }}';
        });
    }

    // Function to handle different login methods
    function handleLoginMethod(provider, buttonElement) {
        // Show loading state
        const originalText = buttonElement.innerHTML;
        buttonElement.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        
        // Handle different login methods
        switch(provider) {
            case 'Facebook':
                handleFacebookLogin(buttonElement, originalText);
                break;
            case 'Google':
                handleGoogleLogin(buttonElement, originalText);
                break;
            case 'Apple':
                handleAppleLogin(buttonElement, originalText);
                break;
        }
    }

    // Facebook Login Logic
    function handleFacebookLogin(buttonElement, originalText) {
        setTimeout(() => {
            buttonElement.innerHTML = originalText;
            
            // Show Facebook login options
            showLoginPopup('Facebook', 'Connexion Facebook', `
                <div class="space-y-4">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-blue-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Se connecter avec Facebook</h3>
                    </div>
                    
                    <!-- Quick Login Option -->
                    <div class="space-y-3">
                        <div class="text-sm font-medium text-gray-700">Connexion rapide :</div>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition duration-200" onclick="quickLogin('Facebook', 'John Doe', 'john.doe@example.com')">
                            <div class="w-10 h-10 bg-gray-200 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-900">John Doe</p>
                                <p class="text-sm text-gray-600">john.doe@example.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">ou</span>
                        </div>
                    </div>
                    
                    <!-- Real Facebook Login -->
                    <div class="space-y-3">
                        <div class="text-sm font-medium text-gray-700">Se connecter avec votre compte Facebook :</div>
                        <button id="realFacebookLogin" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Continuer avec Facebook
                        </button>
                    </div>
                    
                    <div class="text-xs text-gray-500">
                        <p>En continuant, vous autorisez RentCar Platform à :</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Voir votre nom et photo de profil</li>
                            <li>Accéder à votre adresse e-mail</li>
                        </ul>
                    </div>
                </div>
            `);
            
            // Add event listener for real Facebook login
            setTimeout(() => {
                const realFbBtn = document.getElementById('realFacebookLogin');
                if (realFbBtn) {
                    realFbBtn.addEventListener('click', function() {
                        initiateRealFacebookLogin();
                    });
                }
            }, 100);
        }, 1000);
    }

    // Google Login Logic
    function handleGoogleLogin(buttonElement, originalText) {
        setTimeout(() => {
            buttonElement.innerHTML = originalText;
            
            // Show Google login options
            showLoginPopup('Google', 'Se connecter avec Google', `
                <div class="space-y-4">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Se connecter avec Google</h3>
                    </div>
                    
                    <!-- Quick Login Options -->
                    <div class="space-y-3">
                        <div class="text-sm font-medium text-gray-700">Connexion rapide :</div>
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200" onclick="quickLogin('Google', 'John Doe', 'john.doe@gmail.com')">
                            <div class="w-10 h-10 bg-blue-100 rounded-full mr-3 flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">JD</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">John Doe</p>
                                <p class="text-sm text-gray-600">john.doe@gmail.com</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition duration-200" onclick="quickLogin('Google', 'Jane Smith', 'jane.smith@gmail.com')">
                            <div class="w-10 h-10 bg-red-100 rounded-full mr-3 flex items-center justify-center">
                                <span class="text-red-600 font-semibold">JS</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Jane Smith</p>
                                <p class="text-sm text-gray-600">jane.smith@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">ou</span>
                        </div>
                    </div>
                    
                    <!-- Real Google Login -->
                    <div class="space-y-3">
                        <div class="text-sm font-medium text-gray-700">Se connecter avec votre compte Google :</div>
                        <button id="realGoogleLogin" class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continuer avec Google
                        </button>
                    </div>
                </div>
            `);
            
            // Add event listener for real Google login
            setTimeout(() => {
                const realGoogleBtn = document.getElementById('realGoogleLogin');
                if (realGoogleBtn) {
                    realGoogleBtn.addEventListener('click', function() {
                        initiateRealGoogleLogin();
                    });
                }
            }, 100);
        }, 1000);
    }

    // Apple Login Logic
    function handleAppleLogin(buttonElement, originalText) {
        setTimeout(() => {
            buttonElement.innerHTML = originalText;
            
            // Show Apple login options
            showLoginPopup('Apple', 'Se connecter avec Apple', `
                <div class="space-y-4">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-gray-900 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Se connecter avec Apple</h3>
                    </div>
                    
                    <!-- Quick Login Option -->
                    <div class="space-y-3">
                        <div class="text-sm font-medium text-gray-700">Connexion rapide :</div>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition duration-200" onclick="quickLogin('Apple', 'John Doe', 'john.doe@icloud.com')">
                            <div class="w-10 h-10 bg-gray-800 rounded-full mr-3 flex items-center justify-center">
                                <span class="text-white font-semibold">JD</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">john.doe@icloud.com</p>
                                <p class="text-sm text-gray-600">Masquer mon adresse e-mail</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">ou</span>
                        </div>
                    </div>
                    
                    <!-- Real Apple Login -->
                    <div class="space-y-3">
                        <div class="text-sm font-medium text-gray-700">Se connecter avec votre identifiant Apple :</div>
                        <button id="realAppleLogin" class="w-full bg-black hover:bg-gray-800 text-white py-3 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            Continuer avec Apple
                        </button>
                    </div>
                    
                    <div class="text-xs text-gray-500">
                        <p>En continuant, vous autorisez RentCar Platform à :</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Voir votre nom</li>
                            <li>Voir votre adresse e-mail (si vous le souhaitez)</li>
                        </ul>
                    </div>
                </div>
            `);
            
            // Add event listener for real Apple login
            setTimeout(() => {
                const realAppleBtn = document.getElementById('realAppleLogin');
                if (realAppleBtn) {
                    realAppleBtn.addEventListener('click', function() {
                        initiateRealAppleLogin();
                    });
                }
            }, 100);
        }, 1000);
    }

    // Show login popup
    function showLoginPopup(provider, title, content) {
        // Close the main login modal
        loginModal.classList.add('hidden');
        
        // Create provider-specific popup
        const popup = document.createElement('div');
        popup.id = 'providerLoginPopup';
        popup.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        popup.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-md p-8">
                <div class="flex justify-between items-center mb-6">
                    <button id="closeProviderPopup" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <h2 class="text-xl font-bold text-gray-900">${title}</h2>
                </div>
                
                ${content}
                
                <div class="flex space-x-3 mt-6">
                    <button id="cancelProviderLogin" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold transition duration-200">
                        Annuler
                    </button>
                    <button id="confirmProviderLogin" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition duration-200">
                        Continuer
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(popup);
        
        // Add event listeners
        document.getElementById('closeProviderPopup').addEventListener('click', () => {
            document.body.removeChild(popup);
            loginModal.classList.remove('hidden');
        });
        
        document.getElementById('cancelProviderLogin').addEventListener('click', () => {
            document.body.removeChild(popup);
            loginModal.classList.remove('hidden');
        });
        
        document.getElementById('confirmProviderLogin').addEventListener('click', () => {
            document.body.removeChild(popup);
            completeLogin(provider);
        });
        
        // Close popup when clicking outside
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                document.body.removeChild(popup);
                loginModal.classList.remove('hidden');
            }
        });
    }

    // Quick login function (for demo accounts)
    function quickLogin(provider, name, email) {
        // Close any open popups
        const existingPopup = document.getElementById('providerLoginPopup');
        if (existingPopup) {
            document.body.removeChild(existingPopup);
        }
        
        // Close main modal
        loginModal.classList.add('hidden');
        
        // Enable step 2
        const step2 = document.getElementById('step2');
        step2.classList.remove('opacity-50', 'pointer-events-none');
        
        // Enable payment button
        const paymentBtn = document.getElementById('paymentBtn');
        paymentBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        paymentBtn.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white');
        paymentBtn.disabled = false;
        paymentBtn.textContent = 'Suivant';
        
        // Mark step 1 as completed
        const step1 = document.getElementById('step1');
        step1.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">1. Se connecter ou s'inscrire</h2>
                        <p class="text-sm text-gray-600 mt-1">Connecté avec ${provider} (${name})</p>
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
        
        // Show success message
        showNotification(`Connexion ${provider} réussie avec ${name} !`, 'success');
    }

    // Real Facebook Login
    function initiateRealFacebookLogin() {
        showNotification('Redirection vers Facebook...', 'info');
        
        // In a real app, you would use Facebook SDK:
        // FB.login(function(response) {
        //     if (response.authResponse) {
        //         // User logged in successfully
        //         completeLogin('Facebook');
        //     }
        // }, {scope: 'email'});
        
        // For demo purposes, simulate the process
        setTimeout(() => {
            showNotification('Ouverture de Facebook...', 'info');
            // Simulate opening Facebook login
            window.open('https://www.facebook.com/login', '_blank');
            
            // Simulate successful login after delay
            setTimeout(() => {
                completeLogin('Facebook');
            }, 3000);
        }, 1000);
    }

    // Real Google Login
    function initiateRealGoogleLogin() {
        showNotification('Redirection vers Google...', 'info');
        
        // In a real app, you would use Google OAuth:
        // gapi.auth2.getAuthInstance().signIn().then(function(googleUser) {
        //     var profile = googleUser.getBasicProfile();
        //     completeLogin('Google');
        // });
        
        // For demo purposes, simulate the process
        setTimeout(() => {
            showNotification('Ouverture de Google...', 'info');
            // Simulate opening Google login
            window.open('https://accounts.google.com/signin', '_blank');
            
            // Simulate successful login after delay
            setTimeout(() => {
                completeLogin('Google');
            }, 3000);
        }, 1000);
    }

    // Real Apple Login
    function initiateRealAppleLogin() {
        showNotification('Redirection vers Apple...', 'info');
        
        // In a real app, you would use Apple Sign In:
        // AppleID.auth.signIn().then(function(response) {
        //     completeLogin('Apple');
        // });
        
        // For demo purposes, simulate the process
        setTimeout(() => {
            showNotification('Ouverture d\'Apple ID...', 'info');
            // Simulate opening Apple login
            window.open('https://appleid.apple.com/sign-in', '_blank');
            
            // Simulate successful login after delay
            setTimeout(() => {
                completeLogin('Apple');
            }, 3000);
        }, 1000);
    }

    // Complete login process
    function completeLogin(provider) {
        // Close any open popups
        const existingPopup = document.getElementById('providerLoginPopup');
        if (existingPopup) {
            document.body.removeChild(existingPopup);
        }
        
        // Close main modal
        loginModal.classList.add('hidden');
        
        // Enable step 2
        const step2 = document.getElementById('step2');
        step2.classList.remove('opacity-50', 'pointer-events-none');
        
        // Enable payment button
        const paymentBtn = document.getElementById('paymentBtn');
        paymentBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        paymentBtn.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white');
        paymentBtn.disabled = false;
        paymentBtn.textContent = 'Suivant';
        
        // Mark step 1 as completed
        const step1 = document.getElementById('step1');
        step1.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">1. Se connecter ou s'inscrire</h2>
                        <p class="text-sm text-gray-600 mt-1">Connecté avec ${provider}</p>
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
        
        // Show success message
        showNotification(`Connexion ${provider} réussie !`, 'success');
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // ========== Payment Gateway Management ==========
    let stripe = null;
    let elements = null;
    let cardElement = null;
    let cardExpiryElement = null;
    let cardCvcElement = null;
    let clientSecret = null;
    let selectedPaymentGateway = 'stripe';

    // Payment Gateway Selection
    document.querySelectorAll('.payment-gateway-option').forEach(option => {
        option.addEventListener('click', function() {
            const gateway = this.dataset.gateway;
            const radio = this.querySelector('.payment-gateway-radio');
            radio.checked = true;

            document.querySelectorAll('.payment-gateway-option').forEach(opt => {
                opt.classList.remove('border-red-500');
                opt.classList.add('border-gray-200');
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-red-500');

            selectedPaymentGateway = gateway;
            
            if (gateway === 'stripe') {
                document.getElementById('stripe-payment-form').classList.remove('hidden');
                document.getElementById('cmi-payment-form').classList.add('hidden');
                document.getElementById('paypal-payment-form').classList.add('hidden');
                setTimeout(() => initStripe(), 100);
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

    // Initialize Stripe
    function initStripe() {
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
            try { cardElement.unmount(); } catch(e) {}
        }
        if (cardExpiryElement) {
            try { cardExpiryElement.unmount(); } catch(e) {}
        }
        if (cardCvcElement) {
            try { cardCvcElement.unmount(); } catch(e) {}
        }
        
        setTimeout(() => {
            const style = {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                    '::placeholder': { color: '#aab7c4' },
                },
            };

            cardElement = elements.create('cardNumber', { style });
            cardElement.mount('#card-element');

            cardExpiryElement = elements.create('cardExpiry', { style });
            cardExpiryElement.mount('#card-expiry-element');

            cardCvcElement = elements.create('cardCvc', { style });
            cardCvcElement.mount('#card-cvc-element');

            const displayError = document.getElementById('card-errors');
            [cardElement, cardExpiryElement, cardCvcElement].forEach(element => {
                element.on('change', function(event) {
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            });
        }, 100);
    }

    // PayPal Payment Handler
    const paypalSubmitButton = document.getElementById('paypal-submit-button');
    if (paypalSubmitButton) {
        paypalSubmitButton.addEventListener('click', async function() {
            const button = this;
            const buttonText = document.getElementById('paypal-button-text');
            const spinner = document.getElementById('paypal-spinner');
            const errorDiv = document.getElementById('paypal-error');
            
            button.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
            errorDiv.classList.add('hidden');

            try {
                // Récupérer les dates depuis les inputs ou la session
                const startDateInput = document.getElementById('startDateInput');
                const endDateInput = document.getElementById('endDateInput');
                
                const startDate = startDateInput ? startDateInput.value : '{{ session("booking_data.start_date") }}';
                const endDate = endDateInput ? endDateInput.value : '{{ session("booking_data.end_date") }}';
                
                if (!startDate || !endDate) {
                    throw new Error('Veuillez sélectionner des dates de début et de fin.');
                }
                
                const response = await fetch('{{ route("booking.init-paypal-payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        car_id: {{ $car->id }},
                        start_date: startDate,
                        end_date: endDate
                    })
                });

                const data = await response.json();
                
                if (data.success && data.approve_url) {
                    // Ouvrir PayPal dans une nouvelle fenêtre
                    const paypalWindow = window.open(data.approve_url, 'paypal', 'width=600,height=700');
                    
                    // Écouter les messages de succès
                    window.addEventListener('message', function(event) {
                        if (event.data && event.data.type === 'paypal_payment_success') {
                            paypalWindow.close();
                            window.location.href = '{{ route("client.rentals.confirm", $car) }}?payment_success=paypal';
                        }
                    });
                } else {
                    throw new Error(data.error || 'Erreur lors de l\'initialisation du paiement PayPal');
                }
            } catch (error) {
                errorDiv.textContent = error.message;
                errorDiv.classList.remove('hidden');
                button.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    }

    // CMI Payment Handler
    const cmiSubmitButton = document.getElementById('cmi-submit-button');
    let cmiCardNumber = document.getElementById('cmi-card-number');
    let cmiExpiry = document.getElementById('cmi-expiry');
    let cmiCvc = document.getElementById('cmi-cvc');
    let cmiCardholder = document.getElementById('cmi-cardholder');

    function formatCardNumber(value) {
        const digits = value.replace(/\D/g, '').slice(0, 19);
        return digits.replace(/(.{4})/g, '$1 ').trim();
    }

    function formatExpiry(value) {
        const digits = value.replace(/\D/g, '').slice(0, 4);
        if (digits.length <= 2) return digits;
        return `${digits.slice(0, 2)}/${digits.slice(2)}`;
    }

    function isValidExpiry(expiry) {
        if (!/^\d{2}\/\d{2}$/.test(expiry)) return false;
        const [mm, yy] = expiry.split('/').map(v => parseInt(v, 10));
        if (mm < 1 || mm > 12) return false;
        const now = new Date();
        const currentYY = parseInt(now.getFullYear().toString().slice(-2), 10);
        const currentMM = now.getMonth() + 1;
        if (yy < currentYY) return false;
        if (yy === currentYY && mm < currentMM) return false;
        return true;
    }

    function setFieldError(key, message) {
        const map = {
            card: 'cmi-card-error',
            expiry: 'cmi-expiry-error',
            cvc: 'cmi-cvc-error',
            cardholder: 'cmi-cardholder-error',
        };
        const el = document.getElementById(map[key]);
        if (el) {
            el.textContent = message || '';
            el.classList.toggle('hidden', !message);
        }
        const inputMap = {
            card: cmiCardNumber,
            expiry: cmiExpiry,
            cvc: cmiCvc,
            cardholder: cmiCardholder,
        };
        if (inputMap[key]) {
            inputMap[key].classList.toggle('border-red-500', !!message);
        }
    }

    async function validateCardNumberApi(number) {
        try {
            const response = await fetch('{{ route('client.cards.validate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ card_number: number })
            });
            const data = await response.json();
            if (!response.ok || !data.valid) {
                setFieldError('card', data.message || 'Your card is not valid');
                return false;
            }
            setFieldError('card', '');
            return true;
        } catch (e) {
            setFieldError('card', 'Your card is not valid');
            return false;
        }
    }

    function bindCmiListeners() {
        cmiCardNumber = document.getElementById('cmi-card-number');
        cmiExpiry = document.getElementById('cmi-expiry');
        cmiCvc = document.getElementById('cmi-cvc');
        cmiCardholder = document.getElementById('cmi-cardholder');

        if (cmiCardNumber && !cmiCardNumber.dataset.bound) {
            cmiCardNumber.dataset.bound = '1';
            cmiCardNumber.addEventListener('input', (e) => {
                const formatted = formatCardNumber(e.target.value);
                e.target.value = formatted;
                setFieldError('card', '');
            });
            cmiCardNumber.addEventListener('blur', () => {
                const digits = cmiCardNumber.value.replace(/\s/g, '');
                if (digits.length >= 13 && digits.length <= 19) {
                    validateCardNumberApi(digits);
                }
            });
        }

        if (cmiExpiry && !cmiExpiry.dataset.bound) {
            cmiExpiry.dataset.bound = '1';
            cmiExpiry.addEventListener('input', (e) => {
                e.target.value = formatExpiry(e.target.value);
                setFieldError('expiry', '');
            });
        }

        if (cmiCvc && !cmiCvc.dataset.bound) {
            cmiCvc.dataset.bound = '1';
            cmiCvc.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
                setFieldError('cvc', '');
            });
        }

        if (cmiCardholder && !cmiCardholder.dataset.bound) {
            cmiCardholder.dataset.bound = '1';
            cmiCardholder.addEventListener('input', () => setFieldError('cardholder', ''));
        }
    }

    bindCmiListeners();
    // Re-bind if the form is re-rendered dynamically
    document.addEventListener('DOMContentLoaded', bindCmiListeners);
    // Ensure formatting even if events are missed (paste/fast input)
    document.addEventListener('input', (e) => {
        const targetId = e.target && e.target.id;
        if (targetId === 'cmi-card-number') {
            e.target.value = formatCardNumber(e.target.value);
            setFieldError('card', '');
        }
        if (targetId === 'cmi-expiry') {
            e.target.value = formatExpiry(e.target.value);
            setFieldError('expiry', '');
        }
        if (targetId === 'cmi-cvc') {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
            setFieldError('cvc', '');
        }
        // Ensure Stripe modal inputs also stay formatted on fast paste/auto-fill
        if (targetId === 'cardNumber') {
            e.target.value = formatCardNumber(e.target.value);
        }
        if (targetId === 'expirationDate') {
            e.target.value = formatExpiry(e.target.value);
        }
    });

    if (cmiSubmitButton) {
        cmiSubmitButton.addEventListener('click', async function(event) {
            event.preventDefault();

            const cardholder = cmiCardholder ? cmiCardholder.value.trim() : '';
            const cardNumber = cmiCardNumber ? cmiCardNumber.value.replace(/\s/g, '') : '';
            const expiry = cmiExpiry ? cmiExpiry.value.trim() : '';
            const cvc = cmiCvc ? cmiCvc.value.trim() : '';
            const zip = document.getElementById('cmi-zip')?.value.trim() || '';
            const country = document.getElementById('cmi-country')?.value;

            let valid = true;

            if (!cardholder) {
                setFieldError('cardholder', 'Veuillez renseigner le nom du titulaire');
                valid = false;
            }

            if (!cardNumber) {
                setFieldError('card', 'Veuillez remplir le numéro de carte');
                valid = false;
            } else if (cardNumber.length < 13 || cardNumber.length > 19) {
                setFieldError('card', 'Le numéro de carte doit contenir entre 13 et 19 chiffres');
                valid = false;
            }

            if (!expiry) {
                setFieldError('expiry', 'Veuillez remplir la date d\'expiration');
                valid = false;
            } else if (!isValidExpiry(expiry)) {
                setFieldError('expiry', 'La date d\'expiration doit être au format MM/AA et future');
                valid = false;
            }

            if (!cvc) {
                setFieldError('cvc', 'Veuillez remplir le CVV');
                valid = false;
            } else if (cvc.length < 3 || cvc.length > 4) {
                setFieldError('cvc', 'Le CVV doit contenir 3 ou 4 chiffres');
                valid = false;
            }

            if (!zip || !country) {
                showNotification('Veuillez remplir le code postal et le pays', 'error');
                valid = false;
            }

            if (!valid) return;

            const cardOk = await validateCardNumberApi(cardNumber);
            if (!cardOk) return;

            document.getElementById('cmi-form').submit();
        });
    }
});
</script>
@endsection
