@extends('layouts.agence')

@section('title', 'Rapport de Performance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rapport de Performance</h1>
            <p class="text-gray-600">Analysez les performances de votre agence</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportToCSV()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter CSV
            </button>
            <button onclick="exportToPDF()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter PDF
            </button>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenus Totaux</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($performanceData['total_revenue'] ?? 0, 0) }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Locations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $performanceData['total_rentals'] ?? 0 }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Taux d'Occupation</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($performanceData['occupancy_rate'] ?? 0, 1) }}%</p>
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
                    <p class="text-sm font-medium text-gray-600">Revenus Moyens</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($performanceData['average_revenue'] ?? 0, 0) }} MAD</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution des Revenus</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>

        <!-- Rentals Chart -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Locations par Mois</h3>
            <canvas id="rentalsChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Détails des Performances</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux d'Occupation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus Moyens</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($performanceData['monthly_data'] ?? [] as $month)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $month['month'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($month['revenue'], 0) }} MAD</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $month['rentals'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($month['occupancy_rate'], 1) }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($month['average_revenue'], 0) }} MAD</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($performanceData['monthly_data'] ?? [], 'month')) !!},
        datasets: [{
            label: 'Revenus (MAD)',
            data: {!! json_encode(array_column($performanceData['monthly_data'] ?? [], 'revenue')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1
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

// Rentals Chart
const rentalsCtx = document.getElementById('rentalsChart').getContext('2d');
const rentalsChart = new Chart(rentalsCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($performanceData['monthly_data'] ?? [], 'month')) !!},
        datasets: [{
            label: 'Locations',
            data: {!! json_encode(array_column($performanceData['monthly_data'] ?? [], 'rentals')) !!},
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
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

function exportToCSV() {
    console.log('Exporting to CSV...');
    
    // Create CSV content
    let csvContent = "Mois,Revenus,Locations,Taux d'Occupation,Revenus Moyens\n";
    
    @foreach($performanceData['monthly_data'] ?? [] as $month)
        csvContent += "{{ $month['month'] }},{{ $month['revenue'] }},{{ $month['rentals'] }},{{ $month['occupancy_rate'] }},{{ $month['average_revenue'] }}\n";
    @endforeach
    
    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'rapport_performance_{{ date('Y-m-d') }}.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    console.log('CSV export completed');
}

function exportToPDF() {
    console.log('Exporting to PDF...');
    
    // Show loading message
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Génération PDF...';
    
    // Create PDF content
    const pdfContent = `
        <html>
        <head>
            <title>Rapport de Performance - {{ date('d/m/Y') }}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat { text-align: center; }
                .stat-value { font-size: 24px; font-weight: bold; color: #1f2937; }
                .stat-label { font-size: 14px; color: #6b7280; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
                th { background-color: #f9fafb; font-weight: bold; }
                .footer { margin-top: 30px; text-align: center; color: #6b7280; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Rapport de Performance</h1>
                <p>Agence: {{ auth()->user()->agency->name ?? 'Agence' }}</p>
                <p>Période: {{ date('d/m/Y') }}</p>
            </div>
            
            <div class="stats">
                <div class="stat">
                    <div class="stat-value">{{ number_format($performanceData['total_revenue'] ?? 0, 0) }} MAD</div>
                    <div class="stat-label">Revenus Totaux</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{ $performanceData['total_rentals'] ?? 0 }}</div>
                    <div class="stat-label">Locations</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{ number_format($performanceData['occupancy_rate'] ?? 0, 1) }}%</div>
                    <div class="stat-label">Taux d'Occupation</div>
                </div>
                <div class="stat">
                    <div class="stat-value">{{ number_format($performanceData['average_revenue'] ?? 0, 0) }} MAD</div>
                    <div class="stat-label">Revenus Moyens</div>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Mois</th>
                        <th>Revenus</th>
                        <th>Locations</th>
                        <th>Taux d'Occupation</th>
                        <th>Revenus Moyens</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($performanceData['monthly_data'] ?? [] as $month)
                        <tr>
                            <td>{{ $month['month'] }}</td>
                            <td>{{ number_format($month['revenue'], 0) }} MAD</td>
                            <td>{{ $month['rentals'] }}</td>
                            <td>{{ number_format($month['occupancy_rate'], 1) }}%</td>
                            <td>{{ number_format($month['average_revenue'], 0) }} MAD</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="footer">
                <p>Généré le {{ date('d/m/Y à H:i') }}</p>
            </div>
        </body>
        </html>
    `;
    
    // Open PDF in new window
    const newWindow = window.open('', '_blank');
    newWindow.document.write(pdfContent);
    newWindow.document.close();
    
    // Trigger print dialog
    setTimeout(() => {
        newWindow.print();
        event.target.innerHTML = originalText;
    }, 1000);
    
    console.log('PDF export completed');
}
</script>
@endsection