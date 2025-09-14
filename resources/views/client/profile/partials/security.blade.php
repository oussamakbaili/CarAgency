<div class="space-y-6">
    <!-- Change Password -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Changer le Mot de Passe</h3>
        <p class="text-gray-600 mb-6">Pour votre sécurité, utilisez un mot de passe fort et unique.</p>
        
        <form action="{{ route('client.profile.security') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">Exigences du mot de passe :</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Au moins 8 caractères</li>
                    <li>• Contient au moins une lettre majuscule</li>
                    <li>• Contient au moins une lettre minuscule</li>
                    <li>• Contient au moins un chiffre</li>
                    <li>• Contient au moins un caractère spécial</li>
                </ul>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Changer le Mot de Passe
                </button>
            </div>
        </form>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Authentification à Deux Facteurs</h3>
        <p class="text-gray-600 mb-6">Ajoutez une couche de sécurité supplémentaire à votre compte.</p>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if($client->preferences['two_factor_enabled'] ?? false)
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-4">
                    <h4 class="font-medium text-gray-900">Authentification à Deux Facteurs</h4>
                    <p class="text-sm text-gray-500">
                        @if($client->preferences['two_factor_enabled'] ?? false)
                            Activée - Votre compte est protégé
                        @else
                            Désactivée - Ajoutez une sécurité supplémentaire
                        @endif
                    </p>
                </div>
            </div>
            <div>
                @if($client->preferences['two_factor_enabled'] ?? false)
                    <button onclick="disableTwoFactor()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Désactiver
                    </button>
                @else
                    <button onclick="enableTwoFactor()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Activer
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Login History -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Historique de Connexion</h3>
        <p class="text-gray-600 mb-6">Consultez les connexions récentes à votre compte.</p>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Connexion actuelle</h4>
                        <p class="text-sm text-gray-500">Maintenant - {{ request()->ip() }}</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Actuel
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Connexion précédente</h4>
                        <p class="text-sm text-gray-500">Il y a 2 heures - 192.168.1.100</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Terminée
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Connexion mobile</h4>
                        <p class="text-sm text-gray-500">Hier - 10.0.0.50</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Terminée
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Security -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Sécurité du Compte</h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Email vérifié</h4>
                        <p class="text-sm text-gray-500">Votre adresse email est vérifiée</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Vérifié
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Documents d'identité</h4>
                        <p class="text-sm text-gray-500">Vérification en cours</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        En attente
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Authentification à deux facteurs</h4>
                        <p class="text-sm text-gray-500">Non activée</p>
                    </div>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Non activée
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-red-900 mb-4">Zone de Danger</h3>
        <p class="text-red-700 mb-6">Ces actions sont irréversibles. Veuillez procéder avec prudence.</p>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg bg-white">
                <div>
                    <h4 class="font-medium text-gray-900">Supprimer le compte</h4>
                    <p class="text-sm text-gray-500">Supprimer définitivement votre compte et toutes vos données</p>
                </div>
                <button onclick="deleteAccount()" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    Supprimer le Compte
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function enableTwoFactor() {
    alert('Fonctionnalité d\'authentification à deux facteurs bientôt disponible !');
}

function disableTwoFactor() {
    if (confirm('Êtes-vous sûr de vouloir désactiver l\'authentification à deux facteurs ?')) {
        alert('Fonctionnalité bientôt disponible !');
    }
}

function deleteAccount() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et supprimera toutes vos données.')) {
        if (confirm('Cette action supprimera définitivement votre compte. Tapez "SUPPRIMER" pour confirmer.')) {
            alert('Fonctionnalité de suppression de compte bientôt disponible !');
        }
    }
}
</script>


