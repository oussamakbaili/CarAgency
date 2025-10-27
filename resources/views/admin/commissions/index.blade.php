@extends('layouts.admin')

@section('title', 'Gestion des Commissions')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Commissions Admin</h1>
        <p class="text-gray-600 mt-2">Suivi et analyse des commissions de 15% sur toutes les réservations</p>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Commission Totale</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_admin_commission'], 2) }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Ce Mois</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['monthly_commission'], 2) }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Aujourd'hui</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['today_commission'], 2) }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Taux Commission</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['commission_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                    <select class="form-select" onchange="window.location.href='?period='+this.value">
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Mensuel</option>
                        <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Quotidien</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.commissions.report') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>Rapport Détaillé
                </a>
                <a href="{{ route('admin.commissions.validate') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>Valider Calculs
                </a>
                <button onclick="exportCommissions()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Exporter
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Graphique des tendances -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendances des Commissions</h3>
            <canvas id="commissionChart" width="400" height="200"></canvas>
        </div>

        <!-- Top agences -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Agences par Commission</h3>
            <div class="space-y-3">
                @foreach($topAgencies as $index => $agency)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">
                            {{ $index + 1 }}
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">{{ $agency->agency_name }}</p>
                            <p class="text-sm text-gray-500">{{ $agency->transaction_count }} transactions</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ number_format($agency->total_commission, 2) }} MAD</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Transactions récentes -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transactions Récentes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voiture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Original</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->agency->agency_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $transaction->rental->car->brand }} {{ $transaction->rental->car->model }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($transaction->metadata['original_amount'] ?? 0, 2) }} MAD
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-green-600">
                                {{ number_format($transaction->amount, 2) }} MAD
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des tendances
const ctx = document.getElementById('commissionChart').getContext('2d');
const commissionData = @json($commissionTrends);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: commissionData.map(item => {
            if ('{{ $period }}' === 'month') {
                const date = new Date(item.year, item.month - 1);
                return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
            } else {
                const date = new Date(item.period);
                return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
            }
        }),
        datasets: [{
            label: 'Commission Admin (MAD)',
            data: commissionData.map(item => item.total_commission),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
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
                        return value.toLocaleString('fr-FR') + ' MAD';
                    }
                }
            }
        }
    }
});

// Fonction d'export
function exportCommissions() {
    const startDate = new Date();
    startDate.setMonth(startDate.getMonth() - 1);
    
    const params = new URLSearchParams({
        start_date: startDate.toISOString().split('T')[0],
        end_date: new Date().toISOString().split('T')[0],
        format: 'csv'
    });
    
    window.open('{{ route("admin.commissions.export") }}?' + params.toString(), '_blank');
}
</script>
@endsection
