@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(auth()->user()->agency->status === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Votre demande d'inscription a été rejetée
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ auth()->user()->agency->rejection_reason }}</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'update-info')" class="bg-red-50 px-2 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
                                    Mettre à jour les informations
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("Bienvenue dans votre espace agence") }}
            </div>
        </div>
    </div>
</div>

<!-- Update Information Modal -->
@if(auth()->user()->agency->status === 'rejected')
    <x-modal name="update-info" focusable>
        <form method="POST" action="{{ route('agency.update') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Mettre à jour les informations') }}
            </h2>

            <!-- Agency Information -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="agency_name" value="Nom de l'Agence" />
                    <x-text-input id="agency_name" name="agency_name" type="text" class="mt-1 block w-full" :value="old('agency_name', auth()->user()->agency->agency_name)" required />
                    <x-input-error :messages="$errors->get('agency_name')" class="mt-2" />
                </div>

                <!-- Add other fields as needed -->

                <div>
                    <x-input-label for="commercial_register_doc" value="Registre de Commerce (PDF)" />
                    <input type="file" id="commercial_register_doc" name="commercial_register_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="identity_doc" value="Document d'Identité (PDF)" />
                    <input type="file" id="identity_doc" name="identity_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('identity_doc')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tax_doc" value="Document Fiscal (PDF)" />
                    <input type="file" id="tax_doc" name="tax_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('tax_doc')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Mettre à jour') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
@endif
@endsection
