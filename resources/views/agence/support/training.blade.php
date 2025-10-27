@extends('layouts.agence')

@section('title', 'Formation & Documentation')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Formation & Documentation</h1>
            <p class="text-gray-600">Apprenez à utiliser toutes les fonctionnalités de la plateforme</p>
        </div>
        <a href="{{ route('agence.support.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            ← Retour au Support
        </a>
    </div>

    <!-- Quick Access -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="#getting-started" class="bg-blue-50 p-4 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-900">Guide de Démarrage</h3>
                    <p class="text-sm text-blue-700">Premiers pas</p>
                </div>
            </div>
        </a>

        <a href="#tutorials" class="bg-green-50 p-4 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-green-900">Tutoriels Vidéo</h3>
                    <p class="text-sm text-green-700">Apprentissage visuel</p>
                </div>
            </div>
        </a>

        <a href="#documentation" class="bg-purple-50 p-4 rounded-lg border border-purple-200 hover:bg-purple-100 transition-colors">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-purple-900">Documentation</h3>
                    <p class="text-sm text-purple-700">Guides détaillés</p>
                </div>
            </div>
        </a>

        <a href="#webinars" class="bg-orange-50 p-4 rounded-lg border border-orange-200 hover:bg-orange-100 transition-colors">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-orange-900">Webinaires</h3>
                    <p class="text-sm text-orange-700">Formations en direct</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Getting Started Section -->
    <div id="getting-started" class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Guide de Démarrage</h2>
            <p class="text-gray-600">Apprenez les bases de la plateforme en quelques étapes</p>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">1</span>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Configuration de votre profil</h3>
                        <p class="text-gray-600 text-sm mt-1">Complétez les informations de votre agence, ajoutez vos documents et configurez vos heures d'ouverture.</p>
                        <a href="{{ route('agence.profile.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Configurer maintenant →</a>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">2</span>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Ajout de votre flotte</h3>
                        <p class="text-gray-600 text-sm mt-1">Ajoutez vos véhicules avec photos, descriptions et tarifs. Configurez la disponibilité et les règles de location.</p>
                        <a href="{{ route('agence.cars.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ajouter un véhicule →</a>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">3</span>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Configuration des tarifs</h3>
                        <p class="text-gray-600 text-sm mt-1">Définissez vos tarifs de base, configurez la tarification dynamique et créez des offres promotionnelles.</p>
                        <a href="{{ route('agence.pricing.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Configurer les tarifs →</a>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">4</span>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Gestion des réservations</h3>
                        <p class="text-gray-600 text-sm mt-1">Apprenez à gérer les demandes de réservation, les approbations et le suivi des locations.</p>
                        <a href="{{ route('agence.bookings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Gérer les réservations →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Tutorials -->
    <div id="tutorials" class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Tutoriels Vidéo</h2>
            <p class="text-gray-600">Apprenez en regardant nos tutoriels vidéo</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-100 h-48 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900">Configuration initiale</h3>
                        <p class="text-sm text-gray-600 mt-1">Comment configurer votre compte agence</p>
                        <span class="inline-block mt-2 text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">5 min</span>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-100 h-48 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900">Gestion de la flotte</h3>
                        <p class="text-sm text-gray-600 mt-1">Ajouter et gérer vos véhicules</p>
                        <span class="inline-block mt-2 text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">8 min</span>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-100 h-48 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900">Tarification avancée</h3>
                        <p class="text-sm text-gray-600 mt-1">Configuration des tarifs dynamiques</p>
                        <span class="inline-block mt-2 text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">12 min</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentation -->
    <div id="documentation" class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Documentation</h2>
            <p class="text-gray-600">Guides détaillés pour chaque fonctionnalité</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <h3 class="font-medium text-gray-900">Guide de l'Administrateur</h3>
                            <p class="text-sm text-gray-600">Tout ce que vous devez savoir pour administrer votre agence</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-medium">Télécharger PDF</button>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <h3 class="font-medium text-gray-900">API Documentation</h3>
                            <p class="text-sm text-gray-600">Intégration avec des systèmes tiers</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-medium">Consulter</button>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <h3 class="font-medium text-gray-900">FAQ Technique</h3>
                            <p class="text-sm text-gray-600">Réponses aux questions techniques fréquentes</p>
                        </div>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 font-medium">Consulter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Webinars -->
    <div id="webinars" class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Prochains Webinaires</h2>
            <p class="text-gray-600">Formations en direct avec nos experts</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Optimisation des revenus</h3>
                            <p class="text-sm text-gray-600">Apprenez à maximiser vos revenus avec la tarification dynamique</p>
                            <p class="text-xs text-gray-500 mt-1">15 Septembre 2024 - 14h00</p>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            S'inscrire
                        </button>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Gestion des clients</h3>
                            <p class="text-sm text-gray-600">Stratégies pour fidéliser et satisfaire vos clients</p>
                            <p class="text-xs text-gray-500 mt-1">22 Septembre 2024 - 14h00</p>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            S'inscrire
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection
