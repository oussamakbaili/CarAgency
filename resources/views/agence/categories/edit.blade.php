@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier la catégorie</h1>
                    <p class="mt-2 text-gray-600">{{ $category->name }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.categories.show', $category) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Voir
                    </a>
                    <a href="{{ route('agence.fleet.categories') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('agence.categories.update', $category) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de base</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la catégorie *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('sort_order')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Appearance Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Apparence</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                        <select id="icon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="car" {{ old('icon', $category->icon) == 'car' ? 'selected' : '' }}>Voiture</option>
                            <option value="suv" {{ old('icon', $category->icon) == 'suv' ? 'selected' : '' }}>SUV</option>
                            <option value="luxury" {{ old('icon', $category->icon) == 'luxury' ? 'selected' : '' }}>Luxe</option>
                            <option value="economy" {{ old('icon', $category->icon) == 'economy' ? 'selected' : '' }}>Économique</option>
                            <option value="hatchback" {{ old('icon', $category->icon) == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="sedan" {{ old('icon', $category->icon) == 'sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="convertible" {{ old('icon', $category->icon) == 'convertible' ? 'selected' : '' }}>Cabriolet</option>
                            <option value="truck" {{ old('icon', $category->icon) == 'truck' ? 'selected' : '' }}>Camion</option>
                        </select>
                        @error('icon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="color" name="color" value="{{ old('color', $category->color) }}"
                                   class="w-12 h-10 border border-gray-300 rounded-md cursor-pointer">
                            <input type="text" id="color_text" value="{{ old('color', $category->color) }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Statut</h2>
                
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Catégorie active
                    </label>
                </div>
                <p class="text-sm text-gray-500 mt-1">Les catégories inactives ne seront pas visibles dans les sélections</p>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Statistiques</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $category->cars_count }}</div>
                        <div class="text-sm text-gray-500">Véhicules dans cette catégorie</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $category->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">Date de création</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $category->updated_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">Dernière modification</div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('agence.fleet.categories') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Sync color picker with text input
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('color_text').value = this.value;
});

document.getElementById('color_text').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-F]{6}$/i)) {
        document.getElementById('color').value = this.value;
    }
});
</script>
@endpush
@endsection
