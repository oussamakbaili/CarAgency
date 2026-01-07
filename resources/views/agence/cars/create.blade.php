@extends('layouts.agence')

@section('content')
            <div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Ajouter un Véhicule</h1>
                <p class="text-gray-600 mt-2">Ajoutez un nouveau véhicule à votre flotte avec photos et spécifications</p>
            </div>
            <a href="{{ route('agence.fleet.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <form action="{{ route('agence.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations Générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Marque *</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Modèle *</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('model')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">Immatriculation *</label>
                        <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('registration_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Année *</label>
                    <input type="number" name="year" id="year" value="{{ old('year') }}" required min="1900" max="2030"
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                        <div class="relative">
                            @php
                                $colors = [
                                    'Noir' => '#000000',
                                    'Blanc' => '#FFFFFF',
                                    'Gris' => '#808080',
                                    'Argent' => '#C0C0C0',
                                    'Bleu' => '#0066CC',
                                    'Rouge' => '#CC0000',
                                    'Vert' => '#008000',
                                    'Marron' => '#8B4513',
                                    'Beige' => '#F5F5DC',
                                    'Jaune' => '#FFD700',
                                    'Orange' => '#FF6600',
                                ];
                                $selectedColor = old('color', '');
                                $selectedColorHex = $selectedColor && isset($colors[$selectedColor]) ? $colors[$selectedColor] : '';
                            @endphp
                            
                            <!-- Dropdown Button -->
                            <button type="button" id="color-dropdown-button" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex items-center justify-between hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div class="flex items-center space-x-3">
                                    @if($selectedColor && $selectedColorHex)
                                        <div class="w-5 h-5 rounded-full border border-gray-300 flex-shrink-0" 
                                             style="background-color: {{ $selectedColorHex }}; {{ $selectedColor === 'Blanc' || $selectedColor === 'Beige' || $selectedColor === 'Argent' ? 'border-color: #999; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);' : '' }}"></div>
                                        <span class="text-sm text-gray-700" id="color-selected-label">{{ $selectedColor }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">Sélectionner une couleur</span>
                                    @endif
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" id="color-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="color-dropdown-menu" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                                <div class="p-2 space-y-1">
                                    @foreach($colors as $colorName => $colorHex)
                                        <label class="flex items-center space-x-3 px-3 py-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="color" value="{{ $colorName }}" {{ $selectedColor === $colorName ? 'checked' : '' }}
                                                class="sr-only color-radio">
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex-shrink-0" 
                                                 style="background-color: {{ $colorHex }}; {{ $colorName === 'Blanc' || $colorName === 'Beige' || $colorName === 'Argent' ? 'border-color: #999; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);' : '' }}"></div>
                                            <span class="text-sm text-gray-700 flex-1">{{ $colorName }}</span>
                                            @if($selectedColor === $colorName)
                                                <svg class="w-5 h-5 text-blue-600 check-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-blue-600 check-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
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
                        <option value="Gasoline" {{ old('fuel_type') == 'Gasoline' ? 'selected' : '' }}>Essence</option>
                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybride</option>
                        <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Électrique</option>
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
                        <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatique</option>
                        <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manuelle</option>
                        <option value="Semi-Automatic" {{ old('transmission') == 'Semi-Automatic' ? 'selected' : '' }}>Semi-Automatique</option>
                    </select>
                    @error('transmission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="seats" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Places</label>
                    <input type="number" name="seats" id="seats" value="{{ old('seats') }}" min="1" max="20"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('seats')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="engine_size" class="block text-sm font-medium text-gray-700 mb-2">Cylindrée</label>
                    <input type="text" name="engine_size" id="engine_size" value="{{ old('engine_size') }}" placeholder="ex: 2.0L"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('engine_size')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mileage" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage</label>
                    <input type="number" name="mileage" id="mileage" value="{{ old('mileage') }}" min="0"
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
                    <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day') }}" required min="0" step="0.01"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('price_per_day')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>En Location</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                    <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 1) }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                <div class="flex items-center">
                    <input type="checkbox" name="track_stock" id="track_stock" value="1" {{ old('track_stock') ? 'checked' : '' }}
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
                    <input type="date" name="last_maintenance" id="last_maintenance" value="{{ old('last_maintenance') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('last_maintenance')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                    <label for="maintenance_due" class="block text-sm font-medium text-gray-700 mb-2">Prochaine Maintenance</label>
                    <input type="date" name="maintenance_due" id="maintenance_due" value="{{ old('maintenance_due') }}"
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
                    $selectedFeatures = old('features', []);
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
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="imagePreviewImg" src="" alt="Aperçu" class="h-32 w-auto rounded-lg">
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                    </div>

            <!-- Multiple Pictures -->
            <div>
                <label for="pictures" class="block text-sm font-medium text-gray-700 mb-2">Photos Supplémentaires * (1-4 photos)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="pictures" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Télécharger des photos</span>
                                <input id="pictures" name="pictures[]" type="file" class="sr-only" accept="image/*" multiple required onchange="previewImages(this, 'picturesPreview')">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB chacune (minimum 1, maximum 4)</p>
                    </div>
                </div>
                <div id="picturesPreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden">
                </div>
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
                Ajouter le Véhicule
            </button>
    </div>
    </form>
</div>

@push('scripts')
<script>
// Auto-load vehicle data from similar vehicles in database
let autoLoadTimeout;
let isAutoLoading = false;

function searchSimilarVehicle() {
    const brand = document.getElementById('brand')?.value.trim();
    const model = document.getElementById('model')?.value.trim();
    const year = document.getElementById('year')?.value.trim();

    // Only search if we have at least brand and model
    if (!brand || !model || isAutoLoading) {
        return;
    }

    // Clear previous timeout
    clearTimeout(autoLoadTimeout);

    // Debounce: wait 800ms after user stops typing
    autoLoadTimeout = setTimeout(async () => {
        try {
            isAutoLoading = true;
            
            const url = new URL('{{ route("agence.cars.search-similar") }}');
            if (brand) url.searchParams.append('brand', brand);
            if (model) url.searchParams.append('model', model);
            if (year) url.searchParams.append('year', year);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success && data.car) {
                // Show notification
                showAutoLoadNotification('Données chargées automatiquement depuis un véhicule similaire');

                // Fill in the fields that are empty
                if (data.car.fuel_type && !document.getElementById('fuel_type').value) {
                    document.getElementById('fuel_type').value = data.car.fuel_type;
                }
                if (data.car.transmission && !document.getElementById('transmission').value) {
                    document.getElementById('transmission').value = data.car.transmission;
                }
                if (data.car.seats && !document.getElementById('seats').value) {
                    document.getElementById('seats').value = data.car.seats;
                }
                if (data.car.engine_size && !document.getElementById('engine_size').value) {
                    document.getElementById('engine_size').value = data.car.engine_size;
                }
                if (data.car.color && !document.querySelector('input[name="color"]:checked')) {
                    const colorRadio = document.querySelector(`input[name="color"][value="${data.car.color}"]`);
                    if (colorRadio) {
                        colorRadio.checked = true;
                        // Trigger change event to update UI
                        colorRadio.dispatchEvent(new Event('change'));
                    }
                }
                if (data.car.category_id && !document.getElementById('category_id').value) {
                    document.getElementById('category_id').value = data.car.category_id;
                }
                if (data.car.description && !document.getElementById('description').value) {
                    document.getElementById('description').value = data.car.description;
                }
                // Load features if available
                if (data.car.features && Array.isArray(data.car.features)) {
                    data.car.features.forEach(feature => {
                        const featureCheckbox = document.querySelector(`input[name="features[]"][value="${feature}"]`);
                        if (featureCheckbox && !featureCheckbox.checked) {
                            featureCheckbox.checked = true;
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error loading similar vehicle data:', error);
        } finally {
            isAutoLoading = false;
        }
    }, 800);
}

function showAutoLoadNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3';
    notification.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>${message}</span>
    `;
    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const brandInput = document.getElementById('brand');
    const modelInput = document.getElementById('model');
    const yearInput = document.getElementById('year');

    if (brandInput) {
        brandInput.addEventListener('input', searchSimilarVehicle);
    }
    if (modelInput) {
        modelInput.addEventListener('input', searchSimilarVehicle);
    }
    if (yearInput) {
        yearInput.addEventListener('input', searchSimilarVehicle);
    }
});

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const previewImg = document.getElementById(previewId + 'Img');
            if (preview && previewImg) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
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
        if (fileCount < 1) {
            alert('Veuillez sélectionner au moins 1 photo.');
            input.value = '';
            return;
        }
        
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
        const fileCountDiv = document.createElement('div');
        fileCountDiv.className = 'mt-2 text-sm text-blue-600 font-medium';
        fileCountDiv.textContent = `${fileCount} photo(s) sélectionnée(s)`;
        if (preview) {
            preview.parentNode.insertBefore(fileCountDiv, preview.nextSibling);
        }
    }
}

// Handle color dropdown
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('color-dropdown-button');
    const dropdownMenu = document.getElementById('color-dropdown-menu');
    const dropdownArrow = document.getElementById('color-dropdown-arrow');
    const colorRadios = document.querySelectorAll('input[name="color"]');
    const selectedLabel = document.getElementById('color-selected-label');
    const colorButton = dropdownButton.querySelector('.flex.items-center.space-x-3');
    
    const colors = {
        'Noir': '#000000',
        'Blanc': '#FFFFFF',
        'Gris': '#808080',
        'Argent': '#C0C0C0',
        'Bleu': '#0066CC',
        'Rouge': '#CC0000',
        'Vert': '#008000',
        'Marron': '#8B4513',
        'Beige': '#F5F5DC',
        'Jaune': '#FFD700',
        'Orange': '#FF6600'
    };
    
    // Toggle dropdown
    dropdownButton.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = !dropdownMenu.classList.contains('hidden');
        if (isOpen) {
            dropdownMenu.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
        } else {
            dropdownMenu.classList.remove('hidden');
            dropdownArrow.classList.add('rotate-180');
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
        }
    });
    
    // Handle color selection
    colorRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const colorName = this.value;
            const colorHex = colors[colorName];
            
            // Update button display
            if (colorButton) {
                const isLightColor = colorName === 'Blanc' || colorName === 'Beige' || colorName === 'Argent';
                colorButton.innerHTML = `
                    <div class="w-5 h-5 rounded-full border border-gray-300 flex-shrink-0" 
                         style="background-color: ${colorHex}; ${isLightColor ? 'border-color: #999; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);' : ''}"></div>
                    <span class="text-sm text-gray-700">${colorName}</span>
                `;
            }
            
            // Update check icons in dropdown
            document.querySelectorAll('#color-dropdown-menu .color-radio').forEach(r => {
                const label = r.closest('label');
                const checkIcon = label.querySelector('.check-icon');
                if (r.value === colorName) {
                    if (checkIcon) checkIcon.classList.remove('hidden');
                } else {
                    if (checkIcon) checkIcon.classList.add('hidden');
                }
            });
            
            // Close dropdown
            dropdownMenu.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
        });
    });
});

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