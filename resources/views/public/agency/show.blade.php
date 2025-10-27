@extends('layouts.public')

@section('title', $agency->user->name . ' - Agence de Location')

@section('content')
    <!-- Hero Section - Parallax Effect -->
    <div class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900" style="min-height: 100vh;">
        <!-- Content Overlay -->
        <div class="relative z-10 min-h-screen flex items-center justify-center text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-20">
                <div class="space-y-8" id="hero-content">
                    <div class="text-center">
                        <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6 hero-title">
                            {{ $agency->user->name }}
                            <span class="block text-orange-500">
                                Agence Premium
                            </span>
                        </h1>
                        
                        <p class="text-xl text-gray-300 leading-relaxed hero-subtitle">
                            Découvrez notre large gamme de véhicules disponibles dans cette agence. Simple, rapide et sécurisé.
                        </p>
                    </div>
                
                    <!-- Search Form - Airbnb Style -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-full shadow-2xl border border-gray-200 p-2 hero-search">
                        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2">
                            <!-- Where -->
                            <div class="flex-1 px-6 py-3 border-r border-gray-200 cursor-pointer hover:bg-gray-50 rounded-l-full transition-colors" onclick="openSearchModal('where')">
                                <label class="block text-xs font-semibold text-gray-900 mb-1">Where</label>
                                <div class="text-sm text-gray-600" id="whereDisplay">{{ $agency->city ?? 'Search destinations' }}</div>
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
                <p class="text-lg text-gray-600">Nos voitures les plus demandées de cette agence</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($cars->take(4) as $car)
                    <div onclick="window.location='{{ route('public.car.show', [$agency, $car]) }}'" class="group bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-48 bg-gray-100">
                            @if($car->image)
                                <img src="{{ $car->image_url }}" 
                                     alt="{{ $car->brand }} {{ $car->model }}" 
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
                            @if($car->getAverageRating() > 0)
                                <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-white shadow-sm">
                                    <x-star-rating :rating="$car->getAverageRating()" size="w-4 h-4" />
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($car->getAverageRating(), 1) }}</span>
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
                Prêt à Réserver?
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Choisissez votre véhicule parfait et réservez en quelques clics
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#top-picks" 
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-orange-600 px-8 py-3 rounded-lg font-semibold transition-colors">
                    Voir les Voitures
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                        </svg>
                </a>
                <a href="{{ route('public.agencies') }}" 
                   class="inline-flex items-center gap-2 bg-transparent border-2 border-white hover:bg-white hover:text-orange-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Autres Agences
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
                    <a href="{{ route('public.agency.show', $agency) }}" 
                       class="px-5 py-2 rounded-lg font-semibold text-sm transition-colors {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                        Tous
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('public.agency.show', ['agency' => $agency, 'category' => $category->id]) }}" 
                           class="px-5 py-2 rounded-lg font-semibold text-sm transition-colors {{ request('category') == $category->id ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Cars Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($cars as $car)
                    <div onclick="window.location='{{ route('public.car.show', [$agency, $car]) }}'" class="group bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-48 bg-gray-100">
                            @if($car->image)
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
                            <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-white shadow-sm">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-900">4.8</span>
                            </div>
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
        </div>
    </div>

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
        });

        // Search modal functions
        function openSearchModal(section) {
            // For now, just scroll to cars section
            document.getElementById('top-picks').scrollIntoView({ behavior: 'smooth' });
        }

        function performSearch() {
            // For now, just scroll to cars section
            document.getElementById('top-picks').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
        @endsection