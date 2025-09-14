@extends('layouts.agence')

@section('title', 'Offres et Promotions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Offres et Promotions</h1>
            <p class="text-gray-600">Gérez vos offres spéciales et promotions pour attirer plus de clients</p>
        </div>
        <button onclick="showNewOfferModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Nouvelle Offre
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Offres Actives</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $offers->where('is_active', true)->count() }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Réservations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $offers->sum('usage_count') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Économie Moyenne</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($offers->avg('discount_value') ?? 0, 0) }} MAD</p>
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
                    <p class="text-sm font-medium text-gray-600">Taux de Conversion</p>
                    <p class="text-2xl font-bold text-gray-900">12.5%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
        <p class="text-gray-600 mb-4">Créez et gérez vos offres en quelques clics</p>
        <div class="flex space-x-4">
            <button onclick="showNewOfferModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Nouvelle Offre
            </button>
            <button onclick="showFlashOfferModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Offre Flash
            </button>
            <button onclick="showPromoCodeModal()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                Code Promo
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Statut:</label>
                <select id="statusFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Type:</label>
                <select id="typeFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les types</option>
                    <option value="percentage">Pourcentage</option>
                    <option value="fixed">Montant fixe</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Période:</label>
                <select id="periodFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes les périodes</option>
                    <option value="active">En cours</option>
                    <option value="upcoming">À venir</option>
                    <option value="expired">Expirées</option>
                </select>
            </div>
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Nom de l'offre, code promo..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button onclick="applyFilters()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Filtrer
            </button>
        </div>
    </div>

    <!-- Offers List -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Liste des Offres</h2>
        </div>
        
        <div class="p-6">
            @if($offers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($offers as $offer)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="p-2 {{ $offer->type === 'percentage' ? 'bg-blue-100' : 'bg-green-100' }} rounded-full">
                                        @if($offer->type === 'percentage')
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="font-medium text-gray-900">{{ $offer->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $offer->code }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $offer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $offer->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium">
                                        @if($offer->type === 'percentage')
                                            {{ $offer->discount_value }}% de réduction
                                        @else
                                            {{ number_format($offer->discount_value, 0) }} MAD de réduction
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Période:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($offer->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($offer->end_date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Utilisations:</span>
                                    <span class="font-medium">{{ $offer->usage_count }} / {{ $offer->max_usage == 0 ? '∞' : $offer->max_usage }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Véhicules:</span>
                                    <span class="font-medium">{{ count($offer->vehicle_ids ?? []) }} véhicule(s)</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex space-x-2">
                                <button onclick="editOffer({{ $offer->id }})" class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors">
                                    Modifier
                                </button>
                                <button onclick="showOfferStats({{ $offer->id }})" class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded text-sm hover:bg-gray-200 transition-colors">
                                    Statistiques
                                </button>
                                <button onclick="deleteOffer({{ $offer->id }})" class="bg-red-100 text-red-700 px-3 py-2 rounded text-sm hover:bg-red-200 transition-colors">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune offre spéciale</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle offre spéciale.</p>
                    <div class="mt-6">
                        <button onclick="showNewOfferModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nouvelle Offre
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- New Offer Modal -->
<div id="newOfferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Offre Spéciale</h3>
                <button onclick="closeModal('newOfferModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.offers.create') }}" method="POST" id="offerForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de l'Offre</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Offre Été 2024" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code Promo</label>
                        <input type="text" name="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: ETE2024" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type de Réduction</label>
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="percentage">Pourcentage</option>
                                <option value="fixed">Montant fixe</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valeur de la Réduction</label>
                            <input type="number" name="discount_value" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
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
                        <label class="block text-sm font-medium text-gray-700">Utilisations Maximales</label>
                        <input type="number" name="max_usage" min="0" value="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">0 = illimité</p>
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
                    <button type="button" onclick="closeModal('newOfferModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Créer l'Offre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flash Offer Modal -->
<div id="flashOfferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Offre Flash</h3>
                <button onclick="closeModal('flashOfferModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.offers.create') }}" method="POST" id="flashOfferForm">
                @csrf
                <input type="hidden" name="is_flash" value="1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de l'Offre Flash</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Offre Flash Weekend" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code Promo</label>
                        <input type="text" name="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: FLASH2024" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type de Réduction</label>
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="percentage">Pourcentage</option>
                                <option value="fixed">Montant fixe</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valeur de la Réduction</label>
                            <input type="number" name="discount_value" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Début</label>
                            <input type="datetime-local" name="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Fin</label>
                            <input type="datetime-local" name="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Utilisations Maximales</label>
                        <input type="number" name="max_usage" min="1" value="50" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="mt-1 text-xs text-gray-500">Offres flash limitées</p>
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
                    <button type="button" onclick="closeModal('flashOfferModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                        Créer l'Offre Flash
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Promo Code Modal -->
<div id="promoCodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Code Promo</h3>
                <button onclick="closeModal('promoCodeModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.offers.create') }}" method="POST" id="promoCodeForm">
                @csrf
                <input type="hidden" name="is_promo_code" value="1">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom du Code Promo</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Code Promo Été" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code Promo</label>
                        <input type="text" name="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: SUMMER2024" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type de Réduction</label>
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="percentage">Pourcentage</option>
                                <option value="fixed">Montant fixe</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valeur de la Réduction</label>
                            <input type="number" name="discount_value" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
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
                        <label class="block text-sm font-medium text-gray-700">Utilisations Maximales</label>
                        <input type="number" name="max_usage" min="0" value="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">0 = illimité</p>
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
                    <button type="button" onclick="closeModal('promoCodeModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm font-medium hover:bg-purple-700">
                        Créer le Code Promo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Offer Statistics Modal -->
<div id="offerStatsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Statistiques de l'Offre</h3>
                <button onclick="closeModal('offerStatsModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="offerStatsContent">
                <!-- Statistics content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showNewOfferModal() {
    console.log('Opening new offer modal');
    const modal = document.getElementById('newOfferModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: newOfferModal');
        alert('Erreur: Modal non trouvé');
    }
}

function showFlashOfferModal() {
    console.log('Opening flash offer modal');
    const modal = document.getElementById('flashOfferModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Flash offer modal opened successfully');
    } else {
        console.error('Modal not found: flashOfferModal');
        alert('Erreur: Modal non trouvé');
    }
}

function showPromoCodeModal() {
    console.log('Opening promo code modal');
    const modal = document.getElementById('promoCodeModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Promo code modal opened successfully');
    } else {
        console.error('Modal not found: promoCodeModal');
        alert('Erreur: Modal non trouvé');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editOffer(id) {
    window.location.href = '{{ route("agence.pricing.offers.edit", ":id") }}'.replace(':id', id);
}

function deleteOffer(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette offre ? Cette action est irréversible.')) {
        fetch('{{ route("agence.pricing.offers.delete", ":id") }}'.replace(':id', id), {
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

function showOfferStats(id) {
    console.log('Showing stats for offer:', id);
    
    // For now, show a placeholder
    document.getElementById('offerStatsContent').innerHTML = `
        <div class="text-center py-8">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Statistiques de l'Offre #${id}</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Utilisations</p>
                    <p class="text-2xl font-bold text-blue-600">45</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Revenus Générés</p>
                    <p class="text-2xl font-bold text-green-600">12,500 MAD</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Taux de Conversion</p>
                    <p class="text-2xl font-bold text-yellow-600">15.2%</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Économie Moyenne</p>
                    <p class="text-2xl font-bold text-purple-600">125 MAD</p>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">Fonctionnalité de statistiques détaillées à implémenter</p>
        </div>
    `;
    
    const modal = document.getElementById('offerStatsModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    const period = document.getElementById('periodFilter').value;
    const search = document.getElementById('searchInput').value;
    
    console.log('Applying filters:', { status, type, period, search });
    
    // Get all offer cards
    const offerCards = document.querySelectorAll('.border.border-gray-200.rounded-lg.p-4');
    
    offerCards.forEach(card => {
        let show = true;
        
        // Filter by status
        if (status) {
            const statusBadge = card.querySelector('.bg-green-100, .bg-red-100');
            const isActive = statusBadge && statusBadge.textContent.trim() === 'Actif';
            if (status === 'active' && !isActive) show = false;
            if (status === 'inactive' && isActive) show = false;
        }
        
        // Filter by type
        if (type) {
            const typeText = card.querySelector('.font-medium').textContent;
            const isPercentage = typeText.includes('%');
            if (type === 'percentage' && !isPercentage) show = false;
            if (type === 'fixed' && isPercentage) show = false;
        }
        
        // Filter by period
        if (period) {
            const periodText = card.querySelector('.text-sm .font-medium').textContent;
            const today = new Date();
            const startDate = new Date(periodText.split(' - ')[0].split('/').reverse().join('-'));
            const endDate = new Date(periodText.split(' - ')[1].split('/').reverse().join('-'));
            
            if (period === 'active' && (today < startDate || today > endDate)) show = false;
            if (period === 'upcoming' && today >= startDate) show = false;
            if (period === 'expired' && today <= endDate) show = false;
        }
        
        // Filter by search
        if (search) {
            const cardText = card.textContent.toLowerCase();
            if (!cardText.includes(search.toLowerCase())) show = false;
        }
        
        // Show/hide card
        card.style.display = show ? 'block' : 'none';
    });
    
    // Update results count
    const visibleCards = document.querySelectorAll('.border.border-gray-200.rounded-lg.p-4[style*="block"], .border.border-gray-200.rounded-lg.p-4:not([style])');
    console.log(`Showing ${visibleCards.length} offers`);
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    // Handle regular offer form
    const offerForm = document.getElementById('offerForm');
    if (offerForm) {
        offerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Offer form submitted');
            
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
                closeModal('newOfferModal');
                alert(data.message || 'Offre créée avec succès');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la création de l\'offre: ' + error.message);
            });
        });
    }
    
    // Handle flash offer form
    const flashOfferForm = document.getElementById('flashOfferForm');
    if (flashOfferForm) {
        flashOfferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Flash offer form submitted');
            
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
                closeModal('flashOfferModal');
                alert(data.message || 'Offre flash créée avec succès');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la création de l\'offre flash: ' + error.message);
            });
        });
    }
    
    // Handle promo code form
    const promoCodeForm = document.getElementById('promoCodeForm');
    if (promoCodeForm) {
        promoCodeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Promo code form submitted');
            
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
                closeModal('promoCodeModal');
                alert(data.message || 'Code promo créé avec succès');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la création du code promo: ' + error.message);
            });
        });
    }
    
    // Handle search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            applyFilters();
        });
    }
});
</script>
@endsection