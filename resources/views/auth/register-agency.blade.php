<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Inscrivez votre Agence</h2>
                <p class="text-lg text-gray-600">Rejoignez notre plateforme et développez votre activité de location de véhicules</p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
                <form method="POST" action="{{ route('register.agency') }}" enctype="multipart/form-data" class="p-6 md:p-10">
                    @csrf

                    <!-- Layout Horizontal Professionnel -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Colonne Gauche -->
                        <div class="space-y-8">
                            <!-- Informations de l'Agence -->
                            <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Informations de l'Agence</h3>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="agency_name" class="block text-sm font-semibold text-gray-700 mb-2">Nom de l'Agence *</label>
                                        <input id="agency_name" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                            type="text" 
                                            name="agency_name" 
                                            value="{{ old('agency_name') }}" 
                                            required 
                                            autofocus 
                                            placeholder="Nom de l'agence" />
                                        <x-input-error :messages="$errors->get('agency_name')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="commercial_register_number" class="block text-sm font-semibold text-gray-700 mb-2">N° RC *</label>
                                            <input id="commercial_register_number" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="commercial_register_number" 
                                                value="{{ old('commercial_register_number') }}" 
                                                required 
                                                placeholder="N° RC" />
                                            <x-input-error :messages="$errors->get('commercial_register_number')" class="mt-2 text-sm text-red-600" />
                                        </div>

                                        <div>
                                            <label for="tax_number" class="block text-sm font-semibold text-gray-700 mb-2">N° IF (Optionnel)</label>
                                            <input id="tax_number" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="tax_number" 
                                                value="{{ old('tax_number') }}" 
                                                placeholder="N° IF" />
                                            <x-input-error :messages="$errors->get('tax_number')" class="mt-2 text-sm text-red-600" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone *</label>
                                            <input id="phone" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="tel" 
                                                name="phone" 
                                                value="{{ old('phone') }}" 
                                                required 
                                                placeholder="Téléphone" />
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                            <input id="email" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="email" 
                                                name="email" 
                                                value="{{ old('email') }}" 
                                                required 
                                                placeholder="Email" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Adresse *</label>
                                        <input id="address" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                            type="text" 
                                            name="address" 
                                            value="{{ old('address') }}" 
                                            required 
                                            placeholder="Adresse complète" />
                                        <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">Ville *</label>
                                            <input id="city" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="city" 
                                                value="{{ old('city') }}" 
                                                required 
                                                placeholder="Ville" />
                                            <x-input-error :messages="$errors->get('city')" class="mt-2 text-sm text-red-600" />
                                        </div>

                                        <div>
                                            <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">Code Postal</label>
                                            <input id="postal_code" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="postal_code" 
                                                value="{{ old('postal_code') }}" 
                                                placeholder="CP" />
                                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2 text-sm text-red-600" />
                                        </div>

                                        <div>
                                            <label for="years_in_business" class="block text-sm font-semibold text-gray-700 mb-2">Années *</label>
                                            <input id="years_in_business" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="number" 
                                                name="years_in_business" 
                                                value="{{ old('years_in_business') }}" 
                                                required 
                                                placeholder="Années" />
                                            <x-input-error :messages="$errors->get('years_in_business')" class="mt-2 text-sm text-red-600" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="business_description" value="Description *" class="block text-sm font-semibold text-gray-700 mb-2" />
                                        <textarea id="business_description" 
                                            name="business_description" 
                                            rows="3" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 resize-none bg-white" 
                                            required 
                                            placeholder="Description de l'entreprise...">{{ old('business_description') }}</textarea>
                                        <x-input-error :messages="$errors->get('business_description')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div>
                                        <label for="estimated_fleet_size" class="block text-sm font-semibold text-gray-700 mb-2">Taille de la Flotte *</label>
                                        <input id="estimated_fleet_size" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                            type="number" 
                                            name="estimated_fleet_size" 
                                            value="{{ old('estimated_fleet_size') }}" 
                                            required 
                                            placeholder="Nombre de véhicules" />
                                        <x-input-error :messages="$errors->get('estimated_fleet_size')" class="mt-2 text-sm text-red-600" />
                                    </div>
                                </div>
                            </div>

                            <!-- Informations du Responsable -->
                            <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Informations du Responsable</h3>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="responsable_name" class="block text-sm font-semibold text-gray-700 mb-2">Nom Complet *</label>
                                            <input id="responsable_name" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="responsable_name" 
                                                value="{{ old('responsable_name') }}" 
                                                required 
                                                placeholder="Nom complet" />
                                            <x-input-error :messages="$errors->get('responsable_name')" class="mt-2 text-sm text-red-600" />
                                        </div>

                                        <div>
                                            <label for="responsable_position" class="block text-sm font-semibold text-gray-700 mb-2">Poste *</label>
                                            <input id="responsable_position" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="responsable_position" 
                                                value="{{ old('responsable_position') }}" 
                                                required 
                                                placeholder="Poste" />
                                            <x-input-error :messages="$errors->get('responsable_position')" class="mt-2 text-sm text-red-600" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="responsable_phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone *</label>
                                            <input id="responsable_phone" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="tel" 
                                                name="responsable_phone" 
                                                value="{{ old('responsable_phone') }}" 
                                                required 
                                                placeholder="Téléphone" />
                                            <x-input-error :messages="$errors->get('responsable_phone')" class="mt-2 text-sm text-red-600" />
                                        </div>

                                        <div>
                                            <label for="responsable_identity_number" class="block text-sm font-semibold text-gray-700 mb-2">CIN *</label>
                                            <input id="responsable_identity_number" 
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                                type="text" 
                                                name="responsable_identity_number" 
                                                value="{{ old('responsable_identity_number') }}" 
                                                required 
                                                placeholder="N° CIN" />
                                            <x-input-error :messages="$errors->get('responsable_identity_number')" class="mt-2 text-sm text-red-600" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne Droite -->
                        <div class="space-y-8">
                            <!-- Documents Requis -->
                            <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Documents Requis</h3>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="commercial_register_doc" value="Registre Commercial *" class="text-sm font-semibold text-gray-700 mb-2" />
                                        <label for="commercial_register_doc" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition duration-200 hover:border-[#C2410C]">
                                            <div class="flex flex-col items-center justify-center pt-3 pb-2">
                                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Cliquez</span> ou glissez-déposez</p>
                                                <p class="text-xs text-gray-400">PDF, JPG, PNG (MAX. 5MB)</p>
                                            </div>
                                            <input id="commercial_register_doc" type="file" name="commercial_register_doc" class="hidden" required accept=".pdf,.jpg,.jpeg,.png" />
                                        </label>
                                        <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div>
                                        <x-input-label for="identity_doc" value="CIN Responsable *" class="text-sm font-semibold text-gray-700 mb-2" />
                                        <label for="identity_doc" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition duration-200 hover:border-[#C2410C]">
                                            <div class="flex flex-col items-center justify-center pt-3 pb-2">
                                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Cliquez</span> ou glissez-déposez</p>
                                                <p class="text-xs text-gray-400">PDF, JPG, PNG (MAX. 5MB)</p>
                                            </div>
                                            <input id="identity_doc" type="file" name="identity_doc" class="hidden" required accept=".pdf,.jpg,.jpeg,.png" />
                                        </label>
                                        <x-input-error :messages="$errors->get('identity_doc')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div>
                                        <x-input-label for="tax_doc" value="Document Fiscal (Optionnel)" class="text-sm font-semibold text-gray-700 mb-2" />
                                        <label for="tax_doc" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition duration-200 hover:border-[#C2410C]">
                                            <div class="flex flex-col items-center justify-center pt-3 pb-2">
                                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Cliquez</span> ou glissez-déposez</p>
                                                <p class="text-xs text-gray-400">PDF, JPG, PNG (MAX. 5MB)</p>
                                            </div>
                                            <input id="tax_doc" type="file" name="tax_doc" class="hidden" accept=".pdf,.jpg,.jpeg,.png" />
                                        </label>
                                        <x-input-error :messages="$errors->get('tax_doc')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div>
                                        <x-input-label for="additional_docs" value="Documents Supplémentaires" class="text-sm font-semibold text-gray-700 mb-2" />
                                        <label for="additional_docs" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition duration-200 hover:border-[#C2410C]">
                                            <div class="flex flex-col items-center justify-center pt-3 pb-2">
                                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Cliquez</span> ou glissez-déposez</p>
                                                <p class="text-xs text-gray-400">PDF, JPG, PNG (MAX. 5MB)</p>
                                            </div>
                                            <input id="additional_docs" type="file" name="additional_docs[]" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png" />
                                        </label>
                                        <x-input-error :messages="$errors->get('additional_docs')" class="mt-2 text-sm text-red-600" />
                                    </div>
                                </div>
                            </div>

                            <!-- Sécurité du Compte -->
                            <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Sécurité du Compte</h3>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de Passe *</label>
                                        <input id="password" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                            type="password" 
                                            name="password" 
                                            required 
                                            placeholder="Minimum 8 caractères" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le Mot de Passe *</label>
                                        <input id="password_confirmation" 
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                            type="password" 
                                            name="password_confirmation" 
                                            required 
                                            placeholder="Confirmez le mot de passe" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer avec Bouton -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 mt-8 border-t-2 border-gray-100">
                        <a class="text-sm text-gray-600 hover:text-[#C2410C] font-semibold transition duration-200 flex items-center" href="{{ route('login') }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Déjà un compte ? Connectez-vous
                        </a>

                        <button type="submit" 
                            class="group w-full sm:w-auto flex items-center justify-center px-8 py-3 bg-gradient-to-r from-[#C2410C] to-[#9A3412] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#C2410C] focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Inscrire l'Agence
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
