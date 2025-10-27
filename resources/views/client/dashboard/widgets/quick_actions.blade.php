<!-- Smart Quick Actions Widget -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Actions Rapides Intelligentes
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Browse Cars -->
                        <a href="{{ route('client.agencies.index') }}" 
                           class="group flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-all duration-200 hover:shadow-md">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-900 group-hover:text-blue-900">Agences de Location</h3>
                                <p class="text-sm text-gray-600 group-hover:text-blue-700">Découvrir les meilleures agences</p>
                            </div>
                            <div class="ml-auto">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('client.cars.index') }}" 
                           class="group flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-all duration-200 hover:shadow-md">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-900 group-hover:text-green-900">Tous les Véhicules</h3>
                                <p class="text-sm text-gray-600 group-hover:text-green-700">{{ $availableCars }} disponibles</p>
                            </div>
                            <div class="ml-auto">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

            <!-- My Rentals -->
            <a href="{{ route('client.rentals.index') }}" 
               class="group flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-all duration-200 hover:shadow-md">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-gray-900 group-hover:text-green-900">Mes Locations</h3>
                    <p class="text-sm text-gray-600 group-hover:text-green-700">{{ $totalRentals }} au total</p>
                </div>
                <div class="ml-auto">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Profile Management -->
            <a href="{{ route('client.profile.index') }}" 
               class="group flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-all duration-200 hover:shadow-md">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-gray-900 group-hover:text-purple-900">Mon Profil</h3>
                    <p class="text-sm text-gray-600 group-hover:text-purple-700">
                        {{ $quickActions['profileComplete'] ? 'Complet' : 'À compléter' }}
                    </p>
                </div>
                <div class="ml-auto">
                    @if(!$quickActions['profileComplete'])
                        <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                    @else
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                </div>
            </a>

            <!-- Support -->
            <a href="#" 
               class="group flex items-center p-4 {{ $openSupportTickets > 0 ? 'bg-red-50 hover:bg-red-100' : 'bg-gray-50 hover:bg-gray-100' }} rounded-lg transition-all duration-200 hover:shadow-md">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 {{ $openSupportTickets > 0 ? 'bg-red-100 group-hover:bg-red-200' : 'bg-gray-100 group-hover:bg-gray-200' }} rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 {{ $openSupportTickets > 0 ? 'text-red-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="font-medium text-gray-900 group-hover:text-gray-900">Support</h3>
                    <p class="text-sm text-gray-600 group-hover:text-gray-700">
                        {{ $openSupportTickets > 0 ? $openSupportTickets . ' tickets ouverts' : 'Aide disponible' }}
                    </p>
                </div>
                <div class="ml-auto">
                    @if($openSupportTickets > 0)
                        <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                    @else
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Smart Recommendations -->
@if($favoriteCars->count() > 0 || $upcomingRentals->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Upcoming Rentals -->
    @if($upcomingRentals->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Locations à Venir
            </h2>
            <a href="{{ route('client.rentals.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Voir tout →
            </a>
        </div>
        
        <div class="space-y-3">
            @foreach($upcomingRentals as $rental)
            <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-shrink-0">
                    @if($rental->car->image)
                        <img src="{{ $rental->car->image_url }}" 
                             alt="{{ $rental->car->brand }} {{ $rental->car->model }}" 
                             class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <h3 class="font-medium text-gray-900 truncate">
                        {{ $rental->car->brand }} {{ $rental->car->model }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                    </p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ number_format($rental->total_price, 0) }}€
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $rental->start_date->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recommended Cars -->
    @if($favoriteCars->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                </svg>
                Véhicules Recommandés
            </h2>
            <a href="{{ route('client.cars.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Voir tout →
            </a>
        </div>
        
        <div class="space-y-3">
            @foreach($favoriteCars->take(3) as $car)
            <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-shrink-0">
                    @if($car->image)
                        <img src="{{ $car->image_url }}" 
                             alt="{{ $car->brand }} {{ $car->model }}" 
                             class="w-12 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <h3 class="font-medium text-gray-900 truncate">
                        {{ $car->brand }} {{ $car->model }}
                    </h3>
                    <p class="text-sm text-gray-600">{{ $car->agency->user->name ?? 'N/A' }}</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ number_format($car->price_per_day, 0) }}€/jour
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('client.cars.show', $car->id) }}" 
                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors">
                        Voir
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endif

<!-- Profile Completion Alert -->
@if(!$quickActions['profileComplete'])
<div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-6 mb-8">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium text-yellow-800">Profil Incomplet</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Complétez votre profil pour une meilleure expérience de location et des recommandations personnalisées.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('client.profile.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 transition-colors">
                    Compléter mon profil
                </a>
            </div>
        </div>
    </div>
</div>
@endif
