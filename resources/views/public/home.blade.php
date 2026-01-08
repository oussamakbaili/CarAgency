@extends('layouts.public')

@section('title', 'ToubCar - Premium Car Rental Platform')

@section('content')
    <div class="home-mobile">
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
        <div class="relative z-10 min-h-screen flex items-start md:items-center hero-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-16 pb-12 sm:pt-20 sm:pb-20 md:py-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-6 sm:space-y-8 w-full" id="hero-content">
                        <div class="w-full">
                            <h1 class="text-left mb-6 sm:mb-8">
                                <span class="block text-5xl sm:text-5xl md:text-6xl font-bold text-white mb-2 leading-tight">{{ __('home.hero.title_line1') }}</span>
                                <span class="block text-5xl sm:text-5xl md:text-6xl font-bold text-white mb-3 leading-tight">{{ __('home.hero.title_line2') }}</span>
                                <span class="block text-7xl sm:text-6xl md:text-8xl font-bold text-orange-500 leading-tight">
                                    {{ __('home.hero.title_line3') }}
                                </span>
                            </h1>
                            
                            <div class="space-y-1 text-left max-w-lg">
                                <p class="text-base sm:text-lg text-gray-100 leading-relaxed font-normal">
                                    {{ __('home.hero.subtitle_line1') }}
                                </p>
                                <p class="text-base sm:text-lg text-gray-100 leading-relaxed font-normal">
                                    {{ __('home.hero.subtitle_line2') }}
                                </p>
                                <p class="text-sm sm:text-base text-gray-300 leading-relaxed font-normal">
                                    {{ __('home.hero.subtitle_line3') }}
                                </p>
                            </div>
                        </div>
                    
                        <!-- Simple Search Bar - Mobile Only (Airbnb Style) -->
                        <div class="md:hidden bg-white rounded-full shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-200 hero-search cursor-pointer" id="mobileSearchBar" onclick="openSearchModal('where')">
                            <div class="w-full flex items-center gap-3 px-5 py-4">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate" id="mobileSearchBarText">
                                        @if(request('where') || request('check_in') || request('check_out'))
                                            @if(request('where'))
                                                {{ request('where') }}
                                                @if(request('check_in') || request('check_out'))
                                                    · 
                                                @endif
                                            @endif
                                            @if(request('check_in') && request('check_out'))
                                                {{ \Carbon\Carbon::parse(request('check_in'))->format('M d') }} - {{ \Carbon\Carbon::parse(request('check_out'))->format('M d') }}
                                            @elseif(request('check_in'))
                                                {{ \Carbon\Carbon::parse(request('check_in'))->format('M d') }}
                                            @endif
                                        @else
                                            <span class="text-gray-500">{{ __('home.hero.search_placeholder') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Full Search Form - Desktop Only (Airbnb Style) -->
                        <div class="hidden md:block bg-white/95 backdrop-blur-sm rounded-full shadow-2xl border border-gray-200 p-2 hero-search">
                            <div class="flex flex-row items-center gap-2">
                                <!-- Where -->
                                <div class="flex-1 px-6 py-3 border-r border-gray-200 cursor-pointer hover:bg-gray-50 rounded-l-full transition-colors" onclick="openSearchModal('where')" style="pointer-events: auto; position: relative; z-index: 1;">
                                    <label class="block text-xs font-semibold text-gray-900 mb-1 pointer-events-none">{{ __('home.hero.where') }}</label>
                                    <div class="text-sm text-gray-600 pointer-events-none" id="whereDisplayDesktop">{{ __('home.hero.where_placeholder') }}</div>
                                </div>
                                
                                <!-- Check in -->
                                <div class="flex-1 px-6 py-3 border-r border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors" onclick="openSearchModal('checkin')">
                                    <label class="block text-xs font-semibold text-gray-900 mb-1">{{ __('home.hero.check_in') }}</label>
                                    <div class="text-sm text-gray-400" id="checkInDisplayDesktop">{{ __('home.hero.add_dates') }}</div>
                                </div>
                                
                                <!-- Check out -->
                                <div class="flex-1 px-6 py-3 cursor-pointer hover:bg-gray-50 transition-colors" onclick="openSearchModal('checkout')">
                                    <label class="block text-xs font-semibold text-gray-900 mb-1">{{ __('home.hero.check_out') }}</label>
                                    <div class="text-sm text-gray-400" id="checkOutDisplayDesktop">{{ __('home.hero.add_dates') }}</div>
                                </div>
                                
                                <!-- Search Button -->
                                <button type="button" onclick="performSearch()" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-full font-semibold flex items-center justify-center gap-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span>{{ __('home.hero.search') }}</span>
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
        <div class="absolute bottom-6 sm:bottom-10 left-1/2 transform -translate-x-1/2 z-20" id="scroll-indicator">
            <div class="flex flex-col items-center gap-1 sm:gap-2 text-white animate-bounce">
                <span class="text-xs sm:text-sm font-medium">{{ __('home.hero.scroll_to_explore') }}</span>
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Top Picks for this month - Airbnb Style Horizontal Scroll -->
    <div id="top-picks" class="py-10 sm:py-16 bg-white reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 sm:mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">{{ __('home.popular_cars.title') }}</h2>
                    <p class="text-sm sm:text-base text-gray-600">{{ __('home.popular_cars.subtitle') }}</p>
                </div>
                <button onclick="document.getElementById('topCarsScroll').scrollBy({left: 300, behavior: 'smooth'})" class="hidden md:flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 hover:border-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Horizontal Scrollable Container -->
            <div id="topCarsScroll" class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                @forelse($topCars as $car)
                    <div onclick="openCarDetailsModal({{ $car->agency->id }}, {{ $car->id }})" class="car-card group flex-shrink-0 w-[280px] sm:w-[320px] bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="car-card-image relative h-[240px] bg-gray-100 overflow-hidden">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Featured Badge -->
                            @if($car->featured || $car->agency->featured)
                                <div class="absolute top-3 left-3 flex items-center gap-1 px-2.5 py-1 rounded-md bg-orange-600 text-white shadow-lg text-xs font-bold">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span>{{ $car->featured ? __('home.popular_cars.featured') : __('home.popular_cars.partner') }}</span>
                                </div>
                            @endif

                            <!-- Top-right controls: favorite + availability -->
                            <div class="absolute top-3 right-3 flex flex-col items-end gap-2">
                                <button onclick="event.stopPropagation(); handleFavoriteClick({{ $car->id }}, event)" 
                                        class="w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center hover:bg-white transition-colors shadow-sm favorite-btn" 
                                        data-car-id="{{ $car->id }}">
                                    <svg class="w-5 h-5 text-gray-700 favorite-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm
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
                                        {{ __('Disponible') }}
                                    @elseif($car->status === 'rented')
                                        {{ __('En location') }}
                                    @elseif($car->status === 'maintenance')
                                        {{ __('Maintenance') }}
                                    @else
                                        {{ __('Indisponible') }}
                                    @endif
                                </span>
                            </div>
                            
                            <!-- Rating Badge -->
                            @if($car->average_rating > 0)
                                <div class="absolute bottom-3 left-3 flex items-center gap-1 px-2 py-1 rounded-md bg-white/95 backdrop-blur-sm shadow-sm">
                                    <svg class="w-4 h-4 text-orange-600 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($car->average_rating, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Car Details -->
                        <div class="p-4">
                            <div class="mb-3">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $car->brand }} {{ $car->model }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $car->agency->city ?? 'Maroc' }}</p>
                            </div>
                            
                            <!-- Price -->
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($car->client_price_per_day, 0) }}</span>
                                    <span class="text-sm text-gray-600">MAD</span>
                                    <span class="text-sm text-gray-500">/ {{ __('home.popular_cars.per_day') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-12">
                        <p class="text-gray-500">{{ __('home.popular_cars.no_cars') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Join Us Banner -->
    <div class="bg-orange-600 py-10 sm:py-16 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4">
                {{ __('home.cta.title') }}
            </h2>
            <p class="text-sm sm:text-lg text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
                {{ __('home.cta.subtitle') }}
            </p>
            <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 bg-white hover:bg-gray-100 text-orange-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold transition-colors text-sm sm:text-base">
                    {{ __('home.cta.button') }}
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#discover" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 bg-transparent border-2 border-white hover:bg-white hover:text-orange-600 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold transition-colors text-sm sm:text-base">
                    {{ __('home.cta.view_cars') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Discover our wide range of cars -->
    <div id="discover" class="py-10 sm:py-16 bg-gray-50 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ __('home.categories.title') }}</h2>
            </div>
            
            <!-- Category Filters - Horizontal Scrollable (like Véhicules Populaires) -->
            <div class="flex gap-2 sm:gap-3 mb-6 sm:mb-8 overflow-x-auto pb-2 scrollbar-hide scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <a href="{{ route('public.home') }}" 
                   class="flex-shrink-0 px-3 sm:px-5 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm transition-colors whitespace-nowrap {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                        {{ __('home.categories.all') }}
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('public.home', ['category' => $category->id]) }}" 
                       class="flex-shrink-0 px-3 sm:px-5 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm transition-colors whitespace-nowrap {{ request('category') == $category->id ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    <button type="button" 
                            onclick="document.getElementById('filterModal').classList.remove('hidden')"
                        class="flex-shrink-0 px-3 sm:px-5 py-1.5 sm:py-2 rounded-lg font-semibold text-xs sm:text-sm bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition-colors flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        {{ __('home.categories.more_filters') }}
                    </button>
            </div>

            <!-- Cars Grid Container -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @forelse($discoverCars as $car)
                    <div onclick="openCarDetailsModal({{ $car->agency->id }}, {{ $car->id }})" class="car-card group bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="car-card-image relative h-[240px] bg-gray-100 overflow-hidden">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Featured Badge -->
                            @if($car->featured || $car->agency->featured)
                                <div class="absolute top-3 left-3 flex items-center gap-1 px-2.5 py-1 rounded-md bg-orange-600 text-white shadow-lg text-xs font-bold">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span>{{ $car->featured ? __('home.popular_cars.featured') : __('home.popular_cars.partner') }}</span>
                                </div>
                            @endif
                            
                            <!-- Heart Icon (Airbnb style) -->
                            <button onclick="event.stopPropagation(); handleFavoriteClick({{ $car->id }}, event)" 
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center hover:bg-white transition-colors shadow-sm favorite-btn" 
                                    data-car-id="{{ $car->id }}">
                                <svg class="w-5 h-5 text-gray-700 favorite-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                            
                            <!-- Rating Badge -->
                            @if($car->average_rating > 0)
                                <div class="absolute bottom-3 left-3 flex items-center gap-1 px-2 py-1 rounded-md bg-white/95 backdrop-blur-sm shadow-sm">
                                    <svg class="w-4 h-4 text-orange-600 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($car->average_rating, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Car Details -->
                        <div class="p-4">
                            <div class="mb-3">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $car->brand }} {{ $car->model }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $car->agency->city ?? 'Maroc' }}</p>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($car->client_price_per_day, 0) }}</span>
                                    <span class="text-sm text-gray-600">MAD</span>
                                    <span class="text-sm text-gray-500">/ {{ __('home.popular_cars.per_day') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">{{ __('home.popular_cars.no_cars_category') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($discoverCars->hasPages())
                <div class="mt-8 flex justify-center">
                    <nav class="flex items-center gap-2">
                        {{-- Previous Page Link --}}
                        @if($discoverCars->onFirstPage())
                            <span class="px-4 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $discoverCars->previousPageUrl() }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $discoverCars->currentPage();
                            $lastPage = $discoverCars->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp
                        
                        @if($startPage > 1)
                            <a href="{{ $discoverCars->url(1) }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">1</a>
                            @if($startPage > 2)
                                <span class="px-2 text-gray-500">...</span>
                            @endif
                        @endif
                        
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page == $currentPage)
                                <span class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $discoverCars->url($page) }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endfor
                        
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span class="px-2 text-gray-500">...</span>
                            @endif
                            <a href="{{ $discoverCars->url($lastPage) }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $lastPage }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if($discoverCars->hasMorePages())
                            <a href="{{ $discoverCars->nextPageUrl() }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="px-4 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        @endif
                    </nav>
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
    <div id="searchModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white rounded-t-3xl sm:rounded-3xl max-w-2xl w-full h-[90vh] sm:h-auto sm:max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
            <form action="{{ route('public.cars.search') }}" method="GET" id="searchForm" class="flex flex-col h-full">
                <!-- Modal Tabs Header -->
                <div class="bg-white border-b border-gray-200 flex-shrink-0">
                    <div class="flex items-center px-6 py-3">
                        <button type="button" onclick="closeSearchModal()" class="text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="flex-1 flex rounded-full bg-gray-100 p-1 gap-1">
                            <!-- Where Tab -->
                            <button type="button" id="whereTab" onclick="switchToSection('where')" class="flex-1 px-4 sm:px-6 py-3 rounded-full font-medium text-sm transition-all bg-white shadow-sm">
                                <div class="text-left">
                                    <div class="text-xs font-semibold text-gray-900">Where</div>
                                    <div class="text-xs sm:text-sm text-gray-500 truncate" id="whereTabDisplay">Search destinations</div>
                                </div>
                            </button>
                            <!-- Check in Tab -->
                            <button type="button" id="checkInTab" onclick="switchToSection('checkin')" class="flex-1 px-4 sm:px-6 py-3 rounded-full font-medium text-sm transition-all">
                                <div class="text-left">
                                    <div class="text-xs font-semibold text-gray-500">Check in</div>
                                    <div class="text-xs sm:text-sm text-gray-400 truncate" id="checkInTabDisplay">Add dates</div>
                                </div>
                            </button>
                            <!-- Check out Tab -->
                            <button type="button" id="checkOutTab" onclick="switchToSection('checkout')" class="flex-1 px-4 sm:px-6 py-3 rounded-full font-medium text-sm transition-all">
                                <div class="text-left">
                                    <div class="text-xs font-semibold text-gray-500">Check out</div>
                                    <div class="text-xs sm:text-sm text-gray-400 truncate" id="checkOutTabDisplay">Add dates</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Where Section -->
                    <div id="whereSection" class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Where?</h2>
                        <div class="relative mb-6">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="where" id="whereInput" placeholder="Search destinations" 
                                   class="w-full pl-12 pr-10 py-3 border border-gray-300 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all text-base"
                                   style="pointer-events: auto; cursor: text;" autocomplete="off">
                            <!-- Clear button -->
                            <button type="button" id="clearWhereInput" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <!-- Suggestions dropdown -->
                            <div id="citySuggestions" class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-80 overflow-y-auto hidden">
                                <div id="suggestionsList" class="py-2"></div>
                            </div>
                        </div>
                        
                        <!-- Recent searches (Dynamic) -->
                        <div class="mb-6">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Recent searches</p>
                            <div id="recentSearchesContainer" class="space-y-2"></div>
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
            console.log('openSearchModal called with section:', section);
            const modal = document.getElementById('searchModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scroll
                switchToSection(section || 'where');
                console.log('Modal opened, section switched to:', section || 'where');
            } else {
                console.error('Search modal not found!');
            }
        }

        // Close modal
        function closeSearchModal() {
            const modal = document.getElementById('searchModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scroll
            }
        }

        // Ensure functions are available globally
        window.openSearchModal = openSearchModal;
        window.closeSearchModal = closeSearchModal;

        // Add event listener when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const mobileSearchBar = document.getElementById('mobileSearchBar');
            if (mobileSearchBar) {
                // Utiliser capture phase pour intercepter avant ultimate-navigation-fix
                mobileSearchBar.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation(); // Empêcher ultimate-navigation-fix
                    openSearchModal('where');
                    return false;
                }, true); // true = capture phase (avant la phase de propagation)
            }

            // Add click handlers for desktop search fields
            const whereDesktop = document.querySelector('[onclick="openSearchModal(\'where\')"]');
            const checkInDesktop = document.querySelector('[onclick="openSearchModal(\'checkin\')"]');
            const checkOutDesktop = document.querySelector('[onclick="openSearchModal(\'checkout\')"]');
            
            if (whereDesktop) {
                whereDesktop.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openSearchModal('where');
                });
            }
            
            if (checkInDesktop) {
                checkInDesktop.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openSearchModal('checkin');
                });
            }
            
            if (checkOutDesktop) {
                checkOutDesktop.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openSearchModal('checkout');
                });
            }

            // Close modal when clicking outside
            const searchModal = document.getElementById('searchModal');
            if (searchModal) {
                searchModal.addEventListener('click', function(e) {
                    if (e.target === searchModal) {
                        closeSearchModal();
                    }
                });
            }

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('searchModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeSearchModal();
                    }
                }
            });
        });

        // Switch between sections (where/checkin/checkout)
        function switchToSection(section) {
            const whereTab = document.getElementById('whereTab');
            const checkInTab = document.getElementById('checkInTab');
            const checkOutTab = document.getElementById('checkOutTab');
            const whereSection = document.getElementById('whereSection');
            const whenSection = document.getElementById('whenSection');
            
            // Reset all tabs
            [whereTab, checkInTab, checkOutTab].forEach(tab => {
                if (tab) {
                    tab.classList.remove('bg-white', 'shadow-sm');
                    const label = tab.querySelector('.text-xs.font-semibold');
                    const value = tab.querySelector('.text-xs, .text-sm');
                    if (label) {
                        label.classList.remove('text-gray-900');
                        label.classList.add('text-gray-500');
                    }
                    if (value) {
                        value.classList.remove('text-gray-900', 'text-gray-500');
                        value.classList.add('text-gray-400');
                    }
                }
            });
            
            if (section === 'where') {
                // Show where section
                whereSection.classList.remove('hidden');
                whenSection.classList.add('hidden');
                
                // Update tab styles
                whereTab.classList.add('bg-white', 'shadow-sm');
                const whereLabel = whereTab.querySelector('.text-xs.font-semibold');
                const whereValue = whereTab.querySelector('.text-xs, .text-sm');
                if (whereLabel) {
                    whereLabel.classList.remove('text-gray-500');
                    whereLabel.classList.add('text-gray-900');
                }
                if (whereValue) {
                    whereValue.classList.remove('text-gray-400');
                    whereValue.classList.add('text-gray-500');
                }
                
                setTimeout(() => {
                    const input = document.getElementById('whereInput');
                    if (input) {
                        input.focus();
                        // Initialize autocomplete
                        console.log('Initializing city autocomplete...');
                        initCityAutocomplete();
                    } else {
                        console.error('whereInput not found!');
                    }
                }, 100);
            } else if (section === 'checkin' || section === 'checkout') {
                // Show when section
                whereSection.classList.add('hidden');
                whenSection.classList.remove('hidden');
                
                // Update tab styles based on which date tab is active
                const activeTab = section === 'checkin' ? checkInTab : checkOutTab;
                if (activeTab) {
                    activeTab.classList.add('bg-white', 'shadow-sm');
                    const label = activeTab.querySelector('.text-xs.font-semibold');
                    const value = activeTab.querySelector('.text-xs, .text-sm');
                    if (label) {
                        label.classList.remove('text-gray-500');
                        label.classList.add('text-gray-900');
                    }
                    if (value) {
                        value.classList.remove('text-gray-400');
                        value.classList.add('text-gray-500');
                    }
                }
                
                generateCalendar();
            }
        }

        // Update mobile search bar text
        function updateMobileSearchBar() {
            const mobileSearchBar = document.getElementById('mobileSearchBarText');
            if (!mobileSearchBar) return;
            
            const whereValue = document.getElementById('whereInput')?.value || '';
            const checkIn = checkInDate ? new Date(checkInDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : null;
            const checkOut = checkOutDate ? new Date(checkOutDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : null;
            
            let text = '';
            if (whereValue) {
                text = whereValue;
                if (checkIn || checkOut) text += ' · ';
            }
            if (checkIn && checkOut) {
                text += checkIn + ' - ' + checkOut;
            } else if (checkIn) {
                text += checkIn;
            }
            
            if (text) {
                mobileSearchBar.innerHTML = text;
                mobileSearchBar.classList.remove('text-gray-500');
                mobileSearchBar.classList.add('text-gray-900');
            } else {
                mobileSearchBar.innerHTML = '<span class="text-gray-500">Start your search</span>';
                mobileSearchBar.classList.remove('text-gray-900');
            }
        }

        // City autocomplete functionality
        let cityAutocompleteTimeout;
        let isSearchingCities = false;

        function initCityAutocomplete() {
            const whereInput = document.getElementById('whereInput');
            const citySuggestions = document.getElementById('citySuggestions');
            const clearButton = document.getElementById('clearWhereInput');
            const suggestionsList = document.getElementById('suggestionsList');

            if (!whereInput) {
                console.error('whereInput not found in initCityAutocomplete');
                return;
            }
            
            console.log('City autocomplete initialized');
            
            // Check if already initialized to avoid duplicate listeners
            if (whereInput.dataset.autocompleteInitialized === 'true') {
                console.log('Autocomplete already initialized, skipping...');
                return;
            }
            whereInput.dataset.autocompleteInitialized = 'true';

            // Show/hide clear button
            whereInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    if (clearButton) clearButton.classList.remove('hidden');
                } else {
                    if (clearButton) clearButton.classList.add('hidden');
                    if (citySuggestions) citySuggestions.classList.add('hidden');
                }
            });

            // Clear input
            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    whereInput.value = '';
                    if (citySuggestions) citySuggestions.classList.add('hidden');
                    clearButton.classList.add('hidden');
                    whereInput.focus();
                });
            }

            // Search cities on input
            whereInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                if (query.length < 2) {
                    if (citySuggestions) citySuggestions.classList.add('hidden');
                    return;
                }

                // Debounce search
                clearTimeout(cityAutocompleteTimeout);
                cityAutocompleteTimeout = setTimeout(() => {
                    searchCities(query);
                }, 300);
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (whereInput && citySuggestions && !whereInput.contains(e.target) && !citySuggestions.contains(e.target)) {
                    citySuggestions.classList.add('hidden');
                }
            });

            // Handle keyboard navigation
            whereInput.addEventListener('keydown', function(e) {
                if (!suggestionsList) return;
                const suggestions = suggestionsList.querySelectorAll('.suggestion-item');
                const activeSuggestion = suggestionsList.querySelector('.suggestion-item.active');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (activeSuggestion) {
                        activeSuggestion.classList.remove('active');
                        const next = activeSuggestion.nextElementSibling;
                        if (next) {
                            next.classList.add('active');
                            next.scrollIntoView({ block: 'nearest' });
                        }
                    } else if (suggestions.length > 0) {
                        suggestions[0].classList.add('active');
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (activeSuggestion) {
                        activeSuggestion.classList.remove('active');
                        const prev = activeSuggestion.previousElementSibling;
                        if (prev) {
                            prev.classList.add('active');
                            prev.scrollIntoView({ block: 'nearest' });
                        }
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeSuggestion) {
                        const cityName = activeSuggestion.dataset.city;
                        selectDestination(cityName);
                        citySuggestions.classList.add('hidden');
                    }
                } else if (e.key === 'Escape') {
                    citySuggestions.classList.add('hidden');
                }
            });
        }

        async function searchCities(query) {
            if (isSearchingCities) return;
            
            isSearchingCities = true;
            const citySuggestions = document.getElementById('citySuggestions');
            const suggestionsList = document.getElementById('suggestionsList');

            try {
                const response = await fetch(`{{ route('public.cities.search') }}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success && data.cities.length > 0) {
                    suggestionsList.innerHTML = '';
                    
                    data.cities.forEach((city, index) => {
                        const item = document.createElement('div');
                        item.className = 'suggestion-item flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors';
                        item.dataset.city = city.name;
                        if (index === 0) {
                            item.classList.add('active');
                        }
                        
                        item.innerHTML = `
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">${city.name}</p>
                            </div>
                        `;
                        
                        item.addEventListener('click', function() {
                            selectDestination(city.name);
                            citySuggestions.classList.add('hidden');
                        });
                        
                        item.addEventListener('mouseenter', function() {
                            suggestionsList.querySelectorAll('.suggestion-item').forEach(i => i.classList.remove('active'));
                            this.classList.add('active');
                        });
                        
                        suggestionsList.appendChild(item);
                    });
                    
                    citySuggestions.classList.remove('hidden');
                } else {
                    citySuggestions.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error searching cities:', error);
                citySuggestions.classList.add('hidden');
            } finally {
                isSearchingCities = false;
            }
        }

        // Select destination
        function selectDestination(city) {
            document.getElementById('whereInput').value = city;
            
            // Update mobile search bar
            updateMobileSearchBar();
            
            // Update desktop displays
            const whereDisplayDesktop = document.getElementById('whereDisplayDesktop');
            if (whereDisplayDesktop) {
                whereDisplayDesktop.textContent = city;
                whereDisplayDesktop.classList.remove('text-gray-600');
                whereDisplayDesktop.classList.add('text-gray-900');
            }
            
            // Update modal tab display
            document.getElementById('whereTabDisplay').textContent = city;
            document.getElementById('whereDisplay').textContent = city;
            document.getElementById('whereDisplay').classList.remove('text-gray-600');
            document.getElementById('whereDisplay').classList.add('text-gray-900');

            // Save to recent searches
            addRecentSearch(city);
            renderRecentSearches();
            
            // Switch to dates section
            switchToSection('checkin');
        }

        // Perform search
        function performSearch() {
            // Get values from modal inputs if modal is open
            const whereInput = document.getElementById('whereInput');
            const checkInInput = document.getElementById('checkInInput');
            const checkOutInput = document.getElementById('checkOutInput');
            const searchModal = document.getElementById('searchModal');
            const form = document.getElementById('searchForm');
            
            // If modal is open and form exists, submit the form
            if (searchModal && !searchModal.classList.contains('hidden') && form) {
                const whereValue = whereInput ? whereInput.value.trim() : '';
                if (whereValue) {
                    addRecentSearch(whereValue);
                    renderRecentSearches();
                }
                form.submit();
                return;
            }
            
            // If modal is closed, build URL from stored values
            let where = '';
            let checkIn = '';
            let checkOut = '';
            
            // Get where value from input or display
            if (whereInput && whereInput.value.trim()) {
                where = whereInput.value.trim();
            } else {
                const whereDisplayDesktop = document.getElementById('whereDisplayDesktop');
                if (whereDisplayDesktop) {
                    const displayText = whereDisplayDesktop.textContent.trim();
                    // Check if it's not the placeholder
                    if (displayText && displayText !== 'Search destinations' && displayText !== 'Rechercher des destinations') {
                        where = displayText;
                    }
                }
            }
            
            // Get dates from global variables or hidden inputs
            if (checkInDate) {
                checkIn = new Date(checkInDate).toISOString().split('T')[0];
            } else if (checkInInput && checkInInput.value) {
                checkIn = checkInInput.value;
            }
            
            if (checkOutDate) {
                checkOut = new Date(checkOutDate).toISOString().split('T')[0];
            } else if (checkOutInput && checkOutInput.value) {
                checkOut = checkOutInput.value;
            }
            
            // Build search URL
            const searchUrl = new URL('{{ route("public.cars.search") }}', window.location.origin);
            if (where) {
                searchUrl.searchParams.append('where', where);
                addRecentSearch(where);
                renderRecentSearches();
            }
            if (checkIn) {
                searchUrl.searchParams.append('check_in', checkIn);
            }
            if (checkOut) {
                searchUrl.searchParams.append('check_out', checkOut);
            }
            
            // Redirect to search page
            window.location.href = searchUrl.toString();
        }
        
        // Make function globally available
        window.performSearch = performSearch;

        // Recent searches helpers (localStorage)
        function getRecentSearches() {
            try {
                const data = JSON.parse(localStorage.getItem('recent_searches') || '[]');
                return Array.isArray(data) ? data : [];
            } catch (_) {
                return [];
            }
        }

        function addRecentSearch(city) {
            const value = (city || '').trim();
            if (!value) return;
            let list = getRecentSearches();
            // Remove existing duplicates (case-insensitive)
            list = list.filter(item => item.toLowerCase() !== value.toLowerCase());
            // Add to top
            list.unshift(value);
            // Cap to 6 items
            if (list.length > 6) list = list.slice(0, 6);
            localStorage.setItem('recent_searches', JSON.stringify(list));
        }

        function renderRecentSearches() {
            const container = document.getElementById('recentSearchesContainer');
            if (!container) return;
            const list = getRecentSearches();
            if (!list.length) {
                container.innerHTML = '<p class="text-sm text-gray-400">No recent searches</p>';
                return;
            }
            container.innerHTML = list.map(city => `
                <button type="button" class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-gray-200" onclick="selectDestination('${city.replace(/'/g, "\'")}')">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-medium text-gray-900">${city}</p>
                        <p class="text-sm text-gray-500">Recent search</p>
                    </div>
                </button>
            `).join('');
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

            // Check which tab is currently active
            const checkOutTab = document.getElementById('checkOutTab');
            const isCheckOutTabActive = checkOutTab && checkOutTab.classList.contains('bg-white');
            
            if (isCheckOutTabActive && checkInDate) {
                // If check out tab is active and check in is set, set check out date
                if (new Date(dateStr) > new Date(checkInDate)) {
                    checkOutDate = dateStr;
                } else {
                    // If selected date is before check in, set it as new check in and clear check out
                    checkInDate = dateStr;
                    checkOutDate = null;
                }
            } else if (!checkInDate || (checkInDate && checkOutDate)) {
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
            const checkOutTabDisplay = document.getElementById('checkOutTabDisplay');
            const checkInInput = document.getElementById('checkInInput');
            const checkOutInput = document.getElementById('checkOutInput');

            // Update desktop displays
            const checkInDisplayDesktop = document.getElementById('checkInDisplayDesktop');
            const checkOutDisplayDesktop = document.getElementById('checkOutDisplayDesktop');

            if (checkInDate) {
                const date = new Date(checkInDate);
                const formatted = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                
                // Modal displays
                if (checkInEl) {
                checkInEl.textContent = formatted;
                checkInEl.classList.remove('text-gray-400');
                checkInEl.classList.add('text-gray-900');
                }
                if (checkInTabDisplay) {
                    checkInTabDisplay.textContent = formatted;
                checkInTabDisplay.classList.remove('text-gray-400');
                    checkInTabDisplay.classList.add('text-gray-500');
                }
                
                // Desktop display
                if (checkInDisplayDesktop) {
                    checkInDisplayDesktop.textContent = formatted;
                    checkInDisplayDesktop.classList.remove('text-gray-400');
                    checkInDisplayDesktop.classList.add('text-gray-900');
                }
                
                checkInInput.value = checkInDate;
            }

            if (checkOutDate) {
                const date = new Date(checkOutDate);
                const formatted = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                
                // Modal display
                if (checkOutEl) {
                checkOutEl.textContent = formatted;
                checkOutEl.classList.remove('text-gray-400');
                checkOutEl.classList.add('text-gray-900');
                }
                
                // Check out tab display
                if (checkOutTabDisplay) {
                    checkOutTabDisplay.textContent = formatted;
                    checkOutTabDisplay.classList.remove('text-gray-400');
                    checkOutTabDisplay.classList.add('text-gray-500');
                }
                
                // Desktop display
                if (checkOutDisplayDesktop) {
                    checkOutDisplayDesktop.textContent = formatted;
                    checkOutDisplayDesktop.classList.remove('text-gray-400');
                    checkOutDisplayDesktop.classList.add('text-gray-900');
                }
                
                checkOutInput.value = checkOutDate;
                
                // Update check in tab to show check in → check out if both dates are set
                if (checkInDate && checkInTabDisplay) {
                    const checkInDateObj = new Date(checkInDate);
                    checkInTabDisplay.textContent = checkInDateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + ' – ' + formatted;
                }
            }
            
            // Update mobile search bar
            updateMobileSearchBar();
        }

        function clearDates() {
            checkInDate = null;
            checkOutDate = null;
            
            // Reset modal displays
            const checkInDisplay = document.getElementById('checkInDisplay');
            const checkOutDisplay = document.getElementById('checkOutDisplay');
            const checkInTabDisplay = document.getElementById('checkInTabDisplay');
            const checkOutTabDisplay = document.getElementById('checkOutTabDisplay');
            
            if (checkInDisplay) {
                checkInDisplay.textContent = 'Add dates';
                checkInDisplay.classList.remove('text-gray-900');
                checkInDisplay.classList.add('text-gray-400');
            }
            if (checkInTabDisplay) {
                checkInTabDisplay.textContent = 'Add dates';
                checkInTabDisplay.classList.remove('text-gray-500', 'text-gray-900');
                checkInTabDisplay.classList.add('text-gray-400');
            }
            if (checkOutDisplay) {
                checkOutDisplay.textContent = 'Add dates';
                checkOutDisplay.classList.remove('text-gray-900');
                checkOutDisplay.classList.add('text-gray-400');
            }
            if (checkOutTabDisplay) {
                checkOutTabDisplay.textContent = 'Add dates';
                checkOutTabDisplay.classList.remove('text-gray-500', 'text-gray-900');
                checkOutTabDisplay.classList.add('text-gray-400');
            }
            
            // Reset desktop displays
            const checkInDisplayDesktop = document.getElementById('checkInDisplayDesktop');
            const checkOutDisplayDesktop = document.getElementById('checkOutDisplayDesktop');
            const whereDisplayDesktop = document.getElementById('whereDisplayDesktop');
            
            if (checkInDisplayDesktop) {
                checkInDisplayDesktop.textContent = 'Add dates';
                checkInDisplayDesktop.classList.remove('text-gray-900');
                checkInDisplayDesktop.classList.add('text-gray-400');
            }
            if (checkOutDisplayDesktop) {
                checkOutDisplayDesktop.textContent = 'Add dates';
                checkOutDisplayDesktop.classList.remove('text-gray-900');
                checkOutDisplayDesktop.classList.add('text-gray-400');
            }
            if (whereDisplayDesktop) {
                whereDisplayDesktop.textContent = 'Search destinations';
                whereDisplayDesktop.classList.remove('text-gray-900');
                whereDisplayDesktop.classList.add('text-gray-600');
            }
            
            // Reset modal where display
            const whereDisplay = document.getElementById('whereDisplay');
            const whereTabDisplay = document.getElementById('whereTabDisplay');
            if (whereDisplay) {
                whereDisplay.textContent = 'Search destinations';
                whereDisplay.classList.remove('text-gray-900');
                whereDisplay.classList.add('text-gray-600');
            }
            if (whereTabDisplay) {
                whereTabDisplay.textContent = 'Search destinations';
                whereTabDisplay.classList.remove('text-gray-900');
                whereTabDisplay.classList.add('text-gray-500');
            }
            
            document.getElementById('whereInput').value = '';
            document.getElementById('checkInInput').value = '';
            document.getElementById('checkOutInput').value = '';
            
            // Update mobile search bar
            updateMobileSearchBar();
            
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

        // Update where input (keep existing functionality)
        const whereInput = document.getElementById('whereInput');
        if (whereInput) {
            // This listener is already handled by initCityAutocomplete, but we keep this for display updates
            const existingInputHandler = function(e) {
            const value = e.target.value;
            const displayText = value || 'Search destinations';
                
                // Update modal displays
                const whereDisplay = document.getElementById('whereDisplay');
                const whereTabDisplay = document.getElementById('whereTabDisplay');
                
                if (whereDisplay) {
                    whereDisplay.textContent = displayText;
            if (value) {
                        whereDisplay.classList.remove('text-gray-600');
                        whereDisplay.classList.add('text-gray-900');
            } else {
                        whereDisplay.classList.remove('text-gray-900');
                        whereDisplay.classList.add('text-gray-600');
                    }
                }
                
                if (whereTabDisplay) {
                    whereTabDisplay.textContent = displayText;
                    if (value) {
                        whereTabDisplay.classList.remove('text-gray-500');
                        whereTabDisplay.classList.add('text-gray-900');
                    } else {
                        whereTabDisplay.classList.remove('text-gray-900');
                        whereTabDisplay.classList.add('text-gray-500');
                    }
                }
                
                // Update desktop display
                const whereDisplayDesktop = document.getElementById('whereDisplayDesktop');
                if (whereDisplayDesktop) {
                    whereDisplayDesktop.textContent = displayText;
                    if (value) {
                        whereDisplayDesktop.classList.remove('text-gray-600');
                        whereDisplayDesktop.classList.add('text-gray-900');
                    } else {
                        whereDisplayDesktop.classList.remove('text-gray-900');
                        whereDisplayDesktop.classList.add('text-gray-600');
                    }
                }
                
                // Update mobile search bar
                updateMobileSearchBar();
            });

            // Add to recent searches when pressing Enter in the where input
            // Note: This is handled in initCityAutocomplete, but we keep this as fallback
            whereInput.addEventListener('keydown', function(e) {
                const citySuggestions = document.getElementById('citySuggestions');
                const activeSuggestion = document.getElementById('suggestionsList')?.querySelector('.suggestion-item.active');
                
                // If suggestions are visible and active, let autocomplete handle it
                if (citySuggestions && !citySuggestions.classList.contains('hidden') && activeSuggestion) {
                    return; // Let autocomplete handle Enter key
                }
                
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const value = whereInput.value.trim();
                    if (value) {
                        addRecentSearch(value);
                        renderRecentSearches();
                        switchToSection('checkin');
                    }
                }
            });
        }

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
        // Render recent searches on load
        renderRecentSearches();
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

        .wishlist-modal {
            transition: opacity 0.3s ease;
        }

        .wishlist-modal .wishlist-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .wishlist-modal.show .wishlist-overlay {
            opacity: 1;
        }

        .wishlist-sheet {
            transform: translateY(100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .wishlist-modal.show .wishlist-sheet {
            transform: translateY(0);
        }

        .wishlist-card {
            border: 1px solid #e5e7eb;
            border-radius: 1.25rem;
            padding: 0.85rem 1rem;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            width: 100%;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .wishlist-card:hover {
            border-color: #cbd5f5;
        }

        .wishlist-card.active {
            border-color: #0F3B63;
            background: #f8fafc;
            box-shadow: 0 12px 30px rgba(15, 59, 99, 0.15);
        }

        .wishlist-card-thumb {
            width: 64px;
            height: 64px;
            border-radius: 1.5rem;
            overflow: hidden;
            flex-shrink: 0;
            background: linear-gradient(135deg, #f97316, #fb923c);
        }

        .wishlist-card-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .wishlist-card-check {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            border: 2px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            transition: all 0.2s ease;
        }

        .wishlist-card.active .wishlist-card-check {
            background: #0F3B63;
            border-color: #0F3B63;
            color: #fff;
        }

        /* City suggestions styles */
        .suggestion-item.active {
            background-color: #f3f4f6;
        }
        
        .suggestion-item:hover {
            background-color: #f9fafb;
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

        // Bottom Navigation Bar - Show/Hide on Scroll
        document.addEventListener('DOMContentLoaded', function() {
            let lastScrollTop = 0;
            const bottomNav = document.getElementById('bottom-nav');
            let ticking = false;

            function updateNavbar() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down - hide navbar
                    if (bottomNav) {
                        bottomNav.classList.add('hidden');
                    }
                } else {
                    // Scrolling up - show navbar
                    if (bottomNav) {
                        bottomNav.classList.remove('hidden');
                    }
                }
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                ticking = false;
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(updateNavbar);
                    ticking = true;
                }
            }, { passive: true });
        });

        // Smooth scroll to top-picks section
        function scrollToTopPicks(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const topPicksSection = document.getElementById('top-picks');
            if (topPicksSection) {
                const offsetTop = topPicksSection.offsetTop - 20; // 20px offset from top
                window.scrollTo({ 
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
            return false;
        }
    </script>

    @include('components.mobile-bottom-nav')

    <!-- Login Modal (Airbnb Style) -->
    <div id="loginModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeLoginModal()"></div>
            <div class="relative bg-white rounded-t-3xl w-full max-w-md shadow-2xl">
                <!-- Close Button -->
                <button onclick="closeLoginModal()" class="absolute top-4 left-4 w-8 h-8 flex items-center justify-center text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                <!-- Header -->
                <div class="px-6 pt-8 pb-4 text-center border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Log in or sign up</h2>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Welcome to ToubCar</h1>
                    
                    <!-- Login Form -->
                    <form action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-pink-500 to-red-500 text-white py-3 rounded-lg font-semibold hover:from-pink-600 hover:to-red-600 transition-colors">
                            Continue
                        </button>
                    </form>
                    
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">or</span>
                        </div>
                    </div>
                    
                    <!-- Alternative Login -->
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <span class="text-sm font-medium text-gray-700">Continue with Google</span>
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <span class="text-sm font-medium text-gray-700">Continue with email</span>
                        </a>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-4 text-center">
                        Don't have an account? <a href="{{ route('register') }}" class="text-orange-600 hover:underline">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Wishlist Bottom Sheet -->
    <div id="wishlistModal" class="wishlist-modal hidden fixed inset-0 z-50 flex flex-col justify-end px-4 pb-6 sm:pb-10">
        <div class="wishlist-overlay absolute inset-0 bg-black/60" onclick="closeWishlistModal()"></div>
        <div class="wishlist-sheet relative w-full max-w-xl mx-auto bg-white rounded-t-[32px] shadow-2xl overflow-hidden">
            <div class="flex justify-center pt-4 pb-2">
                <span class="block w-16 h-1.5 rounded-full bg-gray-200"></span>
            </div>
            <div class="px-6 pb-6 space-y-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Wishlist</p>
                        <h2 class="text-2xl font-semibold text-gray-900">Save to wishlist</h2>
                    </div>
                    <button onclick="closeWishlistModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div id="wishlistsList" class="space-y-3 max-h-[320px] overflow-y-auto pr-1">
                    <!-- Wishlists injected here -->
                </div>

                <div id="createWishlistForm" class="hidden space-y-3">
                    <input type="text" id="wishlistName" placeholder="Name your wishlist" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <div class="flex gap-3">
                        <button id="saveWishlistBtn" onclick="createWishlist()" 
                                class="flex-1 bg-[#0F3B63] text-white py-3 rounded-2xl font-semibold hover:bg-[#0d3456] transition">
                            Save
                        </button>
                        <button onclick="hideCreateWishlistForm()" 
                                class="px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </div>
                </div>

                <button id="createWishlistTrigger" onclick="showCreateWishlistForm()" 
                        class="w-full border-2 border-dashed border-gray-300 rounded-2xl py-3 font-medium text-gray-700 hover:border-orange-400 hover:text-orange-500 transition">
                    + Create new wishlist
                </button>

                <button onclick="closeWishlistModal()" 
                        class="w-full bg-gray-900 text-white py-3 rounded-2xl font-semibold hover:bg-gray-800 transition">
                    Done
                </button>
            </div>
        </div>
    </div>

    <script>
        const wishlistPlaceholderImage = "{{ asset('images/black-sedan-car-driving-bridge-road.png') }}";
        let currentCarId = null;

        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function openWishlistModal() {
            const modal = document.getElementById('wishlistModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => modal.classList.add('show'));
            document.body.style.overflow = 'hidden';
        }
        
        function handleFavoriteClick(carId, event) {
            event.stopPropagation();
            currentCarId = carId;
            
            @auth
                // User is logged in, show wishlist modal
                loadWishlists();
                // Delay modal opening slightly to avoid the initial tap being interpreted
                // as a click on the overlay (which would close it instantly on mobile).
                setTimeout(() => openWishlistModal(), 50);
            @else
                // User is not logged in, show login modal
                document.getElementById('loginModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            @endauth
        }
        
        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        function closeWishlistModal() {
            const modal = document.getElementById('wishlistModal');
            if (!modal) return;
            modal.classList.remove('show');
            document.body.style.overflow = '';
            setTimeout(() => modal.classList.add('hidden'), 250);
            currentCarId = null;
            hideCreateWishlistForm();
        }
        
        function loadWishlists() {
            if (!currentCarId) return Promise.resolve();

            const container = document.getElementById('wishlistsList');
            if (container) {
                container.innerHTML = '<p class="text-gray-400 text-sm text-center py-4">Loading your wishlists...</p>';
            }
            
            // Load wishlists and check which ones contain this car
            return Promise.all([
                fetch('{{ route("client.wishlists.index") }}', {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(r => r.json()),
                fetch(`{{ url('client/wishlists/check') }}/${currentCarId}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(r => r.json())
            ])
            .then(([wishlists, checkData]) => {
                const listContainer = document.getElementById('wishlistsList');
                if (!listContainer) return;
                listContainer.innerHTML = '';
                
                const inWishlists = checkData.in_wishlists || [];
                
                if (wishlists.length === 0) {
                    listContainer.innerHTML = '<div class="text-center py-8"><p class="text-gray-500 text-sm">No wishlists yet. Create one to start saving cars.</p></div>';
                } else {
                    wishlists.forEach(wishlist => {
                        const isInWishlist = inWishlists.includes(wishlist.id);
                        const card = document.createElement('button');
                        const previewImage = wishlist.preview_image || wishlistPlaceholderImage;
                        const savedLabel = wishlist.items_count === 1 ? '1 saved' : `${wishlist.items_count || 0} saved`;
                        const previewLabel = wishlist.preview_label ? escapeHtml(wishlist.preview_label) : 'Start saving cars';

                        card.type = 'button';
                        card.className = `wishlist-card ${isInWishlist ? 'active' : ''}`;
                        card.innerHTML = `
                            <div class="wishlist-card-thumb">
                                <img src="${previewImage}" alt="${escapeHtml(wishlist.name)} preview" loading="lazy">
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-base font-semibold text-gray-900">${escapeHtml(wishlist.name)}</p>
                                <p class="text-sm text-gray-500">${savedLabel}</p>
                                <p class="text-xs text-gray-400 truncate">${previewLabel}</p>
                            </div>
                            <div class="wishlist-card-check">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        `;
                        card.addEventListener('click', () => handleWishlistCardClick(wishlist.id, card));
                        listContainer.appendChild(card);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading wishlists:', error);
                const listContainer = document.getElementById('wishlistsList');
                if (listContainer) {
                    listContainer.innerHTML = '<p class="text-sm text-red-500 text-center py-4">Unable to load wishlists.</p>';
                }
            });
        }
        
        function handleWishlistCardClick(wishlistId, cardElement) {
            const shouldAdd = !cardElement.classList.contains('active');
            cardElement.classList.toggle('active', shouldAdd);
            toggleWishlist(wishlistId, shouldAdd, cardElement);
        }
        
        function toggleWishlist(wishlistId, add, cardElement = null) {
            if (!currentCarId) return;
            
            const url = add 
                ? `{{ url('client/wishlists') }}/${wishlistId}/cars`
                : `{{ url('client/wishlists') }}/${wishlistId}/cars/${currentCarId}`;
            const method = add ? 'POST' : 'DELETE';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: add ? JSON.stringify({ car_id: currentCarId }) : null
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong');
                }
                return data;
            })
            .then(data => {
                updateFavoriteButton(currentCarId, add);
                // Don't close modal immediately, let user add to multiple wishlists
                setTimeout(() => {
                    loadWishlists(); // Reload to update counts
                }, 300);
            })
            .catch(error => {
                console.error('Error:', error);
                if (cardElement) {
                    cardElement.classList.toggle('active', !add);
                }
                alert('An error occurred. Please try again.');
            });
        }
        
        function showCreateWishlistForm() {
            const form = document.getElementById('createWishlistForm');
            const trigger = document.getElementById('createWishlistTrigger');
            if (form) form.classList.remove('hidden');
            if (trigger) trigger.classList.add('hidden');
            const input = document.getElementById('wishlistName');
            if (input) {
                input.focus();
            }
        }
        
        function hideCreateWishlistForm() {
            const form = document.getElementById('createWishlistForm');
            const trigger = document.getElementById('createWishlistTrigger');
            if (form) form.classList.add('hidden');
            if (trigger) trigger.classList.remove('hidden');
            const input = document.getElementById('wishlistName');
            if (input) input.value = '';
        }
        
        function createWishlist() {
            const nameInput = document.getElementById('wishlistName');
            if (!nameInput) return;
            const name = nameInput.value.trim();
            if (!name) {
                alert('Please enter a wishlist name');
                return;
            }

            const saveButton = document.getElementById('saveWishlistBtn');
            const originalText = saveButton ? saveButton.textContent : '';
            if (saveButton) {
                saveButton.disabled = true;
                saveButton.textContent = 'Saving...';
            }
            
            fetch('{{ route("client.wishlists.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: name })
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Unable to create wishlist');
                }
                return data;
            })
            .then(data => {
                hideCreateWishlistForm();
                nameInput.value = '';
                loadWishlists();
                // Automatically add car to new wishlist
                if (currentCarId) {
                    setTimeout(() => {
                        toggleWishlist(data.id, true);
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Error creating wishlist:', error);
                alert(error.message || 'Unable to create wishlist. Please try again.');
            })
            .finally(() => {
                if (saveButton) {
                    saveButton.disabled = false;
                    saveButton.textContent = originalText;
                }
            });
        }
        
        function updateFavoriteButton(carId, isFavorite) {
            const buttons = document.querySelectorAll(`[data-car-id="${carId}"]`);
            buttons.forEach(btn => {
                if (isFavorite) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
        
        // Check favorite status on page load (for authenticated users)
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            const carIds = Array.from(document.querySelectorAll('.favorite-btn')).map(btn => btn.getAttribute('data-car-id'));
            carIds.forEach(carId => {
                if (carId) {
                    fetch(`{{ url('client/wishlists/check') }}/${carId}`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.in_wishlists && data.in_wishlists.length > 0) {
                            updateFavoriteButton(carId, true);
                        }
                    })
                    .catch(error => console.error('Error checking favorite status:', error));
                }
            });
        });
        @endauth
        
        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
                closeWishlistModal();
                closeCarDetailsModal();
            }
        });

        // Make functions globally available
        window.openCarDetailsModal = openCarDetailsModal;
        window.closeCarDetailsModal = closeCarDetailsModal;
    </script>

    <!-- Car Details Modal -->
    <div id="carDetailsModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center px-4 py-8" style="background-color: rgba(0, 0, 0, 0.75);" onclick="if(event.target === this) closeCarDetailsModal()">
        <!-- Modal Container -->
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-7xl w-full max-h-[90vh] flex flex-col overflow-hidden" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button onclick="closeCarDetailsModal()" class="absolute top-4 right-4 z-50 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Scrollable Content Area -->
            <div class="overflow-y-auto flex-1">
                <!-- Loading State -->
                <div id="carDetailsLoading" class="p-12 text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600"></div>
                    <p class="mt-4 text-gray-600">Chargement des détails...</p>
                </div>

                <!-- Car Details Content -->
                <div id="carDetailsContent" class="hidden">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Car Details Modal Functions
        function openCarDetailsModal(agencyId, carId) {
            const modal = document.getElementById('carDetailsModal');
            const loading = document.getElementById('carDetailsLoading');
            const content = document.getElementById('carDetailsContent');
            const scrollableArea = modal.querySelector('.overflow-y-auto');
            
            // Prevent body scroll completely
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            
            // Show modal
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            content.classList.add('hidden');
            content.innerHTML = '';

            // Scroll to top of scrollable area
            if (scrollableArea) {
                scrollableArea.scrollTop = 0;
            }

            // Fetch car details
            fetch(`/agencies/${agencyId}/cars/${carId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderCarDetails(data.car);
                        loading.classList.add('hidden');
                        content.classList.remove('hidden');
                        // Scroll to top after content loads
                        if (scrollableArea) {
                            scrollableArea.scrollTop = 0;
                        }
                    } else {
                        alert('Erreur lors du chargement des détails du véhicule');
                        closeCarDetailsModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors du chargement des détails du véhicule');
                    closeCarDetailsModal();
                });
        }

        function closeCarDetailsModal() {
            const modal = document.getElementById('carDetailsModal');
            modal.classList.add('hidden');
            // Restore body scroll
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        }

        function renderCarDetails(car) {
            const content = document.getElementById('carDetailsContent');
            
            // Get images
            const mainImage = car.main_image || car.images[0] || null;
            const otherImages = car.other_images || [];
            const allImages = car.images || [];

            // Build specifications string
            const specs = [
                car.year,
                car.fuel_type || 'N/A',
                car.seats ? `${car.seats} places` : null,
                car.transmission || null
            ].filter(Boolean).join(' • ');

            // Build HTML - Gallery horizontale avec toutes les images côte à côte
            let html = `
                <div class="p-6 lg:p-8">
                    <!-- Image Gallery Section - Horizontal Scrollable -->
                    <div class="mb-6">
                        ${allImages.length > 0 ? `
                            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                                ${allImages.map((image, index) => `
                                    <div class="flex-shrink-0 w-[300px] h-[300px] rounded-xl overflow-hidden cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('${image}')">
                                        <img src="${image}" 
                                             alt="${car.brand} ${car.model} - Image ${index + 1}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                `).join('')}
                            </div>
                        ` : `
                            <div class="w-full h-[400px] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-2xl">
                                <div class="text-center">
                                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Photo du véhicule</p>
                                </div>
                            </div>
                        `}
                    </div>

                    <!-- Car Info Section -->
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">${car.brand} ${car.model}</h1>
                            <p class="text-gray-600 mb-4">${specs}</p>
                        </div>
                        <div class="text-right ml-4">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gray-900">${parseInt(car.client_price_per_day).toLocaleString('fr-FR')}</span>
                                <span class="text-lg text-gray-600">MAD</span>
                            </div>
                            <span class="text-sm text-gray-500">/ jour</span>
                        </div>
                    </div>

                    <!-- Additional Details Section (Optional - can be expanded) -->
                    ${car.description || (car.features && car.features.length > 0) ? `
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            ${car.description ? `
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-900 mb-3">Description</h2>
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">${car.description}</p>
                                </div>
                            ` : ''}
                            ${car.features && car.features.length > 0 ? `
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-3">Équipements</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        ${car.features.map(feature => `
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="text-gray-700">${feature}</span>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    ` : ''}

                    <!-- Date Selection Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Choisissez vos dates de réservation</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="border border-gray-300 rounded-lg p-3">
                                <label class="text-xs font-semibold text-gray-700 block mb-1">DATE DE DÉBUT</label>
                                <input type="date" 
                                       id="modal-start-date-${car.id}" 
                                       class="w-full text-sm border-none outline-none bg-transparent" 
                                       min="${new Date().toISOString().split('T')[0]}"
                                       onchange="checkCarAvailability(${car.id}, ${car.agency.id})">
                            </div>
                            <div class="border border-gray-300 rounded-lg p-3">
                                <label class="text-xs font-semibold text-gray-700 block mb-1">DATE DE FIN</label>
                                <input type="date" 
                                       id="modal-end-date-${car.id}" 
                                       class="w-full text-sm border-none outline-none bg-transparent" 
                                       min="${new Date(Date.now() + 86400000).toISOString().split('T')[0]}"
                                       onchange="checkCarAvailability(${car.id}, ${car.agency.id})">
                            </div>
                        </div>
                        
                        <!-- Availability Status Message -->
                        <div id="availability-message-${car.id}" class="mb-4 hidden">
                            <div id="availability-content-${car.id}" class="p-3 rounded-lg"></div>
                        </div>
                        
                        <!-- Booking Button -->
                        <button id="booking-button-${car.id}" 
                                onclick="redirectToBooking(${car.id})" 
                                disabled
                                class="w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span id="booking-button-text-${car.id}">Sélectionnez des dates</span>
                        </button>
                    </div>
                </div>
            `;

            content.innerHTML = html;
        }

        // Check car availability function
        window.checkCarAvailability = function(carId, agencyId) {
            const startDateInput = document.getElementById(`modal-start-date-${carId}`);
            const endDateInput = document.getElementById(`modal-end-date-${carId}`);
            const bookingButton = document.getElementById(`booking-button-${carId}`);
            const buttonText = document.getElementById(`booking-button-text-${carId}`);
            const availabilityMessage = document.getElementById(`availability-message-${carId}`);
            const availabilityContent = document.getElementById(`availability-content-${carId}`);
            
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            // Reset button state
            bookingButton.disabled = true;
            bookingButton.className = 'w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2';
            buttonText.textContent = 'Vérification en cours...';
            availabilityMessage.classList.add('hidden');
            
            // Validate dates
            if (!startDate || !endDate) {
                buttonText.textContent = 'Sélectionnez des dates';
                return;
            }
            
            if (new Date(endDate) <= new Date(startDate)) {
                buttonText.textContent = 'Date de fin invalide';
                availabilityMessage.classList.remove('hidden');
                availabilityContent.className = 'p-3 rounded-lg bg-red-50 border border-red-200';
                availabilityContent.innerHTML = '<p class="text-red-700 text-sm">La date de fin doit être après la date de début.</p>';
                return;
            }
            
            // Update end date minimum
            const minEndDate = new Date(startDate);
            minEndDate.setDate(minEndDate.getDate() + 1);
            endDateInput.min = minEndDate.toISOString().split('T')[0];
            
            // Make API call
            fetch(`/agencies/${agencyId}/cars/${carId}/check-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    // Vehicle is available
                    bookingButton.disabled = false;
                    bookingButton.className = 'w-full bg-gradient-to-r from-[#C2410C] to-[#9A3412] text-white font-semibold py-4 px-6 rounded-xl hover:from-[#9A3412] hover:to-[#7C2D12] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 cursor-pointer';
                    buttonText.textContent = `Réserver maintenant • ${parseInt(data.total_price).toLocaleString('fr-FR')} MAD`;
                    
                    availabilityMessage.classList.remove('hidden');
                    availabilityContent.className = 'p-3 rounded-lg bg-green-50 border border-green-200';
                    availabilityContent.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-green-700 text-sm font-medium">${data.message}</p>
                        </div>
                        <p class="text-green-600 text-xs mt-1">${data.days} jour(s) • ${parseInt(data.price_per_day).toLocaleString('fr-FR')} MAD/jour</p>
                    `;
                } else {
                    // Vehicle is not available
                    bookingButton.disabled = true;
                    bookingButton.className = 'w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2';
                    buttonText.textContent = 'Véhicule non disponible';
                    
                    availabilityMessage.classList.remove('hidden');
                    availabilityContent.className = 'p-3 rounded-lg bg-red-50 border border-red-200';
                    availabilityContent.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <p class="text-red-700 text-sm font-medium">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error checking availability:', error);
                bookingButton.disabled = true;
                buttonText.textContent = 'Erreur de vérification';
                availabilityMessage.classList.remove('hidden');
                availabilityContent.className = 'p-3 rounded-lg bg-red-50 border border-red-200';
                availabilityContent.innerHTML = '<p class="text-red-700 text-sm">Une erreur est survenue. Veuillez réessayer.</p>';
            });
        };
        
        // Redirect to booking with dates
        window.redirectToBooking = function(carId) {
            const startDateInput = document.getElementById(`modal-start-date-${carId}`);
            const endDateInput = document.getElementById(`modal-end-date-${carId}`);
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (startDate && endDate) {
                closeCarDetailsModal();
                window.location.href = `/booking/${carId}?start_date=${startDate}&end_date=${endDate}`;
            }
        };
        
        // Image modal functions (if needed)
        function openImageModal(imageUrl) {
            // Simple image modal implementation
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90';
            modal.onclick = () => modal.remove();
            modal.innerHTML = `
                <img src="${imageUrl}" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
            `;
            document.body.appendChild(modal);
        }
    </script>
@endsection
