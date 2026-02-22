<div class="space-y-6">
    <!-- Profile Picture Section -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Photo de Profil</h3>
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                @if($client->profile_picture)
                    <img class="h-20 w-20 rounded-full object-cover" 
                         src="{{ Storage::url($client->profile_picture) }}" 
                         alt="{{ $client->user->name }}">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-2xl font-bold text-gray-600">{{ substr($client->user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <form action="{{ route('client.profile.picture') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <input type="file" name="profile_picture" id="profile_picture" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept="image/*">
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Mettre à jour
                        </button>
                        @if($client->profile_picture)
                            <button type="button" onclick="deleteProfilePicture()" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Supprimer
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- General Information Form -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Personnelles</h3>
        <form action="{{ route('client.profile.general') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $client->user->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $client->user->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cin" class="block text-sm font-medium text-gray-700 mb-2">CIN</label>
                    <input type="text" name="cin" id="cin" value="{{ old('cin', $client->cin) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('cin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $client->date_of_birth?->format('Y-m-d')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                    <select name="gender" id="gender" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner</option>
                        <option value="male" {{ old('gender', $client->gender) == 'male' ? 'selected' : '' }}>Homme</option>
                        <option value="female" {{ old('gender', $client->gender) == 'female' ? 'selected' : '' }}>Femme</option>
                        <option value="other" {{ old('gender', $client->gender) == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">Nationalité</label>
                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $client->nationality) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nationality')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                <input type="text" name="address" id="address" value="{{ old('address', $client->address) }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $client->city) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $client->postal_code) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                    <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $client->occupation) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('occupation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Entreprise</label>
                    <input type="text" name="company" id="company" value="{{ old('company', $client->company) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('company')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                <textarea name="bio" id="bio" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Parlez-nous de vous...">{{ old('bio', $client->bio) }}</textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- Driving License Section -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Permis de Conduire</h3>
        <form action="{{ route('client.profile.general') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="driving_license_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro de permis</label>
                    <input type="text" name="driving_license_number" id="driving_license_number" 
                           value="{{ old('driving_license_number', $client->driving_license_number) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('driving_license_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="driving_license_expiry" class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration</label>
                    <input type="date" name="driving_license_expiry" id="driving_license_expiry" 
                           value="{{ old('driving_license_expiry', $client->driving_license_expiry?->format('Y-m-d')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('driving_license_expiry')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>

    <!-- Emergency Contact Section -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact d'Urgence</h3>
        <form action="{{ route('client.profile.preferences') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                           value="{{ old('emergency_contact_name', $client->emergency_contact_name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" 
                           value="{{ old('emergency_contact_phone', $client->emergency_contact_phone) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="emergency_contact_relation" class="block text-sm font-medium text-gray-700 mb-2">Relation</label>
                    <input type="text" name="emergency_contact_relation" id="emergency_contact_relation" 
                           value="{{ old('emergency_contact_relation', $client->emergency_contact_relation) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="ex: Époux/épouse, Parent, Ami">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function deleteProfilePicture() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
        fetch('{{ route("client.profile.picture.delete") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression de la photo');
            }
        });
    }
}
</script>


