@extends('layouts.agence')

@section('title', 'Tarifs Saisonniers')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tarifs Saisonniers</h1>
            <p class="text-gray-600">Gérez vos règles de tarification saisonnières</p>
        </div>
        <button onclick="showNewSeasonalModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Nouvelle Règle
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Règles Actives</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seasonalRules->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenus Saisonniers</p>
                    <p class="text-2xl font-bold text-green-600">+22.5%</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Périodes Configurées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seasonalRules->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Véhicules</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $cars->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seasonal Rules -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Règles de Tarification Saisonnières</h2>
                <button onclick="showNewSeasonalModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Nouvelle Règle
                </button>
            </div>
        </div>
        
        <div class="p-6">
            @if($seasonalRules->count() > 0)
                <div class="space-y-4">
                    @foreach($seasonalRules as $rule)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-medium text-gray-900">{{ $rule->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $rule->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </div>
                                    
                                    @if($rule->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ $rule->description }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">Période:</span>
                                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($rule->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($rule->end_date)->format('d/m/Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Multiplicateur:</span>
                                            <span class="text-gray-600">{{ $rule->price_multiplier }}x</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Véhicules:</span>
                                            <span class="text-gray-600">{{ count($rule->vehicle_ids ?? []) }} sélectionné(s)</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2 ml-4">
                                    <button onclick="editSeasonalRule({{ $rule->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Modifier
                                    </button>
                                    <button onclick="toggleSeasonalRule({{ $rule->id }})" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                        {{ $rule->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                    <button onclick="deleteSeasonalRule({{ $rule->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune règle saisonnière</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle règle de tarification saisonnière.</p>
                    <div class="mt-6">
                        <button onclick="showNewSeasonalModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nouvelle Règle
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- New Seasonal Rule Modal -->
<div id="newSeasonalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Règle Saisonnière</h3>
                <button onclick="closeModal('newSeasonalModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.seasonal.create') }}" method="POST" id="seasonalRuleForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de la Règle</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Tarif Été 2024" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
                        <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2" placeholder="Description de la règle saisonnière"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Début</label>
                            <input type="date" name="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Fin</label>
                            <input type="date" name="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur de Prix</label>
                        <input type="number" name="price_multiplier" step="0.1" min="0.1" max="3" value="1.0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="mt-1 text-xs text-gray-500">Ex: 1.5 = +50% du tarif de base, 0.8 = -20% du tarif de base</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Véhicules Concernés</label>
                        <select name="vehicle_ids[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->model }} - {{ number_format($car->price_per_day, 0) }} DH</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs véhicules</p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('newSeasonalModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Créer la Règle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showNewSeasonalModal() {
    console.log('Opening new seasonal rule modal');
    const modal = document.getElementById('newSeasonalModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: newSeasonalModal');
        alert('Erreur: Modal non trouvé');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editSeasonalRule(id) {
    window.location.href = '{{ route("agence.pricing.seasonal.edit", ":id") }}'.replace(':id', id);
}

function toggleSeasonalRule(id) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cette règle ?')) {
        fetch('{{ route("agence.pricing.seasonal.toggle", ":id") }}'.replace(':id', id), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du changement de statut');
        });
    }
}

function deleteSeasonalRule(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette règle ? Cette action est irréversible.')) {
        fetch('{{ route("agence.pricing.seasonal.delete", ":id") }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('seasonalRuleForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Seasonal rule form submitted');
            
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
                closeModal('newSeasonalModal');
                alert(data.message || 'Règle saisonnière créée avec succès');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la création de la règle: ' + error.message);
            });
        });
    }
});
</script>
@endsection