@php
use App\Models\Agency;
$maxTries = 3; // This matches Agency::MAX_TRIES
$remainingTries = $maxTries - $agency->tries_count;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mise à jour des Informations') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Rejection Notice -->
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
                            <p class="font-semibold">Raison du rejet:</p>
                            <p class="mt-1">{{ $agency->rejection_reason }}</p>
                        </div>
                        <div class="mt-2 text-sm text-red-700">
                            <p class="font-semibold">Tentatives restantes: {{ $remainingTries }}</p>
                            @if($remainingTries == 1)
                                <p class="mt-1 font-bold">Attention: C'est votre dernière tentative!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('agence.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Agency Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de l'Agence</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="agency_name" value="Nom de l'Agence" />
                                    <x-text-input id="agency_name" name="agency_name" type="text" class="mt-1 block w-full" :value="old('agency_name', $agency->agency_name)" required />
                                    <x-input-error :messages="$errors->get('agency_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" value="Email" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $agency->email)" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" value="Téléphone" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $agency->phone)" required />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" value="Adresse" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $agency->address)" required />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="city" value="Ville" />
                                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $agency->city)" required />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="commercial_register_number" value="Numéro du Registre de Commerce" />
                                    <x-text-input id="commercial_register_number" name="commercial_register_number" type="text" class="mt-1 block w-full" :value="old('commercial_register_number', $agency->commercial_register_number)" required />
                                    <x-input-error :messages="$errors->get('commercial_register_number')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Responsable Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du Responsable</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="responsable_name" value="Nom du Responsable" />
                                    <x-text-input id="responsable_name" name="responsable_name" type="text" class="mt-1 block w-full" :value="old('responsable_name', $agency->responsable_name)" required />
                                    <x-input-error :messages="$errors->get('responsable_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="responsable_phone" value="Téléphone du Responsable" />
                                    <x-text-input id="responsable_phone" name="responsable_phone" type="text" class="mt-1 block w-full" :value="old('responsable_phone', $agency->responsable_phone)" required />
                                    <x-input-error :messages="$errors->get('responsable_phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="responsable_position" value="Poste du Responsable" />
                                    <x-text-input id="responsable_position" name="responsable_position" type="text" class="mt-1 block w-full" :value="old('responsable_position', $agency->responsable_position)" required />
                                    <x-input-error :messages="$errors->get('responsable_position')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="responsable_identity_number" value="Numéro d'Identité du Responsable" />
                                    <x-text-input id="responsable_identity_number" name="responsable_identity_number" type="text" class="mt-1 block w-full" :value="old('responsable_identity_number', $agency->responsable_identity_number)" required />
                                    <x-input-error :messages="$errors->get('responsable_identity_number')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Documents</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="commercial_register_doc" value="Registre de Commerce" />
                                    <input type="file" id="commercial_register_doc" name="commercial_register_doc" class="mt-1 block w-full" accept=".pdf" />
                                    <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2" />
                                    @if($agency->commercial_register_doc)
                                        <a href="{{ Storage::url($agency->commercial_register_doc) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            Document actuel
                                        </a>
                                    @endif
                                </div>

                                <div>
                                    <x-input-label for="identity_doc" value="Document d'Identité" />
                                    <input type="file" id="identity_doc" name="identity_doc" class="mt-1 block w-full" accept=".pdf" />
                                    <x-input-error :messages="$errors->get('identity_doc')" class="mt-2" />
                                    @if($agency->identity_doc)
                                        <a href="{{ Storage::url($agency->identity_doc) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            Document actuel
                                        </a>
                                    @endif
                                </div>

                                <div>
                                    <x-input-label for="tax_doc" value="Document Fiscal (Optionnel)" />
                                    <input type="file" id="tax_doc" name="tax_doc" class="mt-1 block w-full" accept=".pdf" />
                                    <x-input-error :messages="$errors->get('tax_doc')" class="mt-2" />
                                    @if($agency->tax_doc)
                                        <a href="{{ Storage::url($agency->tax_doc) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                            Document actuel
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <x-primary-button>
                                {{ __('Mettre à jour et Soumettre') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>