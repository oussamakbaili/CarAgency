@extends('layouts.agence')

@section('content')
<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Nouvelle Maintenance</h1>
                    <p class="mt-2 text-gray-600">Programmez une nouvelle maintenance pour un véhicule</p>
                </div>
                <a href="{{ route('agence.fleet.maintenance') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <form action="{{ route('agence.maintenances.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Vehicle Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Sélection du Véhicule</h2>
                
                <div>
                    <label for="car_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                    <select name="car_id" id="car_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                {{ $car->brand }} {{ $car->model }} {{ $car->year }} - {{ $car->registration_number }}
                                @if($car->category && $car->category->name)
                                    ({{ $car->category->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('car_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Maintenance Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Détails de la Maintenance</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de la maintenance *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Révision générale - 15,000 km">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de maintenance *</label>
                        <select name="type" id="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="routine" {{ old('type') == 'routine' ? 'selected' : '' }}>Routine</option>
                            <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>Réparation</option>
                            <option value="inspection" {{ old('type') == 'inspection' ? 'selected' : '' }}>Inspection</option>
                            <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Urgence</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Programmé</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez les détails de la maintenance...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule & Dates -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Planification</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Scheduled Date -->
                    <div>
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">Date programmée *</label>
                        <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('scheduled_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Garage & Cost -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Garage et Coût</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Garage Name -->
                    <div>
                        <label for="garage_name" class="block text-sm font-medium text-gray-700 mb-2">Nom du garage</label>
                        <input type="text" id="garage_name" name="garage_name" value="{{ old('garage_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: Auto Service Plus">
                        @error('garage_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Garage Contact -->
                    <div>
                        <label for="garage_contact" class="block text-sm font-medium text-gray-700 mb-2">Contact du garage</label>
                        <input type="text" id="garage_contact" name="garage_contact" value="{{ old('garage_contact') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: +212 6 12 34 56 78">
                        @error('garage_contact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cost -->
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Coût (DH)</label>
                        <input type="number" id="cost" name="cost" value="{{ old('cost') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                        @error('cost')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mileage at Service -->
                    <div>
                        <label for="mileage_at_service" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage au service</label>
                        <input type="number" id="mileage_at_service" name="mileage_at_service" value="{{ old('mileage_at_service') }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: 15000">
                        @error('mileage_at_service')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Notes supplémentaires sur la maintenance...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('agence.fleet.maintenance') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Créer la maintenance
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Auto-fill start date when status is in_progress
document.addEventListener('DOMContentLoaded', function() {
    const statusField = document.getElementById('status');
    const startDateField = document.getElementById('start_date');
    const endDateField = document.getElementById('end_date');
    
    if (statusField && startDateField && endDateField) {
        statusField.addEventListener('change', function() {
            if (this.value === 'in_progress') {
                startDateField.value = new Date().toISOString().split('T')[0];
            }
            
            if (this.value === 'completed') {
                endDateField.value = new Date().toISOString().split('T')[0];
            }
        });
    }
});
</script>
@endpush
@endsection
