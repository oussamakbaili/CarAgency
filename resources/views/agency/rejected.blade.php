@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Demande Rejetée') }}
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Status Alert Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-red-50 border-l-4 border-red-400">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">Votre demande a été rejetée</h3>
                        <p class="text-red-700 mt-1">
                            Malheureusement, votre demande d'inscription n'a pas été approuvée. Vous trouverez ci-dessous les détails.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Reason Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Raison du Rejet</h3>
            </div>
            <div class="px-6 py-4">
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <p class="text-red-800">
                        {{ auth()->user()->agency->rejection_reason ?? 'Aucune raison spécifique fournie.' }}
                    </p>
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

        <!-- What You Can Do Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Que pouvez-vous faire ?</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 text-sm font-bold">1</div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Vérifiez vos informations</h5>
                            <p class="text-sm text-gray-600">Assurez-vous que toutes vos informations sont correctes et complètes</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 text-sm font-bold">2</div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Vérifiez vos documents</h5>
                            <p class="text-sm text-gray-600">Assurez-vous que tous vos documents sont clairs et lisibles</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 text-sm font-bold">3</div>
                        </div>
                        <div class="ml-4">
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Soumettez une nouvelle demande</h5>
                            <p class="text-sm text-gray-600">Vous pouvez soumettre une nouvelle demande après avoir corrigé les problèmes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
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
                        Si vous avez des questions ou souhaitez plus d'informations, n'hésitez pas à nous contacter
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

        <!-- Action Buttons Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actions</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('agency.register') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouvelle Demande
                    </a>
                    <a href="{{ route('welcome') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Retour à l'Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
