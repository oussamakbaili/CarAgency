@extends('layouts.admin')

@section('header', 'Santé du Système')

@section('content')
<!-- System Health Status -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($healthChecks as $component => $status)
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ $status['status'] === 'healthy' ? 'bg-green-100' : 'bg-red-100' }}">
                    @if($status['status'] === 'healthy')
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @else
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    @endif
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900 capitalize">{{ $component }}</h2>
                    <p class="text-sm {{ $status['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $status['status'] === 'healthy' ? 'Opérationnel' : 'Problème détecté' }}
                    </p>
                    @if(isset($status['response_time']))
                    <p class="text-xs text-gray-500">{{ $status['response_time'] }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- System Information and Performance -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- System Information -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Système</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Version PHP:</span>
                <span class="text-sm text-gray-900">{{ $systemInfo['php_version'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Version Laravel:</span>
                <span class="text-sm text-gray-900">{{ $systemInfo['laravel_version'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Serveur:</span>
                <span class="text-sm text-gray-900">{{ $systemInfo['server_software'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Limite mémoire:</span>
                <span class="text-sm text-gray-900">{{ $systemInfo['memory_limit'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Temps d'exécution max:</span>
                <span class="text-sm text-gray-900">{{ $systemInfo['max_execution_time'] }}s</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Taille upload max:</span>
                <span class="text-sm text-gray-900">{{ $systemInfo['upload_max_filesize'] }}</span>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Métriques de Performance</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Utilisateurs</p>
                        <p class="text-sm text-gray-500">Total enregistrés</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $performanceMetrics['total_users'] }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Agences</p>
                        <p class="text-sm text-gray-500">Total approuvées</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $performanceMetrics['total_agencies'] }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Véhicules</p>
                        <p class="text-sm text-gray-500">Total enregistrés</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $performanceMetrics['total_vehicles'] }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Réservations</p>
                        <p class="text-sm text-gray-500">Total actives</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $performanceMetrics['activeRentals'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Storage and Database Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Storage Usage -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Utilisation du Stockage</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Stockage utilisé</span>
                    <span class="text-gray-900">{{ $performanceMetrics['storage_usage'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Espace libre</span>
                    <span class="text-gray-900">{{ $healthChecks['storage']['free_space'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Information -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Base de Données</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Taille de la base:</span>
                <span class="text-sm text-gray-900">{{ $performanceMetrics['database_size'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Statut:</span>
                <span class="text-sm {{ $healthChecks['database']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $healthChecks['database']['status'] === 'healthy' ? 'Opérationnel' : 'Problème' }}
                </span>
            </div>
            @if(isset($healthChecks['database']['response_time']))
            <div class="flex justify-between">
                <span class="text-sm text-gray-500">Temps de réponse:</span>
                <span class="text-sm text-gray-900">{{ $healthChecks['database']['response_time'] }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- System Actions -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Système</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button onclick="clearCache()" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Vider le Cache
        </button>
        
        <button onclick="optimizeSystem()" class="flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Optimiser le Système
        </button>
        
        <a href="{{ route('admin.system.backups') }}" class="flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Gérer les Sauvegardes
        </a>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('Êtes-vous sûr de vouloir vider le cache ?')) {
        fetch('{{ route("admin.system.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache vidé avec succès !');
                location.reload();
            } else {
                alert('Erreur lors du vidage du cache.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du vidage du cache.');
        });
    }
}

function optimizeSystem() {
    if (confirm('Êtes-vous sûr de vouloir optimiser le système ?')) {
        fetch('{{ route("admin.system.optimize") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Système optimisé avec succès !');
                location.reload();
            } else {
                alert('Erreur lors de l\'optimisation du système.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'optimisation du système.');
        });
    }
}
</script>
@endsection
