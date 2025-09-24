@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('En Attente d\'Approbation') }}
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Status Alert Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-yellow-50 border-l-4 border-yellow-400">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-yellow-800">Votre demande est en cours d'examen</h3>
                        <p class="text-yellow-700 mt-1">
                            Notre équipe examine actuellement votre dossier. Vous recevrez une réponse par email dans les plus brefs délais.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agency Information Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de votre Agence</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Informations Générales</h4>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium">Nom:</span> {{ auth()->user()->agency->agency_name ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">Email:</span> {{ auth()->user()->agency->email ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">Téléphone:</span> {{ auth()->user()->agency->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Adresse</h4>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium">Adresse:</span> {{ auth()->user()->agency->address ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">Ville:</span> {{ auth()->user()->agency->city ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">RC:</span> {{ auth()->user()->agency->commercial_register_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Responsable</h4>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium">Nom:</span> {{ auth()->user()->agency->responsable_name ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">Poste:</span> {{ auth()->user()->agency->responsable_position ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">Téléphone:</span> {{ auth()->user()->agency->responsable_phone ?? 'N/A' }}</p>
                            <p class="text-sm"><span class="font-medium">CIN:</span> {{ auth()->user()->agency->responsable_identity_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Status Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Documents Soumis</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm font-semibold text-gray-900">Registre de Commerce</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-green-600 font-medium">Soumis</span>
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm font-semibold text-gray-900">Document d'Identité</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-green-600 font-medium">Soumis</span>
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm font-semibold text-gray-900">Document Fiscal</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-green-600 font-medium">Soumis</span>
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Prochaines Étapes</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600 text-sm font-bold">1</div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Examen du dossier</h5>
                            <p class="text-sm text-gray-600">Notre équipe examine votre dossier et vos documents</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600 text-sm font-bold">2</div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Notification par email</h5>
                            <p class="text-sm text-gray-600">Vous recevez un email de confirmation ou de demande d'informations supplémentaires</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600 text-sm font-bold">3</div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Accès au dashboard</h5>
                            <p class="text-sm text-gray-600">Une fois approuvée, vous pourrez accéder à votre tableau de bord</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Besoin d'Aide ?</h3>
            </div>
            <div class="px-6 py-4">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-900 mb-2">Notre équipe est là pour vous</h5>
                    <p class="text-gray-600 mb-4">
                        Si vous avez des questions, n'hésitez pas à nous contacter
                    </p>
                    <a href="mailto:support@caragency.com" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        support@caragency.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection