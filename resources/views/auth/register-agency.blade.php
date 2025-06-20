<x-guest-layout>
    <div class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Inscription Agence de Location</h2>

        <form method="POST" action="{{ route('register.agency') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Agency Information -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de l'Agence</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="agency_name" value="Nom de l'Agence" />
                        <x-text-input id="agency_name" class="block mt-1 w-full" type="text" name="agency_name" :value="old('agency_name')" required autofocus />
                        <x-input-error :messages="$errors->get('agency_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="commercial_register_number" value="Numéro du Registre de Commerce" />
                        <x-text-input id="commercial_register_number" class="block mt-1 w-full" type="text" name="commercial_register_number" :value="old('commercial_register_number')" required />
                        <x-input-error :messages="$errors->get('commercial_register_number')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tax_number" value="Numéro d'Identification Fiscale (Optional)" />
                        <x-text-input id="tax_number" class="block mt-1 w-full" type="text" name="tax_number" :value="old('tax_number')" />
                        <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" value="Téléphone de l'Agence" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email de l'Agence" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="years_in_business" value="Années d'Expérience" />
                        <x-text-input id="years_in_business" class="block mt-1 w-full" type="number" name="years_in_business" :value="old('years_in_business')" required />
                        <x-input-error :messages="$errors->get('years_in_business')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="address" value="Adresse" />
                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <x-input-label for="city" value="Ville" />
                        <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="postal_code" value="Code Postal" />
                        <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" />
                        <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="business_description" value="Description de l'Activité" />
                    <textarea id="business_description" name="business_description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('business_description') }}</textarea>
                    <x-input-error :messages="$errors->get('business_description')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="estimated_fleet_size" value="Taille Estimée de la Flotte" />
                    <x-text-input id="estimated_fleet_size" class="block mt-1 w-full" type="number" name="estimated_fleet_size" :value="old('estimated_fleet_size')" required />
                    <x-input-error :messages="$errors->get('estimated_fleet_size')" class="mt-2" />
                </div>
            </div>

            <!-- Responsable Information -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du Responsable</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="responsable_name" value="Nom Complet" />
                        <x-text-input id="responsable_name" class="block mt-1 w-full" type="text" name="responsable_name" :value="old('responsable_name')" required />
                        <x-input-error :messages="$errors->get('responsable_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="responsable_position" value="Poste" />
                        <x-text-input id="responsable_position" class="block mt-1 w-full" type="text" name="responsable_position" :value="old('responsable_position')" required />
                        <x-input-error :messages="$errors->get('responsable_position')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="responsable_phone" value="Téléphone" />
                        <x-text-input id="responsable_phone" class="block mt-1 w-full" type="tel" name="responsable_phone" :value="old('responsable_phone')" required />
                        <x-input-error :messages="$errors->get('responsable_phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="responsable_identity_number" value="Numéro CIN" />
                        <x-text-input id="responsable_identity_number" class="block mt-1 w-full" type="text" name="responsable_identity_number" :value="old('responsable_identity_number')" required />
                        <x-input-error :messages="$errors->get('responsable_identity_number')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Required Documents -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Documents Requis</h3>
                <div class="space-y-6">
                    <div>
                        <x-input-label for="commercial_register_doc" value="Copie du Registre de Commerce" />
                        <input id="commercial_register_doc" type="file" name="commercial_register_doc" class="block mt-1 w-full" required accept=".pdf,.jpg,.jpeg,.png" />
                        <p class="mt-1 text-sm text-gray-500">Format accepté: PDF, JPG, PNG (Max: 5MB)</p>
                        <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="identity_doc" value="Document d'Identification du Responsable" />
                        <input id="identity_doc" type="file" name="identity_doc" class="block mt-1 w-full" required accept=".pdf,.jpg,.jpeg,.png" />
                        <p class="mt-1 text-sm text-gray-500">Format accepté: PDF, JPG, PNG (Max: 5MB)</p>
                        <x-input-error :messages="$errors->get('identity_doc')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tax_doc" value="Document Fiscal (Optional)" />
                        <input id="tax_doc" type="file" name="tax_doc" class="block mt-1 w-full" accept=".pdf,.jpg,.jpeg,.png" />
                        <p class="mt-1 text-sm text-gray-500">Format accepté: PDF, JPG, PNG (Max: 5MB)</p>
                        <x-input-error :messages="$errors->get('tax_doc')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="additional_docs" value="Documents Supplémentaires (Optional)" />
                        <input id="additional_docs" type="file" name="additional_docs[]" class="block mt-1 w-full" multiple accept=".pdf,.jpg,.jpeg,.png" />
                        <p class="mt-1 text-sm text-gray-500">Format accepté: PDF, JPG, PNG (Max: 5MB par fichier)</p>
                        <x-input-error :messages="$errors->get('additional_docs')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Account Security -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sécurité du Compte</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="password" value="Mot de Passe" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirmer le Mot de Passe" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Déjà inscrit?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('S\'inscrire') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
