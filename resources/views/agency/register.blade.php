@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Inscription Agence') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Devenez Partenaire</h3>
                    <p class="text-gray-600">Rejoignez notre plateforme de location de véhicules et développez votre business.</p>
                </div>

                <form method="POST" action="{{ route('agency.register') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Agency Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informations de l'Agence</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="agency_name" class="block text-sm font-medium text-gray-700">Nom de l'agence *</label>
                                <input type="text" name="agency_name" id="agency_name" value="{{ old('agency_name') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('agency_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="commercial_register_number" class="block text-sm font-medium text-gray-700">Numéro RC *</label>
                                <input type="text" name="commercial_register_number" id="commercial_register_number" value="{{ old('commercial_register_number') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('commercial_register_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Adresse *</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Ville *</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                                <input type="password" name="password" id="password" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Manager Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Informations du Responsable</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="responsable_name" class="block text-sm font-medium text-gray-700">Nom complet *</label>
                                <input type="text" name="responsable_name" id="responsable_name" value="{{ old('responsable_name') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('responsable_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="responsable_position" class="block text-sm font-medium text-gray-700">Poste *</label>
                                <input type="text" name="responsable_position" id="responsable_position" value="{{ old('responsable_position') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('responsable_position')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="responsable_phone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                                <input type="tel" name="responsable_phone" id="responsable_phone" value="{{ old('responsable_phone') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('responsable_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="responsable_identity_number" class="block text-sm font-medium text-gray-700">CIN *</label>
                                <input type="text" name="responsable_identity_number" id="responsable_identity_number" value="{{ old('responsable_identity_number') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('responsable_identity_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Documents Requis</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="commercial_register_doc" class="block text-sm font-medium text-gray-700">Registre de Commerce *</label>
                                <input type="file" name="commercial_register_doc" id="commercial_register_doc" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                @error('commercial_register_doc')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="identity_doc" class="block text-sm font-medium text-gray-700">Document d'Identité *</label>
                                <input type="file" name="identity_doc" id="identity_doc" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                @error('identity_doc')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tax_doc" class="block text-sm font-medium text-gray-700">Document Fiscal *</label>
                                <input type="file" name="tax_doc" id="tax_doc" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                @error('tax_doc')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Votre demande sera examinée par notre équipe. Vous recevrez une réponse par email dans les 48 heures.</p>
                                    <p class="mt-1">Tous les documents doivent être clairs et lisibles.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Soumettre la Demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
