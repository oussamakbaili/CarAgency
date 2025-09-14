<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Register Your Agency</h2>
        <p class="mt-2 text-sm text-gray-600">Join our platform and start managing your car rental business</p>
    </div>

    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

            <form method="POST" action="{{ route('register.agency') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- Agency Information -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Agency Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="agency_name" value="Agency Name" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="agency_name" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="agency_name" 
                                :value="old('agency_name')" 
                                required 
                                autofocus 
                                placeholder="Enter agency name" />
                            <x-input-error :messages="$errors->get('agency_name')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="commercial_register_number" value="Commercial Register Number" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="commercial_register_number" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="commercial_register_number" 
                                :value="old('commercial_register_number')" 
                                required 
                                placeholder="Enter commercial register number" />
                            <x-input-error :messages="$errors->get('commercial_register_number')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="tax_number" value="Tax Identification Number (Optional)" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="tax_number" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="tax_number" 
                                :value="old('tax_number')" 
                                placeholder="Enter tax identification number" />
                            <x-input-error :messages="$errors->get('tax_number')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Agency Phone" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="phone" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="tel" 
                                name="phone" 
                                :value="old('phone')" 
                                required 
                                placeholder="Enter agency phone number" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Agency Email" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="email" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                placeholder="Enter agency email address" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="years_in_business" value="Years in Business" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="years_in_business" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="number" 
                                name="years_in_business" 
                                :value="old('years_in_business')" 
                                required 
                                placeholder="Enter years in business" />
                            <x-input-error :messages="$errors->get('years_in_business')" class="mt-2 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-input-label for="address" value="Address" class="text-sm font-medium text-gray-700 mb-2" />
                        <x-text-input id="address" 
                            class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                            type="text" 
                            name="address" 
                            :value="old('address')" 
                            required 
                            placeholder="Enter agency address" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <x-input-label for="city" value="City" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="city" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="city" 
                                :value="old('city')" 
                                required 
                                placeholder="Enter city" />
                            <x-input-error :messages="$errors->get('city')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="postal_code" value="Postal Code" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="postal_code" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="postal_code" 
                                :value="old('postal_code')" 
                                placeholder="Enter postal code" />
                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-input-label for="business_description" value="Business Description" class="text-sm font-medium text-gray-700 mb-2" />
                        <textarea id="business_description" 
                            name="business_description" 
                            rows="3" 
                            class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                            required 
                            placeholder="Describe your car rental business">{{ old('business_description') }}</textarea>
                        <x-input-error :messages="$errors->get('business_description')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <div class="mt-6">
                        <x-input-label for="estimated_fleet_size" value="Estimated Fleet Size" class="text-sm font-medium text-gray-700 mb-2" />
                        <x-text-input id="estimated_fleet_size" 
                            class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                            type="number" 
                            name="estimated_fleet_size" 
                            :value="old('estimated_fleet_size')" 
                            required 
                            placeholder="Enter estimated fleet size" />
                        <x-input-error :messages="$errors->get('estimated_fleet_size')" class="mt-2 text-sm text-red-600" />
                    </div>
                </div>

                <!-- Manager Information -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Manager Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="responsable_name" value="Full Name" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="responsable_name" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="responsable_name" 
                                :value="old('responsable_name')" 
                                required 
                                placeholder="Enter manager's full name" />
                            <x-input-error :messages="$errors->get('responsable_name')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="responsable_position" value="Position" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="responsable_position" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="responsable_position" 
                                :value="old('responsable_position')" 
                                required 
                                placeholder="Enter manager's position" />
                            <x-input-error :messages="$errors->get('responsable_position')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="responsable_phone" value="Phone Number" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="responsable_phone" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="tel" 
                                name="responsable_phone" 
                                :value="old('responsable_phone')" 
                                required 
                                placeholder="Enter manager's phone number" />
                            <x-input-error :messages="$errors->get('responsable_phone')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="responsable_identity_number" value="Identity Number (CIN)" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="responsable_identity_number" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="text" 
                                name="responsable_identity_number" 
                                :value="old('responsable_identity_number')" 
                                required 
                                placeholder="Enter manager's identity number" />
                            <x-input-error :messages="$errors->get('responsable_identity_number')" class="mt-2 text-sm text-red-600" />
                        </div>
                    </div>
                </div>

                <!-- Required Documents -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Required Documents
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="commercial_register_doc" value="Commercial Register Copy" class="text-sm font-medium text-gray-700 mb-2" />
                            <input id="commercial_register_doc" 
                                type="file" 
                                name="commercial_register_doc" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                required 
                                accept=".pdf,.jpg,.jpeg,.png" />
                            <p class="mt-2 text-sm text-gray-500">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                            <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="identity_doc" value="Manager Identity Document" class="text-sm font-medium text-gray-700 mb-2" />
                            <input id="identity_doc" 
                                type="file" 
                                name="identity_doc" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                required 
                                accept=".pdf,.jpg,.jpeg,.png" />
                            <p class="mt-2 text-sm text-gray-500">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                            <x-input-error :messages="$errors->get('identity_doc')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="tax_doc" value="Tax Document (Optional)" class="text-sm font-medium text-gray-700 mb-2" />
                            <input id="tax_doc" 
                                type="file" 
                                name="tax_doc" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                accept=".pdf,.jpg,.jpeg,.png" />
                            <p class="mt-2 text-sm text-gray-500">Accepted formats: PDF, JPG, PNG (Max: 5MB)</p>
                            <x-input-error :messages="$errors->get('tax_doc')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="additional_docs" value="Additional Documents (Optional)" class="text-sm font-medium text-gray-700 mb-2" />
                            <input id="additional_docs" 
                                type="file" 
                                name="additional_docs[]" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                multiple 
                                accept=".pdf,.jpg,.jpeg,.png" />
                            <p class="mt-2 text-sm text-gray-500">Accepted formats: PDF, JPG, PNG (Max: 5MB per file)</p>
                            <x-input-error :messages="$errors->get('additional_docs')" class="mt-2 text-sm text-red-600" />
                        </div>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Account Security
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="password" value="Password" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="password" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="password" 
                                name="password" 
                                required 
                                placeholder="Create a strong password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" value="Confirm Password" class="text-sm font-medium text-gray-700 mb-2" />
                            <x-text-input id="password_confirmation" 
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                placeholder="Confirm your password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6">
                    <a class="text-sm text-gray-600 hover:text-primary-600 font-medium transition duration-200" href="{{ route('login') }}">
                        {{ __('Already have an account? Sign in here') }}
                    </a>

                    <button type="submit" 
                        class="btn-secondary w-full sm:w-auto flex justify-center py-3 px-8 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ __('Register Agency') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
