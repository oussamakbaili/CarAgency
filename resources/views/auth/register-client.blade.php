<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
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
                    <div class="bg-gradient-to-br from-[#0A66C2]/5 to-[#0A66C2]/5 p-6 rounded-2xl border border-[#0A66C2]/10 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#0A66C2] rounded-lg mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- Full Name -->
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

                            <!-- Date of Birth -->
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

                    <!-- Contact Information Section -->
                    <div class="bg-gradient-to-br from-[#0A66C2]/5 to-[#0A66C2]/5 p-6 rounded-2xl border border-[#0A66C2]/10 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#0A66C2] rounded-lg mr-3">
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

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Phone Number') }}</label>
                                <input id="phone"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 bg-white"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    autocomplete="tel"
                                    placeholder="Enter your phone number" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="bg-gradient-to-br from-[#0A66C2]/5 to-[#0A66C2]/5 p-6 rounded-2xl border border-[#0A66C2]/10 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-[#0A66C2] rounded-lg mr-3">
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

                    <!-- Social Sign Up -->
                    <div class="mb-6">
                        <div class="relative mb-5">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-3 bg-white text-gray-400 font-medium">ou s'inscrire avec</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('login.google') }}"
                               class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl bg-white hover:bg-gray-50 transition duration-200 text-sm font-medium text-gray-700 shadow-sm">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Google
                            </a>
                            <a href="{{ route('login.apple') }}"
                               class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl bg-black hover:bg-gray-900 transition duration-200 text-sm font-medium text-white shadow-sm">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                </svg>
                                Apple
                            </a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t-2 border-gray-100">
                        <div class="text-center sm:text-left">
                            <p class="text-sm text-gray-600">
                                {{ __('Already have an account?') }}
                                <a href="{{ route('login') }}" class="font-semibold text-[#0A66C2] hover:text-[#0A66C2] transition duration-200">
                                    {{ __('Sign in here') }}
                                </a>
                            </p>
                        </div>

                        <button type="submit"
                            class="group relative w-full sm:w-auto flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#0A66C2] to-[#0A66C2] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#0A66C2] focus:ring-offset-2">
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
