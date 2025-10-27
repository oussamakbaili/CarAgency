@extends('layouts.public')

@section('title', 'Nos Agences Partenaires - ToubCar')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-gray-50 to-white py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4">
                    Nos Agences
                    <span class="block text-[#C2410C]">
                        Partenaires
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Découvrez nos agences de confiance partout au Maroc
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white border-b border-gray-200 sticky top-20 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('public.agencies') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Rechercher</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nom de l'agence ou ville..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <!-- City -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Ville</label>
                        <input type="text" 
                               name="city" 
                               value="{{ request('city') }}" 
                               placeholder="Ville..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Note minimum</label>
                        <select name="min_rating" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">Toutes les notes</option>
                            <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ étoiles</option>
                            <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ étoiles</option>
                        </select>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-[#C2410C] hover:bg-[#9A3412] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Agencies Grid -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($agencies->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($agencies as $agency)
                        <div class="group bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            <div class="p-6">
                                <!-- Header with Name and Rating -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">
                                            {{ $agency->agency_name }}
                                        </h3>
                                    </div>
                                    <div class="flex items-center gap-1 px-3 py-1 rounded-lg bg-orange-50">
                                        <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-sm font-bold text-orange-600">4.8</span>
                                    </div>
                                </div>
                                
                                <!-- Info Items -->
                                <div class="space-y-3 mb-6">
                                    <!-- Location -->
                                    <div class="flex items-center gap-3 text-gray-600">
                                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium">{{ $agency->city }}</span>
                                    </div>
                                    
                                    <!-- Phone -->
                                    <div class="flex items-center gap-3 text-gray-600">
                                        <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium">{{ $agency->phone }}</span>
                                    </div>
                                    
                                    <!-- Cars Available -->
                                    <div class="flex items-center gap-3 text-gray-600">
                                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium">{{ $agency->cars_count ?? 0 }} voitures disponibles</span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-3 pt-4 border-t border-gray-100">
                                    <a href="{{ route('public.agency.show', $agency) }}" 
                                       class="flex-1 text-center px-4 py-2.5 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-orange-500 hover:text-orange-600 transition-all">
                                        Détails
                                    </a>
                                    <a href="{{ route('public.agency.cars', $agency) }}" 
                                       class="flex-1 text-center bg-[#C2410C] hover:bg-[#9A3412] text-white px-4 py-2.5 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                        Voir les voitures
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $agencies->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune agence trouvée</h3>
                    <p class="text-gray-600 mb-8">Essayez de modifier vos critères de recherche.</p>
                    <a href="{{ route('public.agencies') }}" 
                       class="inline-flex items-center gap-2 bg-[#C2410C] hover:bg-[#9A3412] text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Voir toutes les agences
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-[#C2410C] py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Vous êtes une agence?
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Rejoignez ToubCar et développez votre activité de location de voitures
            </p>
            <a href="{{ route('register') }}" 
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-orange-600 px-8 py-4 rounded-xl font-semibold text-lg transition-colors shadow-xl">
                Devenir Partenaire
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
@endsection
