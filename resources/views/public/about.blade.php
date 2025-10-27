@extends('layouts.public')

@section('title', 'À Propos de ToubCar')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    À Propos de <span class="text-orange-600">ToubCar</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    La plateforme de location de voitures la plus innovante au Maroc
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Notre <span class="text-orange-600">Mission</span>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        ToubCar est la plateforme de location de voitures la plus innovante au Maroc. Nous connectons les voyageurs avec une large sélection de véhicules de qualité, tout en offrant aux propriétaires une opportunité unique de monétiser leurs véhicules.
                    </p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Notre mission est de rendre la location de voiture simple, sécurisée et accessible à tous, tout en créant une communauté de confiance entre propriétaires et locataires.
                    </p>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-orange-50 rounded-xl">
                            <div class="text-3xl font-bold text-orange-600 mb-2">500+</div>
                            <div class="text-sm text-gray-700 font-medium">Véhicules</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="text-3xl font-bold text-blue-600 mb-2">10K+</div>
                            <div class="text-sm text-gray-700 font-medium">Clients Satisfaits</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-xl">
                            <div class="text-3xl font-bold text-orange-600 mb-2">24/7</div>
                            <div class="text-sm text-gray-700 font-medium">Support</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800" 
                             alt="About ToubCar" 
                             class="w-full h-[500px] object-cover">
                    </div>
                    <div class="absolute -bottom-8 -left-8 bg-white rounded-xl shadow-lg p-6 max-w-xs">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">100% Sécurisé</div>
                                <div class="text-sm text-gray-600">Assurance complète</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Nos <span class="text-orange-600">Valeurs</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Ce qui nous distingue et guide nos actions au quotidien
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sécurité</h3>
                    <p class="text-gray-600 leading-relaxed">
                        La sécurité de nos clients et partenaires est notre priorité absolue. Tous nos véhicules sont vérifiés et assurés.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Nous utilisons les dernières technologies pour offrir une expérience de location moderne et intuitive.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Communauté</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Nous créons une communauté de confiance entre locataires et propriétaires, basée sur le respect mutuel.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Prêt à Commencer?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Rejoignez des milliers de voyageurs qui font confiance à ToubCar pour leurs besoins de location
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.home') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-white text-orange-600 hover:bg-gray-50 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                    Trouver une Voiture
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('register.agency') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white text-white hover:bg-white hover:text-orange-600 px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-200">
                    Devenir Partenaire
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection

