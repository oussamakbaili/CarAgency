<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Connexion par téléphone</h2>
                <p class="text-gray-600">Entrez votre numéro pour recevoir un code</p>
            </div>

            <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-200">

                @if (session('status'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.phone.send-otp') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Numéro de téléphone</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                +212
                            </span>
                            <input id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                required
                                autofocus
                                placeholder="6 12 34 56 78"
                                class="flex-1 block w-full px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200" />
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-4 rounded-xl text-base font-semibold transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Envoyer le code
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#0A66C2] hover:underline font-medium">
                        ← Retour à la connexion
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
