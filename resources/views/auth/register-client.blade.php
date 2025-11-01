<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Create Customer Account</h2>
                <p class="text-lg text-gray-600">Fill in your details to get started</p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
                <form method="POST" action="{{ route('register.client') }}" class="p-6 md:p-10">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Full Name') }}</label>
                                <input id="name" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name" 
                                    placeholder="Enter your full name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                            </div>

                            <!-- CIN and Birthday -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="cin" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('CIN Number') }}</label>
                                    <input id="cin" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                        type="text" 
                                        name="cin" 
                                        value="{{ old('cin') }}" 
                                        required 
                                        autocomplete="cin" 
                                        placeholder="Enter CIN number" />
                                    <x-input-error :messages="$errors->get('cin')" class="mt-2 text-sm text-red-600" />
                                </div>

                                <div>
                                    <label for="birthday" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Date of Birth') }}</label>
                                    <input id="birthday" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                        type="date" 
                                        name="birthday" 
                                        value="{{ old('birthday') }}" 
                                        required />
                                    <x-input-error :messages="$errors->get('birthday')" class="mt-2 text-sm text-red-600" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Contact Information</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Email Address') }}</label>
                                <input id="email" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="username" 
                                    placeholder="Enter your email address" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                            </div>

                            <!-- Phone and Address -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Phone Number') }}</label>
                                    <input id="phone" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                        type="text" 
                                        name="phone" 
                                        value="{{ old('phone') }}" 
                                        required 
                                        autocomplete="tel" 
                                        placeholder="Enter phone number" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Address') }}</label>
                                    <input id="address" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white" 
                                        type="text" 
                                        name="address" 
                                        value="{{ old('address') }}" 
                                        required 
                                        autocomplete="street-address" 
                                        placeholder="Enter your address" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2 text-sm text-red-600" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="bg-gradient-to-br from-[#C2410C]/5 to-[#9A3412]/5 p-6 rounded-2xl border border-[#C2410C]/10 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#C2410C] rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Account Security</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Password') }}</label>
                                <input id="password" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white"
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="Create a strong password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Confirm Password') }}</label>
                                <input id="password_confirmation" 
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white"
                                    type="password"
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="Confirm your password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                            </div>
                        </div>
                    </div>

                    <!-- Footer avec Bouton -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t-2 border-gray-100">
                        <div class="text-center sm:text-left">
                            <p class="text-sm text-gray-600">
                                {{ __('Already have an account?') }}
                                <a href="{{ route('login') }}" class="font-semibold text-[#C2410C] hover:text-[#9A3412] transition duration-200">
                                    {{ __('Sign in here') }}
                                </a>
                            </p>
                        </div>

                        <button type="submit" 
                            class="group relative w-full sm:w-auto flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#C2410C] to-[#9A3412] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#C2410C] focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Create Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
