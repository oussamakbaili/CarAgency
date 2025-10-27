@extends('layouts.public')

@section('title', 'Voitures de ' . $agency->agency_name)

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-orange-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        Voitures de <span class="text-[#C2410C]">{{ $agency->agency_name }}</span>
                    </h1>
                    <p class="text-lg text-gray-600">{{ $cars->total() }} voitures disponibles</p>
                </div>
                <a href="{{ route('public.agency.show', $agency) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 border-2 border-orange-600 text-orange-600 hover:bg-orange-600 hover:text-white rounded-xl font-semibold transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à l'agence
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="sticky top-0 z-40 bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="GET" action="{{ route('public.agency.cars', $agency) }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Marque</label>
                        <input type="text" name="brand" value="{{ request('brand') }}" 
                               placeholder="Marque..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Modèle</label>
                        <input type="text" name="model" value="{{ request('model') }}" 
                               placeholder="Modèle..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prix min (MAD/jour)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="Prix min..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prix max (MAD/jour)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="Prix max..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cars Grid -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        @if($cars->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($cars as $car)
                    <div onclick="window.location='{{ route('public.car.show', [$agency, $car]) }}'" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                </svg>
                            </div>
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
                                        {{ number_format($car->price_per_day, 0) }} <span class="text-lg">MAD</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3" onclick="event.stopPropagation()">
                                <a href="{{ route('public.car.show', [$agency, $car]) }}" 
                                   class="flex-1 px-4 py-3 border-2 border-orange-600 text-orange-600 hover:bg-orange-600 hover:text-white rounded-xl text-sm font-semibold text-center transition-all duration-200">
                                    Détails
                                </a>
                                <a href="{{ route('public.require-login') }}" 
                                   class="flex-1 bg-[#C2410C] hover:bg-[#9A3412] text-white px-4 py-3 rounded-xl text-sm font-semibold text-center transition-all duration-200 shadow-lg hover:shadow-xl">
                                    Réserver
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
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune voiture trouvée</h3>
                <p class="text-gray-600 mb-8">Essayez de modifier vos critères de recherche.</p>
                <a href="{{ route('public.agency.cars', $agency) }}" 
                   class="inline-flex items-center gap-2 bg-[#C2410C] hover:bg-[#9A3412] text-white px-8 py-4 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Réinitialiser les filtres
                </a>
            </div>
        @endif
    </div>
@endsection
