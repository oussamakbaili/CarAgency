<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Choisissez votre type de compte') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Registration Card -->
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:border-indigo-500 transition-colors">
                            <h3 class="text-xl font-semibold mb-4">{{ __('Je suis un Client') }}</h3>
                            <p class="text-gray-600 mb-4">{{ __('Créez un compte client pour louer des voitures auprès de nos agences partenaires.') }}</p>
                            <ul class="text-sm text-gray-600 mb-6">
                                <li class="flex items-center mb-2">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    {{ __('Accès à toutes les voitures disponibles') }}
                                </li>
                                <li class="flex items-center mb-2">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    {{ __('Réservation en ligne simple') }}
                                </li>
                            </ul>
                            <a href="{{ route('register.client') }}" class="inline-block w-full px-4 py-2 bg-indigo-600 text-white text-center font-semibold rounded-md hover:bg-indigo-700 transition-colors">
                                {{ __('S\'inscrire comme Client') }}
                            </a>
                        </div>

                        <!-- Agency Registration Card -->
                        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:border-indigo-500 transition-colors">
                            <h3 class="text-xl font-semibold mb-4">{{ __('Je suis une Agence') }}</h3>
                            <p class="text-gray-600 mb-4">{{ __('Créez un compte agence pour gérer votre flotte de véhicules et vos locations.') }}</p>
                            <ul class="text-sm text-gray-600 mb-6">
                                <li class="flex items-center mb-2">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    {{ __('Gestion complète de votre flotte') }}
                                </li>
                                <li class="flex items-center mb-2">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    {{ __('Tableau de bord professionnel') }}
                                </li>
                            </ul>
                            <a href="{{ route('register.agency') }}" class="inline-block w-full px-4 py-2 bg-indigo-600 text-white text-center font-semibold rounded-md hover:bg-indigo-700 transition-colors">
                                {{ __('S\'inscrire comme Agence') }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            {{ __('Déjà inscrit?') }}
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('Connectez-vous ici') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

