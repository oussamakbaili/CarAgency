@extends('layouts.public')

@section('title', 'Comment Ça Fonctionne - ToubCar')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gray-50 py-10 sm:py-16 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl sm:text-5xl md:text-6xl font-bold text-gray-900 mb-4 sm:mb-6">
                    Comment Ça <span class="text-orange-600">Fonctionne?</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto">
                    Louez une voiture en 3 étapes simples
                </p>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <div class="py-10 sm:py-16 bg-white reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-12 relative">
                <!-- Connecting Lines -->
                <div class="hidden md:block absolute top-24 sm:top-32 left-0 right-0 h-0.5 bg-orange-600 opacity-20"></div>

                <!-- Step 1 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute -top-4 sm:-top-6 left-6 sm:left-8">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                                1
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-orange-100 rounded-xl flex items-center justify-center mb-4 sm:mb-6 mx-auto">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 text-center">Recherchez</h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed text-center">
                                Trouvez la voiture parfaite parmi notre large sélection. Filtrez par ville, dates et type de véhicule pour des résultats personnalisés.
                            </p>
                            <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-3">
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Recherche par ville et dates</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Filtres avancés</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Photos et détails complets</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute -top-4 sm:-top-6 left-6 sm:left-8">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                                2
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-100 rounded-xl flex items-center justify-center mb-4 sm:mb-6 mx-auto">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 text-center">Réservez</h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed text-center">
                                Sélectionnez vos dates, ajoutez vos informations et confirmez votre réservation en quelques clics. Paiement 100% sécurisé.
                            </p>
                            <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-3">
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Réservation instantanée</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Paiement sécurisé</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Confirmation immédiate</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute -top-4 sm:-top-6 left-6 sm:left-8">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                                3
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-orange-100 rounded-xl flex items-center justify-center mb-4 sm:mb-6 mx-auto">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 text-center">Conduisez</h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed text-center">
                                Récupérez votre véhicule au lieu convenu et profitez de votre voyage en toute tranquillité avec notre assistance 24/7.
                            </p>
                            <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-3">
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Récupération facile</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Support 24/7</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Assurance complète</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-10 sm:py-16 bg-gray-50 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                    Pourquoi Choisir <span class="text-orange-600">ToubCar?</span>
                </h2>
                <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto">
                    Les avantages qui font la différence
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div class="bg-white rounded-xl p-5 sm:p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-3 sm:mb-4 mx-auto">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2">Prix Transparents</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Pas de frais cachés, tous les coûts sont clairs dès le début</p>
                </div>

                <div class="bg-white rounded-xl p-5 sm:p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-3 sm:mb-4 mx-auto">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2">Assurance Complète</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Tous nos véhicules sont assurés pour votre tranquillité</p>
                </div>

                <div class="bg-white rounded-xl p-5 sm:p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-3 sm:mb-4 mx-auto">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2">Disponibilité 24/7</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Réservez et récupérez votre véhicule à tout moment</p>
                </div>

                <div class="bg-white rounded-xl p-5 sm:p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-3 sm:mb-4 mx-auto">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2">Support Client</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Notre équipe est là pour vous aider à chaque étape</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 py-10 sm:py-16 reveal-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4">
                Prêt à Louer Votre Prochaine Voiture?
            </h2>
            <p class="text-sm sm:text-lg text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
                Des centaines de véhicules vous attendent. Commencez votre recherche maintenant!
            </p>
            <a href="{{ route('public.home') }}" 
               class="inline-flex items-center gap-1.5 sm:gap-2 bg-white hover:bg-gray-100 text-orange-600 px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold transition-colors text-sm sm:text-base">
                Commencer Maintenant
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
    @include('components.mobile-bottom-nav')
@endsection

