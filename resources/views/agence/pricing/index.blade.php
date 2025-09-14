@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des Prix</h1>
                    <p class="mt-2 text-gray-600">Optimisez vos tarifs et maximisez vos revenus</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.pricing.competitor-analysis') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analyse Concurrentielle
                    </a>
                    <button onclick="showNewPricingModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouveau Tarif
                    </button>
                </div>
            </div>
        </div>

        <!-- Pricing Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Prix Moyen</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($pricingOverview['average_daily_rate'] ?? 0, 0) }} DH</p>
                            <p class="text-xs text-gray-500">par jour</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Prix Minimum</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($pricingOverview['price_range']['min'] ?? 0, 0) }} DH</p>
                            <p class="text-xs text-gray-500">dans votre flotte</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Prix Maximum</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($pricingOverview['price_range']['max'] ?? 0, 0) }} DH</p>
                            <p class="text-xs text-gray-500">dans votre flotte</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Véhicules</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $pricingOverview['total_cars'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">dans la flotte</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Strategies Tabs -->
        <div class="bg-white shadow-sm rounded-lg mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="switchTab('current')" id="current-tab" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Tarifs Actuels
                    </button>
                    <button onclick="switchTab('dynamic')" id="dynamic-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Tarification Dynamique
                    </button>
                    <button onclick="switchTab('seasonal')" id="seasonal-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Tarifs Saisonniers
                    </button>
                    <button onclick="switchTab('offers')" id="offers-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Offres Spéciales
                    </button>
                </nav>
            </div>

            <!-- Current Pricing -->
            <div id="current-content" class="tab-content p-6">
                <div class="space-y-6">
                    @forelse($cars ?? [] as $car)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    @if($car->image)
                                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                                    <p class="text-sm text-gray-500">{{ $car->year }} • {{ $car->registration_number }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $car->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $car->is_available ? 'Disponible' : 'Occupé' }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $car->rentals->count() }} réservations</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-blue-600">{{ number_format($car->price_per_day, 0) }} DH</div>
                                <div class="text-sm text-gray-500">par jour</div>
                                <div class="mt-2 flex space-x-2">
                                    <a href="{{ route('agence.pricing.car.edit', $car->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                        Modifier
                                    </a>
                                    <a href="{{ route('agence.pricing.car.history', $car->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                                        Historique
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun véhicule</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des véhicules à votre flotte.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Dynamic Pricing -->
            <div id="dynamic-content" class="tab-content p-6 hidden">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tarification Dynamique</h3>
                    <p class="mt-1 text-sm text-gray-500">Ajustez automatiquement vos prix selon la demande et la concurrence.</p>
                    <div class="mt-6">
                        <button onclick="showDynamicPricingModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Configurer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Seasonal Pricing -->
            <div id="seasonal-content" class="tab-content p-6 hidden">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tarifs Saisonniers</h3>
                    <p class="mt-1 text-sm text-gray-500">Définissez des tarifs spéciaux pour les périodes de forte demande.</p>
                    <div class="mt-6">
                        <button onclick="showSeasonalPricingModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Créer une Période
                        </button>
                    </div>
                </div>
            </div>

            <!-- Special Offers -->
            <div id="offers-content" class="tab-content p-6 hidden">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Offres Spéciales</h3>
                    <p class="mt-1 text-sm text-gray-500">Créez des promotions attractives pour attirer plus de clients.</p>
                    <div class="mt-6">
                        <button onclick="showOffersModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nouvelle Offre
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Price Performance Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance des Prix</h3>
                    <div class="h-64">
                        <canvas id="pricePerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Competitor Analysis -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Analyse Concurrentielle</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Votre Prix Moyen</h4>
                                <p class="text-xs text-gray-500">Tous véhicules confondus</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-blue-600">{{ number_format($pricingOverview['average_daily_rate'] ?? 0, 0) }} DH</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Prix Moyen du Marché</h4>
                                <p class="text-xs text-gray-500">Concurrents locaux</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-600">350 DH</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Position Concurrentielle</h4>
                                <p class="text-xs text-gray-500">Par rapport au marché</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Compétitif
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Recommandation</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Vos prix sont compétitifs. Considérez une légère augmentation de 5-10% pour maximiser vos revenus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

document.addEventListener('DOMContentLoaded', function() {
    // Price Performance Chart
    const priceCtx = document.getElementById('pricePerformanceChart').getContext('2d');
    const priceChart = new Chart(priceCtx, {
        type: 'bar',
        data: {
            labels: ['Économique', 'Confort', 'Luxe', 'SUV'],
            datasets: [{
                label: 'Prix Moyen (DH)',
                data: [200, 350, 800, 500],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(168, 85, 247)',
                    'rgb(245, 158, 11)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' DH';
                        }
                    }
                }
            }
        }
    });
});

// Modal functions - These are now defined in the main script section
</script>
@endpush

