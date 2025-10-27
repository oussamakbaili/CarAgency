@extends('layouts.public')

@section('title', $car->brand . ' ' . $car->model . ' - ' . $agency->agency_name . ' - ToubCar')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Column - Car Images and Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Car Image Gallery -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="relative">
                        @if($car->image_url)
                            <img src="{{ $car->image_url }}" 
                                 alt="{{ $car->brand }} {{ $car->model }}" 
                                 class="w-full h-96 md:h-[500px] object-cover">
                        @else
                            <div class="w-full h-96 md:h-[500px] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Photo du véhicule</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Rating Badge -->
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-xl px-4 py-2 flex items-center gap-2 shadow-lg">
                            <x-star-rating :rating="$car->getAverageRating()" size="w-5 h-5" />
                            <span class="font-semibold text-gray-900">{{ number_format($car->getAverageRating(), 1) }}</span>
                            <span class="text-xs text-gray-500">({{ $car->getReviewsCount() }})</span>
                        </div>
                    </div>
                </div>

                <!-- Car Specifications -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-2xl font-bold text-gray-900">Spécifications</h2>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700">Marque</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->brand }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700">Modèle</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->model }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700">Année</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->year }}</span>
                                </div>
                                <div class="flex justify-between py-3">
                                    <span class="text-sm font-semibold text-gray-700">Carburant</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->fuel_type }}</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700">Transmission</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->transmission }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700">Places</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->seats }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-semibold text-gray-700">Portes</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->doors }}</span>
                                </div>
                                <div class="flex justify-between py-3">
                                    <span class="text-sm font-semibold text-gray-700">Couleur</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $car->color ?? 'Non spécifiée' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                @if($car->features && is_array($car->features) && count($car->features) > 0)
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-2xl font-bold text-gray-900">Équipements</h2>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($car->features as $feature)
                                    <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-blue-800">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Description -->
                @if($car->description)
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-2xl font-bold text-gray-900">Description</h2>
                        </div>
                        <div class="p-8">
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $car->description }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Booking Sidebar -->
            <div class="space-y-8">
                <!-- Price and Booking Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <div class="text-4xl font-bold text-orange-600 mb-2">
                                {{ number_format($car->price_per_day, 0) }} MAD
                            </div>
                            <div class="text-lg text-gray-500">par jour</div>
                        </div>

                        <!-- Agency Info -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-8">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $agency->agency_name }}</h3>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-700">4.8</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-semibold">Disponible</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-4">
                            @auth
                                @if(auth()->user() && auth()->user()->role === 'client')
                                    <a href="{{ route('client.rentals.create', $car) }}" 
                                       class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-4 rounded-xl font-bold text-center block transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        Réserver maintenant
                                    </a>
                                @else
                                    <a href="{{ route('public.require-login') }}" 
                                       class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-4 rounded-xl font-bold text-center block transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        Réserver maintenant
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('public.require-login') }}" 
                                   class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-4 rounded-xl font-bold text-center block transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    Réserver maintenant
                                </a>
                            @endauth
                            
                            <a href="{{ route('public.agency.show', $agency) }}" 
                               class="w-full bg-white hover:bg-gray-50 text-gray-900 border-2 border-gray-300 hover:border-gray-400 px-6 py-4 rounded-xl font-bold text-center block transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                Voir l'agence
                            </a>
                        </div>

                        @guest
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-500">
                                Vous devez être connecté pour effectuer une réservation
                            </p>
                        </div>
                        @endguest
                    </div>
                </div>

                <!-- Agency Contact -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl font-bold text-gray-900">Contact de l'agence</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Téléphone</p>
                                <p class="font-semibold text-gray-900">{{ $agency->phone }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-semibold text-gray-900">{{ $agency->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Adresse</p>
                                <p class="font-semibold text-gray-900">{{ $agency->address }}, {{ $agency->city }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-2xl font-bold text-gray-900">Avis Clients</h2>
                <p class="text-gray-600 mt-2">Découvrez ce que pensent les autres clients de ce véhicule</p>
            </div>
            
            <div class="p-8">
                <!-- Rating Summary -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gray-900">{{ number_format($car->getAverageRating(), 1) }}</div>
                            <x-star-rating :rating="$car->getAverageRating()" size="w-6 h-6" />
                            <div class="text-sm text-gray-500 mt-1">{{ $car->getReviewsCount() }} avis</div>
                        </div>
                    </div>
                    
                    @auth
                        <!-- Review Form -->
                        <div class="w-full max-w-md">
                            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="review_type" value="car">
                                <input type="hidden" name="car_id" value="{{ $car->id }}">
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Votre note</label>
                                    <div x-data="{ rating: 0 }" @rating-changed.window="rating = $event.detail">
                                        <x-star-rating :rating="0" size="w-6 h-6" :interactive="true" />
                                    </div>
                                    <input type="hidden" name="rating" x-model="rating" required>
                                </div>
                                
                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Votre commentaire (optionnel)</label>
                                    <textarea 
                                        id="comment" 
                                        name="comment" 
                                        rows="3" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        placeholder="Partagez votre expérience avec ce véhicule..."
                                    ></textarea>
                                </div>
                                
                                <button 
                                    type="submit" 
                                    class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors font-medium"
                                >
                                    Publier mon avis
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Connectez-vous pour laisser un avis</p>
                            <a href="{{ route('login') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                Se connecter
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Reviews List -->
                <div id="reviews-container" class="space-y-6">
                    @forelse($car->approvedReviews()->with('user')->latest()->limit(5)->get() as $review)
                        <div class="border-b border-gray-100 pb-6 last:border-b-0">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <span class="text-orange-600 font-semibold text-sm">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <x-star-rating :rating="$review->rating" size="w-4 h-4" />
                                            <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($review->comment)
                                <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-gray-500">Aucun avis pour le moment</p>
                            <p class="text-sm text-gray-400 mt-1">Soyez le premier à laisser un avis !</p>
                        </div>
                    @endforelse
                </div>

                @if($car->getReviewsCount() > 5)
                    <div class="text-center mt-6">
                        <button class="text-orange-600 hover:text-orange-700 font-medium">
                            Voir tous les avis ({{ $car->getReviewsCount() }})
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Similar Cars Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Véhicules Similaires</h2>
                <p class="text-lg text-gray-600">Découvrez d'autres véhicules avec la même transmission et type</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($agency->cars()->where('id', '!=', $car->id)->where('transmission', $car->transmission)->where('category_id', $car->category_id)->take(4)->get() as $similarCar)
                    <div onclick="window.location='{{ route('public.car.show', [$agency, $similarCar]) }}'" class="group bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                        <!-- Car Image -->
                        <div class="relative h-48 bg-gray-100">
                            @if($similarCar->image_url)
                                <img src="{{ $similarCar->image_url }}" alt="{{ $similarCar->brand }} {{ $similarCar->model }}" 
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
                                <span class="text-sm font-semibold text-gray-900">4.8</span>
                            </div>
                        </div>
                        
                        <!-- Car Details -->
                        <div class="p-5">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $similarCar->brand }}</h3>
                                <p class="text-sm text-gray-600">{{ $similarCar->model }}</p>
                            </div>
                            
                            <!-- Features -->
                            <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <span>{{ ucfirst($similarCar->transmission ?? 'Auto') }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>{{ $similarCar->seats ?? 5 }}</span>
                                </div>
                            </div>
                            
                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($similarCar->price_per_day, 0) }} MAD</div>
                                    <div class="text-sm text-gray-500">par jour</div>
                                </div>
                                <div class="text-orange-600 font-semibold text-sm">
                                    Voir détails →
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Aucun autre véhicule disponible pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Smooth scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        // Add scroll reveal animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards
        document.querySelectorAll('.bg-white').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush