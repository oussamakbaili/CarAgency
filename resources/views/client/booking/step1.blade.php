@extends('layouts.public')

@section('title', 'Réserver - ' . $car->brand . ' ' . $car->model)

@section('content')
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('public.home') }}" class="text-blue-600 hover:text-blue-800">Accueil</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ route('public.agencies') }}" class="text-blue-600 hover:text-blue-800">Agences</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ route('public.agency.show', $car->agency) }}" class="text-blue-600 hover:text-blue-800">{{ $car->agency->agency_name }}</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-600">{{ $car->brand }} {{ $car->model }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Car Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Car Images -->
                        <div class="relative h-64 md:h-80 bg-gray-100">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-lg px-3 py-2">
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">{{ number_format($car->price_per_day, 0) }} MAD</div>
                                    <div class="text-sm text-gray-600">par jour</div>
                                </div>
                            </div>
                        </div>

                        <!-- Car Details -->
                        <div class="p-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h1>
                            <p class="text-gray-600 mb-4">{{ $car->year }} • {{ $car->registration_number }}</p>

                            <!-- Car Features -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                @if($car->fuel_type)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ ucfirst($car->fuel_type) }}</span>
                                    </div>
                                @endif
                                @if($car->transmission)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ ucfirst($car->transmission) }}</span>
                                    </div>
                                @endif
                                @if($car->seats)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $car->seats }} places</span>
                                    </div>
                                @endif
                                @if($car->color)
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 mr-2 rounded-full bg-gray-400 border border-gray-300"></div>
                                        <span class="text-sm text-gray-600">{{ ucfirst($car->color) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Agency Information -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-center">
                                    @if($car->agency->user->profile_photo_path)
                                        <img src="{{ Storage::url($car->agency->user->profile_photo_path) }}" 
                                             alt="{{ $car->agency->agency_name }}" 
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h3 class="font-semibold text-gray-900">{{ $car->agency->agency_name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $car->agency->address ?? 'Adresse non spécifiée' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-6">
                            <form method="POST" action="{{ route('booking.process-step1', $car) }}" id="booking-form">
                            @csrf
                            
                            <!-- Progress Indicator -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Étape 1 sur 5</span>
                                    <span class="text-sm text-gray-500">Sélection des dates</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 20%"></div>
                                </div>
                            </div>

                            <!-- Date Selection -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date de début
                                    </label>
                                    <input type="date" id="start_date" name="start_date" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           required>
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date de fin
                                    </label>
                                    <input type="date" id="end_date" name="end_date" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           required>
                                </div>
                            </div>

                            <!-- Price Preview -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-6" id="price-preview" style="display: none;">
                                <h3 class="font-semibold text-gray-900 mb-3">Résumé des prix</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ number_format($car->price_per_day, 0) }} MAD × <span id="days-count">0</span> jour(s)</span>
                                        <span class="text-gray-900" id="subtotal">0 MAD</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Frais de service</span>
                                        <span class="text-gray-900" id="service-fee">0 MAD</span>
                                    </div>
                                    <hr class="border-gray-300">
                                    <div class="flex justify-between font-semibold">
                                        <span class="text-gray-900">Total</span>
                                        <span class="text-blue-600" id="total-price">0 MAD</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Continue Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="continue-btn" disabled>
                                Continuer
                            </button>

                            <!-- Login Prompt -->
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">
                                    Vous avez déjà un compte ? 
                                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Connectez-vous
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const pricePreview = document.getElementById('price-preview');
            const daysCount = document.getElementById('days-count');
            const subtotal = document.getElementById('subtotal');
            const serviceFee = document.getElementById('service-fee');
            const totalPrice = document.getElementById('total-price');
            const continueBtn = document.getElementById('continue-btn');
            
            const pricePerDay = {{ $car->price_per_day }};

            function updatePrice() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDate && endDate && endDate > startDate) {
                    const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                    const subtotalAmount = days * pricePerDay;
                    const serviceFeeAmount = subtotalAmount * 0.05; // 5% service fee
                    const totalAmount = subtotalAmount + serviceFeeAmount;

                    daysCount.textContent = days;
                    subtotal.textContent = subtotalAmount.toLocaleString() + ' MAD';
                    serviceFee.textContent = serviceFeeAmount.toLocaleString() + ' MAD';
                    totalPrice.textContent = totalAmount.toLocaleString() + ' MAD';
                    
                    pricePreview.style.display = 'block';
                    continueBtn.disabled = false;
                } else {
                    pricePreview.style.display = 'none';
                    continueBtn.disabled = true;
                }
            }

            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                updatePrice();
            });

            endDateInput.addEventListener('change', updatePrice);
        });
    </script>
@endsection
