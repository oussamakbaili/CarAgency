@extends('layouts.admin')

@section('title', 'Rapport des Commissions')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Rapport Détaillé des Commissions</h1>
        <p class="text-gray-600 mt-2">Analyse complète des commissions admin pour la période sélectionnée</p>
    </div>

    <!-- Filtres de période -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" value="{{ $startDate }}" class="form-input" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" value="{{ $endDate }}" class="form-input" disabled>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.commissions.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour Dashboard
                </a>
                <button onclick="exportReport()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Résumé des statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Transactions</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($summary['total_transactions']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Commission</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($summary['total_commission'], 2) }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Commission Moyenne</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($summary['average_commission'], 2) }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Chiffre d'Affaires</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($summary['total_revenue'], 2) }} MAD</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des tendances -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des Commissions</h3>
        <canvas id="commissionTrendChart" width="400" height="200"></canvas>
    </div>

    <!-- Tableau détaillé des transactions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Transactions Détaillées</h3>
            <div class="flex gap-2">
                <button onclick="exportCSV()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-file-csv mr-2"></i>Export CSV
                </button>
                <button onclick="exportExcel()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voiture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Original</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission (15%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $transaction->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->agency->agency_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $transaction->rental->car->brand ?? 'N/A' }} {{ $transaction->rental->car->model ?? '' }}
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune transaction trouvée pour cette période
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->count() > 0)
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Affichage de {{ $transactions->count() }} transaction(s)
            </div>
            <div class="text-sm text-gray-500">
                Total: {{ number_format($transactions->sum('amount'), 2) }} MAD
            </div>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des tendances
const ctx = document.getElementById('commissionTrendChart').getContext('2d');
const transactionData = @json($transactions);

// Grouper les données par jour
const dailyData = {};
transactionData.forEach(transaction => {
    const date = new Date(transaction.created_at).toISOString().split('T')[0];
    if (!dailyData[date]) {
        dailyData[date] = 0;
    }
    dailyData[date] += parseFloat(transaction.amount);
});

const sortedDates = Object.keys(dailyData).sort();
const chartData = sortedDates.map(date => dailyData[date]);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: sortedDates.map(date => new Date(date).toLocaleDateString('fr-FR')),
        datasets: [{
            label: 'Commissions quotidiennes (MAD)',
            data: chartData,
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

// Fonctions d'export
function exportReport() {
    window.print();
}

function exportCSV() {
    const params = new URLSearchParams({
        start_date: '{{ $startDate }}',
        end_date: '{{ $endDate }}',
        format: 'csv'
    });
    window.open('{{ route("admin.commissions.export") }}?' + params.toString(), '_blank');
}

function exportExcel() {
    const params = new URLSearchParams({
        start_date: '{{ $startDate }}',
        end_date: '{{ $endDate }}',
        format: 'excel'
    });
    window.open('{{ route("admin.commissions.export") }}?' + params.toString(), '_blank');
}
</script>
@endsection
