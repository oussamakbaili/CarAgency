<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Vérification</h2>
                <p class="text-gray-600">Code envoyé au <strong>{{ $phone }}</strong></p>
            </div>

            <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-200">

                @if (session('status'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.phone.verify') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="phone" value="{{ $phone }}" />

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">
                            Entrez le code à 6 chiffres
                        </label>
                        <input id="otp"
                            name="otp"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            maxlength="6"
                            required
                            autofocus
                            placeholder="000000"
                            class="block w-full px-4 py-4 text-center text-3xl font-bold tracking-[0.5em] border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200" />
                        <x-input-error :messages="$errors->get('otp')" class="mt-2 text-sm text-red-600 text-center" />
                    </div>

                    <button type="submit"
                        class="w-full bg-[#0A66C2] hover:bg-blue-700 text-white py-3 px-4 rounded-xl text-base font-semibold transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Vérifier et se connecter
                    </button>
                </form>

                <div class="mt-6 text-center space-y-2">
                    <form method="POST" action="{{ route('login.phone.send-otp') }}">
                        @csrf
                        <input type="hidden" name="phone" value="{{ $phone }}" />
                        <button type="submit" class="text-sm text-orange-600 hover:underline font-medium">
                            Renvoyer le code
                        </button>
                    </form>
                    <div>
                        <a href="{{ route('login.phone') }}" class="text-sm text-gray-500 hover:underline">
                            Changer de numéro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit when 6 digits entered
        document.getElementById('otp').addEventListener('input', function () {
            if (this.value.replace(/\D/g, '').length === 6) {
                this.form.submit();
            }
        });
    </script>
</x-guest-layout>
