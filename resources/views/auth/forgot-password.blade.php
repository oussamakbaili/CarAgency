@extends('layouts.public')

@section('title', 'Mot de passe oublié - ToubCar')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8 bg-orange-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-2 sm:mb-4">Mot de passe oublié ?</h2>
            <p class="text-sm sm:text-lg text-gray-600">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe</p>
        </div>

        <div class="bg-white py-6 sm:py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-200">
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4 sm:space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Adresse Email</label>
                    <input id="email" 
                        class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="Entrez votre adresse email" />
                    @error('email')
                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-[#0A66C2] hover:bg-[#0A66C2] text-white py-2.5 sm:py-3 px-4 rounded-xl text-sm sm:text-base font-semibold transition duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Envoyer le lien de réinitialisation
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-xs sm:text-sm text-orange-600 hover:text-orange-500 font-medium transition duration-200">
                        ← Retour à la connexion
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
