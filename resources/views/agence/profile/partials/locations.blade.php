<form action="{{ route('agence.profile.locations') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de Localisation</h3>
            <p class="text-sm text-gray-600 mb-4">Définissez l'adresse et les coordonnées de votre agence.</p>
        </div>
        
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
            <textarea name="address" id="address" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" 
                      placeholder="Entrez l'adresse complète de votre agence...">{{ old('address', $agency->address) }}</textarea>
            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                <input type="text" name="city" id="city" value="{{ old('city', $agency->city) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror" required>
                @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code Postal</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $agency->postal_code) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('postal_code') border-red-500 @enderror" required>
                @error('postal_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                <input type="text" name="country" id="country" value="{{ old('country', $agency->country ?? 'Maroc') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror" required>
                @error('country')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude (optionnel)</label>
                <input type="number" name="latitude" id="latitude" step="any" value="{{ old('latitude', $agency->latitude) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror" 
                       placeholder="Ex: 33.5731">
                @error('latitude')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude (optionnel)</label>
                <input type="number" name="longitude" id="longitude" step="any" value="{{ old('longitude', $agency->longitude) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror" 
                       placeholder="Ex: -7.5898">
                @error('longitude')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Conseil</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Les coordonnées GPS (latitude/longitude) permettent aux clients de localiser facilement votre agence sur une carte. Vous pouvez les obtenir en utilisant Google Maps ou un autre service de géolocalisation.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Sauvegarder
            </button>
        </div>
    </div>
</form>
