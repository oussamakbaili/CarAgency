<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Create Customer Account</h2>
        <p class="mt-2 text-sm text-gray-600">Fill in your details to get started</p>
    </div>

    <form method="POST" action="{{ route('register.client') }}" class="space-y-6">
        @csrf

        <!-- Personal Information Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Personal Information</h3>
            
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="name" 
                    class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="Enter your full name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- CIN and Birthday -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="cin" :value="__('CIN Number')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input id="cin" 
                        class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                        type="text" 
                        name="cin" 
                        :value="old('cin')" 
                        required 
                        autocomplete="cin" 
                        placeholder="Enter CIN number" />
                    <x-input-error :messages="$errors->get('cin')" class="mt-2 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="birthday" :value="__('Date of Birth')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input id="birthday" 
                        class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                        type="date" 
                        name="birthday" 
                        :value="old('birthday')" 
                        required />
                    <x-input-error :messages="$errors->get('birthday')" class="mt-2 text-sm text-red-600" />
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Contact Information</h3>
            
            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="email" 
                    class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username" 
                    placeholder="Enter your email address" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Phone and Address -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input id="phone" 
                        class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                        type="text" 
                        name="phone" 
                        :value="old('phone')" 
                        required 
                        autocomplete="tel" 
                        placeholder="Enter phone number" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                </div>

                <div>
                    <x-input-label for="address" :value="__('Address')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input id="address" 
                        class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200" 
                        type="text" 
                        name="address" 
                        :value="old('address')" 
                        required 
                        autocomplete="street-address" 
                        placeholder="Enter your address" />
                    <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-red-600" />
                </div>
            </div>
        </div>

        <!-- Security Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Account Security</h3>
            
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="password" 
                    class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password" 
                    placeholder="Create a strong password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="password_confirmation" 
                    class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Confirm your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                class="btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('Create Account') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-200">
                    {{ __('Sign in here') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