<!-- New Pricing Modal -->
<div id="newPricingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouveau Tarif</h3>
                <button onclick="closeModal('newPricingModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.update') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Véhicule</label>
                        <select name="car_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Sélectionner un véhicule</option>
                            @foreach($cars ?? [] as $car)
                                <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->model }} - {{ number_format($car->price_per_day, 0) }} DH</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nouveau Prix (DH/jour)</label>
                        <input type="number" name="price_per_day" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="0" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Saisonnier (optionnel)</label>
                        <input type="number" name="seasonal_multiplier" step="0.1" min="0.1" max="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1.0" value="1.0">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Raison du changement</label>
                        <textarea name="reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Expliquez la raison de ce changement de prix" required></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('newPricingModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Appliquer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dynamic Pricing Modal -->
<div id="dynamicPricingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Configuration Tarification Dynamique</h3>
                <button onclick="closeModal('dynamicPricingModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.dynamic.configure') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="enabled" id="enabled" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="enabled" class="ml-2 block text-sm text-gray-900">Activer la tarification dynamique</label>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Heures de Pointe</label>
                        <input type="number" name="peak_hour_multiplier" step="0.1" min="1" max="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="1.2" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Weekend</label>
                        <input type="number" name="weekend_multiplier" step="0.1" min="1" max="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="1.1" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur Dernière Minute</label>
                        <input type="number" name="last_minute_multiplier" step="0.1" min="0.5" max="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="0.9" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Seuil de Demande (%)</label>
                        <input type="number" name="demand_threshold" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="80" required>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('dynamicPricingModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Configurer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Seasonal Pricing Modal -->
<div id="seasonalPricingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Règle Saisonnière</h3>
                <button onclick="closeModal('seasonalPricingModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.seasonal.create') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de la règle</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Été 2024" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2" placeholder="Description de la règle"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de début</label>
                            <input type="date" name="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                            <input type="date" name="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Multiplicateur de prix</label>
                        <input type="number" name="price_multiplier" step="0.1" min="0.1" max="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1.0" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Véhicules concernés</label>
                        <div class="mt-2 max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2">
                            @foreach($cars ?? [] as $car)
                            <label class="flex items-center">
                                <input type="checkbox" name="vehicle_ids[]" value="{{ $car->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $car->brand }} {{ $car->model }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('seasonalPricingModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Offers Modal -->
<div id="offersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouvelle Offre</h3>
                <button onclick="closeModal('offersModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('agence.pricing.offers.create') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom de l'offre</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Offre Été 2024" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code promo</label>
                        <input type="text" name="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: ETE2024" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="percentage">Pourcentage</option>
                                <option value="fixed">Montant fixe</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valeur</label>
                            <input type="number" name="discount_value" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="0" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de début</label>
                            <input type="date" name="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                            <input type="date" name="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Usage maximum (0 = illimité)</label>
                        <input type="number" name="max_usage" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="100" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Véhicules concernés</label>
                        <div class="mt-2 max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2">
                            @foreach($cars ?? [] as $car)
                            <label class="flex items-center">
                                <input type="checkbox" name="vehicle_ids[]" value="{{ $car->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $car->brand }} {{ $car->model }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('offersModal')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function testButton() {
    alert('Bouton cliqué ! Test réussi.');
    console.log('Test button clicked');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Update modal functions to show modals
function showNewPricingModal() {
    console.log('Opening new pricing modal');
    const modal = document.getElementById('newPricingModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: newPricingModal');
        alert('Erreur: Modal non trouvé');
    }
}

function showDynamicPricingModal() {
    console.log('Opening dynamic pricing modal');
    const modal = document.getElementById('dynamicPricingModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: dynamicPricingModal');
        alert('Erreur: Modal non trouvé');
    }
}

function showSeasonalPricingModal() {
    console.log('Opening seasonal pricing modal');
    const modal = document.getElementById('seasonalPricingModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: seasonalPricingModal');
        alert('Erreur: Modal non trouvé');
    }
}

function showOffersModal() {
    console.log('Opening offers modal');
    const modal = document.getElementById('offersModal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal not found: offersModal');
        alert('Erreur: Modal non trouvé');
    }
}

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    console.log('Pricing page JavaScript loaded');
    
    // Handle new pricing form
    const newPricingForm = document.querySelector('form[action="{{ route('agence.pricing.update') }}"]');
    if (newPricingForm) {
        console.log('New pricing form found');
        newPricingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('New pricing form submitted');
            
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
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                closeModal('newPricingModal');
                alert(data.message || 'Prix mis à jour avec succès');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour du prix: ' + error.message);
            });
        });
    } else {
        console.log('New pricing form not found');
    }

    // Handle dynamic pricing form
    const dynamicPricingForm = document.querySelector('form[action="{{ route('agence.pricing.dynamic.configure') }}"]');
    if (dynamicPricingForm) {
        console.log('Dynamic pricing form found');
        dynamicPricingForm.addEventListener('submit', function(e) {
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
                closeModal('dynamicPricingModal');
                alert(data.message || 'Configuration mise à jour avec succès');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la configuration: ' + error.message);
            });
        });
    }

    // Handle seasonal pricing form
    const seasonalPricingForm = document.querySelector('form[action="{{ route('agence.pricing.seasonal.create') }}"]');
    if (seasonalPricingForm) {
        console.log('Seasonal pricing form found');
        seasonalPricingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Seasonal pricing form submitted');
            
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
                closeModal('seasonalPricingModal');
                alert(data.message || 'Règle saisonnière créée avec succès');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la création de la règle: ' + error.message);
            });
        });
    }

    // Handle offers form
    const offersForm = document.querySelector('form[action="{{ route('agence.pricing.offers.create') }}"]');
    if (offersForm) {
        console.log('Offers form found');
        offersForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Offers form submitted');
            
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
                closeModal('offersModal');
                alert(data.message || 'Offre créée avec succès');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la création de l\'offre: ' + error.message);
            });
        });
    }
});
</script>
@endsection
