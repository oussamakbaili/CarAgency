<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="flex items-center justify-center">
                        <div class="text-center max-w-4xl w-full">
                            <!-- Progress Steps -->
                            <div class="flex justify-center items-center space-x-2 md:space-x-4 mb-10">
                                <!-- Step 1: Soumis -->
                                <div class="flex items-center">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-xs md:text-sm font-semibold text-green-600">Soumis</span>
                                </div>
                                
                                <!-- Connector 1 -->
                                <div class="w-12 md:w-20 h-1 bg-gradient-to-r from-green-400 to-[#C2410C]"></div>
                                
                                <!-- Step 2: En Révision -->
                                <div class="flex items-center">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-[#C2410C] to-[#9A3412] rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-xs md:text-sm font-semibold text-[#C2410C]">En Révision</span>
                                </div>
                                
                                <!-- Connector 2 -->
                                <div class="w-12 md:w-20 h-1 bg-gray-200"></div>
                                
                                <!-- Step 3: Approuvé -->
                                <div class="flex items-center">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-200 rounded-full flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-xs md:text-sm font-semibold text-gray-400">Approuvé</span>
                                </div>
                            </div>

                            <!-- Status Icon -->
                            <div class="mb-8">
                                <div class="mx-auto w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-[#C2410C]/10 to-[#9A3412]/10 rounded-full flex items-center justify-center border-4 border-[#C2410C]/20">
                                    <svg class="h-12 w-12 md:h-14 md:w-14 text-[#C2410C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Main Content -->
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Compte en Attente d'Approbation</h2>
                            <p class="text-gray-600 mb-8 text-base md:text-lg max-w-2xl mx-auto">
                                Votre compte est actuellement en cours d'examen par notre équipe. 
                                Nous vous notifierons dès que la vérification sera terminée.
                            </p>

                            <!-- Estimated Time -->
                            <div class="bg-gradient-to-br from-[#C2410C]/10 to-[#9A3412]/5 rounded-2xl border border-[#C2410C]/20 p-6 mb-8 shadow-sm">
                                <div class="flex items-center justify-center space-x-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-full">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-[#C2410C] font-semibold text-base md:text-lg">
                                        Temps d'attente estimé: 24-48 heures
                                    </p>
                                </div>
                            </div>

                            <!-- Required Documents -->
                            <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 rounded-2xl border border-[#C2410C]/20 p-6 md:p-8 mb-8 shadow-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-full">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-lg md:text-xl font-bold text-gray-900 mb-4">
                                            Documents Requis
                                        </h4>
                                        <ul class="space-y-3 text-gray-700">
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <svg class="h-5 w-5 text-[#C2410C] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm md:text-base">Copie du registre de commerce</span>
                                            </li>
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <svg class="h-5 w-5 text-[#C2410C] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm md:text-base">Documents d'identification</span>
                                            </li>
                                            <li class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <svg class="h-5 w-5 text-[#C2410C] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm md:text-base">Liste des véhicules disponibles</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                                <h4 class="text-xl font-bold text-gray-900 mb-3">Besoin d'aide ?</h4>
                                <p class="text-gray-600 mb-6 text-sm md:text-base">
                                    Notre équipe de support est disponible pour répondre à vos questions
                                </p>
                                <div class="flex flex-col sm:flex-row justify-center gap-4">
                                    <a href="{{ route('agence.support.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#C2410C] to-[#9A3412] text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition duration-200 font-semibold">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Contacter le Support
                                    </a>
                                    <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:border-[#C2410C] hover:text-[#C2410C] transition duration-200 font-semibold">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        FAQ
                                    </a>
                                </div>
                            </div>

                            <!-- Auto Refresh Notice -->
                            <div class="mt-8 text-sm text-gray-500 text-center">
                                Cette page s'actualise automatiquement toutes les 30 secondes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
