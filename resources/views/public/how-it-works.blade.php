@extends('layouts.public')

@section('title', 'Comment Ça Fonctionne - ToubCar')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Comment Ça <span class="text-orange-600">Fonctionne?</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Louez une voiture en 3 étapes simples
                </p>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Connecting Lines -->
                <div class="hidden md:block absolute top-32 left-0 right-0 h-0.5 bg-orange-600 opacity-20"></div>

                <!-- Step 1 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute -top-6 left-8">
                            <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                1
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="w-20 h-20 bg-orange-100 rounded-xl flex items-center justify-center mb-6 mx-auto">
                                <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Recherchez</h3>
                            <p class="text-gray-600 leading-relaxed text-center">
                                Trouvez la voiture parfaite parmi notre large sélection. Filtrez par ville, dates et type de véhicule pour des résultats personnalisés.
                            </p>
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Recherche par ville et dates</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Filtres avancés</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute -top-6 left-8">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                2
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-xl flex items-center justify-center mb-6 mx-auto">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Réservez</h3>
                            <p class="text-gray-600 leading-relaxed text-center">
                                Sélectionnez vos dates, ajoutez vos informations et confirmez votre réservation en quelques clics. Paiement 100% sécurisé.
                            </p>
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Réservation instantanée</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Paiement sécurisé</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="absolute -top-6 left-8">
                            <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                3
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="w-20 h-20 bg-orange-100 rounded-xl flex items-center justify-center mb-6 mx-auto">
                                <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Conduisez</h3>
                            <p class="text-gray-600 leading-relaxed text-center">
                                Récupérez votre véhicule au lieu convenu et profitez de votre voyage en toute tranquillité avec notre assistance 24/7.
                            </p>
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Récupération facile</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Support 24/7</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Pourquoi Choisir <span class="text-orange-600">ToubCar?</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Les avantages qui font la différence
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Prix Transparents</h3>
                    <p class="text-sm text-gray-600">Pas de frais cachés, tous les coûts sont clairs dès le début</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Assurance Complète</h3>
                    <p class="text-sm text-gray-600">Tous nos véhicules sont assurés pour votre tranquillité</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Disponibilité 24/7</h3>
                    <p class="text-sm text-gray-600">Réservez et récupérez votre véhicule à tout moment</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Support Client</h3>
                    <p class="text-sm text-gray-600">Notre équipe est là pour vous aider à chaque étape</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Prêt à Louer Votre Prochaine Voiture?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Des centaines de véhicules vous attendent. Commencez votre recherche maintenant!
            </p>
            <a href="{{ route('public.home') }}" 
               class="inline-flex items-center justify-center gap-2 bg-white text-orange-600 hover:bg-gray-50 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                Commencer Maintenant
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
@endsection

