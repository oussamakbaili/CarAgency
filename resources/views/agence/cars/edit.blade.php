@extends('layouts.agence')

@section('content')
            <div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier le Véhicule</h1>
                <p class="text-gray-600 mt-2">{{ $car->brand }} {{ $car->model }} - {{ $car->registration_number }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('agence.fleet.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('agence.cars.show', $car->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Voir
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('agence.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations Générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Marque *</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $car->brand) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Modèle *</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('model')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">Immatriculation *</label>
                        <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number', $car->registration_number) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('registration_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Année *</label>
                    <input type="number" name="year" id="year" value="{{ old('year', $car->year) }}" required min="1900" max="2030"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category_id" id="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach(\App\Models\Category::active()->ordered()->get() as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $car->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                    <input type="text" name="color" id="color" value="{{ old('color', $car->color) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('color')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $car->description) }}</textarea>
                        @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Technical Specifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Spécifications Techniques</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-2">Type de Carburant</label>
                    <select name="fuel_type" id="fuel_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="Gasoline" {{ old('fuel_type', $car->fuel_type) == 'Gasoline' ? 'selected' : '' }}>Essence</option>
                        <option value="Diesel" {{ old('fuel_type', $car->fuel_type) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Hybrid" {{ old('fuel_type', $car->fuel_type) == 'Hybrid' ? 'selected' : '' }}>Hybride</option>
                        <option value="Electric" {{ old('fuel_type', $car->fuel_type) == 'Electric' ? 'selected' : '' }}>Électrique</option>
                    </select>
                    @error('fuel_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transmission" class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select name="transmission" id="transmission"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un type</option>
                        <option value="Automatic" {{ old('transmission', $car->transmission) == 'Automatic' ? 'selected' : '' }}>Automatique</option>
                        <option value="Manual" {{ old('transmission', $car->transmission) == 'Manual' ? 'selected' : '' }}>Manuelle</option>
                        <option value="Semi-Automatic" {{ old('transmission', $car->transmission) == 'Semi-Automatic' ? 'selected' : '' }}>Semi-Automatique</option>
                    </select>
                    @error('transmission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="seats" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Places</label>
                    <input type="number" name="seats" id="seats" value="{{ old('seats', $car->seats) }}" min="1" max="20"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('seats')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="engine_size" class="block text-sm font-medium text-gray-700 mb-2">Cylindrée</label>
                    <input type="text" name="engine_size" id="engine_size" value="{{ old('engine_size', $car->engine_size) }}" placeholder="ex: 2.0L"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('engine_size')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mileage" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage</label>
                    <input type="number" name="mileage" id="mileage" value="{{ old('mileage', $car->mileage) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('mileage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Availability -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Tarification & Disponibilité</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-700 mb-2">Prix par Jour (MAD) *</label>
                    <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}" required min="0" step="0.01"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('price_per_day')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="rented" {{ old('status', $car->status) == 'rented' ? 'selected' : '' }}>En Location</option>
                        <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Stock Management -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Gestion du Stock</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantité en Stock *</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $car->stock_quantity) }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                    <label for="available_stock" class="block text-sm font-medium text-gray-700 mb-2">Stock Disponible *</label>
                                <input type="number" name="available_stock" id="available_stock" value="{{ old('available_stock', $car->available_stock) }}" required min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('available_stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                    <input type="checkbox" name="track_stock" id="track_stock" value="1" {{ old('track_stock', $car->track_stock) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="track_stock" class="ml-2 block text-sm text-gray-700">
                        Suivre le stock
                    </label>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Maintenance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="last_maintenance" class="block text-sm font-medium text-gray-700 mb-2">Dernière Maintenance</label>
                    <input type="date" name="last_maintenance" id="last_maintenance" value="{{ old('last_maintenance', $car->last_maintenance?->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('last_maintenance')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="maintenance_due" class="block text-sm font-medium text-gray-700 mb-2">Prochaine Maintenance</label>
                    <input type="date" name="maintenance_due" id="maintenance_due" value="{{ old('maintenance_due', $car->maintenance_due?->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('maintenance_due')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Équipements</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                    $commonFeatures = ['GPS', 'Bluetooth', 'Air Conditioning', 'Leather Seats', 'Sunroof', 'Backup Camera', 'USB Port', 'Heated Seats', 'Cruise Control', 'Parking Sensors', 'Keyless Entry', 'Remote Start'];
                    $selectedFeatures = old('features', $car->features ?? []);
                @endphp
                @foreach($commonFeatures as $feature)
                    <div class="flex items-center">
                        <input type="checkbox" name="features[]" id="feature_{{ $loop->index }}" value="{{ $feature }}" 
                            {{ in_array($feature, $selectedFeatures) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="feature_{{ $loop->index }}" class="ml-2 text-sm text-gray-700">
                            {{ $feature }}
                                </label>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <label for="custom_features" class="block text-sm font-medium text-gray-700 mb-2">Autres équipements (séparés par des virgules)</label>
                <input type="text" name="custom_features" id="custom_features" placeholder="ex: Wi-Fi, Barre de toit, Sièges chauffants"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
                            </div>

        <!-- Images -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Photos du Véhicule</h2>
            
            <!-- Main Image -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Photo Principale</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Télécharger une photo</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, 'imagePreview')">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                    </div>
                </div>
                @if($car->image)
                    <div class="mt-4">
                        <img id="imagePreview" src="{{ $car->image_url }}" alt="Photo actuelle" class="h-32 w-auto rounded-lg">
                        <p class="text-sm text-gray-500 mt-2">Photo actuelle</p>
                    </div>
                @endif
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                    </div>

            <!-- Multiple Pictures -->
            <div>
                <label for="pictures" class="block text-sm font-medium text-gray-700 mb-2">Photos Supplémentaires (maximum 4 photos)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="pictures" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Télécharger des photos</span>
                                <input id="pictures" name="pictures[]" type="file" class="sr-only" accept="image/*" multiple onchange="previewImages(this, 'picturesPreview')">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB chacune (maximum 4)</p>
                    </div>
                </div>
                @if($car->pictures && count($car->pictures) > 0)
                    <div class="mt-4">
                        <div id="picturesPreview" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($car->picture_urls as $index => $pictureUrl)
                                <div class="relative">
                                    <img src="{{ $pictureUrl }}" alt="Photo {{ $index + 1 }}" class="h-32 w-full object-cover rounded-lg">
                                    <p class="text-sm text-gray-500 mt-1">Photo {{ $index + 1 }}</p>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Photos actuelles</p>
                    </div>
                @endif
                @error('pictures')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('pictures.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('agence.fleet.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Mettre à jour
            </button>
    </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById(previewId);
            if (!preview) {
                preview = document.createElement('img');
                preview.id = previewId;
                preview.className = 'h-32 w-auto rounded-lg mt-4';
                input.parentNode.parentNode.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewImages(input, previewId) {
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.innerHTML = '';
        preview.classList.remove('hidden');
    }
    
    if (input.files) {
        const fileCount = input.files.length;
        
        // Validate file count
        if (fileCount > 4) {
            alert('Vous ne pouvez sélectionner que 4 photos maximum.');
            input.value = '';
            return;
        }
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Photo ${index + 1}" class="h-32 w-full object-cover rounded-lg">
                    <p class="text-sm text-gray-500 mt-1">Photo ${index + 1}</p>
                `;
                if (preview) {
                    preview.appendChild(div);
                }
            }
            reader.readAsDataURL(file);
        });
        
        // Show file count
        if (fileCount > 0) {
            const fileCountDiv = document.createElement('div');
            fileCountDiv.className = 'mt-2 text-sm text-blue-600 font-medium';
            fileCountDiv.textContent = `${fileCount} nouvelle(s) photo(s) sélectionnée(s)`;
            if (preview) {
                preview.parentNode.insertBefore(fileCountDiv, preview.nextSibling);
            }
        }
    }
}

// Handle custom features
document.addEventListener('DOMContentLoaded', function() {
    const customFeaturesInput = document.getElementById('custom_features');
    const featuresCheckboxes = document.querySelectorAll('input[name="features[]"]');
    
    // Add custom features when input changes
    customFeaturesInput.addEventListener('blur', function() {
        const customFeatures = this.value.split(',').map(f => f.trim()).filter(f => f);
        customFeatures.forEach(feature => {
            // Check if feature already exists
            const existingCheckbox = Array.from(featuresCheckboxes).find(cb => cb.value === feature);
            if (!existingCheckbox) {
                // Create new checkbox
                const container = document.querySelector('.grid.grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4');
                const div = document.createElement('div');
                div.className = 'flex items-center';
                div.innerHTML = `
                    <input type="checkbox" name="features[]" id="custom_${feature}" value="${feature}" checked
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="custom_${feature}" class="ml-2 text-sm text-gray-700">
                        ${feature}
                    </label>
                `;
                container.appendChild(div);
            }
        });
    });
});
</script>
@endpush
@endsection