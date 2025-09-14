<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Sécurité du Compte</h3>
        <p class="text-sm text-gray-600 mb-4">Gérez la sécurité de votre compte et changez votre mot de passe.</p>
    </div>
    
    <!-- Change Password -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-medium text-gray-900 mb-4">Changer le mot de passe</h4>
        
        <form action="{{ route('agence.profile.security') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                <input type="password" name="current_password" id="current_password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror" required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_password') border-red-500 @enderror" required>
                @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Le mot de passe doit contenir au moins 8 caractères.</p>
            </div>
            
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Changer le mot de passe
                </button>
            </div>
        </form>
    </div>
    
    <!-- Account Information -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-medium text-gray-900 mb-4">Informations du compte</h4>
        
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Nom d'utilisateur</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->name }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Email de connexion</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Membre depuis</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Dernière connexion</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y à H:i') : 'Jamais' }}
                </dd>
            </div>
        </dl>
    </div>
    
    <!-- Security Tips -->
    <div class="bg-yellow-50 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.726-1.36 3.491 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Conseils de sécurité</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Utilisez un mot de passe fort avec des lettres, chiffres et symboles</li>
                        <li>Ne partagez jamais vos identifiants de connexion</li>
                        <li>Déconnectez-vous toujours après utilisation sur un ordinateur partagé</li>
                        <li>Changez régulièrement votre mot de passe</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
