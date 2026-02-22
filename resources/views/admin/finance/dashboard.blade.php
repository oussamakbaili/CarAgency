@extends('layouts.admin')

@section('header', 'Tableau de Bord Financier')

@section('content')
<!-- Financial Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Revenus Totaux</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['totalRevenue'], 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Revenus Mensuels</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['monthlyRevenue'], 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Commissions Totales</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['totalCommissions'], 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Paiements en Attente</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pendingPayouts'], 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart and Top Agencies -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Trends Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tendances des Revenus</h3>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Performing Agencies -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Agences Performantes</h3>
        <div class="space-y-4">
            @forelse($topAgencies as $agency)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $agency->agency_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $agency->city }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ number_format($agency->transactions_sum_amount ?? 0, 0, ',', ' ') }} MAD</p>
                    <p class="text-sm text-gray-500">Revenus</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-gray-500">Aucune donnée disponible.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900">Transactions Récentes</h3>
        <a href="{{ route('admin.finance.payments') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tout</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Agence
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Montant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentTransactions as $transaction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $transaction->type === 'rental_payment' ? 'bg-green-100 text-green-800' : 
                               ($transaction->type === 'commission' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $transaction->type === 'rental_payment' ? 'Paiement Location' : 
                               ($transaction->type === 'commission' ? 'Commission' : ucfirst($transaction->type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaction->agency->agency_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($transaction->amount, 0, ',', ' ') }} MAD
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $transaction->status === 'completed' ? 'Terminé' : 
                               ($transaction->status === 'pending' ? 'En attente' : 'Échoué') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Aucune transaction récente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Revenus (MAD)',
            data: @json($revenueTrends),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
</script>
@endpush
