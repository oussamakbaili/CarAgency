@extends('layouts.admin')

@section('header', 'Détails de l\'Agence')

@section('content')
<div class="py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.agencies.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Agency Details Card -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $agency->agency_name }}
                </h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $agency->status === 'approved' ? 'bg-green-100 text-green-800' : 
                       ($agency->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $agency->status === 'approved' ? 'Approuvée' : 
                       ($agency->status === 'pending' ? 'En attente' : 'Rejetée') }}
                </span>
            </div>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Agency Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Informations de l'Agence</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Nom:</span> {{ $agency->agency_name }}</p>
                        <p class="text-sm"><span class="font-medium">Email:</span> {{ $agency->email }}</p>
                        <p class="text-sm"><span class="font-medium">Téléphone:</span> {{ $agency->phone }}</p>
                        <p class="text-sm"><span class="font-medium">Adresse:</span> {{ $agency->address }}</p>
                        <p class="text-sm"><span class="font-medium">Ville:</span> {{ $agency->city }}</p>
                    </div>
                </div>

                <!-- Manager Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Responsable</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Nom:</span> {{ $agency->responsable_name }}</p>
                        <p class="text-sm"><span class="font-medium">Position:</span> {{ $agency->responsable_position }}</p>
                        <p class="text-sm"><span class="font-medium">Téléphone:</span> {{ $agency->responsable_phone }}</p>
                        <p class="text-sm"><span class="font-medium">CIN:</span> {{ $agency->responsable_identity_number }}</p>
                    </div>
                </div>

                <!-- Status Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Statut</h4>
                    <div class="space-y-2">
                        <p class="text-sm"><span class="font-medium">Statut:</span> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $agency->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($agency->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $agency->status === 'approved' ? 'Approuvée' : 
                                   ($agency->status === 'pending' ? 'En attente' : 'Rejetée') }}
                            </span>
                        </p>
                        <p class="text-sm"><span class="font-medium">Créée le:</span> {{ $agency->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-sm"><span class="font-medium">Modifiée le:</span> {{ $agency->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Card -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Documents</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if($agency->commercial_register_doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Registre de Commerce</h4>
                            <a href="{{ Storage::url($agency->commercial_register_doc) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Télécharger
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($agency->identity_doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Document d'Identité</h4>
                            <a href="{{ Storage::url($agency->identity_doc) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Télécharger
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($agency->tax_doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Document Fiscal</h4>
                            <a href="{{ Storage::url($agency->tax_doc) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                Télécharger
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if(!$agency->commercial_register_doc && !$agency->identity_doc && !$agency->tax_doc)
                <div class="col-span-full text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucun document n'a été téléchargé par cette agence.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    @if($agency->status === 'pending')
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Actions</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex space-x-4">
                <form action="{{ route('admin.agencies.approve', $agency) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approuver
                    </button>
                </form>

                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'reject-agency')" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Rejeter
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

    <!-- Rejection Modal -->
    <x-modal name="reject-agency" focusable>
        <form method="POST" action="{{ route('admin.agencies.reject', $agency) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Raison du Rejet') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="rejection_reason" value="{{ __('Raison') }}" class="sr-only" />

                <x-text-area
                    id="rejection_reason"
                    name="rejection_reason"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Entrez la raison du rejet...') }}"
                    required
                />

                <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Rejeter l\'Agence') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endsection 