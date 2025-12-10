@extends('layouts.public')

@section('title', $car->brand . ' ' . $car->model . ' - ' . $agency->agency_name . ' - ToubCar')

@push('styles')
<style>
    .calendar-day {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s;
    }
    .calendar-day:hover {
        background-color: #f3f4f6;
    }
    .calendar-day.selected {
        background-color: #000;
        color: white;
    }
    .calendar-day.in-range {
        background-color: #f3f4f6;
    }
    .calendar-day.disabled {
        color: #d1d5db;
        cursor: not-allowed;
    }
    #map {
        height: 500px;
        width: 100%;
        border-radius: 12px;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white">
    <!-- Navigation Tabs -->
    <div class="sticky top-0 bg-white border-b border-gray-200 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8">
                <a href="#photos" class="py-4 border-b-2 border-black font-semibold text-gray-900">Photos</a>
                <a href="#amenities" class="py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900">Équipements</a>
                <a href="#reviews" class="py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900">Avis</a>
                <a href="#location" class="py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900">Localisation</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Car Title and Info -->
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h1>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-semibold">{{ number_format($car->getAverageRating(), 1) }}</span>
                            <span>({{ $car->getReviewsCount() }} avis)</span>
                        </div>
                        <span>•</span>
                        <span>{{ $agency->city }}, {{ $agency->country ?? 'Maroc' }}</span>
                    </div>
                </div>

                <!-- Image Gallery - Horizontal Scrollable -->
                <div id="photos" class="mb-6">
                    @php
                        $allImages = [];
                        if ($car->image_url) {
                            $allImages[] = $car->image_url;
                        }
                        if ($car->picture_urls && is_array($car->picture_urls)) {
                            $allImages = array_merge($allImages, $car->picture_urls);
                        }
                        $allImages = array_unique(array_filter($allImages));
                    @endphp
                    
                    @if(count($allImages) > 0)
                        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($allImages as $index => $image)
                                <div class="flex-shrink-0 w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] rounded-xl overflow-hidden cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('{{ $image }}')">
                                    <img src="{{ $image }}" 
                                         alt="{{ $car->brand }} {{ $car->model }} - Image {{ $index + 1 }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="w-full h-[400px] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-2xl">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium">Photo du véhicule</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Description Section -->
                <div class="border-b border-gray-200 pb-8">
                    <div class="flex items-start gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-2">Véhicule entier • {{ $agency->city }}</h2>
                            <p class="text-gray-600">
                                {{ $car->seats ?? 5 }} passagers • {{ $car->transmission ?? 'Automatique' }} • {{ $car->fuel_type ?? 'Essence' }}
                            </p>
                        </div>
                    </div>
                    
                    @if($car->description)
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $car->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Amenities Section -->
                @if($car->features && is_array($car->features) && count($car->features) > 0)
                <div id="amenities" class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold mb-6">Ce que ce véhicule offre</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($car->features as $feature)
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Calendar Section -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold mb-6">Choisissez vos dates</h2>
                    <div id="calendar-container" class="max-w-md">
                        <!-- Calendar will be rendered here by JavaScript -->
                    </div>
                </div>

                <!-- Reviews Section -->
                <div id="reviews" class="border-b border-gray-200 pb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xl font-semibold">{{ number_format($car->getAverageRating(), 1) }}</span>
                            <span class="text-gray-600">({{ $car->getReviewsCount() }} avis)</span>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse($car->approvedReviews()->with('user')->latest()->limit(5)->get() as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-b-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <span class="text-orange-600 font-semibold">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ $review->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->created_at->format('M Y') }}</div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500">Aucun avis pour le moment</p>
                        @endforelse
                    </div>
                </div>

                <!-- Location Map Section -->
                <div id="location" class="pb-8">
                    <h2 class="text-xl font-semibold mb-4">Où vous serez</h2>
                    <p class="text-gray-600 mb-4">{{ $agency->address }}, {{ $agency->city }}</p>
                    <div id="map"></div>
                </div>
            </div>

            <!-- Right Column - Booking Widget (Sticky) -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="border border-gray-200 rounded-2xl shadow-lg p-6">
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-2xl font-semibold">{{ number_format($car->client_price_per_day, 0) }}</span>
                            <span class="text-gray-600">MAD</span>
                            <span class="text-gray-600">/ jour</span>
                        </div>

                        <!-- Date Selection -->
                        <form action="{{ route('booking.process-step1', $car) }}" method="POST" id="bookingForm">
                            @csrf
                            <div class="space-y-4 mb-4">
                                <div class="border border-gray-300 rounded-lg p-3">
                                    <label class="text-xs font-semibold text-gray-700 block mb-1">CHECK-IN</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           value="{{ old('start_date', \Carbon\Carbon::tomorrow()->format('Y-m-d')) }}"
                                           min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                           class="w-full text-sm border-none outline-none" required>
                                </div>
                                <div class="border border-gray-300 rounded-lg p-3">
                                    <label class="text-xs font-semibold text-gray-700 block mb-1">CHECKOUT</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           value="{{ old('end_date', \Carbon\Carbon::tomorrow()->addDays(7)->format('Y-m-d')) }}"
                                           min="{{ \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d') }}"
                                           class="w-full text-sm border-none outline-none" required>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-pink-600 to-pink-700 hover:from-pink-700 hover:to-pink-800 text-white font-semibold py-3 rounded-lg transition-all duration-200">
                                Réserver
                            </button>
                            
                            <p class="text-xs text-center text-gray-600 mt-4">Vous ne serez pas débité pour le moment</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key', 'YOUR_API_KEY') }}&libraries=places"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Google Map
    @if($agency->latitude && $agency->longitude)
    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: {{ $agency->latitude }}, lng: {{ $agency->longitude }} },
        zoom: 15,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });
    
    new google.maps.Marker({
        position: { lat: {{ $agency->latitude }}, lng: {{ $agency->longitude }} },
        map: map,
        title: '{{ $agency->agency_name }}'
    });
    @else
    // Geocode address if no coordinates
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: '{{ $agency->address }}, {{ $agency->city }}' }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const map = new google.maps.Map(document.getElementById('map'), {
                center: results[0].geometry.location,
                zoom: 15
            });
            
            new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                title: '{{ $agency->agency_name }}'
            });
        }
    });
    @endif

    // Date validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 1);
        endDateInput.min = minEndDate.toISOString().split('T')[0];
        
        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = minEndDate.toISOString().split('T')[0];
        }
    });
    
    // Image modal function
    window.openImageModal = function(imageUrl) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90';
        modal.onclick = () => modal.remove();
        modal.innerHTML = `
            <img src="${imageUrl}" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
        `;
        document.body.appendChild(modal);
    };
});
</script>
@endpush
@endsection
