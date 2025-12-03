@extends('layouts.public')

@section('title', 'Voitures de ' . $agency->agency_name)

@push('styles')
<style>
    .scrollbar-hide {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-orange-50 py-10 sm:py-16 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-5xl sm:text-5xl md:text-6xl font-bold text-gray-900 mb-6 sm:mb-8">
                        Voitures de <span class="text-orange-600">{{ $agency->agency_name }}</span>
                    </h1>
                    <p class="text-base sm:text-lg text-gray-600 leading-relaxed">{{ $cars->total() }} voitures disponibles</p>
                </div>
                <a href="{{ route('public.agency.show', $agency) }}" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2 sm:py-3 border-2 border-orange-600 text-orange-600 hover:bg-orange-600 hover:text-white rounded-xl font-semibold text-sm sm:text-base transition-all duration-200">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à l'agence
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="md:sticky md:top-0 z-40 bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <form method="GET" action="{{ route('public.agency.cars', $agency) }}" class="space-y-3 sm:space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Marque</label>
                        <input type="text" name="brand" value="{{ request('brand') }}" 
                               placeholder="Marque..." 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Modèle</label>
                        <input type="text" name="model" value="{{ request('model') }}" 
                               placeholder="Modèle..." 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Prix min (MAD/jour)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="Prix min..." 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Prix max (MAD/jour)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="Prix max..." 
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold text-sm sm:text-base transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cars Section -->
    <div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8 reveal-section">
        @if($cars->count() > 0)
            <!-- Mobile: Horizontal Scroll -->
            <div class="md:hidden mb-6 sm:mb-8">
                <div class="flex items-center justify-between mb-1 sm:mb-2">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ $cars->total() }} voitures</h2>
                </div>
                <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach($cars as $car)
                        <div onclick="openCarDetailsModal({{ $agency->id }}, {{ $car->id }})" class="car-card group flex-shrink-0 w-[280px] sm:w-[320px] bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 cursor-pointer">
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
                                
                                <!-- Rating Badge -->
                                <div class="absolute bottom-3 left-3 flex items-center gap-1 px-2 py-1 rounded-md bg-white/95 backdrop-blur-sm shadow-sm">
                                    <svg class="w-4 h-4 text-orange-600 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-900">4.8</span>
                                </div>
                            </div>
                            
                            <!-- Car Details -->
                            <div class="p-4">
                                <div class="mb-3">
                                    <h3 class="text-base font-semibold text-gray-900 truncate">{{ $car->brand }} {{ $car->model }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $car->year }} • {{ $car->fuel_type }}</p>
                                </div>
                                
                                <!-- Price + CTA -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($car->client_price_per_day, 0) }}</span>
                                        <span class="text-sm text-gray-600">MAD</span>
                                        <span class="text-sm text-gray-500">/jour</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Desktop: Grid Layout -->
            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($cars as $car)
                    <div onclick="openCarDetailsModal({{ $agency->id }}, {{ $car->id }})" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-gray-300 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                </div>
                            @endif
                            <!-- Rating Badge -->
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm rounded-xl px-3 py-1.5 flex items-center gap-1.5 shadow-lg">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-bold text-gray-900">4.8</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Car Title -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $car->brand }} {{ $car->model }}</h3>
                            
                            <!-- Car Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $car->year }} • {{ $car->fuel_type }} • {{ $car->transmission }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $car->seats }} places • {{ $car->doors }} portes
                                </div>
                            </div>

                            <!-- Features -->
                            @if($car->features && is_array($car->features) && count($car->features) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($car->features, 0, 3) as $feature)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-50 text-gray-700">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                    @if(count($car->features) > 3)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            +{{ count($car->features) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="flex items-end justify-between mb-4 pt-4 border-t border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Prix par jour</p>
                                    <p class="text-3xl font-bold text-[#C2410C]">
                                        {{ number_format($car->client_price_per_day, 0) }} <span class="text-lg">MAD</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3" onclick="event.stopPropagation()">
                                <a href="{{ route('public.car.show', ['agency' => $agency, 'car' => $car]) }}" 
                                   class="flex-1 px-4 py-3 border-2 border-orange-600 text-orange-600 hover:bg-orange-600 hover:text-white rounded-xl text-sm font-semibold text-center transition-all duration-200">
                                    Détails
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $cars->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">Aucune voiture trouvée</h3>
                <p class="text-base sm:text-lg text-gray-600 leading-relaxed mb-6 sm:mb-8">Essayez de modifier vos critères de recherche.</p>
                <a href="{{ route('public.agency.cars', $agency) }}" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 bg-[#C2410C] hover:bg-[#9A3412] text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold transition-colors text-sm sm:text-base shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Réinitialiser les filtres
                </a>
            </div>
        @endif
    </div>
    @include('components.mobile-bottom-nav')
@endsection
