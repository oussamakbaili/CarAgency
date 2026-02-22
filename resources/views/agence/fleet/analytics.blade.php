@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Analytiques de Flotte</h1>
                    <p class="mt-2 text-gray-600">Analysez les performances et l'utilisation de votre flotte</p>
                </div>
                <div class="flex space-x-4">
                    <select class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option>Derniers 30 jours</option>
                        <option>Derniers 3 mois</option>
                        <option>Dernière année</option>
                    </select>
                    <a href="{{ route('agence.fleet.analytics', ['export' => 'csv']) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exporter
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Taux d'Utilisation</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $utilizationRate }}%</p>
                            <p class="text-xs text-gray-500">{{ $rentedCars }} / {{ $totalCars }} véhicules</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Revenus par Véhicule</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($revenuePerVehicle, 0) }} DH</p>
                            <p class="text-xs text-gray-500">Ce mois-ci</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Durée Moyenne</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($avgDuration, 1) }} jours</p>
                            <p class="text-xs text-gray-500">par location</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Satisfaction</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $satisfaction }}/5</p>
                            <p class="text-xs text-gray-500">Note moyenne</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Fleet Utilization Chart -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Utilisation de la Flotte</h3>
                    <div class="h-64">
                        <canvas id="utilizationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Revenue by Vehicle Type -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenus par Type</h3>
                    <div class="h-64">
                        <canvas id="revenueByTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Performing Vehicles -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Véhicules les Plus Performants</h3>
                    <div class="space-y-4">
                        @forelse($topVehicles as $index => $vehicle)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 {{ $index === 0 ? 'bg-blue-100' : ($index === 1 ? 'bg-gray-100' : 'bg-yellow-100') }} rounded-lg flex items-center justify-center">
                                    <span class="{{ $index === 0 ? 'text-blue-600' : ($index === 1 ? 'text-gray-600' : 'text-yellow-600') }} font-semibold">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }} {{ $vehicle->year }}</h4>
                                    <p class="text-xs text-gray-500">{{ $vehicle->rental_count }} locations</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($vehicle->revenue, 0) }} DH</p>
                                <p class="text-xs text-gray-500">revenus</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Aucune donnée de performance disponible</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Maintenance Costs -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Coûts de Maintenance</h3>
                    <div class="space-y-4">
                        @php
                            $colors = ['blue', 'green', 'yellow', 'purple', 'red', 'indigo'];
                            $totalMaintenanceCost = $maintenanceCosts->sum('total_cost');
                        @endphp
                        
                        @forelse($maintenanceCosts as $index => $cost)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-{{ $colors[$index % count($colors)] }}-500 rounded-full"></div>
                                <span class="text-sm text-gray-900">{{ ucfirst($cost->type) }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($cost->total_cost, 0) }} DH</span>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <p>Aucun coût de maintenance ce mois-ci</p>
                        </div>
                        @endforelse

                        @if($totalMaintenanceCost > 0)
                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">Total</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($totalMaintenanceCost, 0) }} DH</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fleet Utilization Chart
    const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
    const utilizationChart = new Chart(utilizationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Disponible', 'En location', 'Maintenance'],
            datasets: [{
                data: [{{ $fleetUtilization['available'] }}, {{ $fleetUtilization['rented'] }}, {{ $fleetUtilization['maintenance'] }}],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(245, 158, 11)'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Revenue by Type Chart
    const revenueCtx = document.getElementById('revenueByTypeChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoryStats->pluck('category')) !!},
            datasets: [{
                label: 'Revenus (DH)',
                data: {!! json_encode($categoryStats->pluck('revenue')) !!},
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(16, 185, 129, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(168, 85, 247)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(16, 185, 129)'
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
</script>
@endpush
@endsection
