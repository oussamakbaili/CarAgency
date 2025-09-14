@extends('layouts.agence')

@section('title', 'Tarification Dynamique')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tarification Dynamique</h1>
            <p class="text-gray-600">Configurez et gérez vos tarifs dynamiques en fonction de la demande</p>
        </div>
        <button onclick="showNewRuleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
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
                    <p class="text-sm font-medium text-gray-600">Tarif Moyen</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pricingOverview['average_daily_rate'] ?? 0, 0) }} MAD</p>
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
                    <p class="text-sm font-medium text-gray-600">Revenus Optimisés</p>
                    <p class="text-2xl font-bold text-green-600">+15.3%</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Règles Actives</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $dynamicConfigs->count() }}</p>
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

    <!-- Dynamic Pricing Rules -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Règles de Tarification Dynamique</h2>
                <button onclick="showNewRuleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Nouvelle Règle
                </button>
            </div>
        </div>
        
        <div class="p-6">
            @if($dynamicConfigs->count() > 0)
                <div class="space-y-4">
                    @foreach($dynamicConfigs as $config)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-900">Configuration Dynamique</h3>
                                    <p class="text-sm text-gray-600">
                                        Multiplicateur Heures de Pointe: {{ $config->peak_hour_multiplier }}x • 
                                        Weekend: {{ $config->weekend_multiplier }}x • 
                                        Dernière Minute: {{ $config->last_minute_multiplier }}x
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Seuil de Demande: {{ $config->demand_threshold }}% • 
                                        Statut: 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $config->enabled ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editRule({{ $config->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Modifier
                                    </button>
                                    <button onclick="deleteRule({{ $config->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune règle de tarification dynamique</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle règle de tarification dynamique.</p>
                    <div class="mt-6">
                        <button onclick="showNewRuleModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
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

<!-- New Rule Modal -->
<div id="newRuleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Règle de Tarification Dynamique</h3>
                <button onclick="closeModal('newRuleModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.dynamic.configure') }}" method="POST" id="dynamicPricingForm">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="enabled" id="enabled" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="enabled" class="ml-2 block text-sm text-gray-900">
                            Activer la tarification dynamique
                        </label>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Heures de Pointe</label>
                        <input type="number" name="peak_hour_multiplier" step="0.1" min="1" max="3" value="1.2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="mt-1 text-xs text-gray-500">Ex: 1.2 = +20% du tarif de base</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Weekend</label>
                        <input type="number" name="weekend_multiplier" step="0.1" min="1" max="3" value="1.1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="mt-1 text-xs text-gray-500">Ex: 1.1 = +10% du tarif de base</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Dernière Minute</label>
                        <input type="number" name="last_minute_multiplier" step="0.1" min="0.5" max="2" value="0.9" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="mt-1 text-xs text-gray-500">Ex: 0.9 = -10% du tarif de base</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Seuil de Demande (%)</label>
                        <input type="number" name="demand_threshold" min="0" max="100" value="80" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="mt-1 text-xs text-gray-500">Pourcentage de réservations pour déclencher les tarifs dynamiques</p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('newRuleModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
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
function showNewRuleModal() {
    console.log('Opening new rule modal');
    const modal = document.getElementById('newRuleModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: newRuleModal');
        alert('Erreur: Modal non trouvé');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editRule(id) {
    alert('Fonctionnalité de modification à implémenter pour la règle ID: ' + id);
}

function deleteRule(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette règle ?')) {
        alert('Fonctionnalité de suppression à implémenter pour la règle ID: ' + id);
    }
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('dynamicPricingForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Dynamic pricing form submitted');
            
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
                closeModal('newRuleModal');
                alert(data.message || 'Règle créée avec succès');
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