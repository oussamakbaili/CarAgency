<!-- Analytics Charts Widget -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Monthly Spending Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Dépenses Mensuelles</h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">6 derniers mois</span>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir tout
                </button>
            </div>
        </div>
        
        @if($analytics['monthlySpending']->count() > 0)
        <div class="h-64 flex items-end space-x-2">
            @php
                $maxAmount = $analytics['monthlySpending']->max('total') ?: 1;
            @endphp
            @foreach($analytics['monthlySpending'] as $month)
            <div class="flex-1 flex flex-col items-center group">
                <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t transition-all duration-300 group-hover:from-blue-600 group-hover:to-blue-400" 
                     style="height: {{ ($month->total / $maxAmount) * 200 }}px">
                </div>
                <div class="text-xs text-gray-600 mt-2">
                    {{ Carbon::create($month->year, $month->month, 1)->format('M') }}
                </div>
                <div class="text-xs font-medium text-gray-900 group-hover:text-blue-600">
                    {{ number_format($month->total, 0) }}€
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="h-64 flex items-center justify-center">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune donnée</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par faire des locations pour voir vos statistiques.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Rental Status Distribution -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Répartition des Locations</h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Total: {{ $totalRentals }}</span>
            </div>
        </div>
        
        @if($analytics['rentalStatusDistribution']->count() > 0)
        <div class="space-y-4">
            @php
                $statusColors = [
                    'completed' => 'green',
                    'approved' => 'blue',
                    'pending' => 'yellow',
                    'cancelled' => 'red',
                    'rejected' => 'gray'
                ];
                $statusLabels = [
                    'completed' => 'Terminées',
                    'approved' => 'Approuvées',
                    'pending' => 'En attente',
                    'cancelled' => 'Annulées',
                    'rejected' => 'Rejetées'
                ];
            @endphp
            
            @foreach($analytics['rentalStatusDistribution'] as $status => $count)
            @php
                $percentage = $totalRentals > 0 ? ($count / $totalRentals) * 100 : 0;
                $color = $statusColors[$status] ?? 'gray';
            @endphp
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-{{ $color }}-500 mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">{{ $statusLabels[$status] ?? ucfirst($status) }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">{{ $count }}</span>
                        <span class="text-xs text-gray-500">({{ number_format($percentage, 1) }}%)</span>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="h-32 flex items-center justify-center">
            <div class="text-center">
                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="mt-1 text-sm text-gray-500">Aucune donnée disponible</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Top Car Brands -->
@if($analytics['topCarBrands']->count() > 0)
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Marques Préférées</h2>
        <span class="text-sm text-gray-500">Basé sur vos locations</span>
    </div>
    
    <div class="space-y-3">
        @foreach($analytics['topCarBrands'] as $brand)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-sm font-medium text-blue-600">{{ $loop->iteration }}</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">{{ $brand->brand }}</h3>
                    <p class="text-sm text-gray-500">{{ $brand->count }} location{{ $brand->count > 1 ? 's' : '' }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm font-medium text-gray-900">
                    {{ $totalRentals > 0 ? number_format(($brand->count / $totalRentals) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Rental Insights -->
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Insights Personnalisés</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $analytics['averageDuration'] }}</div>
            <div class="text-sm text-gray-600">jours de location moyenne</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ number_format($averageRentalValue, 0) }}€</div>
            <div class="text-sm text-gray-600">valeur moyenne par location</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $totalRentals > 0 ? number_format(($completedRentals / $totalRentals) * 100, 1) : 0 }}%</div>
            <div class="text-sm text-gray-600">taux de completion</div>
        </div>
    </div>
</div>
