<!-- Advanced Statistics Widget -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Rentals with Trend -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Total Locations</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $totalRentals }}</p>
                <p class="text-sm text-gray-500">{{ $completedRentals }} terminées</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center">
            <span class="text-sm text-green-600 font-medium">+12%</span>
            <span class="text-sm text-gray-500 ml-2">vs mois dernier</span>
        </div>
    </div>

    <!-- Active Rentals -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Locations Actives</h2>
                <p class="text-3xl font-bold text-green-600">{{ $activeRentals }}</p>
                <p class="text-sm text-gray-500">{{ $pendingRentals }} en attente</p>
            </div>
            <div class="p-3 rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        @if($activeRentals > 0)
        <div class="mt-4">
            <a href="{{ route('client.rentals.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                Voir mes locations →
            </a>
        </div>
        @endif
    </div>

    <!-- Total Spent with Monthly Breakdown -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Total Dépensé</h2>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($totalSpent, 0) }}€</p>
                <p class="text-sm text-gray-500">{{ number_format($thisMonthSpent, 0) }}€ ce mois</p>
            </div>
            <div class="p-3 rounded-full bg-purple-100">
                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="text-sm text-gray-600">
                Moyenne: {{ number_format($averageRentalValue, 0) }}€/location
            </div>
        </div>
    </div>

    <!-- Rating & Reviews -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Note Moyenne</h2>
                <div class="flex items-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $averageRating }}</p>
                    <span class="text-lg text-gray-500 ml-1">/5</span>
                </div>
                <p class="text-sm text-gray-500">{{ $totalReviews }} avis</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100">
                <svg class="h-8 w-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
        </div>
        @if($totalReviews > 0)
        <div class="mt-4">
            <div class="flex items-center">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        </div>
        @endif
    </div>
</div>
