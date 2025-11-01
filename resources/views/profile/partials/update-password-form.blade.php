<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('Mot de Passe Actuel') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('updatePassword.current_password') border-red-500 @enderror" 
                   autocomplete="current-password" />
            @error('updatePassword.current_password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('Nouveau Mot de Passe') }}
            </label>
            <input id="update_password_password" name="password" type="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('updatePassword.password') border-red-500 @enderror" 
                   autocomplete="new-password" />
            @error('updatePassword.password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('Confirmer le Mot de Passe') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('updatePassword.password_confirmation') border-red-500 @enderror" 
                   autocomplete="new-password" />
            @error('updatePassword.password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
        <div>
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-medium">
                    ✅ {{ __('Mot de passe mis à jour avec succès.') }}
                </div>
            @endif
        </div>
        
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            {{ __('Mettre à Jour') }}
        </button>
    </div>
</form>
