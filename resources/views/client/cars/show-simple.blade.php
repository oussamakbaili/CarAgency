<!DOCTYPE html>
<html>
<head>
    <title>{{ $car->brand ?? 'Car' }} {{ $car->model ?? 'Details' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-blue-600 text-white w-64 flex-shrink-0">
            <div class="p-6">
                <h1 class="text-xl font-bold">Client Panel</h1>
            </div>
            
            <!-- Agency Info -->
            <div class="px-6 pb-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 rounded-lg p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 3v18"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold">{{ $car->agency->agency_name ?? 'Agence Premium' }}</h2>
                        <p class="text-sm text-blue-200">{{ $car->agency->user->email ?? 'contact@agence.com' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="px-3">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('client.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Tableau de bord
                    </a>

                    <!-- Cars Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">Véhicules</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('client.cars.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-blue-700 text-white">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 3v18"></path>
                                </svg>
                                Tous les véhicules
                            </a>
                        </div>
                    </div>

                    <!-- Rentals Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">Mes Réservations</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('client.rentals.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Toutes les réservations
                            </a>
                        </div>
                    </div>

                    <!-- Profile Section -->
                    <div class="mt-6">
                        <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">Mon Compte</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('client.profile.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Mon profil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Info at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 w-64 p-4 border-t border-blue-500">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold">{{ substr(auth()->user()->name ?? 'C', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Client' }}</p>
                            <p class="text-xs text-blue-200 truncate">{{ auth()->user()->email ?? 'client@example.com' }}</p>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord</h1>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Revenus du mois</p>
                                <p class="text-lg font-semibold text-green-600">{{ number_format($car->price_per_day ?? 0, 2) }}€/jour</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Locations actives</p>
                                <p class="text-lg font-semibold text-blue-600">1</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $car->brand ?? 'Unknown' }} {{ $car->model ?? 'Car' }}</h1>
                <p class="text-gray-600">{{ $car->registration_number ?? 'N/A' }} • {{ $car->year ?? 'N/A' }}</p>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex space-x-4 mb-8">
                <a href="{{ route('client.cars.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    ← Retour
                </a>
                <a href="{{ route('client.rentals.create', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Louer
                </a>
            </div>

            <!-- Car Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Photos Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-4">Photos du Véhicule</h2>
                        @if($car->image_url)
                            <img src="{{ $car->image_url }}" alt="Car Photo" class="w-full h-64 object-cover rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <p class="text-gray-500">Aucune photo disponible</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Details Section -->
                <div class="space-y-6">
                    <!-- General Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-4">Informations Générales</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Marque:</span>
                                <span class="font-medium">{{ $car->brand ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Modèle:</span>
                                <span class="font-medium">{{ $car->model ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Année:</span>
                                <span class="font-medium">{{ $car->year ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Immatriculation:</span>
                                <span class="font-medium">{{ $car->registration_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Couleur:</span>
                                <span class="font-medium">{{ $car->color ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Carburant:</span>
                                <span class="font-medium">{{ $car->fuel_type ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold mb-4">Tarification</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Prix par jour:</span>
                                <span class="font-medium text-green-600">{{ number_format($car->price_per_day ?? 0, 2) }}€</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Statut:</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ ($car->status ?? 'unknown') === 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($car->status ?? 'Unknown') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($car->description ?? false)
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Description</h2>
                <p class="text-gray-700">{{ $car->description }}</p>
            </div>
            @endif
            </div>
        </div>
    </div>
    </div>
</body>
</html>
