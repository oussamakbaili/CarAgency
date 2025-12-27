@extends('layouts.public')

@section('title', __('booking.step3.page_title', ['brand' => $car->brand, 'model' => $car->model]))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('client.cars.show', $car) }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('booking.step3.back') }}
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('booking.step3.title') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('booking.step3.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Booking Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">{{ __('booking.step3.details.title') }}</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ __('booking.step3.details.vehicle') }}</span>
                            <span class="text-gray-900 font-medium">{{ $car->brand }} {{ $car->model }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ __('booking.step3.details.agency') }}</span>
                            <span class="text-gray-900 font-medium">{{ $car->agency->agency_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ __('booking.step3.details.dates') }}</span>
                            <span class="text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($bookingData['start_date'])->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($bookingData['end_date'])->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ __('booking.step3.details.duration') }}</span>
                            <span class="text-gray-900 font-medium">{{ trans_choice('booking.step3.details.days', $bookingData['days'], ['count' => $bookingData['days']]) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ __('booking.step3.details.price_per_day') }}</span>
                            <span class="text-gray-900 font-medium">{{ number_format($bookingData['price_per_day'], 2, ',', ' ') }} MAD</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ __('booking.step3.details.subtotal') }}</span>
                            <span class="text-gray-900 font-medium">{{ number_format($bookingData['total_price'], 2, ',', ' ') }} MAD</span>
                        </div>
                        <div class="flex justify-between pt-4">
                            <span class="text-gray-900 font-semibold text-lg">{{ __('booking.step3.details.total') }}</span>
                            <span class="text-gray-900 font-bold text-xl">{{ number_format($bookingData['total_with_fees'], 2, ',', ' ') }} MAD</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('booking.step4') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                        {{ __('booking.step3.actions.continue_payment') }}
                    </a>
                </div>
            </div>

            <!-- Right Column - Car Summary -->
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

                    <!-- Price Details -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 text-sm">{{ $bookingData['days'] }} jour(s) × {{ number_format($bookingData['price_per_day'], 0) }} MAD</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-gray-900 font-semibold">Total</span>
                                <span class="text-gray-900 font-bold text-lg">{{ number_format($bookingData['total_with_fees'], 2, ',', ' ') }} MAD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

