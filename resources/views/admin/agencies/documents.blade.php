@extends('layouts.admin')

@section('header', 'Vérification des Documents')

@section('content')
<div class="mb-6">
    <h2 class="text-lg font-semibold text-gray-900">Documents en Attente de Vérification</h2>
    <p class="text-sm text-gray-600 mt-1">Vérifiez et validez les documents soumis par les agences</p>
</div>

<!-- Search and Filters -->
<div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par nom d'agence..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Rechercher
        </button>
        <a href="{{ route('admin.agencies.documents') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Réinitialiser
        </a>
    </form>
</div>

<!-- Agencies with Documents -->
<div class="space-y-6">
    @forelse($agencies as $agency)
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $agency->agency_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $agency->responsable_name }} • {{ $agency->email }}</p>
                        <p class="text-sm text-gray-500">{{ $agency->city }} • Inscrit le {{ $agency->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.agencies.show', $agency) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Voir Détails
                    </a>
                </div>
            </div>

            <!-- Documents Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Commercial Register Document -->
                @if($agency->commercial_register_doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-900">Registre Commercial</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Document commercial</p>
                            <a href="{{ Storage::url($agency->commercial_register_doc) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">Voir le document</a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Identity Document -->
                @if($agency->identity_doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-900">Pièce d'Identité</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Carte d'identité</p>
                            <a href="{{ Storage::url($agency->identity_doc) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">Voir le document</a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tax Document -->
                @if($agency->tax_doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-900">Document Fiscal</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Document fiscal</p>
                            <a href="{{ Storage::url($agency->tax_doc) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">Voir le document</a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Additional Documents -->
                @if($agency->additional_docs && is_array($agency->additional_docs))
                    @foreach($agency->additional_docs as $index => $doc)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">Document Supplémentaire {{ $index + 1 }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Document additionnel</p>
                                <a href="{{ Storage::url($doc) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">Voir le document</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <!-- Agency Information -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Informations de l'Agence</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Numéro fiscal:</span>
                        <span class="text-gray-900 ml-2">{{ $agency->tax_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Registre commercial:</span>
                        <span class="text-gray-900 ml-2">{{ $agency->commercial_register_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Années d'expérience:</span>
                        <span class="text-gray-900 ml-2">{{ $agency->years_in_business }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Taille de flotte estimée:</span>
                        <span class="text-gray-900 ml-2">{{ $agency->estimated_fleet_size }}</span>
                    </div>
                </div>
                @if($agency->business_description)
                <div class="mt-3">
                    <span class="text-gray-500">Description:</span>
                    <p class="text-gray-900 mt-1">{{ $agency->business_description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document en attente</h3>
        <p class="mt-1 text-sm text-gray-500">Tous les documents ont été vérifiés.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($agencies->hasPages())
<div class="mt-6">
    {{ $agencies->links() }}
</div>
@endif
@endsection
