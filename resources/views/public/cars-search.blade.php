@extends('layouts.public')

@section('title', 'Recherche de Voitures - ToubCar')

@section('content')
    <!-- Search Header -->
    <div class="bg-white border-b border-gray-200 sticky top-16 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <!-- Compact Search Form -->
            <div class="bg-white rounded-full shadow-lg border border-gray-200 p-2">
                <form action="{{ route('public.cars.search') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-2">
                    <!-- Where -->
                    <div class="flex-1 px-4 py-2 border-r border-gray-200">
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Where</label>
                        <input type="text" name="where" placeholder="Marrakesh, Morocco" 
                               value="{{ request('where') }}"
                               class="w-full text-sm text-gray-600 placeholder-gray-400 border-0 p-0 focus:ring-0 focus:outline-none">
                    </div>
                    
                    <!-- Check in -->
                    <div class="flex-1 px-4 py-2 border-r border-gray-200">
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Check in</label>
                        <input type="date" name="check_in" 
                               value="{{ request('check_in') }}"
                               class="w-full text-sm text-gray-600 border-0 p-0 focus:ring-0 focus:outline-none">
                    </div>
                    
                    <!-- Check out -->
                    <div class="flex-1 px-4 py-2 border-r border-gray-200">
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Check out</label>
                        <input type="date" name="check_out" 
                               value="{{ request('check_out') }}"
                               class="w-full text-sm text-gray-600 border-0 p-0 focus:ring-0 focus:outline-none">
                    </div>
                    
                    <!-- Who -->
                    <div class="flex-1 px-4 py-2">
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Who</label>
                        <input type="number" name="passengers" min="1" max="8" 
                               value="{{ request('passengers', 2) }}"
                               class="w-full text-sm text-gray-600 border-0 p-0 focus:ring-0 focus:outline-none">
                    </div>
                    
                    <!-- Search Button -->
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-6 py-2 rounded-full font-semibold flex items-center justify-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Results Info -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    @if(request('where'))
                        Locations de voitures à {{ request('where') }}
                    @else
                        Tous les véhicules disponibles
                    @endif
                </h1>
                
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    @if(request('check_in') && request('check_out'))
                        <span>📅 {{ \Carbon\Carbon::parse(request('check_in'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('check_out'))->format('d M Y') }}</span>
                    @endif
                    @if(request('passengers'))
                        <span>👥 {{ request('passengers') }} passager(s)</span>
                    @endif
                    <span class="ml-auto font-medium">{{ $cars->total() }} véhicule(s) trouvé(s)</span>
                </div>
            </div>

            <!-- Cars Grid - Same design as home page -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($cars as $car)
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
                            
                            <!-- Rating Badge -->
                            <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-lg bg-white shadow-sm">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($car->average_rating, 1) }}</span>
                            </div>
                            
                            <!-- Location Badge -->
                            <div class="absolute top-3 left-3 px-2 py-1 rounded-lg bg-white shadow-sm text-xs font-medium text-gray-700">
                                📍 {{ $car->agency->city }}
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
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-700 font-medium mb-2">Aucun véhicule trouvé</p>
                        <p class="text-gray-500 mb-4">Essayez de modifier vos critères de recherche</p>
                        <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 bg-[#C2410C] hover:bg-[#9A3412] text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Retour à l'accueil
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($cars->hasPages())
                <div class="mt-12">
                    {{ $cars->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

