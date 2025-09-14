@extends('layouts.agence')

@section('title', 'Modifier Règle Saisonnière')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier Règle Saisonnière</h1>
            <p class="text-gray-600">Modifiez les paramètres de votre règle saisonnière</p>
        </div>
        <a href="{{ route('agence.pricing.seasonal') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            Retour
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6">
            <form action="{{ route('agence.pricing.seasonal.update', $rule->id) }}" method="POST" id="editSeasonalRuleForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom de la Règle</label>
                            <input type="text" name="name" value="{{ $rule->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Multiplicateur de Prix</label>
                            <input type="number" name="price_multiplier" step="0.1" min="0.1" max="3" value="{{ $rule->price_multiplier }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <p class="mt-1 text-xs text-gray-500">Ex: 1.5 = +50% du tarif de base, 0.8 = -20% du tarif de base</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Description de la règle saisonnière">{{ $rule->description }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Début</label>
                            <input type="date" name="start_date" value="{{ $rule->start_date }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Fin</label>
                            <input type="date" name="end_date" value="{{ $rule->end_date }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Véhicules Concernés</label>
                        <select name="vehicle_ids[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}" 
                                    {{ in_array($car->id, $rule->vehicle_ids ?? []) ? 'selected' : '' }}>
                                    {{ $car->brand }} {{ $car->model }} - {{ number_format($car->price_per_day, 0) }} DH
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs véhicules</p>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('agence.pricing.seasonal') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editSeasonalRuleForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Edit seasonal rule form submitted');
            
            const formData = new FormData(this);
            console.log('Form data:', Object.fromEntries(formData));
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
                alert(data.message || 'Règle saisonnière mise à jour avec succès');
                window.location.href = '{{ route("agence.pricing.seasonal") }}';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour de la règle: ' + error.message);
            });
        });
    }
});
</script>
@endsection
