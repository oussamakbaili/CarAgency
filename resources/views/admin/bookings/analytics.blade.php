@extends('layouts.admin')

@section('header', 'Analytics des Réservations')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la liste
        </a>
    </div>

    <!-- Period Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Filtres d'Analyse</h3>
            <div class="flex space-x-4">
                <select id="periodSelect" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="12months">12 derniers mois</option>
                    <option value="6months">6 derniers mois</option>
                    <option value="3months">3 derniers mois</option>
                    <option value="30days">30 derniers jours</option>
                </select>
                <button onclick="updateAnalytics()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Actualiser
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Réservations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $bookingTrends['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Revenus Totaux</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($revenueAnalytics['total'] ?? 0, 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Taux d'Annulation</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $cancellationAnalytics['cancellation_rate'] ?? 0 }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Taux de Réussite</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ 100 - ($cancellationAnalytics['cancellation_rate'] ?? 0) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Booking Trends Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des Réservations</h3>
            <div class="h-64">
                <canvas id="bookingTrendsChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des Revenus</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Popular Categories -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Catégories Populaires</h3>
            <div class="space-y-3">
                @forelse($popularCategories as $category)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $category->brand }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($category->count / $popularCategories->max('count')) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $category->count }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>

        <!-- Agency Performance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Performance des Agences</h3>
            <div class="space-y-3">
                @forelse($agencyPerformance as $agency)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $agency->agency_name }}</p>
                        <p class="text-xs text-gray-500">{{ $agency->total_bookings }} réservations</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ number_format($agency->total_revenue, 0, ',', ' ') }} MAD</p>
                        <p class="text-xs text-gray-500">Moy: {{ number_format($agency->avg_booking_value, 0, ',', ' ') }} MAD</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Cancellation Analytics -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Analyse des Annulations</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <p class="text-3xl font-bold text-red-600">{{ $cancellationAnalytics['total_cancellations'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Total Annulations</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-yellow-600">{{ $cancellationAnalytics['cancellation_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Taux d'Annulation</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ 100 - ($cancellationAnalytics['cancellation_rate'] ?? 0) }}%</p>
                <p class="text-sm text-gray-500">Taux de Réussite</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Booking Trends Chart
const bookingTrendsCtx = document.getElementById('bookingTrendsChart').getContext('2d');
new Chart(bookingTrendsCtx, {
    type: 'line',
    data: {
        labels: @json($bookingTrends['labels']),
        datasets: [{
            label: 'Réservations',
            data: @json($bookingTrends['data']),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
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
                beginAtZero: true
            }
        }
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: @json($revenueAnalytics['labels']),
        datasets: [{
            label: 'Revenus (MAD)',
            data: @json($revenueAnalytics['data']),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
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
                        return value.toLocaleString() + ' MAD';
                    }
                }
            }
        }
    }
});

function updateAnalytics() {
    const period = document.getElementById('periodSelect').value;
    window.location.href = `{{ route('admin.bookings.analytics') }}?period=${period}`;
}
</script>
@endsection
