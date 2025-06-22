<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-8">
                    <div class="flex items-center justify-center">
                        <div class="text-center max-w-3xl">
                            <!-- Progress Steps -->
                            <div class="flex justify-center items-center space-x-4 mb-8">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">Soumis</span>
                                </div>
                                <div class="w-16 h-1 bg-yellow-200"></div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center animate-pulse">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">En Révision</span>
                                </div>
                                <div class="w-16 h-1 bg-gray-200"></div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-400">Approuvé</span>
                                </div>
                            </div>

                            <!-- Status Icon -->
                            <div class="mb-6">
                                <div class="mx-auto w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="h-10 w-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Main Content -->
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Compte en Attente d'Approbation</h2>
                            <p class="text-gray-600 mb-8 text-lg">
                                Votre compte est actuellement en cours d'examen par notre équipe. 
                                Nous vous notifierons dès que la vérification sera terminée.
                            </p>

                            <!-- Estimated Time -->
                            <div class="bg-blue-50 rounded-xl p-4 mb-8">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-blue-700 font-medium">
                                        Temps d'attente estimé: 24-48 heures
                                    </p>
                                </div>
                            </div>

                            <!-- Required Documents -->
                            <div class="bg-yellow-50 rounded-xl border border-yellow-100 p-6 mb-8">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4 text-left">
                                        <h4 class="text-lg font-semibold text-yellow-800 mb-2">
                                            Documents Requis
                                        </h4>
                                        <ul class="space-y-2 text-yellow-700">
                                            <li class="flex items-center">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                </svg>
                                                Copie du registre de commerce
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                </svg>
                                                Documents d'identification
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                </svg>
                                                Liste des véhicules disponibles
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3">Besoin d'aide ?</h4>
                                <p class="text-gray-600 mb-4">
                                    Notre équipe de support est disponible pour répondre à vos questions
                                </p>
                                <div class="flex justify-center space-x-4">
                                    <a href="mailto:support@example.com" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Contacter le Support
                                    </a>
                                    <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        FAQ
                                    </a>
                                </div>
                            </div>

                            <!-- Auto Refresh Notice -->
                            <div class="mt-8 text-sm text-gray-500">
                                Cette page s'actualise automatiquement toutes les 30 secondes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>