@extends('layouts.client')

@section('title', 'Tableau de bord - Client')

@section('content')
    <div class="p-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 mb-6 text-white">
            <h1 class="text-2xl font-bold mb-2">
                Bienvenue, {{ auth()->user()->name }} !
            </h1>
            <p class="text-blue-100">
                Découvrez les meilleures voitures de location disponibles et gérez vos réservations facilement.
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Réservations</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Rental::where('user_id', auth()->id())->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Locations Actives</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Rental::where('user_id', auth()->id())->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En Attente</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Rental::where('user_id', auth()->id())->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Terminées</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Rental::where('user_id', auth()->id())->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Rentals -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mes Réservations Récentes</h2>
            </div>
            <div class="p-6">
                @php
                    $recentRentals = \App\Models\Rental::where('user_id', auth()->id())
                        ->with(['car.agency', 'car.category'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if($recentRentals->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentRentals as $rental)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($rental->car->image_url)
                                            <img class="w-12 h-12 rounded-lg object-cover" src="{{ $rental->car->image_url }}" alt="{{ $rental->car->brand }} {{ $rental->car->model }}">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }}</h3>
                                        <p class="text-sm text-gray-500">{{ $rental->car->agency->agency_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($rental->status === 'active') bg-green-100 text-green-800
                                        @elseif($rental->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($rental->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($rental->total_amount, 0) }} MAD</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('client.rentals.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Voir toutes mes réservations →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune réservation</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par explorer nos voitures disponibles.</p>
                        <div class="mt-6">
                            <a href="{{ route('client.cars.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Explorer les voitures
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Cars Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Voitures Recommandées</h2>
                <p class="text-sm text-gray-600">Découvrez les voitures les mieux notées</p>
            </div>
            <div class="p-6">
                @php
                    $topCars = \App\Models\Car::whereHas('agency', function($query) {
                            $query->where('status', 'approved');
                        })
                        ->where('status', 'available')
                        ->with(['agency.user', 'category'])
                        ->withCount(['avis as reviews_count' => function($query) {
                            $query->where('is_public', true);
                        }])
                        ->get()
                        ->map(function($car) {
                            $car->average_rating = $car->getAverageRating();
                            return $car;
                        })
                        ->sortByDesc('average_rating')
                        ->take(6)
                        ->values();
                @endphp

                @if($topCars->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($topCars as $car)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="relative h-32 bg-gray-200 rounded-lg mb-3">
                                    @if($car->image_url)
                                        <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Rating Badge -->
                                    @if($car->getAverageRating() > 0)
                                        <div class="absolute top-2 right-2 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                            <x-star-rating :rating="$car->getAverageRating()" size="w-3 h-3" />
                                            <span class="text-xs font-semibold text-gray-900 ml-1">{{ number_format($car->getAverageRating(), 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $car->brand }} {{ $car->model }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $car->year }} • {{ $car->agency->agency_name }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-blue-600">{{ number_format($car->price_per_day, 0) }} MAD/jour</span>
                                    <a href="{{ route('public.car.show', ['agency' => $car->agency, 'car' => $car]) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir détails →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('client.cars.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Voir toutes les voitures →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune voiture disponible</h3>
                        <p class="mt-1 text-sm text-gray-500">Aucune voiture disponible pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
