@extends('layouts.public')

@section('title', __('booking.step1.page_title', ['brand' => $car->brand, 'model' => $car->model]))

@section('content')
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('public.home') }}" class="text-blue-600 hover:text-blue-800">{{ __('booking.step1.breadcrumbs.home') }}</a></li>
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
                            
                            <!-- Price + Availability Badge -->
                            <div class="absolute top-4 right-4 space-y-2 text-right">
                                <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-sm">
                                    <div class="text-2xl font-bold text-blue-600">{{ number_format($car->client_price_per_day, 0) }} MAD</div>
                                    <div class="text-sm text-gray-600">{{ __('booking.step1.price.per_day') }}</div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($car->is_available)
                                        bg-green-100 text-green-800
                                    @elseif($car->status === 'rented')
                                        bg-blue-100 text-blue-800
                                    @elseif($car->status === 'maintenance')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    @if($car->is_available)
                                        {{ __('booking.step1.availability.available') }}
                                    @elseif($car->status === 'rented')
                                        {{ __('booking.step1.availability.rented') }}
                                    @elseif($car->status === 'maintenance')
                                        {{ __('booking.step1.availability.maintenance') }}
                                    @else
                                        {{ __('booking.step1.availability.unavailable') }}
                                    @endif
                                </span>
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
                                        <span class="text-sm text-gray-600">{{ trans_choice('booking.step1.agency.seats', $car->seats, ['count' => $car->seats]) }}</span>
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
                                        <p class="text-sm text-gray-600">{{ $car->agency->address ?? __('booking.step1.agency.address_unknown') }}</p>
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
                                    <span class="text-sm font-medium text-gray-600">{{ __('booking.step1.progress.step', ['current' => 1, 'total' => 5]) }}</span>
                                    <span class="text-sm text-gray-500">{{ __('booking.step1.progress.title') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 20%"></div>
                                </div>
                            </div>

                            <!-- Date Selection -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('booking.step1.dates.start') }}
                                    </label>
                                    <input type="date" id="start_date" name="start_date" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           required>
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('booking.step1.dates.end') }}
                                    </label>
                                    <input type="date" id="end_date" name="end_date" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           required>
                                </div>
                            </div>

                            <!-- Price Preview -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-6" id="price-preview" style="display: none;">
                                <h3 class="font-semibold text-gray-900 mb-3">{{ __('booking.step1.price_summary.title') }}</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ number_format($car->client_price_per_day, 0) }} MAD × <span id="days-count">0</span> {{ __('booking.step1.price_summary.days') }}</span>
                                        <span class="text-gray-900" id="subtotal">0 MAD</span>
                                    </div>
                                    <hr class="border-gray-300">
                                    <div class="flex justify-between font-semibold">
                                        <span class="text-gray-900">{{ __('booking.step1.price_summary.total') }}</span>
                                        <span class="text-blue-600" id="total-price">0 MAD</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Continue Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="continue-btn" disabled>
                                {{ __('booking.step1.actions.continue') }}
                            </button>

                            <!-- Login Prompt -->
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">
                                    {{ __('booking.step1.actions.login_prompt') }} 
                                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ __('booking.step1.actions.login') }}
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
            const totalPrice = document.getElementById('total-price');
            const continueBtn = document.getElementById('continue-btn');
            
            const pricePerDay = {{ $car->client_price_per_day }};

            function updatePrice(trigger) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDate && endDate && endDate > startDate) {
                    const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                    const totalAmount = days * pricePerDay;

                    daysCount.textContent = days;
                    totalPrice.textContent = totalAmount.toLocaleString() + ' MAD';
                    
                    pricePreview.style.display = 'block';
                    // Le bouton ne s'active qu'après une modification de la date de fin
                    continueBtn.disabled = trigger !== 'end';
                } else {
                    pricePreview.style.display = 'none';
                    continueBtn.disabled = true;
                }
            }

            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                updatePrice('start');
            });

            endDateInput.addEventListener('change', function() {
                updatePrice('end');
            });
        });
    </script>
@endsection
