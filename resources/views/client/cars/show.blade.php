<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $car->brand }} {{ $car->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('client.cars.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Retour
                        </a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Car Image -->
                        <div>
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-96 object-cover rounded-lg">
                            @else
                                <div class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Car Details -->
                        <div>
                            <div class="space-y-6">
                                <!-- Title and Price -->
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $car->brand }} {{ $car->model }}
                                    </h1>
                                    <div class="mt-2 flex items-center space-x-4">
                                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format($car->price_per_day, 2) }}€/jour
                                        </span>
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                            Disponible
                                        </span>
                                    </div>
                                </div>

                                <!-- Car Specifications -->
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Caractéristiques</h3>
                                    <dl class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <dt class="font-medium text-gray-500 dark:text-gray-400">Année</dt>
                                            <dd class="text-gray-900 dark:text-gray-100">{{ $car->year }}</dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-500 dark:text-gray-400">Immatriculation</dt>
                                            <dd class="text-gray-900 dark:text-gray-100">{{ $car->registration_number }}</dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-500 dark:text-gray-400">Couleur</dt>
                                            <dd class="text-gray-900 dark:text-gray-100">{{ $car->color ?? 'Non spécifiée' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-500 dark:text-gray-400">Carburant</dt>
                                            <dd class="text-gray-900 dark:text-gray-100">{{ $car->fuel_type ?? 'Non spécifié' }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Agency Info -->
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Agence</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $car->agency->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $car->agency->address ?? 'Adresse non spécifiée' }}</p>
                                </div>

                                <!-- Description -->
                                @if($car->description)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $car->description }}</p>
                                </div>
                                @endif

                                <!-- Rent Button -->
                                <div class="pt-4">
                                    <a href="{{ route('client.rentals.create', $car) }}" class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Louer ce véhicule
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Bookings -->
                    @if($car->rentals->count() > 0)
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Réservations à venir</h3>
                        <div class="space-y-2">
                            @foreach($car->rentals as $rental)
                            <div class="flex justify-between items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <span class="text-gray-900 dark:text-gray-100">
                                    {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200">
                                    Réservé
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 