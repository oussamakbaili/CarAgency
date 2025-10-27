@extends('layouts.public')

@section('title', 'ToubCar - Premium Car Rental Platform')

@section('content')
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg" role="alert" id="success-message">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg" role="alert" id="error-message">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Hero Section - Parallax Effect -->
    <div class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900" style="min-height: 100vh;">
        <!-- Background Image Layer -->
        <div id="hero-bg" class="absolute inset-0 w-full h-full">
            <img src="{{ asset('images/black-sedan-car-driving-bridge-road.png') }}" 
                 alt="Background" 
                 class="w-full h-full object-cover opacity-30">
        </div>
        
        <!-- Car Image Layer (Parallax) -->
        <div id="hero-car" class="absolute inset-0 w-full h-full">
            <img src="{{ asset('images/black-sedan-car-driving-bridge-road-no-bg.png') }}" 
                 alt="Premium Car" 
                 class="w-full h-full object-cover">
        </div>
        
        <!-- Content Overlay -->
        <div class="relative z-10 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-20">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8" id="hero-content">
                        <div>
                            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6 hero-title">
                                Find Your Perfect
                                <span class="block text-orange-500">
                                    Car Rental
                                </span>
                            </h1>
                            
                            <p class="text-xl text-gray-300 leading-relaxed hero-subtitle">
                                Découvrez notre large gamme de véhicules disponibles partout au Maroc. Simple, rapide et sécurisé.
                            </p>
                        </div>
                    
                        <!-- Search Form - Airbnb Style -->
                        <div class="bg-white/95 backdrop-blur-sm rounded-full shadow-2xl border border-gray-200 p-2 hero-search">
                            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2">
                                <!-- Where -->
                                <div class="flex-1 px-6 py-3 border-r border-gray-200 cursor-pointer hover:bg-gray-50 rounded-l-full transition-colors" onclick="openSearchModal('where')">
                                    <label class="block text-xs font-semibold text-gray-900 mb-1">Where</label>
                                    <div class="text-sm text-gray-600" id="whereDisplay">Search destinations</div>
                                </div>
                                
                                <!-- Check in -->
                                <div class="flex-1 px-6 py-3 border-r border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors" onclick="openSearchModal('checkin')">
                                    <label class="block text-xs font-semibold text-gray-900 mb-1">Check in</label>
                                    <div class="text-sm text-gray-400" id="checkInDisplay">Add dates</div>
                                </div>
                                
                                <!-- Check out -->
                                <div class="flex-1 px-6 py-3 cursor-pointer hover:bg-gray-50 transition-colors" onclick="openSearchModal('checkout')">
                                    <label class="block text-xs font-semibold text-gray-900 mb-1">Check out</label>
                                    <div class="text-sm text-gray-400" id="checkOutDisplay">Add dates</div>
                                </div>
                                
                                <!-- Search Button -->
                                <button type="button" onclick="performSearch()" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-full font-semibold flex items-center justify-center gap-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span class="hidden md:inline">Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Empty space for car -->
                    <div class="hidden lg:block"></div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-20" id="scroll-indicator">
            <div class="flex flex-col items-center gap-2 text-white animate-bounce">
                <span class="text-sm font-medium">Scroll to explore</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Top Picks for this month -->
    <div id="top-picks" class="py-16 bg-white reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Véhicules Populaires</h2>
                <p class="text-lg text-gray-600">Nos voitures les plus demandées ce mois-ci</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($topCars as $car)
                    <div onclick="window.location='{{ route('public.car.show', [$car->agency, $car]) }}'" class="group bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-48 bg-gray-100">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Featured Badge -->
                            @if($car->featured || $car->agency->featured)
                                <div class="absolute top-3 left-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-orange-600 text-white shadow-md">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-xs font-bold">{{ $car->featured ? 'FEATURED' : 'TOP PARTNER' }}</span>
                                </div>
                            @endif
                            
                            <!-- Rating Badge -->
                            @if($car->average_rating > 0)
                                <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-white shadow-sm">
                                    <x-star-rating :rating="$car->average_rating" size="w-4 h-4" />
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($car->average_rating, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Car Details -->
                        <div class="p-5">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $car->brand }}</h3>
                                <p class="text-sm text-gray-600">{{ $car->model }}</p>
                            </div>
                            
                            <!-- Features -->
                            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    <span>{{ ucfirst($car->transmission ?? 'Auto') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    <span>{{ $car->seats ?? 5 }}</span>
                            </div>
                            </div>
                            
                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($car->price_per_day, 0) }} MAD</div>
                                    <div class="text-sm text-gray-500">par jour</div>
                                </div>
                                <a href="{{ route('booking.main', $car) }}" 
                                   class="bg-[#C2410C] hover:bg-[#9A3412] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                                   onclick="event.stopPropagation()">
                                    Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Aucune voiture disponible pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Join Us Banner -->
    <div class="bg-orange-600 py-16 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Prêt à Commencer?
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Louez une voiture ou devenez partenaire et gérez votre flotte facilement
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-orange-600 px-8 py-3 rounded-lg font-semibold transition-colors">
                    Commencer
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#discover" 
                   class="inline-flex items-center gap-2 bg-transparent border-2 border-white hover:bg-white hover:text-orange-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Voir les Voitures
                </a>
            </div>
        </div>
    </div>

    <!-- Discover our wide range of cars -->
    <div id="discover" class="py-16 bg-gray-50 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">Parcourir Par Catégorie</h2>
                
                <!-- Category Filters -->
                <div class="flex flex-wrap gap-3 mb-8">
                    <a href="{{ route('public.home') }}" 
                       class="px-5 py-2 rounded-lg font-semibold text-sm transition-colors {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                        Tous
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('public.home', ['category' => $category->id]) }}" 
                           class="px-5 py-2 rounded-lg font-semibold text-sm transition-colors {{ request('category') == $category->id ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    <button type="button" 
                            onclick="document.getElementById('filterModal').classList.remove('hidden')"
                            class="px-5 py-2 rounded-lg font-semibold text-sm bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Plus de Filtres
                    </button>
                </div>
            </div>

            <!-- Cars Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($discoverCars as $car)
                    <div onclick="window.location='{{ route('public.car.show', [$car->agency, $car]) }}'" class="group bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-48 bg-gray-100">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Featured Badge -->
                            @if($car->featured || $car->agency->featured)
                                <div class="absolute top-3 left-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-orange-600 text-white shadow-md">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-xs font-bold">{{ $car->featured ? 'FEATURED' : 'TOP PARTNER' }}</span>
                                </div>
                            @endif
                            
                            <!-- Rating Badge -->
                            @if($car->average_rating > 0)
                                <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-white shadow-sm">
                                    <x-star-rating :rating="$car->average_rating" size="w-4 h-4" />
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($car->average_rating, 1) }}</span>
                                </div>
                            @endif
                    </div>
                        
                        <!-- Car Details -->
                        <div class="p-5">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $car->brand }}</h3>
                                <p class="text-sm text-gray-600">{{ $car->model }}</p>
                </div>

                            <!-- Features -->
                            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <span>{{ ucfirst($car->transmission ?? 'Auto') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                                    <span>{{ $car->seats ?? 5 }}</span>
                                </div>
                            </div>
                            
                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($car->price_per_day, 0) }} MAD</div>
                                    <div class="text-sm text-gray-500">par jour</div>
                                </div>
                                <a href="{{ route('booking.main', $car) }}" 
                                   class="bg-[#C2410C] hover:bg-[#9A3412] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                                   onclick="event.stopPropagation()">
                                    Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Aucune voiture disponible dans cette catégorie</p>
                    </div>
                @endforelse
                </div>

            <!-- View More Button -->
            @if($discoverCars->count() >= 12)
                <div class="text-center mt-12">
                    <a href="{{ route('public.agencies') }}" 
                       class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        Voir Toutes les Voitures
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Filter Cars</h3>
                <button onclick="document.getElementById('filterModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('public.home') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input type="text" name="where" value="{{ request('where') }}" 
                               placeholder="City or location..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white py-3 px-6 rounded-xl font-semibold transition duration-200">
                        Apply Filters
                    </button>
                    <a href="{{ route('public.home') }}" 
                       class="px-6 py-3 rounded-xl font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition duration-200">
                        Reset
                    </a>
            </div>
            </form>
        </div>
    </div>

    <!-- Search Modal - Airbnb Style -->
    <div id="searchModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
            <form action="{{ route('public.cars.search') }}" method="GET" id="searchForm" class="flex flex-col h-full">
                <!-- Modal Tabs Header -->
                <div class="bg-white border-b border-gray-200 flex-shrink-0">
                    <div class="flex items-center px-6 py-3">
                        <button type="button" onclick="closeSearchModal()" class="text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="flex-1 flex rounded-full bg-gray-100 p-1">
                            <!-- Where Tab -->
                            <button type="button" id="whereTab" onclick="switchToSection('where')" class="flex-1 px-6 py-3 rounded-full font-medium text-sm transition-all bg-white shadow-sm">
                                <div class="text-left">
                                    <div class="text-xs font-semibold text-gray-900">Where</div>
                                    <div class="text-sm text-gray-500" id="whereTabDisplay">Search destinations</div>
                                </div>
                            </button>
                            <!-- Check in Tab -->
                            <button type="button" id="checkInTab" onclick="switchToSection('checkin')" class="flex-1 px-6 py-3 rounded-full font-medium text-sm transition-all">
                                <div class="text-left">
                                    <div class="text-xs font-semibold text-gray-500">Check in</div>
                                    <div class="text-sm text-gray-400" id="checkInTabDisplay">Add dates</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Where Section -->
                    <div id="whereSection" class="p-6">
                        <input type="text" name="where" id="whereInput" placeholder="Search destinations" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-base mb-6">
                        
                        <!-- Recent searches (Optional) -->
                        <div class="mb-6">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Recent searches</p>
                            <button type="button" onclick="selectDestination('Marrakesh')" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-gray-200">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-medium text-gray-900">Marrakesh</p>
                                    <p class="text-sm text-gray-500">Oct 16 – Nov 2</p>
                                </div>
                            </button>
                        </div>

                        <!-- Suggested Destinations -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Suggested destinations</p>
                            <div class="space-y-2">
                                <button type="button" onclick="selectDestination('Nearby')" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900">Nearby</p>
                                        <p class="text-sm text-gray-500">Find what's around you</p>
                                    </div>
                                </button>

                                <button type="button" onclick="selectDestination('Tangier, Morocco')" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900">Tangier, Morocco</p>
                                        <p class="text-sm text-gray-500">Guests interested in Marrakesh also looked here</p>
                                    </div>
                                </button>

                                <button type="button" onclick="selectDestination('Rabat, Morocco')" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900">Rabat, Morocco</p>
                                        <p class="text-sm text-gray-500">Near you</p>
                                    </div>
                                </button>

                                <button type="button" onclick="selectDestination('Agadir, Morocco')" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900">Agadir, Morocco</p>
                                        <p class="text-sm text-gray-500">Guests interested in Marrakesh also looked here</p>
                                    </div>
                                </button>

                                <button type="button" onclick="selectDestination('Istanbul, Türkiye')" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900">Istanbul, Türkiye</p>
                                        <p class="text-sm text-gray-500">Especially like Galata Tower</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- When Section (Calendar) -->
                    <div id="whenSection" class="hidden p-6">
                        <!-- Calendar Tabs -->
                        <div class="mb-8 flex justify-center">
                            <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full p-1">
                                <button type="button" class="calendar-tab px-8 py-3 font-semibold rounded-full bg-white text-gray-900 shadow-sm" data-tab="dates" onclick="switchCalendarTab('dates')">
                                    Dates
                                </button>
                                <button type="button" class="calendar-tab px-8 py-3 font-semibold rounded-full text-gray-600 hover:text-gray-900" data-tab="months" onclick="switchCalendarTab('months')">
                                    Months
                                </button>
                                <button type="button" class="calendar-tab px-8 py-3 font-semibold rounded-full text-gray-600 hover:text-gray-900" data-tab="flexible" onclick="switchCalendarTab('flexible')">
                                    Flexible
                                </button>
                            </div>
                        </div>

                        <!-- Dates Tab Content -->
                        <div id="datesContent" class="tab-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Month 1 -->
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900 mb-3" id="month1Title">October 2025</h4>
                                    <div class="calendar-grid">
                                        <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs font-medium text-gray-500">
                                            <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
                                        </div>
                                        <div class="grid grid-cols-7 gap-1" id="month1Days"></div>
                                    </div>
                                </div>

                                <!-- Month 2 -->
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900 mb-3" id="month2Title">November 2025</h4>
                                    <div class="calendar-grid">
                                        <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs font-medium text-gray-500">
                                            <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
                                        </div>
                                        <div class="grid grid-cols-7 gap-1" id="month2Days"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="prevMonth" class="p-2 rounded-full hover:bg-gray-100 transition-colors border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button type="button" id="nextMonth" class="p-2 rounded-full hover:bg-gray-100 transition-colors border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Months Tab Content -->
                        <div id="monthsContent" class="tab-content hidden">
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Select a month</h3>
                                <p class="text-gray-600">Choose when you want to rent a car</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
                                <button type="button" onclick="selectMonth('October 2025')" class="flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">October</p>
                                    <p class="text-sm text-gray-500">2025</p>
                                </button>
                                <button type="button" onclick="selectMonth('November 2025')" class="flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">November</p>
                                    <p class="text-sm text-gray-500">2025</p>
                                </button>
                                <button type="button" onclick="selectMonth('December 2025')" class="flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">December</p>
                                    <p class="text-sm text-gray-500">2025</p>
                                </button>
                                <button type="button" onclick="selectMonth('January 2026')" class="flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">January</p>
                                    <p class="text-sm text-gray-500">2026</p>
                                </button>
                                <button type="button" onclick="selectMonth('February 2026')" class="flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">February</p>
                                    <p class="text-sm text-gray-500">2026</p>
                                </button>
                                <button type="button" onclick="selectMonth('March 2026')" class="flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all">
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">March</p>
                                    <p class="text-sm text-gray-500">2026</p>
                                </button>
                            </div>
                        </div>

                        <!-- Flexible Tab Content -->
                        <div id="flexibleContent" class="tab-content hidden">
                            <!-- Duration Selection -->
                            <div class="text-center mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">How long would you like to stay?</h3>
                                <div class="flex justify-center gap-3">
                                    <button type="button" onclick="selectDuration('weekend')" class="px-8 py-3 border-2 border-gray-300 rounded-full font-semibold text-gray-900 hover:border-gray-900 transition-all">
                                        Weekend
                                    </button>
                                    <button type="button" onclick="selectDuration('week')" class="px-8 py-3 border-2 border-gray-300 rounded-full font-semibold text-gray-900 hover:border-gray-900 transition-all">
                                        Week
                                    </button>
                                    <button type="button" onclick="selectDuration('month')" class="px-8 py-3 border-2 border-gray-300 rounded-full font-semibold text-gray-900 hover:border-gray-900 transition-all">
                                        Month
                                    </button>
                                </div>
                            </div>

                            <!-- Go Anytime -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Go anytime</h3>
                                <div class="flex gap-3 overflow-x-auto pb-4">
                                    <button type="button" onclick="selectFlexibleMonth('October 2025')" class="flex-shrink-0 w-32 p-4 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="font-semibold text-gray-900 text-sm">October</p>
                                        <p class="text-xs text-gray-500">2025</p>
                                    </button>
                                    <button type="button" onclick="selectFlexibleMonth('November 2025')" class="flex-shrink-0 w-32 p-4 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="font-semibold text-gray-900 text-sm">November</p>
                                        <p class="text-xs text-gray-500">2025</p>
                                    </button>
                                    <button type="button" onclick="selectFlexibleMonth('December 2025')" class="flex-shrink-0 w-32 p-4 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="font-semibold text-gray-900 text-sm">December</p>
                                        <p class="text-xs text-gray-500">2025</p>
                                    </button>
                                    <button type="button" onclick="selectFlexibleMonth('January 2026')" class="flex-shrink-0 w-32 p-4 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="font-semibold text-gray-900 text-sm">January</p>
                                        <p class="text-xs text-gray-500">2026</p>
                                    </button>
                                    <button type="button" onclick="selectFlexibleMonth('February 2026')" class="flex-shrink-0 w-32 p-4 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="font-semibold text-gray-900 text-sm">February</p>
                                        <p class="text-xs text-gray-500">2026</p>
                                    </button>
                                    <button type="button" onclick="selectFlexibleMonth('March 2026')" class="flex-shrink-0 w-32 p-4 border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-md transition-all text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="font-semibold text-gray-900 text-sm">March</p>
                                        <p class="text-xs text-gray-500">2026</p>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for dates -->
                <input type="hidden" name="check_in" id="checkInInput">
                <input type="hidden" name="check_out" id="checkOutInput">

                <!-- Modal Footer -->
                <div class="bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
                    <button type="button" onclick="clearDates()" class="text-sm font-medium text-gray-900 hover:underline underline-offset-2">
                        Clear all
                    </button>
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentMonth = new Date();
        let checkInDate = null;
        let checkOutDate = null;

        // Open modal with specific section
        function openSearchModal(section) {
            document.getElementById('searchModal').classList.remove('hidden');
            switchToSection(section);
        }

        // Switch between sections (where/checkin)
        function switchToSection(section) {
            const whereTab = document.getElementById('whereTab');
            const checkInTab = document.getElementById('checkInTab');
            const whereSection = document.getElementById('whereSection');
            const whenSection = document.getElementById('whenSection');
            
            if (section === 'where') {
                // Show where section
                whereSection.classList.remove('hidden');
                whenSection.classList.add('hidden');
                
                // Update tab styles
                whereTab.classList.add('bg-white', 'shadow-sm');
                whereTab.querySelector('.text-xs').classList.remove('text-gray-500');
                whereTab.querySelector('.text-xs').classList.add('text-gray-900');
                
                checkInTab.classList.remove('bg-white', 'shadow-sm');
                checkInTab.querySelector('.text-xs').classList.remove('text-gray-900');
                checkInTab.querySelector('.text-xs').classList.add('text-gray-500');
                
                setTimeout(() => document.getElementById('whereInput').focus(), 100);
            } else if (section === 'checkin' || section === 'checkout') {
                // Show when section
                whereSection.classList.add('hidden');
                whenSection.classList.remove('hidden');
                
                // Update tab styles
                checkInTab.classList.add('bg-white', 'shadow-sm');
                checkInTab.querySelector('.text-xs').classList.remove('text-gray-500');
                checkInTab.querySelector('.text-xs').classList.add('text-gray-900');
                
                whereTab.classList.remove('bg-white', 'shadow-sm');
                whereTab.querySelector('.text-xs').classList.remove('text-gray-900');
                whereTab.querySelector('.text-xs').classList.add('text-gray-500');
                
                generateCalendar();
            }
        }

        // Close modal
        function closeSearchModal() {
            document.getElementById('searchModal').classList.add('hidden');
        }

        // Select destination
        function selectDestination(city) {
            document.getElementById('whereInput').value = city;
            document.getElementById('whereDisplay').textContent = city;
            document.getElementById('whereTabDisplay').textContent = city;
            document.getElementById('whereDisplay').classList.remove('text-gray-600');
            document.getElementById('whereDisplay').classList.add('text-gray-900');
            // Switch to dates section
            switchToSection('checkin');
        }

        // Perform search
        function performSearch() {
            const form = document.getElementById('searchForm');
            form.submit();
        }

        function generateCalendar() {
            const month1 = new Date(currentMonth);
            const month2 = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);

            document.getElementById('month1Title').textContent = month1.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            document.getElementById('month2Title').textContent = month2.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            renderMonth('month1Days', month1);
            renderMonth('month2Days', month2);
        }

        function renderMonth(containerId, date) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            const year = date.getFullYear();
            const month = date.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Empty cells for days before month starts
            for (let i = 0; i < firstDay; i++) {
                container.innerHTML += '<div></div>';
            }

            // Days of month
            for (let day = 1; day <= daysInMonth; day++) {
                const currentDate = new Date(year, month, day);
                const dateStr = currentDate.toISOString().split('T')[0];
                const isPast = currentDate < today;
                
                let classes = 'h-11 flex items-center justify-center rounded-lg cursor-pointer transition-all text-sm ';
                
                if (isPast) {
                    classes += 'text-gray-300 cursor-not-allowed';
                } else if (checkInDate && checkOutDate && currentDate >= new Date(checkInDate) && currentDate <= new Date(checkOutDate)) {
                    classes += 'bg-orange-100 text-gray-900 font-medium';
                } else if (dateStr === checkInDate || dateStr === checkOutDate) {
                    classes += 'bg-gray-900 text-white font-bold';
                } else {
                    classes += 'hover:border-2 hover:border-gray-900 text-gray-700 font-medium';
                }

                container.innerHTML += `<div class="${classes}" onclick="selectDate('${dateStr}', ${isPast})">${day}</div>`;
            }
        }

        function selectDate(dateStr, isPast) {
            if (isPast) return;

            if (!checkInDate || (checkInDate && checkOutDate)) {
                // Start new selection
                checkInDate = dateStr;
                checkOutDate = null;
            } else if (new Date(dateStr) > new Date(checkInDate)) {
                // Set check out
                checkOutDate = dateStr;
            } else {
                // Reset if selecting earlier date
                checkInDate = dateStr;
                checkOutDate = null;
            }

            updateDisplay();
            generateCalendar();
        }

        function updateDisplay() {
            const checkInEl = document.getElementById('checkInDisplay');
            const checkOutEl = document.getElementById('checkOutDisplay');
            const checkInTabDisplay = document.getElementById('checkInTabDisplay');
            const checkInInput = document.getElementById('checkInInput');
            const checkOutInput = document.getElementById('checkOutInput');

            if (checkInDate) {
                const date = new Date(checkInDate);
                const formatted = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                checkInEl.textContent = formatted;
                checkInTabDisplay.textContent = formatted;
                checkInEl.classList.remove('text-gray-400');
                checkInEl.classList.add('text-gray-900');
                checkInTabDisplay.classList.remove('text-gray-400');
                checkInTabDisplay.classList.add('text-gray-900');
                checkInInput.value = checkInDate;
            }

            if (checkOutDate) {
                const date = new Date(checkOutDate);
                const formatted = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                checkOutEl.textContent = formatted;
                checkOutEl.classList.remove('text-gray-400');
                checkOutEl.classList.add('text-gray-900');
                checkOutInput.value = checkOutDate;
                
                // Update check in tab to show check in → check out
                if (checkInDate) {
                    const checkInDateObj = new Date(checkInDate);
                    checkInTabDisplay.textContent = checkInDateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + ' – ' + formatted;
                }
            }
        }

        function clearDates() {
            checkInDate = null;
            checkOutDate = null;
            document.getElementById('checkInDisplay').textContent = 'Add dates';
            document.getElementById('checkInDisplay').classList.remove('text-gray-900');
            document.getElementById('checkInDisplay').classList.add('text-gray-400');
            document.getElementById('checkInTabDisplay').textContent = 'Add dates';
            document.getElementById('checkInTabDisplay').classList.remove('text-gray-900');
            document.getElementById('checkInTabDisplay').classList.add('text-gray-400');
            document.getElementById('checkOutDisplay').textContent = 'Add dates';
            document.getElementById('checkOutDisplay').classList.remove('text-gray-900');
            document.getElementById('checkOutDisplay').classList.add('text-gray-400');
            document.getElementById('whereInput').value = '';
            document.getElementById('whereDisplay').textContent = 'Search destinations';
            document.getElementById('whereTabDisplay').textContent = 'Search destinations';
            document.getElementById('whereDisplay').classList.remove('text-gray-900');
            document.getElementById('whereDisplay').classList.add('text-gray-600');
            document.getElementById('checkInInput').value = '';
            document.getElementById('checkOutInput').value = '';
            generateCalendar();
        }

        document.getElementById('prevMonth').addEventListener('click', function() {
            currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
            generateCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', function() {
            currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
            generateCalendar();
        });

        // Update where input
        document.getElementById('whereInput').addEventListener('input', function(e) {
            const value = e.target.value;
            const displayText = value || 'Search destinations';
            document.getElementById('whereDisplay').textContent = displayText;
            document.getElementById('whereTabDisplay').textContent = displayText;
            if (value) {
                document.getElementById('whereDisplay').classList.remove('text-gray-600');
                document.getElementById('whereDisplay').classList.add('text-gray-900');
            } else {
                document.getElementById('whereDisplay').classList.remove('text-gray-900');
                document.getElementById('whereDisplay').classList.add('text-gray-600');
            }
        });

        // Switch calendar tabs (Dates/Months/Flexible)
        function switchCalendarTab(tab) {
            // Hide all tab contents
            document.getElementById('datesContent').classList.add('hidden');
            document.getElementById('monthsContent').classList.add('hidden');
            document.getElementById('flexibleContent').classList.add('hidden');
            
            // Show selected tab content
            if (tab === 'dates') {
                document.getElementById('datesContent').classList.remove('hidden');
                generateCalendar();
            } else if (tab === 'months') {
                document.getElementById('monthsContent').classList.remove('hidden');
            } else if (tab === 'flexible') {
                document.getElementById('flexibleContent').classList.remove('hidden');
            }
            
            // Update tab styles
            document.querySelectorAll('.calendar-tab').forEach(t => {
                t.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                t.classList.add('text-gray-600');
            });
            const activeTab = document.querySelector(`.calendar-tab[data-tab="${tab}"]`);
            if (activeTab) {
                activeTab.classList.remove('text-gray-600');
                activeTab.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            }
        }

        // Select month (for Months tab)
        function selectMonth(month) {
            // For now, just close the modal - you can add more logic here
            console.log('Selected month:', month);
            closeSearchModal();
        }

        // Select duration (for Flexible tab)
        function selectDuration(duration) {
            console.log('Selected duration:', duration);
            // Add visual feedback
            event.target.classList.toggle('border-gray-900');
            event.target.classList.toggle('bg-gray-50');
        }

        // Select flexible month (for Flexible tab)
        function selectFlexibleMonth(month) {
            console.log('Selected flexible month:', month);
            // Add visual feedback
            event.target.classList.toggle('border-orange-500');
            event.target.classList.toggle('bg-orange-50');
        }

        // Close modal on outside click
        document.getElementById('searchModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSearchModal();
            }
        });

        // Initialize calendar
        generateCalendar();
    </script>

    <style>
        /* Hero section styles */
        .reveal-section {
            opacity: 0;
            transform: translateY(50px);
        }
        
        /* Hero content initial state - hidden for animation */
        .hero-title,
        .hero-subtitle,
        .hero-search {
            opacity: 0;
            transform: translateY(30px);
        }
    </style>

    <script>
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Register GSAP plugins
            gsap.registerPlugin(ScrollTrigger);

            // Hero Parallax Animation
            gsap.to("#hero-bg", {
                scrollTrigger: {
                    trigger: ".relative.overflow-hidden",
                    start: "top top",
                    end: "bottom top",
                    scrub: 1
                },
                y: 300,
                scale: 1.2,
                ease: "none"
            });

            gsap.to("#hero-car", {
                scrollTrigger: {
                    trigger: ".relative.overflow-hidden",
                    start: "top top",
                    end: "bottom top",
                    scrub: 1.5
                },
                y: 200,
                scale: 0.8,
                opacity: 0.3,
                ease: "none"
            });

            // Hero Content Animation (On Load) - Animate FROM hidden TO visible
            gsap.timeline({delay: 0.3})
                .to(".hero-title", {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "power3.out"
                })
                .to(".hero-subtitle", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power3.out"
                }, "-=0.5")
                .to(".hero-search", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    ease: "power3.out"
                }, "-=0.5");

            // Scroll Indicator fade out
            gsap.to("#scroll-indicator", {
                scrollTrigger: {
                    trigger: ".relative.overflow-hidden",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                },
                opacity: 0,
                y: -20
            });

            // Reveal sections on scroll
            gsap.utils.toArray('.reveal-section').forEach((section) => {
                gsap.to(section, {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: section,
                        start: "top 80%",
                        end: "top 50%",
                        toggleActions: "play none none reverse"
                    }
                });
            });

            // Animate car cards on scroll
            gsap.utils.toArray('.group').forEach((card, index) => {
                gsap.from(card, {
                    opacity: 0,
                    y: 50,
                    duration: 0.6,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: card,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    delay: (index % 4) * 0.1
                });
            });

            // Category filters animation
            gsap.utils.toArray('.px-5.py-2.rounded-lg').forEach((filter, index) => {
                gsap.from(filter, {
                    opacity: 0,
                    scale: 0.8,
                    duration: 0.4,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: filter,
                        start: "top 90%",
                        toggleActions: "play none none reverse"
                    },
                    delay: index * 0.05
                });
            });
        });

        // Auto-hide success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 500);
                }, 5000); // Hide after 5 seconds
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = 'opacity 0.5s ease-out';
                    errorMessage.style.opacity = '0';
                    setTimeout(() => {
                        errorMessage.remove();
                    }, 500);
                }, 7000); // Hide after 7 seconds
            }
        });
    </script>
@endsection
