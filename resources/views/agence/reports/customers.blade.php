@extends('layouts.agence')

@section('title', 'Rapport Clients')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rapport Clients</h1>
            <p class="text-gray-600">Analysez le comportement et la satisfaction de vos clients</p>
        </div>
    </div>

    <!-- Customer Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Clients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customerStats['total_customers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clients Actifs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customerStats['active_customers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Nouveaux ce Mois</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customerStats['new_customers_this_month'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clients Fidèles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customerStats['repeat_customers'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer Acquisition Chart -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acquisition de Clients</h3>
            <canvas id="acquisitionChart" width="400" height="200"></canvas>
        </div>

        <!-- Customer Satisfaction Chart -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Satisfaction Client</h3>
            <div class="flex items-center justify-center h-48">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ $satisfactionData['satisfaction_score'] }}%</div>
                    <p class="text-gray-600">Score de Satisfaction</p>
                    <div class="mt-4">
                        <div class="flex items-center justify-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $satisfactionData['average_rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Note moyenne: {{ $satisfactionData['average_rating'] }}/5</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers Table -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Top Clients par Revenus</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topCustomers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $customer->user->name ?? 'Client #' . $customer->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->user->email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->phone ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->total_rentals }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($customer->total_revenue, 0) }} MAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($customer->total_rentals > 5)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        VIP
                                    </span>
                                @elseif($customer->total_rentals > 2)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Fidèle
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Nouveau
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Aucun client trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Customer Acquisition Chart
const acquisitionCtx = document.getElementById('acquisitionChart').getContext('2d');
const acquisitionChart = new Chart(acquisitionCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($acquisitionData, 'month')) !!},
        datasets: [{
            label: 'Nouveaux Clients',
            data: {!! json_encode(array_column($acquisitionData, 'new_customers')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection