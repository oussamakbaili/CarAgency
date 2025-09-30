<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voitures de {{ $agency->agency_name }} - RentCar Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        accent: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">RentCar Platform</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">
                        Accueil
                    </a>
                    <a href="{{ route('public.agencies') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">
                        Toutes les Agences
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Se connecter
                    </a>
                    <a href="{{ route('register.client') }}" class="bg-accent-600 hover:bg-accent-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Voitures de {{ $agency->agency_name }}</h1>
                    <p class="mt-2 text-gray-600">{{ $cars->total() }} voitures disponibles</p>
                </div>
                <a href="{{ route('public.agency.show', $agency) }}" 
                   class="text-primary-600 hover:text-primary-700 font-medium">
                    ← Retour aux détails de l'agence
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('public.agency.cars', $agency) }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marque</label>
                        <input type="text" name="brand" value="{{ request('brand') }}" 
                               placeholder="Marque..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Modèle</label>
                        <input type="text" name="model" value="{{ request('model') }}" 
                               placeholder="Modèle..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix min (MAD/jour)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="Prix min..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix max (MAD/jour)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="Prix max..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cars Grid -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if($cars->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cars as $car)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition duration-300">
                        <div class="p-6">
                            <!-- Car Image Placeholder -->
                            <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                </svg>
                            </div>

                            <div class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $car->year }} • {{ $car->fuel_type }} • {{ $car->transmission }}</p>
                                <p class="text-gray-600 text-sm">{{ $car->seats }} places • {{ $car->doors }} portes</p>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="text-2xl font-bold text-primary-600">
                                    {{ number_format($car->price_per_day, 0) }} MAD
                                    <span class="text-sm font-normal text-gray-500">/jour</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-900">4.8</span>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                @if($car->features && is_array($car->features) && count($car->features) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($car->features as $feature)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('public.car.show', [$agency, $car]) }}" 
                                   class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                    Voir détails
                                </a>
                                <a href="{{ route('public.require-login') }}" 
                                   class="flex-1 bg-accent-600 hover:bg-accent-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                    Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $cars->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune voiture trouvée</h3>
                <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                <div class="mt-6">
                    <a href="{{ route('public.agency.cars', $agency) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        Voir toutes les voitures
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">RentCar Platform</h3>
                    <p class="text-gray-400">La plateforme de location de voitures qui connecte les clients aux meilleures agences.</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Liens utiles</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('public.agencies') }}" class="hover:text-white">Toutes les agences</a></li>
                        <li><a href="#" class="hover:text-white">Comment ça marche</a></li>
                        <li><a href="#" class="hover:text-white">Aide</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Pour les agences</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('register.agency') }}" class="hover:text-white">Rejoindre la plateforme</a></li>
                        <li><a href="#" class="hover:text-white">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: contact@rentcar.com</li>
                        <li>Téléphone: +212 5XX XXX XXX</li>
                        <li>Adresse: Casablanca, Maroc</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} RentCar Platform. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
