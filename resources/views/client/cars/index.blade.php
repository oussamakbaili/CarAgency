<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Parcourir les Véhicules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('client.cars.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rechercher</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                       placeholder="Marque, modèle..."
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Brand Filter -->
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marque</label>
                                <select id="brand" name="brand" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Toutes les marques</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                            {{ $brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Max Price -->
                            <div>
                                <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix max/jour</label>
                                <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" 
                                       placeholder="100€"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Year From -->
                            <div>
                                <label for="year_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Année min</label>
                                <input type="number" id="year_from" name="year_from" value="{{ request('year_from') }}" 
                                       placeholder="2020"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $cars->total() }} véhicule(s) trouvé(s)
                            </div>
                            <div class="space-x-2">
                                <a href="{{ route('client.cars.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Réinitialiser
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cars Grid -->
            @if($cars->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($cars as $car)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <!-- Car Image -->
                            <div class="aspect-video bg-gray-200 dark:bg-gray-700">
                                @if($car->image)
                                    <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Car Details -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $car->brand }} {{ $car->model }}
                                    </h3>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($car->price_per_day, 2) }}€/jour
                                    </span>
                                </div>

                                <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <p><span class="font-medium">Année:</span> {{ $car->year }}</p>
                                    <p><span class="font-medium">Agence:</span> {{ $car->agency->user->name }}</p>
                                    <p><span class="font-medium">Immatriculation:</span> {{ $car->registration_number }}</p>
                                </div>

                                @if($car->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                        {{ $car->description }}
                                    </p>
                                @endif

                                <div class="flex justify-between items-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                        Disponible
                                    </span>
                                    <div class="space-x-2">
                                        <a href="{{ route('client.cars.show', $car) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            Voir détails
                                        </a>
                                        <a href="{{ route('client.rentals.create', $car) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Louer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        {{ $cars->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <!-- No Cars Found -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun véhicule trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Essayez de modifier vos critères de recherche.</p>
                        <div class="mt-6">
                            <a href="{{ route('client.cars.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Voir tous les véhicules
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 