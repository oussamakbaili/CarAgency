@extends('layouts.admin')

@section('title', 'Validation des Calculs de Commission')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Validation des Calculs de Commission</h1>
        <p class="text-gray-600 mt-2">Vérification de l'exactitude des calculs de commission sur les réservations</p>
    </div>

    <!-- Statistiques de validation -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Calculs Valides</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $validations->where('is_valid', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Calculs Invalides</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $invalidCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Vérifiées</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $validations->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Actions de Validation</h3>
                <p class="text-gray-600">Vérifiez et corrigez les calculs de commission</p>
            </div>
            <div class="flex gap-2">
                <button onclick="validateAll()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>Valider Tout
                </button>
                <a href="{{ route('admin.commissions.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour Dashboard
                </a>
            </div>
        </div>
    </div>

    @if($invalidCount > 0)
    <!-- Alert pour les calculs invalides -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-red-800 font-semibold">Attention !</h4>
                <p class="text-red-700">{{ $invalidCount }} calcul(s) de commission invalide(s) détecté(s). Vérifiez les détails ci-dessous.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Tableau de validation -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails des Validations</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Différence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($validations as $validation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $validation['rental_id'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($validation['is_valid'])
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Valide
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Invalide
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($validation['difference'] != 0)
                                <span class="text-red-600 font-semibold">
                                    {{ number_format($validation['difference'], 4) }} MAD
                                </span>
                            @else
                                <span class="text-green-600 font-semibold">
                                    0.0000 MAD
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if(!$validation['is_valid'])
                                <button onclick="fixCalculation({{ $validation['rental_id'] }})" class="text-blue-600 hover:text-blue-900 font-medium">
                                    <i class="fas fa-wrench mr-1"></i>Corriger
                                </button>
                            @else
                                <span class="text-gray-400">
                                    <i class="fas fa-check mr-1"></i>OK
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucune validation disponible
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Informations sur la validation -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
        <h4 class="text-blue-800 font-semibold mb-2">Informations sur la Validation</h4>
        <div class="text-blue-700 text-sm space-y-1">
            <p><strong>Tolérance :</strong> ±0.01 MAD (1 centime)</p>
            <p><strong>Méthode :</strong> Vérification des calculs de commission selon la formule :</p>
            <div class="bg-white p-3 rounded border ml-4 mt-2">
                <code class="text-sm">
                    Commission Admin (15%) + Commission Agence + Montant Agence = Prix Total
                </code>
            </div>
            <p><strong>Dernière vérification :</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</div>

<script>
// Fonctions JavaScript pour les actions
function validateAll() {
    if (confirm('Voulez-vous valider tous les calculs ?')) {
        // Ici vous pourriez faire un appel AJAX pour valider tous les calculs
        alert('Validation en cours...');
    }
}

function fixCalculation(rentalId) {
    if (confirm(`Voulez-vous corriger le calcul pour la location #${rentalId} ?`)) {
        // Ici vous pourriez faire un appel AJAX pour corriger le calcul
        alert(`Correction en cours pour la location #${rentalId}...`);
    }
}

// Auto-refresh de la page toutes les 30 secondes pour les validations en temps réel
setTimeout(function() {
    location.reload();
}, 30000);
</script>
@endsection
