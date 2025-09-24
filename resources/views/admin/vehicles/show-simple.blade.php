<!DOCTYPE html>
<html>
<head>
    <title>{{ $vehicle->brand ?? 'Vehicle' }} {{ $vehicle->model ?? 'Details' }} - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle->brand ?? 'Unknown' }} {{ $vehicle->model ?? 'Vehicle' }}</h1>
                    <p class="text-gray-600">{{ $vehicle->registration_number ?? 'N/A' }} • {{ $vehicle->year ?? 'N/A' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.vehicles.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        ← Retour à la liste
                    </a>
                    <button onclick="updateStatus()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Modifier le statut
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Vehicle Image -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Photo du véhicule</h2>
                @if($vehicle->image ?? false)
                    <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Vehicle Photo" class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Vehicle Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Détails du véhicule</h2>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if(($vehicle->status ?? 'unknown') === 'available') bg-green-100 text-green-800
                            @elseif(($vehicle->status ?? 'unknown') === 'rented') bg-blue-100 text-blue-800
                            @elseif(($vehicle->status ?? 'unknown') === 'maintenance') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($vehicle->status ?? 'Unknown') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Immatriculation</dt>
                            <dd class="text-gray-900">{{ $vehicle->registration_number ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Année</dt>
                            <dd class="text-gray-900">{{ $vehicle->year ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Prix par jour</dt>
                            <dd class="text-gray-900">{{ number_format($vehicle->price_per_day ?? 0, 2) }}€</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Agence</dt>
                            <dd class="text-gray-900">{{ $vehicle->agency->agency_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Couleur</dt>
                            <dd class="text-gray-900">{{ $vehicle->color ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Carburant</dt>
                            <dd class="text-gray-900">{{ $vehicle->fuel_type ?? 'N/A' }}</dd>
                        </div>
                    </div>

                    @if($vehicle->description ?? false)
                    <div class="mt-4">
                        <dt class="font-medium text-gray-500 mb-2">Description</dt>
                        <dd class="text-gray-900">{{ $vehicle->description }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Locations</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['totalRentals'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Locations Terminées</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['completedRentals'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Revenus Totaux</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($statistics['totalRevenue'] ?? 0, 2) }}€</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Taux d'Utilisation</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($statistics['utilizationRate'] ?? 0, 1) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Monthly Performance Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Performance Mensuelle</h3>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Recent Rentals -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Locations Récentes</h3>
                <div class="space-y-4">
                    @if(isset($rentalHistory) && $rentalHistory->count() > 0)
                        @foreach($rentalHistory->take(5) as $rental)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $rental->user->name ?? 'Client inconnu' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $rental->start_date->format('d/m/Y') ?? 'N/A' }} - {{ $rental->end_date->format('d/m/Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ number_format($rental->total_price ?? 0, 2) }}€
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if(($rental->status ?? 'unknown') === 'active') bg-green-100 text-green-800
                                    @elseif(($rental->status ?? 'unknown') === 'completed') bg-blue-100 text-blue-800
                                    @elseif(($rental->status ?? 'unknown') === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($rental->status ?? 'Unknown') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Aucune location trouvée</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rental History Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des Locations</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($rentalHistory) && $rentalHistory->count() > 0)
                                @foreach($rentalHistory as $rental)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $rental->user->name ?? 'Client inconnu' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rental->start_date->format('d/m/Y') ?? 'N/A' }} - {{ $rental->end_date->format('d/m/Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($rental->total_price ?? 0, 2) }}€
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if(($rental->status ?? 'unknown') === 'active') bg-green-100 text-green-800
                                            @elseif(($rental->status ?? 'unknown') === 'completed') bg-blue-100 text-blue-800
                                            @elseif(($rental->status ?? 'unknown') === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($rental->status ?? 'Unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rental->created_at->format('d/m/Y H:i') ?? 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Aucune location trouvée
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($rentalHistory) && $rentalHistory->hasPages())
                <div class="mt-4">
                    {{ $rentalHistory->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Modifier le statut</h3>
                    <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.vehicles.update-status', $vehicle) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="available" {{ ($vehicle->status ?? '') === 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="rented" {{ ($vehicle->status ?? '') === 'rented' ? 'selected' : '' }}>En location</option>
                            <option value="maintenance" {{ ($vehicle->status ?? '') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ajoutez des notes sur le changement de statut..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Monthly Performance Chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels ?? []),
                datasets: [{
                    label: 'Locations',
                    data: @json(array_column($monthlyPerformance ?? [], 'rentals')),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Revenus (€)',
                    data: @json(array_column($monthlyPerformance ?? [], 'revenue')),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        function updateStatus() {
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
</body>
</html>
