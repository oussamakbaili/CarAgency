@extends('layouts.agence')

@section('title', 'Compte Suspendu')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Compte Suspendu</h1>
            <p class="text-lg text-gray-600 mb-8">Votre compte d'agence a été temporairement suspendu</p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Suspension Details -->
            <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Raison de la suspension</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ auth()->user() && auth()->user()->agency ? auth()->user()->agency->suspension_reason : 'Trop d\'annulations de réservations' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suspension Information -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations sur la suspension</h2>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Date de suspension:</span>
                            <span class="text-sm text-gray-900">
                                {{ auth()->user() && auth()->user()->agency && auth()->user()->agency->suspended_at ? auth()->user()->agency->suspended_at->format('d/m/Y à H:i') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Nombre d'annulations:</span>
                            <span class="text-sm text-gray-900">{{ auth()->user() && auth()->user()->agency ? auth()->user()->agency->cancellation_count : 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Limite autorisée:</span>
                            <span class="text-sm text-gray-900">{{ auth()->user() && auth()->user()->agency ? auth()->user()->agency->max_cancellations : 3 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Consequences -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Conséquences de la suspension</h2>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Vous ne pouvez plus recevoir de nouvelles réservations</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Votre profil n'apparaît plus dans les recherches</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Vous ne pouvez pas modifier vos véhicules</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Les réservations existantes restent valides</span>
                        </div>
                    </div>
                </div>

                <!-- How to Reactivate -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Comment réactiver votre compte ?</h2>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-800 mb-3">Pour réactiver votre compte, vous devez :</p>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-blue-700">
                            <li>Contacter notre équipe de support</li>
                            <li>Expliquer les raisons des annulations</li>
                            <li>Fournir un plan d'amélioration</li>
                            <li>Attendre l'approbation de l'administrateur</li>
                        </ol>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contactez le support</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:support@rentcarplatform.com" class="text-blue-600 hover:text-blue-500">
                                support@rentcarplatform.com
                            </a>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-700">+212 5XX XXX XXX</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <a href="mailto:support@rentcarplatform.com" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-blue-700 transition duration-200">
                        Contacter le support
                    </a>
                    <a href="{{ route('logout') }}" class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-gray-700 transition duration-200">
                        Se déconnecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
