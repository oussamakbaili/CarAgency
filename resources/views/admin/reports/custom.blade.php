@extends('layouts.admin')

@section('header', 'Rapports Personnalisés')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Report Builder Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Créer un Rapport Personnalisé</h3>
        
        <form method="POST" action="{{ route('admin.reports.custom') }}">
            @csrf
            
            <!-- Report Name -->
            <div class="mb-6">
                <label for="report_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom du Rapport
                </label>
                <input type="text" name="report_name" id="report_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Ex: Rapport des Agences de Casablanca">
            </div>

            <!-- Data Sources -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sources de Données
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($dataSources as $key => $label)
                    <label class="flex items-center">
                        <input type="checkbox" name="data_sources[]" value="{{ $key }}"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Date Range -->
            <div class="mb-6">
                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">
                    Période
                </label>
                <select name="date_range" id="date_range" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="toggleCustomDateRange()">
                    @foreach($dateRanges as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Custom Date Range -->
            <div id="custom_date_range" class="mb-6 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de Début
                        </label>
                        <input type="date" name="date_from" id="date_from"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de Fin
                        </label>
                        <input type="date" name="date_to" id="date_to"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="mb-6">
                <h4 class="text-md font-medium text-gray-900 mb-3">Filtres Avancés</h4>
                
                <!-- Agency Filters -->
                <div id="agency_filters" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Filtres Agences</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="agency_status" class="block text-sm text-gray-600 mb-1">Statut</label>
                            <select name="filters[status]" id="agency_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="approved">Approuvé</option>
                                <option value="rejected">Rejeté</option>
                            </select>
                        </div>
                        <div>
                            <label for="agency_city" class="block text-sm text-gray-600 mb-1">Ville</label>
                            <input type="text" name="filters[city]" id="agency_city"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: Casablanca">
                        </div>
                    </div>
                </div>

                <!-- Customer Filters -->
                <div id="customer_filters" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Filtres Clients</h5>
                    <div>
                        <label for="has_bookings" class="block text-sm text-gray-600 mb-1">Avec Réservations</label>
                        <select name="filters[has_bookings]" id="has_bookings"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Tous</option>
                            <option value="yes">Avec réservations</option>
                            <option value="no">Sans réservations</option>
                        </select>
                    </div>
                </div>

                <!-- Vehicle Filters -->
                <div id="vehicle_filters" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Filtres Véhicules</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="vehicle_status" class="block text-sm text-gray-600 mb-1">Statut</label>
                            <select name="filters[status]" id="vehicle_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="available">Disponible</option>
                                <option value="rented">Loué</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label for="vehicle_brand" class="block text-sm text-gray-600 mb-1">Marque</label>
                            <input type="text" name="filters[brand]" id="vehicle_brand"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: Toyota">
                        </div>
                    </div>
                </div>

                <!-- Booking Filters -->
                <div id="booking_filters" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Filtres Réservations</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="booking_status" class="block text-sm text-gray-600 mb-1">Statut</label>
                            <select name="filters[status]" id="booking_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="active">Active</option>
                                <option value="completed">Terminée</option>
                                <option value="cancelled">Annulée</option>
                            </select>
                        </div>
                        <div>
                            <label for="agency_id" class="block text-sm text-gray-600 mb-1">Agence</label>
                            <select name="filters[agency_id]" id="agency_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Toutes les agences</option>
                                @foreach(\App\Models\Agency::where('status', 'approved')->get() as $agency)
                                <option value="{{ $agency->id }}">{{ $agency->agency_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Transaction Filters -->
                <div id="transaction_filters" class="hidden mb-4 p-4 bg-gray-50 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Filtres Transactions</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="transaction_type" class="block text-sm text-gray-600 mb-1">Type</label>
                            <select name="filters[type]" id="transaction_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les types</option>
                                <option value="rental_payment">Paiement Location</option>
                                <option value="commission">Commission</option>
                                <option value="withdrawal">Retrait</option>
                            </select>
                        </div>
                        <div>
                            <label for="transaction_status" class="block text-sm text-gray-600 mb-1">Statut</label>
                            <select name="filters[status]" id="transaction_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="completed">Terminé</option>
                                <option value="failed">Échoué</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Générer le Rapport
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Custom Reports -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Rapports Personnalisés Récents</h3>
        
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rapport personnalisé</h3>
            <p class="mt-1 text-sm text-gray-500">Créez votre premier rapport personnalisé ci-dessus.</p>
        </div>
    </div>
</div>

<script>
function toggleCustomDateRange() {
    const dateRange = document.getElementById('date_range').value;
    const customDateRange = document.getElementById('custom_date_range');
    
    if (dateRange === 'custom') {
        customDateRange.classList.remove('hidden');
    } else {
        customDateRange.classList.add('hidden');
    }
}

// Show/hide filter sections based on selected data sources
document.addEventListener('DOMContentLoaded', function() {
    const dataSourceCheckboxes = document.querySelectorAll('input[name="data_sources[]"]');
    
    dataSourceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateFilterSections();
        });
    });
    
    function updateFilterSections() {
        const selectedSources = Array.from(dataSourceCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        // Hide all filter sections
        document.getElementById('agency_filters').classList.add('hidden');
        document.getElementById('customer_filters').classList.add('hidden');
        document.getElementById('vehicle_filters').classList.add('hidden');
        document.getElementById('booking_filters').classList.add('hidden');
        document.getElementById('transaction_filters').classList.add('hidden');
        
        // Show relevant filter sections
        selectedSources.forEach(source => {
            const filterSection = document.getElementById(source + '_filters');
            if (filterSection) {
                filterSection.classList.remove('hidden');
            }
        });
    }
});
</script>
@endsection
