@extends('layouts.client')

@section('header', $car->brand . ' ' . $car->model)

@section('content')
@php
    // Get all images
    $allImages = [];
    if ($car->image_url) {
        $allImages[] = $car->image_url;
    }
    if ($car->picture_urls && is_array($car->picture_urls)) {
        $allImages = array_merge($allImages, $car->picture_urls);
    }
    $allImages = array_unique($allImages);
    $mainImage = $allImages[0] ?? null;
    $otherImages = array_slice($allImages, 1, 4);
    
    // Get ratings data
    $averageRating = $car->getAverageRating();
    $totalReviews = $car->getTotalReviews();
    $ratingDistribution = $car->getRatingDistribution();
    $recentReviews = $car->getRecentReviews(10);
    
    // Calculate category ratings (for vehicle-specific categories)
    $categoryRatings = [
        'cleanliness' => 0,
        'accuracy' => 0,
        'pickup' => 0,
        'communication' => 0,
        'location' => 0,
        'value' => 0
    ];
    
    // For now, we'll use the overall rating for each category
    // In a real system, you'd have separate ratings for each category
    foreach ($categoryRatings as $key => $value) {
        $categoryRatings[$key] = $averageRating > 0 ? round($averageRating, 1) : 0;
    }
    
    // Check if it's a top-rated vehicle (top 5%)
    $isTopRated = $averageRating >= 4.5 && $totalReviews >= 3;
@endphp

