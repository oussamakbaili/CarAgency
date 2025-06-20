@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Détails de l\'Agence') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informations de l'Agence</h3>
                        <p><strong>Nom:</strong> {{ $agency->agency_name }}</p>
                        <p><strong>Email:</strong> {{ $agency->email }}</p>
                        <p><strong>Téléphone:</strong> {{ $agency->phone }}</p>
                        <p><strong>Adresse:</strong> {{ $agency->address }}</p>
                        <p><strong>Ville:</strong> {{ $agency->city }}</p>
                        <p><strong>Status:</strong> 
                            <span class="px-2 py-1 rounded text-white
                                @if($agency->status === 'pending') bg-yellow-500
                                @elseif($agency->status === 'approved') bg-green-500
                                @else bg-red-500
                                @endif">
                                {{ ucfirst($agency->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informations du Responsable</h3>
                        <p><strong>Nom:</strong> {{ $agency->responsable_name }}</p>
                        <p><strong>Position:</strong> {{ $agency->responsable_position }}</p>
                        <p><strong>Téléphone:</strong> {{ $agency->responsable_phone }}</p>
                        <p><strong>CIN:</strong> {{ $agency->responsable_identity_number }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Documents</h3>
                        <div class="mt-2 space-y-2">
                            @if($agency->commercial_register_doc)
                                <div>
                                    <span>Registre de Commerce:</span>
                                    <a href="{{ Storage::url($agency->commercial_register_doc) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        Télécharger
                                    </a>
                                </div>
                            @endif

                            @if($agency->identity_doc)
                                <div>
                                    <span>Document d'Identité:</span>
                                    <a href="{{ Storage::url($agency->identity_doc) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        Télécharger
                                    </a>
                                </div>
                            @endif

                            @if($agency->tax_doc)
                                <div>
                                    <span>Document Fiscal:</span>
                                    <a href="{{ Storage::url($agency->tax_doc) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        Télécharger
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($agency->status === 'pending')
                        <div class="flex space-x-4">
                            <form action="{{ route('admin.agencies.approve', $agency) }}" method="POST">
                                @csrf
                                <x-primary-button>
                                    {{ __('Approuver') }}
                                </x-primary-button>
                            </form>

                            <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'reject-agency')">
                                {{ __('Rejeter') }}
                            </x-danger-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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