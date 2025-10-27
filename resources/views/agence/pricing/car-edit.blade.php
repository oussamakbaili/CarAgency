@extends('layouts.agence')

@section('content')
<div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier le Prix</h1>
                    <p class="mt-2 text-gray-600">{{ $car->brand }} {{ $car->model }} - {{ $car->registration_number }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.pricing.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                    <a href="{{ route('agence.pricing.car.history', $car->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Historique
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Car Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informations du Véhicule</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                                @if($car->image_url)
                                    <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h4>
                            <p class="text-sm text-gray-500">{{ $car->year }} • {{ $car->registration_number }}</p>
                            <div class="mt-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $car->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $car->is_available ? 'Disponible' : 'Occupé' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Prix actuel:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($car->price_per_day, 0) }} DH</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Réservations:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $car->rentals->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Catégorie:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $car->category ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Modifier le Prix</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('agence.pricing.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="price_per_day" class="block text-sm font-medium text-gray-700">Nouveau Prix (DH/jour)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" 
                                               name="price_per_day" 
                                               id="price_per_day" 
                                               value="{{ $car->price_per_day }}"
                                               class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" 
                                               placeholder="0"
                                               min="0"
                                               step="0.01"
                                               required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">DH</span>
                                        </div>
                                    </div>
                                    @error('price_per_day')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="seasonal_multiplier" class="block text-sm font-medium text-gray-700">Multiplicateur Saisonnier (optionnel)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="number" 
                                               name="seasonal_multiplier" 
                                               id="seasonal_multiplier" 
                                               value="1.0"
                                               class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" 
                                               placeholder="1.0"
                                               min="0.1"
                                               max="3"
                                               step="0.1">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">x</span>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Multiplicateur pour ajuster le prix selon la saison (1.0 = prix normal)</p>
                                    @error('seasonal_multiplier')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Raison du changement</label>
                                    <textarea name="reason" 
                                              id="reason" 
                                              rows="3" 
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                              placeholder="Expliquez la raison de ce changement de prix..."></textarea>
                                    @error('reason')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Price Preview -->
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">Aperçu du Prix</h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-blue-700">Prix de base:</span>
                                        <span class="text-sm font-semibold text-blue-900" id="base-price">{{ number_format($car->price_per_day, 0) }} DH</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-blue-700">Avec multiplicateur:</span>
                                        <span class="text-sm font-semibold text-blue-900" id="final-price">{{ number_format($car->price_per_day, 0) }} DH</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-3">
                                <a href="{{ route('agence.pricing.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Annuler
                                </a>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                    Mettre à jour le prix
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price_per_day');
    const multiplierInput = document.getElementById('seasonal_multiplier');
    const basePriceSpan = document.getElementById('base-price');
    const finalPriceSpan = document.getElementById('final-price');

    function updatePricePreview() {
        const basePrice = parseFloat(priceInput.value) || 0;
        const multiplier = parseFloat(multiplierInput.value) || 1;
        const finalPrice = basePrice * multiplier;

        basePriceSpan.textContent = basePrice.toLocaleString() + ' DH';
        finalPriceSpan.textContent = finalPrice.toLocaleString() + ' DH';
    }

    priceInput.addEventListener('input', updatePricePreview);
    multiplierInput.addEventListener('input', updatePricePreview);
});
</script>
@endpush
@endsection