<div class="max-w-7xl mx-auto">
    <!-- Image Gallery Section -->
    <div class="mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-2 h-[500px] lg:h-[600px]">
            <!-- Main Image -->
            <div class="lg:col-span-2 row-span-2">
                @if($mainImage)
                    <img src="{{ $mainImage }}" 
                         alt="{{ $car->brand }} {{ $car->model }}" 
                         class="w-full h-full object-cover rounded-l-2xl cursor-pointer"
                         onclick="openImageModal('{{ $mainImage }}')">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-l-2xl">
                        <div class="text-center">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Photo du véhicule</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Small Images Grid -->
            @if(count($otherImages) > 0)
                @foreach($otherImages as $index => $image)
                    <div class="relative">
                        <img src="{{ $image }}" 
                             alt="{{ $car->brand }} {{ $car->model }} - Image {{ $index + 2 }}" 
                             class="w-full h-full object-cover cursor-pointer {{ $index === 0 ? 'rounded-tr-2xl' : '' }} {{ $index === count($otherImages) - 1 && count($otherImages) === 4 ? 'rounded-br-2xl' : '' }}"
                             onclick="openImageModal('{{ $image }}')">
                        @if($index === count($otherImages) - 1 && count($allImages) > 5)
                            <div class="absolute inset-0 bg-black bg-opacity-40 rounded-br-2xl flex items-center justify-center cursor-pointer"
                                 onclick="openImageGallery()">
                                <button class="bg-white text-gray-900 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Voir toutes les photos
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <!-- Placeholder for missing images -->
                @for($i = 0; $i < min(4, 4 - count($otherImages)); $i++)
                    <div class="bg-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endfor
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Title and Summary -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h1>
                <div class="flex items-center gap-4 text-gray-600 mb-4">
                    <span>{{ $car->year }} • {{ $car->fuel_type ?? 'N/A' }}</span>
                    @if($car->seats)
                        <span>• {{ $car->seats }} places</span>
                    @endif
                    @if($car->transmission)
                        <span>• {{ $car->transmission }}</span>
                    @endif
                </div>
                
                <!-- Rating and Reviews Summary -->
                @if($totalReviews > 0)
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-semibold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-gray-600">({{ $totalReviews }} avis)</span>
                        </div>
                        @if($isTopRated)
                            <div class="flex items-center gap-2 px-3 py-1 bg-orange-50 border border-orange-200 rounded-full">
                                <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-sm font-medium text-orange-700">Véhicule favori</span>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Agency Info -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Proposé par {{ $car->agency->agency_name ?? 'Agence' }}</p>
                        <p class="text-sm text-gray-600">{{ $car->agency->city ?? '' }} {{ $car->agency->address ?? '' }}</p>
                    </div>
                </div>
            </div>

        <!-- Informations Générales -->
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Informations Générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Marque</span>
                        <span class="font-medium text-gray-900">{{ $car->brand }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Modèle</span>
                        <span class="font-medium text-gray-900">{{ $car->model }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Année</span>
                        <span class="font-medium text-gray-900">{{ $car->year }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Immatriculation</span>
                        <span class="font-medium text-gray-900">{{ $car->registration_number }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Catégorie</span>
                        <span class="font-medium text-gray-900">{{ $car->category->name ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Couleur</span>
                        <span class="font-medium text-gray-900">{{ $car->color ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Carburant</span>
                        <span class="font-medium text-gray-900">{{ $car->fuel_type ?? 'Non défini' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Transmission</span>
                        <span class="font-medium text-gray-900">{{ $car->transmission ?? 'Non définie' }}</span>
                    </div>
                    @if($car->seats)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Places</span>
                        <span class="font-medium text-gray-900">{{ $car->seats }}</span>
                    </div>
                    @endif
                    @if($car->engine_size)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Cylindrée</span>
                        <span class="font-medium text-gray-900">{{ $car->engine_size }}</span>
                    </div>
                    @endif
                    @if($car->mileage)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Kilométrage</span>
                        <span class="font-medium text-gray-900">{{ number_format($car->mileage, 0, ',', ' ') }} km</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Équipements -->
            @if($car->features && count($car->features) > 0)
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Équipements</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($car->features as $feature)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Description -->
            @if($car->description)
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $car->description }}</p>
            </div>
            @endif

            <!-- Ratings and Reviews Section -->
            @if($totalReviews > 0)
            <div class="border-t border-gray-200 pt-8">
                <!-- Overall Rating Header -->
                <div class="text-center mb-8 pb-8 border-b border-gray-200">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-5xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                        <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    @if($isTopRated)
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Véhicule favori</h3>
                        <p class="text-gray-600">Ce véhicule fait partie des <strong>top 5%</strong> des véhicules disponibles selon les notes, avis et fiabilité.</p>
                    @endif
                </div>

                <!-- Rating Distribution and Categories -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Overall Rating Distribution -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Répartition des notes</h4>
                        <div class="space-y-2">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-600 w-8">{{ $i }}</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 relative overflow-hidden">
                                        @php
                                            $count = $ratingDistribution[$i] ?? 0;
                                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                        @endphp
                                        <div class="bg-orange-600 h-full rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Category Ratings -->
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-4">Détails des notes</h4>
                        <div class="space-y-4">
                            @php
                                $categories = [
                                    'cleanliness' => ['name' => 'Propreté'],
                                    'accuracy' => ['name' => 'Précision'],
                                    'pickup' => ['name' => 'Récupération'],
                                    'communication' => ['name' => 'Communication'],
                                    'location' => ['name' => 'Localisation'],
                                    'value' => ['name' => 'Rapport qualité-prix']
                                ];
                            @endphp
                            @foreach($categories as $key => $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-full bg-orange-600"></div>
                                        </div>
                                        <span class="text-gray-700">{{ $category['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900">{{ number_format($categoryRatings[$key], 1) }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= round($categoryRatings[$key]) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="mt-8">
                    <h4 class="text-xl font-bold text-gray-900 mb-6">{{ $totalReviews }} avis</h4>
                    <div class="space-y-6">
                        @foreach($recentReviews as $review)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-orange-600">
                                                {{ substr($review->client->user->name ?? 'C', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-gray-900">{{ $review->client->user->name ?? 'Client anonyme' }}</h5>
                                            <div class="flex items-center gap-2 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($review->title)
                                    <h6 class="font-medium text-gray-900 mb-2">{{ $review->title }}</h6>
                                @endif
                                @if($review->comment)
                                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Agency Location Map (if coordinates available) -->
            @if($car->agency && $car->agency->latitude && $car->agency->longitude)
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Où se trouve l'agence ?</h2>
                <div class="bg-gray-100 rounded-2xl overflow-hidden" style="height: 400px;">
                    <iframe
                        width="100%"
                        height="100%"
                        style="border:0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.api_key', '') }}&q={{ $car->agency->latitude }},{{ $car->agency->longitude }}">
                    </iframe>
                </div>
                <div class="mt-4">
                    <p class="font-semibold text-gray-900">{{ $car->agency->agency_name }}</p>
                    <p class="text-gray-600">{{ $car->agency->address }}, {{ $car->agency->city }}, {{ $car->agency->postal_code }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Booking Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-8">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6">
                        <!-- Price -->
                        <div class="mb-6">
                            <div class="text-3xl font-bold text-orange-600 mb-1">
                                {{ number_format($car->client_price_per_day, 0) }} MAD
                            </div>
                            <div class="text-gray-600">par jour</div>
                        </div>

                        <!-- Booking Form -->
                        <form action="{{ route('booking.process-step1', $car) }}" method="POST" id="bookingForm">
                            @csrf
                <div class="space-y-4">
                                <!-- Check-in -->
                                <div>
                                    <label for="check_in_display" class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">CHECK-IN</label>
                                    <input type="text" 
                                           id="check_in_display" 
                                           placeholder="JJ/MM/AAAA"
                                           readonly
                                           class="w-full px-4 py-3 border-2 border-gray-900 rounded-lg cursor-pointer focus:outline-none text-gray-900 bg-white"
                                           onclick="openDatePicker('check_in')">
                                    <input type="hidden" 
                                           name="start_date" 
                                           id="check_in" 
                                           required>
                                </div>

                                <!-- Check-out -->
                                <div>
                                    <label for="check_out_display" class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">CHECK-OUT</label>
                                    <input type="text" 
                                           id="check_out_display" 
                                           placeholder="Add date"
                                           readonly
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg cursor-pointer focus:outline-none text-gray-500 bg-white"
                                           onclick="openDatePicker('check_out')">
                                    <input type="hidden" 
                                           name="end_date" 
                                           id="check_out" 
                                           required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        id="bookingButton"
                                        class="w-full bg-orange-600 text-white py-4 rounded-lg font-semibold hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ !$car->is_available ? 'disabled' : '' }}>
                                    <span id="buttonText">
                                        @if($car->is_available)
                                            Vérifier la disponibilité
                                        @else
                                            Indisponible
                                        @endif
                                    </span>
                                    <span id="buttonLoading" class="hidden">
                                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                    </div>
                        </form>

                        <!-- Availability Status -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Statut:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($car->is_available) bg-green-100 text-green-800
                            @elseif($car->status === 'rented') bg-blue-100 text-blue-800
                            @elseif($car->status === 'maintenance') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($car->is_available) Disponible
                            @elseif($car->status === 'rented') En location
                            @elseif($car->status === 'maintenance') Maintenance
                            @else Indisponible
                            @endif
                        </span>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4">
    <div class="max-w-6xl max-h-full relative">
        <img id="modalImage" src="" alt="Image" class="max-w-full max-h-[90vh] rounded-lg">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Date Picker Modal -->
<div id="datePickerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Select dates</h2>
                    <p class="text-gray-600 text-sm mt-1">Add your travel dates for exact pricing</p>
                </div>
                <button onclick="closeDatePicker()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                </button>
            </div>
            
            <!-- Date Input Fields -->
            <div class="flex gap-2 mt-4">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">CHECK-IN</label>
                    <input type="text" 
                           id="modal_check_in" 
                           placeholder="JJ/MM/AAAA"
                           readonly
                           class="w-full px-4 py-3 border-2 border-gray-900 rounded-lg text-gray-900 bg-white cursor-pointer"
                           onclick="setActiveField('check_in')">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">CHECKOUT</label>
                    <input type="text" 
                           id="modal_check_out" 
                           placeholder="Add date"
                           readonly
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-500 bg-white cursor-pointer"
                           onclick="setActiveField('check_out')">
                </div>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="p-6">
            <div id="calendarContainer" class="flex gap-8 justify-center flex-wrap">
                <!-- Calendars will be generated here -->
                </div>
        </div>
        
        <!-- Footer -->
        <div class="p-6 border-t border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                <button onclick="clearDates()" class="text-gray-700 hover:text-gray-900 font-medium text-sm">Clear dates</button>
            </div>
            <button onclick="closeDatePicker()" class="bg-gray-900 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-800 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Image Gallery Modal -->
<div id="imageGalleryModal" class="fixed inset-0 bg-black bg-opacity-95 hidden z-50 overflow-y-auto">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Toutes les photos</h2>
            <button onclick="closeImageGallery()" class="text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($allImages as $image)
                <img src="{{ $image }}" 
                     alt="{{ $car->brand }} {{ $car->model }}" 
                     class="w-full h-64 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                     onclick="openImageModal('{{ $image }}')">
            @endforeach
        </div>
    </div>
</div>

<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openImageGallery() {
    document.getElementById('imageGalleryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageGallery() {
    document.getElementById('imageGalleryModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.getElementById('imageGalleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageGallery();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closeImageGallery();
    }
});

// Date Picker Functionality
let activeField = 'check_in';
let selectedCheckIn = null;
let selectedCheckOut = null;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let availabilityCheckTimeout = null;
const carId = {{ $car->id }};

const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                   'July', 'August', 'September', 'October', 'November', 'December'];
const dayNames = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

function openDatePicker(field) {
    activeField = field;
    document.getElementById('datePickerModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Set current values
    const checkInDisplay = document.getElementById('check_in_display').value;
    const checkOutDisplay = document.getElementById('check_out_display').value;
    
    if (checkInDisplay) {
        document.getElementById('modal_check_in').value = checkInDisplay;
        selectedCheckIn = document.getElementById('check_in').value;
    }
    if (checkOutDisplay) {
        document.getElementById('modal_check_out').value = checkOutDisplay;
        selectedCheckOut = document.getElementById('check_out').value;
    }
    
    setActiveField(field);
    generateCalendars();
}

function closeDatePicker() {
    document.getElementById('datePickerModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function setActiveField(field) {
    activeField = field;
    const checkInInput = document.getElementById('modal_check_in');
    const checkOutInput = document.getElementById('modal_check_out');
    
    if (field === 'check_in') {
        checkInInput.classList.remove('border-gray-300');
        checkInInput.classList.add('border-2', 'border-gray-900');
        checkOutInput.classList.remove('border-2', 'border-gray-900');
        checkOutInput.classList.add('border', 'border-gray-300');
                } else {
        checkOutInput.classList.remove('border-gray-300');
        checkOutInput.classList.add('border-2', 'border-gray-900');
        checkInInput.classList.remove('border-2', 'border-gray-900');
        checkInInput.classList.add('border', 'border-gray-300');
    }
}

function generateCalendars() {
    const container = document.getElementById('calendarContainer');
    container.innerHTML = '';
    
    // Generate two calendars side by side
    for (let i = 0; i < 2; i++) {
        const month = (currentMonth + i) % 12;
        const year = currentYear + Math.floor((currentMonth + i) / 12);
        
        const calendarDiv = document.createElement('div');
        calendarDiv.className = 'w-full';
        calendarDiv.innerHTML = generateCalendar(month, year, i);
        container.appendChild(calendarDiv);
    }
}

function generateCalendar(month, year, index) {
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    let html = `
        <div class="mb-4 w-full">
            <div class="flex items-center justify-between mb-4">
                ${index === 0 ? `
                    <button onclick="previousMonth()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                ` : '<div class="w-9"></div>'}
                <h3 class="text-lg font-semibold text-gray-900">${monthNames[month]} ${year}</h3>
                ${index === 1 ? `
                    <button onclick="nextMonth()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                ` : '<div class="w-9"></div>'}
            </div>
            <div class="grid grid-cols-7 gap-1 mb-2">
    `;
    
    // Day headers
    dayNames.forEach(day => {
        html += `<div class="text-center text-xs font-semibold text-gray-500 py-2">${day}</div>`;
    });
    
    html += `</div><div class="grid grid-cols-7 gap-1">`;
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        html += `<div class="h-11"></div>`;
    }
    
    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const dateStr = date.toISOString().split('T')[0];
        const isPast = date < today;
        
        let classes = 'h-11 flex items-center justify-center rounded-lg text-sm font-medium transition-all ';
        let clickable = true;
        
        if (isPast) {
            classes += 'text-gray-300 cursor-not-allowed';
            clickable = false;
        } else if (selectedCheckIn && selectedCheckOut) {
            const checkIn = new Date(selectedCheckIn);
            const checkOut = new Date(selectedCheckOut);
            if (dateStr === selectedCheckIn) {
                classes += 'bg-gray-900 text-white font-bold';
            } else if (dateStr === selectedCheckOut) {
                classes += 'bg-gray-900 text-white font-bold';
            } else if (date >= checkIn && date <= checkOut) {
                classes += 'bg-gray-100 text-gray-900';
            } else {
                classes += 'text-gray-700 hover:border-2 hover:border-gray-900 cursor-pointer font-medium';
            }
        } else if (dateStr === selectedCheckIn || dateStr === selectedCheckOut) {
            classes += 'bg-gray-900 text-white font-bold';
                } else {
            classes += 'text-gray-700 hover:border-2 hover:border-gray-900 cursor-pointer font-medium';
        }
        
        html += `<div class="${classes}" ${clickable ? `onclick="selectDate('${dateStr}')"` : ''}>${day}</div>`;
    }
    
    html += `</div></div>`;
    return html;
}

function selectDate(dateStr) {
    const date = new Date(dateStr);
    const formattedDate = formatDate(dateStr);
    
    if (activeField === 'check_in') {
        selectedCheckIn = dateStr;
        document.getElementById('modal_check_in').value = formattedDate;
        document.getElementById('check_in').value = dateStr;
        document.getElementById('check_in_display').value = formattedDate;
        
        // If check-out is before check-in, clear it
        if (selectedCheckOut && new Date(selectedCheckOut) <= date) {
            selectedCheckOut = null;
            document.getElementById('modal_check_out').value = '';
            document.getElementById('check_out').value = '';
            document.getElementById('check_out_display').value = '';
            resetBookingButton();
        }
        
        // Switch to check-out field
        setActiveField('check_out');
    } else {
        if (selectedCheckIn && new Date(dateStr) > new Date(selectedCheckIn)) {
            selectedCheckOut = dateStr;
            document.getElementById('modal_check_out').value = formattedDate;
            document.getElementById('check_out').value = dateStr;
            document.getElementById('check_out_display').value = formattedDate;
            
            // Check availability after 1-2 seconds
            checkAvailabilityDelayed();
        } else {
            // If date is before check-in, set as new check-in
            selectedCheckIn = dateStr;
            selectedCheckOut = null;
            document.getElementById('modal_check_in').value = formattedDate;
            document.getElementById('check_in').value = dateStr;
            document.getElementById('check_in_display').value = formattedDate;
            document.getElementById('modal_check_out').value = '';
            document.getElementById('check_out').value = '';
            document.getElementById('check_out_display').value = '';
            setActiveField('check_out');
            resetBookingButton();
        }
    }
    
    generateCalendars();
}

function checkAvailabilityDelayed() {
    // Clear previous timeout
    if (availabilityCheckTimeout) {
        clearTimeout(availabilityCheckTimeout);
    }
    
    // Reset button to initial state
    resetBookingButton();
    
    // Wait 1.5 seconds before checking
    availabilityCheckTimeout = setTimeout(() => {
        checkAvailability();
    }, 1500);
}

function checkAvailability() {
    if (!selectedCheckIn || !selectedCheckOut) {
                return;
            }
            
    const button = document.getElementById('bookingButton');
    const buttonText = document.getElementById('buttonText');
    const buttonLoading = document.getElementById('buttonLoading');
    
    // Show loading state
    button.disabled = true;
    buttonText.classList.add('hidden');
    buttonLoading.classList.remove('hidden');
    
    // Make API call
    fetch(`/client/cars/${carId}/check-availability`, {
                method: 'POST',
                headers: {
            'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            start_date: selectedCheckIn,
            end_date: selectedCheckOut
        })
            })
            .then(response => response.json())
            .then(data => {
        buttonLoading.classList.add('hidden');
        buttonText.classList.remove('hidden');
        
        if (data.available) {
            // Change button to "Réserver"
            buttonText.textContent = 'Réserver';
            button.disabled = false;
        } else {
            // Keep button as "Vérifier la disponibilité" but show error
            buttonText.textContent = 'Vérifier la disponibilité';
            button.disabled = true;
            button.classList.add('bg-red-600', 'hover:bg-red-700');
            button.classList.remove('bg-orange-600', 'hover:bg-orange-700');
            
            // Optionally show a message
                    setTimeout(() => {
                alert('Ce véhicule n\'est pas disponible pour les dates sélectionnées.');
            }, 100);
                }
            })
            .catch(error => {
        console.error('Error checking availability:', error);
        buttonLoading.classList.add('hidden');
        buttonText.classList.remove('hidden');
        buttonText.textContent = 'Vérifier la disponibilité';
        button.disabled = false;
        resetBookingButton();
    });
}

function resetBookingButton() {
    const button = document.getElementById('bookingButton');
    const buttonText = document.getElementById('buttonText');
    const buttonLoading = document.getElementById('buttonLoading');
    
    buttonText.textContent = 'Vérifier la disponibilité';
    button.disabled = false;
    button.classList.remove('bg-red-600', 'hover:bg-red-700');
    button.classList.add('bg-orange-600', 'hover:bg-orange-700');
    buttonLoading.classList.add('hidden');
    buttonText.classList.remove('hidden');
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function previousMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    generateCalendars();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    generateCalendars();
}

function clearDates() {
    selectedCheckIn = null;
    selectedCheckOut = null;
    document.getElementById('modal_check_in').value = '';
    document.getElementById('modal_check_out').value = '';
    document.getElementById('check_in').value = '';
    document.getElementById('check_out').value = '';
    document.getElementById('check_in_display').value = '';
    document.getElementById('check_out_display').value = '';
    resetBookingButton();
    generateCalendars();
}

// Close modal when clicking outside
document.getElementById('datePickerModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDatePicker();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDatePicker();
    }
});

// Initialize current month/year
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    currentMonth = now.getMonth();
    currentYear = now.getFullYear();
    
    // Check if we should scroll to booking form (from "Louer" or "Louer maintenant" buttons)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('book') === '1') {
        // Scroll to booking form after a short delay to ensure page is fully loaded
        setTimeout(function() {
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                bookingForm.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
                // Add a subtle highlight effect
                bookingForm.style.transition = 'box-shadow 0.3s ease';
                bookingForm.style.boxShadow = '0 0 0 3px rgba(249, 115, 22, 0.3)';
                setTimeout(function() {
                    bookingForm.style.boxShadow = '';
                }, 2000);
            }
        }, 300);
    }
});
</script>
@endsection 
