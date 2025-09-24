<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $vehicle->brand }} {{ $vehicle->model }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Retour à la liste
                </a>
                <button onclick="updateStatus()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Modifier le statut
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Vehicle Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Vehicle Image -->
                        <div>
                            @if($vehicle->image)
                                <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->brand }} {{ $vehicle->model }}" class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Vehicle Details -->
                        <div class="lg:col-span-2">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $vehicle->brand }} {{ $vehicle->model }}
                                    </h1>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                                        @if($vehicle->status === 'available') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                        @elseif($vehicle->status === 'rented') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200
                                        @elseif($vehicle->status === 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                        @endif">
                                        @if($vehicle->status === 'available') Disponible
                                        @elseif($vehicle->status === 'rented') En location
                                        @elseif($vehicle->status === 'maintenance') Maintenance
                                        @else {{ ucfirst($vehicle->status) }}
                                        @endif
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Immatriculation</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ $vehicle->registration_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Année</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ $vehicle->year }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Prix par jour</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ number_format($vehicle->price_per_day, 2) }}€</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-500 dark:text-gray-400">Agence</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ $vehicle->agency->agency_name ?? 'N/A' }}</dd>
                                    </div>
                                </div>

                                @if($vehicle->description)
                                <div>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400 mb-2">Description</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $vehicle->description }}</dd>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Locations</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $statistics['totalRentals'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Locations Terminées</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $statistics['completedRentals'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Revenus Totaux</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ number_format($statistics['totalRevenue'], 2) }}€</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Taux d'Utilisation</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ number_format($statistics['utilizationRate'], 1) }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Monthly Performance Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Performance Mensuelle</h3>
                        <div class="h-64">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Rentals -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Locations Récentes</h3>
                        <div class="space-y-4">
                            @forelse($rentalHistory->take(5) as $rental)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $rental->user->name ?? 'Client inconnu' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ number_format($rental->total_price, 2) }}€
                                    </p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($rental->status === 'active') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                        @elseif($rental->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200
                                        @elseif($rental->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                        @endif">
                                        @if($rental->status === 'active') Actif
                                        @elseif($rental->status === 'completed') Terminé
                                        @elseif($rental->status === 'pending') En attente
                                        @else {{ ucfirst($rental->status) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">Aucune location trouvée</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental History Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Historique des Locations</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Période</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prix</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($rentalHistory as $rental)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $rental->user->name ?? 'Client inconnu' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($rental->total_price, 2) }}€
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($rental->status === 'active') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                            @elseif($rental->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200
                                            @elseif($rental->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                            @endif">
                                            @if($rental->status === 'active') Actif
                                            @elseif($rental->status === 'completed') Terminé
                                            @elseif($rental->status === 'pending') En attente
                                            @else {{ ucfirst($rental->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $rental->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aucune location trouvée
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($rentalHistory->hasPages())
                    <div class="mt-4">
                        {{ $rentalHistory->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modifier le statut</h3>
                    <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.vehicles.update-status', $vehicle) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nouveau statut</label>
                        <select name="status" id="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="available" {{ $vehicle->status === 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="rented" {{ $vehicle->status === 'rented' ? 'selected' : '' }}>En location</option>
                            <option value="maintenance" {{ $vehicle->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ajoutez des notes sur le changement de statut..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Performance Chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Locations',
                    data: @json(array_column($monthlyPerformance, 'rentals')),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Revenus (€)',
                    data: @json(array_column($monthlyPerformance, 'revenue')),
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
</x-app-layout>
