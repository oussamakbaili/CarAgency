@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Demande Soumise') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-2">Demande Soumise avec Succès !</h3>
                
                <p class="text-gray-600 mb-6">
                    Votre demande d'inscription en tant qu'agence partenaire a été soumise avec succès. 
                    Notre équipe va examiner votre dossier et vous contactera dans les plus brefs délais.
                </p>

                <!-- Information Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-900">Email de Confirmation</h4>
                                <p class="text-xs text-blue-700">Vérifiez votre boîte email</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-900">Délai de Traitement</h4>
                                <p class="text-xs text-yellow-700">Réponse sous 48 heures</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Prochaines Étapes</h4>
                    <div class="space-y-3 text-left">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-600 text-sm font-medium">1</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">Notre équipe examine votre dossier et vos documents</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-600 text-sm font-medium">2</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">Vous recevez un email de confirmation ou de demande d'informations supplémentaires</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-600 text-sm font-medium">3</div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700">Une fois approuvée, vous recevez vos identifiants de connexion</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-indigo-900 mb-2">Besoin d'Aide ?</h4>
                    <p class="text-sm text-indigo-700">
                        Si vous avez des questions, n'hésitez pas à nous contacter à 
                        <a href="mailto:support@caragency.com" class="font-medium underline">support@caragency.com</a>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-center space-x-4">
                    <a href="{{ route('agency.register') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Nouvelle Demande
                    </a>
                    <a href="{{ route('welcome') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Retour à l'Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
