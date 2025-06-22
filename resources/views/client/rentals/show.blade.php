<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la Location') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('client.rentals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Retour aux locations
                        </a>
                    </div>

                    <!-- Status Alert -->
                    <div class="mb-6">
                        @switch($rental->status)
                            @case('pending')
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                Demande en attente
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                <p>Votre demande de location est en cours d'examen par l'agence. Vous recevrez une notification dès qu'elle sera traitée.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('approved')
                                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                                Location approuvée
                                            </h3>
                                            <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                                <p>Félicitations! Votre demande de location a été approuvée. Contactez l'agence pour organiser la prise en charge du véhicule.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('rejected')
                                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                                Location refusée
                                            </h3>
                                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                <p>Votre demande de location a été refusée par l'agence. Vous pouvez essayer avec d'autres dates ou contacter l'agence pour plus d'informations.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                            
                            @case('completed')
                                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                                Location terminée
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                                <p>Cette location a été complétée avec succès. Merci d'avoir utilisé nos services!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Véhicule</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    @if($rental->car->image)
                                        <img src="{{ asset('storage/' . $rental->car->image) }}" alt="{{ $rental->car->brand }} {{ $rental->car->model }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $rental->car->brand }} {{ $rental->car->model }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rental->car->year }} • {{ $rental->car->registration_number }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-500">{{ $rental->car->color ?? 'Couleur non spécifiée' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Agency Information -->
                            <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Agence</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $rental->car->agency->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $rental->car->agency->address ?? 'Adresse non spécifiée' }}</p>
                                @if($rental->car->agency->user->phone)
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tél: {{ $rental->car->agency->user->phone }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Rental Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Détails de la location</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Numéro de réservation</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">#{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                                        <dd>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @switch($rental->status)
                                                    @case('approved')
                                                        bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200
                                                        @break
                                                    @case('pending')
                                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200
                                                        @break
                                                    @case('rejected')
                                                        bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200
                                                        @break
                                                    @case('completed')
                                                        bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                                @endswitch">
                                                {{ ucfirst($rental->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>

                                <hr class="border-gray-300 dark:border-gray-600">

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de début</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ $rental->start_date->format('d/m/Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de fin</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ $rental->end_date->format('d/m/Y') }}</dd>
                                    </div>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Durée</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $rental->start_date->diffInDays($rental->end_date) }} jour(s)</dd>
                                </div>

                                <hr class="border-gray-300 dark:border-gray-600">

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prix par jour</dt>
                                        <dd class="text-gray-900 dark:text-gray-100">{{ number_format($rental->car->price_per_day, 2) }}€</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prix total</dt>
                                        <dd class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($rental->total_price, 2) }}€</dd>
                                    </div>
                                </div>

                                <hr class="border-gray-300 dark:border-gray-600">

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de demande</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $rental->created_at->format('d/m/Y à H:i') }}</dd>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 space-y-2">
                                @if($rental->status === 'pending')
                                    <form method="POST" action="{{ route('client.rentals.cancel', $rental) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande de location?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Annuler la demande
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('client.cars.show', $rental->car) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Voir le véhicule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
