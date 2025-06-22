<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Louer un Véhicule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('client.cars.show', $car) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Retour
                        </a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Car Summary -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Véhicule sélectionné</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    @if($car->image)
                                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->brand }} {{ $car->model }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $car->year }} • {{ $car->registration_number }}</p>
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($car->price_per_day, 2) }}€/jour</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Agency Info -->
                            <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Agence</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $car->agency->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $car->agency->address ?? 'Adresse non spécifiée' }}</p>
                            </div>
                        </div>

                        <!-- Rental Form -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Détails de la location</h3>
                            
                            @if ($errors->any())
                                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                                Il y a eu des erreurs avec votre demande.
                                            </h3>
                                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                <ul role="list" class="list-disc pl-5 space-y-1">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('client.rentals.store', $car) }}" id="rental-form">
                                @csrf
                                
                                <div class="space-y-4">
                                    <!-- Start Date -->
                                    <div>
                                        <x-input-label for="start_date" :value="__('Date de début')" />
                                        <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </div>

                                    <!-- End Date -->
                                    <div>
                                        <x-input-label for="end_date" :value="__('Date de fin')" />
                                        <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" required />
                                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                    </div>

                                    <!-- Price Calculation -->
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Calcul du prix</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Prix par jour:</span>
                                                <span class="text-gray-900 dark:text-gray-100">{{ number_format($car->price_per_day, 2) }}€</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Nombre de jours:</span>
                                                <span class="text-gray-900 dark:text-gray-100" id="days-count">-</span>
                                            </div>
                                            <hr class="border-gray-300 dark:border-gray-600">
                                            <div class="flex justify-between font-semibold">
                                                <span class="text-gray-900 dark:text-gray-100">Total:</span>
                                                <span class="text-blue-600 dark:text-blue-400" id="total-price">-</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <x-primary-button class="w-full justify-center">
                                            {{ __('Confirmer la demande de location') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const daysCountElement = document.getElementById('days-count');
            const totalPriceElement = document.getElementById('total-price');
            const pricePerDay = {{ $car->price_per_day }};

            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            startDateInput.min = tomorrow.toISOString().split('T')[0];

            function calculatePrice() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (startDate && endDate && endDate > startDate) {
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    const totalPrice = daysDiff * pricePerDay;

                    daysCountElement.textContent = daysDiff;
                    totalPriceElement.textContent = totalPrice.toFixed(2) + '€';
                } else {
                    daysCountElement.textContent = '-';
                    totalPriceElement.textContent = '-';
                }
            }

            startDateInput.addEventListener('change', function() {
                // Set minimum end date to the day after start date
                if (this.value) {
                    const startDate = new Date(this.value);
                    startDate.setDate(startDate.getDate() + 1);
                    endDateInput.min = startDate.toISOString().split('T')[0];
                }
                calculatePrice();
            });

            endDateInput.addEventListener('change', calculatePrice);
        });
    </script>
</x-app-layout>
