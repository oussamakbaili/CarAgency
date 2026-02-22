@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Historique des Prix</h1>
                    <p class="mt-2 text-gray-600">{{ $car->brand }} {{ $car->model }} - {{ $car->registration_number }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.pricing.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                    <a href="{{ route('agence.pricing.car.edit', $car->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier le Prix
                    </a>
                </div>
            </div>
        </div>

        <!-- Car Information -->
        <div class="bg-white shadow-sm rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informations du Véhicule</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h4>
                        <p class="text-sm text-gray-500">{{ $car->year }} • {{ $car->registration_number }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $car->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $car->is_available ? 'Disponible' : 'Occupé' }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $car->rentals->count() }} réservations</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-blue-600">{{ number_format($car->price_per_day, 0) }} DH</div>
                        <div class="text-sm text-gray-500">prix actuel</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing History -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Historique des Modifications de Prix</h3>
            </div>
            <div class="p-6">
                @forelse($history as $entry)
                <div class="flex items-center justify-between py-4 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $entry->reason }}</h4>
                            <p class="text-xs text-gray-500">Modifié par {{ $entry->user->name ?? 'Admin' }} le {{ $entry->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ number_format($entry->old_price, 0) }} DH</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($entry->new_price, 0) }} DH</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            @if($entry->new_price > $entry->old_price)
                                <span class="text-red-600">+{{ number_format($entry->new_price - $entry->old_price, 0) }} DH</span>
                            @else
                                <span class="text-green-600">{{ number_format($entry->new_price - $entry->old_price, 0) }} DH</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun historique</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucune modification de prix enregistrée pour ce véhicule.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Price Chart -->
        @if($history->count() > 0)
        <div class="bg-white shadow-sm rounded-lg mt-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Évolution du Prix</h3>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="priceHistoryChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($history->count() > 0)
    // Price history chart
    const ctx = document.getElementById('priceHistoryChart').getContext('2d');
    const priceHistoryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach($history->reverse() as $entry)
                '{{ $entry->created_at->format("d/m") }}',
                @endforeach
            ],
            datasets: [{
                label: 'Prix (DH)',
                data: [
                    @foreach($history->reverse() as $entry)
                    {{ $entry->new_price }},
                    @endforeach
                ],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1,
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
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return value + ' DH';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection
