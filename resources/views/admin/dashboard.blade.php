@extends('layouts.admin')

@section('header', 'Tableau de bord')

@section('content')
<!-- Overview Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Agencies Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Total Agences</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['totalAgencies'] }}</p>
                    <div class="flex items-center mt-1 text-xs text-gray-500">
                        <span class="text-green-600">{{ $stats['approvedAgencies'] }} approuvées</span>
                        <span class="mx-1.5">•</span>
                        <span class="text-yellow-600">{{ $stats['pendingAgencies'] }} en attente</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Customers Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-green-50">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Total Clients</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['totalCustomers'] }}</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">+12% ce mois</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commissions Admin Card (replaces Active Rentals) -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-blue-50">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Commissions Admin</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($financialStats['total_admin_commission'] ?? 0, 0, ',', ' ') }} MAD</p>
                    <div class="flex items-center mt-1 text-xs text-gray-500">
                        <span>Ce mois: <span class="text-blue-600 font-medium">{{ number_format($financialStats['monthly_admin_commission'] ?? 0, 0, ',', ' ') }} MAD</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Card -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
        <div class="p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-yellow-50">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h2 class="text-sm font-medium text-gray-600">Revenus Mensuels</h2>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['monthlyRevenue'], 0, ',', ' ') }} MAD</p>
                    <div class="flex items-center mt-1 text-xs">
                        <span class="text-green-600">+8% vs mois dernier</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row: Recent Applications and Revenue Chart -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Recent Agency Applications -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Demandes Récentes</h3>
                <a href="{{ route('admin.agencies.pending') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Voir tout</a>
            </div>
            <div class="space-y-3">
                @forelse($recentApplications as $application)
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-[#C2410C] rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $application->agency_name }}</p>
                            <p class="text-sm text-gray-500">{{ $application->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                        En attente
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucune nouvelle demande d'agence en attente.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Revenue Chart (with period controls) -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des Revenus</h3>
                <div>
                    <a href="?period=month" class="text-xs px-2 py-1 rounded {{ ($period ?? 'month')==='month' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700' }}">Mensuel</a>
                    <a href="?period=day" class="ml-1 text-xs px-2 py-1 rounded {{ ($period ?? 'month')==='day' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700' }}">Quotidien</a>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Third Row: Booking and Commission Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Booking Trends Chart -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendances des Réservations</h3>
            <div class="h-64">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Commission Trends Chart -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des Commissions Admin</h3>
            <div class="h-64">
                <canvas id="commissionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Agencies by Revenue and Commission -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Agences par Revenus</h3>
            <div class="space-y-3">
                @foreach(($topAgencies ?? []) as $index => $agency)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">{{ $index + 1 }}</div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">{{ $agency->agency_name }}</p>
                            <p class="text-sm text-gray-500">{{ $agency->rentals_count }} réservations</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ number_format($agency->rentals_sum_total_price ?? 0, 2) }} MAD</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Agences par Commissions</h3>
            <div class="space-y-3">
                @foreach(($topAgenciesByCommission ?? []) as $index => $agency)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold">{{ $index + 1 }}</div>
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
</div>

<!-- Bottom Row: Recent Activity Feed -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h3>
        <div class="flow-root">
            <ul class="-mb-8">
                @forelse($recentActivity as $activity)
                <li>
                    <div class="relative pb-8">
                        @if(!$loop->last)
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full {{ ($activity['color'] === 'green' ? 'bg-green-500' : ($activity['color'] === 'yellow' ? 'bg-yellow-500' : 'bg-red-500')) }} flex items-center justify-center ring-8 ring-white">
                                    @if($activity['icon'] === 'building')
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    @elseif($activity['icon'] === 'user')
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                    <time datetime="{{ $activity['time'] }}">{{ $activity['time']->diffForHumans() }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune activité</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucune activité récente à afficher.</p>
                </div>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Quick Actions Panel -->
<div class="mt-8 bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.agencies.pending') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-orange-900">Approuver Agences</p>
                    <p class="text-sm text-orange-700">{{ $quickActionsData['pendingAgencies'] }} en attente</p>
                </div>
            </a>

            <a href="{{ route('admin.agencies.documents') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-900">Vérifier Documents</p>
                    <p class="text-sm text-blue-700">{{ $quickActionsData['pendingDocuments'] }} documents</p>
                </div>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-900">Générer Rapports</p>
                    <p class="text-sm text-green-700">Exports disponibles</p>
                </div>
            </a>

            <a href="{{ route('admin.system.health') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-purple-900">Maintenance</p>
                    <p class="text-sm text-purple-700">Outils système</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DASHBOARD DEBUG START ===');
    const commissionData = @json($commissionTrends ?? []);
    
    // Test Chart.js loading
    console.log('Chart.js loaded:', typeof Chart !== 'undefined');
    if (typeof Chart !== 'undefined') {
        console.log('Chart.js version:', Chart.version);
    } else {
        console.error('Chart.js failed to load!');
        return;
    }
    
    // Test data
    const revenueData = @json($chartsData['revenue']);
    const bookingData = @json($chartsData['bookings']);
    
    console.log('Raw revenue data:', revenueData);
    console.log('Raw booking data:', bookingData);
    
    // Test canvas elements
    const revenueCanvas = document.getElementById('revenueChart');
    const bookingCanvas = document.getElementById('bookingChart');
    const commissionCanvas = document.getElementById('commissionChart');
    
    console.log('Revenue canvas found:', !!revenueCanvas);
    console.log('Booking canvas found:', !!bookingCanvas);
    
    if (!revenueCanvas || !bookingCanvas) {
        console.error('Canvas elements not found!');
        return;
    }
    
    // Create charts with real data
    try {
        // Revenue Chart
        new Chart(revenueCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: revenueData.labels || [],
                datasets: [{
                    label: 'Revenus (MAD)',
                    data: revenueData.data || [],
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
        console.log('✅ Revenue chart created successfully');
    } catch (error) {
        console.error('❌ Error creating revenue chart:', error);
    }
    
    try {
        // Booking Chart
        new Chart(bookingCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: bookingData.labels || [],
                datasets: [{
                    label: 'Réservations',
                    data: bookingData.data || [],
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
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
                        beginAtZero: true
                    }
                }
            }
        });
        console.log('✅ Booking chart created successfully');
    } catch (error) {
        console.error('❌ Error creating booking chart:', error);
    }
    
    if (commissionCanvas && Array.isArray(commissionData)) {
        try {
            new Chart(commissionCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: commissionData.map(item => item.period || (item.month + '/' + item.year)),
                    datasets: [{
                        label: 'Commissions Admin (MAD)',
                        data: commissionData.map(item => item.total_commission),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            console.log('✅ Commission chart created successfully');
        } catch (error) {
            console.error('❌ Error creating commission chart:', error);
        }
    }
    
    console.log('=== DASHBOARD DEBUG END ===');
});
</script>
@endpush