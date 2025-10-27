<div class="space-y-6">
    <!-- Notification Preferences -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Préférences de Notification</h3>
        <p class="text-gray-600 mb-6">Choisissez comment vous souhaitez recevoir les notifications.</p>
        
        <form action="{{ route('client.profile.preferences') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Notifications par Email</h4>
                        <p class="text-sm text-gray-500">Recevoir des notifications par email pour les réservations et les mises à jour</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notifications_email" value="1" 
                               {{ (($client->preferences['notifications_email'] ?? true) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Notifications SMS</h4>
                        <p class="text-sm text-gray-500">Recevoir des notifications par SMS pour les rappels importants</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notifications_sms" value="1" 
                               {{ (($client->preferences['notifications_sms'] ?? false) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Emails Marketing</h4>
                        <p class="text-sm text-gray-500">Recevoir des offres spéciales et des promotions par email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="marketing_emails" value="1" 
                               {{ (($client->preferences['marketing_emails'] ?? false) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Sauvegarder les Préférences
                </button>
            </div>
        </form>
    </div>

    <!-- Language and Regional Settings -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Langue et Région</h3>
        
        <form action="{{ route('client.profile.preferences') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-2">Langue Préférée</label>
                    <select name="preferred_language" id="preferred_language" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="fr" {{ (($client->preferences['preferred_language'] ?? 'fr') == 'fr' ? 'selected' : '') }}>Français</option>
                        <option value="ar" {{ (($client->preferences['preferred_language'] ?? 'fr') == 'ar' ? 'selected' : '') }}>العربية</option>
                        <option value="en" {{ (($client->preferences['preferred_language'] ?? 'fr') == 'en' ? 'selected' : '') }}>English</option>
                    </select>
                </div>
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Fuseau Horaire</label>
                    <select name="timezone" id="timezone" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Africa/Casablanca" {{ (($client->preferences['timezone'] ?? 'Africa/Casablanca') == 'Africa/Casablanca' ? 'selected' : '') }}>Casablanca (GMT+1)</option>
                        <option value="Africa/Rabat" {{ (($client->preferences['timezone'] ?? 'Africa/Casablanca') == 'Africa/Rabat' ? 'selected' : '') }}>Rabat (GMT+1)</option>
                        <option value="Europe/Paris" {{ (($client->preferences['timezone'] ?? 'Africa/Casablanca') == 'Europe/Paris' ? 'selected' : '') }}>Paris (GMT+1)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>

    <!-- Privacy Settings -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres de Confidentialité</h3>
        
        <form action="{{ route('client.profile.preferences') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Profil Public</h4>
                        <p class="text-sm text-gray-500">Permettre aux autres utilisateurs de voir votre profil de base</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="public_profile" value="1" 
                               {{ (($client->preferences['public_profile'] ?? false) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Partage de Données</h4>
                        <p class="text-sm text-gray-500">Autoriser le partage de données anonymisées pour améliorer le service</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="data_sharing" value="1" 
                               {{ (($client->preferences['data_sharing'] ?? true) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>

    <!-- Emergency Contact -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact d'Urgence</h3>
        <p class="text-gray-600 mb-6">Informations de contact en cas d'urgence lors de vos locations.</p>
        
        <form action="{{ route('client.profile.preferences') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Nom du Contact</label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                           value="{{ old('emergency_contact_name', $client->emergency_contact_name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nom complet">
                </div>
                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" 
                           value="{{ old('emergency_contact_phone', $client->emergency_contact_phone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="+212 6XX XXX XXX">
                </div>
                <div>
                    <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700 mb-2">Relation</label>
                    <input type="text" name="emergency_contact_relation" id="emergency_contact_relation" 
                           value="{{ old('emergency_contact_relation', $client->emergency_contact_relation) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="ex: Époux/épouse, Parent, Ami">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>

    <!-- Account Preferences -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Préférences du Compte</h3>
        
        <form action="{{ route('client.profile.preferences') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Mode Sombre</h4>
                        <p class="text-sm text-gray-500">Activer le mode sombre pour l'interface</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="dark_mode" value="1" 
                               {{ (($client->preferences['dark_mode'] ?? false) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">Rappels de Réservation</h4>
                        <p class="text-sm text-gray-500">Recevoir des rappels avant le début de vos réservations</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="booking_reminders" value="1" 
                               {{ (($client->preferences['booking_reminders'] ?? true) ? 'checked' : '') }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>


