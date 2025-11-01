@extends('layouts.agence')

@section('content')
<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Détails de la Réservation</h1>
                    <p class="mt-2 text-gray-600">Réservation #{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('agence.bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                    @if($rental->status === 'pending')
                        <form method="POST" action="{{ route('agence.rentals.approve', $rental) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 shadow-sm">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approuver
                            </button>
                        </form>
                        <form method="POST" action="{{ route('agence.rentals.reject', $rental) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter cette réservation ?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 shadow-sm">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Rejeter
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Rental Information -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations de la Réservation</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID Réservation</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'active' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'rejected' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'En attente',
                                            'active' => 'Active',
                                            'completed' => 'Terminée',
                                            'cancelled' => 'Annulée',
                                            'rejected' => 'Rejetée'
                                        ];
                                        $class = $statusClasses[$rental->status] ?? 'bg-gray-100 text-gray-800';
                                        $label = $statusLabels[$rental->status] ?? ucfirst($rental->status);
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                        {{ $label }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de Création</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Montant Total</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($rental->total_price, 0) }} DH</dd>
                            </div>
                            @if($rental->start_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de Début</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($rental->end_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de Fin</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($rental->start_date && $rental->end_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durée</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) }} jours
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <a href="{{ route('agence.cars.show', $rental->car) }}" class="block group bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100 hover:border-[#C2410C]/40 hover:shadow-md transition">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Véhicule</h3>
                        <span class="text-xs text-[#C2410C] opacity-0 group-hover:opacity-100 transition">Voir la fiche véhicule →</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            @if($rental->car->image_url)
                                <img class="h-20 w-20 rounded-lg object-cover" src="{{ $rental->car->image_url }}" alt="{{ $rental->car->brand }} {{ $rental->car->model }}">
                            @else
                                <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }} {{ $rental->car->year }}</h4>
                                <p class="text-sm text-gray-500">Immatriculation: {{ $rental->car->registration_number }}</p>
                                @if($rental->car->color)
                                    <p class="text-sm text-gray-500">Couleur: {{ $rental->car->color }}</p>
                                @endif
                                <p class="text-sm text-gray-500">Prix par jour: {{ number_format($rental->car->price_per_day, 0) }} DH</p>

                                <!-- Extra vehicle details -->
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
                                    @if($rental->car->category)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Catégorie:</span>
                                        <span class="font-medium">{{ $rental->car->category->name ?? $rental->car->category_id }}</span>
                                    </div>
                                    @endif

                                    @if($rental->car->fuel_type)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Carburant:</span>
                                        <span class="font-medium">{{ ucfirst($rental->car->fuel_type) }}</span>
                                    </div>
                                    @endif

                                    @if($rental->car->transmission)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Transmission:</span>
                                        <span class="font-medium">{{ ucfirst($rental->car->transmission) }}</span>
                                    </div>
                                    @endif

                                    @if(!is_null($rental->car->seats))
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Places:</span>
                                        <span class="font-medium">{{ $rental->car->seats }}</span>
                                    </div>
                                    @endif

                                    @if(!is_null($rental->car->engine_size))
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Moteur:</span>
                                        <span class="font-medium">{{ $rental->car->engine_size }}</span>
                                    </div>
                                    @endif

                                    @if(!is_null($rental->car->mileage))
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Kilométrage:</span>
                                        <span class="font-medium">{{ number_format($rental->car->mileage, 0, ',', ' ') }} km</span>
                                    </div>
                                    @endif

                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Statut:</span>
                                        <span class="font-medium">{{ ucfirst($rental->car->status) }}</span>
                                    </div>

                                    @if(!is_null($rental->car->available_stock))
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Stock dispo:</span>
                                        <span class="font-medium">{{ $rental->car->available_stock }} / {{ $rental->car->stock_quantity }}</span>
                                    </div>
                                    @endif

                                    @if($rental->car->last_maintenance)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Dernière maintenance:</span>
                                        <span class="font-medium">{{ optional($rental->car->last_maintenance)->format('d/m/Y') }}</span>
                                    </div>
                                    @endif

                                    @if($rental->car->maintenance_due)
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Prochaine maintenance:</span>
                                        <span class="font-medium">{{ optional($rental->car->maintenance_due)->format('d/m/Y') }}</span>
                                    </div>
                                    @endif
                                </div>

                                @if($rental->car->features && is_array($rental->car->features) && count($rental->car->features))
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 mb-2">Équipements:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($rental->car->features as $feature)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $feature }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Client Information (expanded) -->
                <a href="{{ $rental->client ? route('agence.customers.show', $rental->client) : '#' }}" class="block group bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100 hover:border-[#C2410C]/40 hover:shadow-md transition">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Client</h3>
                        <span class="text-xs text-[#C2410C] opacity-0 group-hover:opacity-100 transition">Voir le profil client →</span>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">{{ substr($rental->user->name ?? 'N/A', 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $rental->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $rental->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($rental->client)
                        <div class="mt-5 grid grid-cols-1 gap-3 text-sm text-gray-700">
                            @if($rental->client->phone)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $rental->client->phone }}
                            </div>
                            @endif

                            @if($rental->client->cin)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $rental->client->cin }}
                            </div>
                            @endif

                            @if($rental->client->birthday)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ optional($rental->client->birthday)->format('d/m/Y') }}
                            </div>
                            @endif

                            @if($rental->client->address)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $rental->client->address }}
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </a>
                <!-- Fin client info -->
            </div>
        </div>
    </div>
</div>

@endsection
