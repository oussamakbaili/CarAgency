@extends('layouts.agence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Planification de Maintenance</h1>
                    <p class="mt-2 text-gray-600">Gérez la maintenance de votre flotte de véhicules</p>
                </div>
                <a href="{{ route('agence.maintenances.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouvelle Maintenance
                </a>
            </div>
        </div>

        <!-- Maintenance Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">En Maintenance</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $inProgress }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Programmées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $scheduled }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Terminées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $completed }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Coût Total</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalCost, 0, ',', ' ') }} DH</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Tabs -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="switchTab('in_progress')" id="in_progress-tab" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        En Cours
                    </button>
                    <button onclick="switchTab('scheduled')" id="scheduled-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Programmées
                    </button>
                    <button onclick="switchTab('completed')" id="completed-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Terminées
                    </button>
                </nav>
            </div>

            <!-- In Progress Maintenance -->
            <div id="in_progress-content" class="tab-content p-6">
                <div class="space-y-4">
                    @forelse($maintenances->where('status', 'in_progress') as $maintenance)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $maintenance->car->brand }} {{ $maintenance->car->model }} {{ $maintenance->car->year }}</h3>
                                    <p class="text-sm text-gray-500">{{ $maintenance->title }}</p>
                                    <p class="text-xs text-gray-400">Démarrée le {{ $maintenance->start_date ? $maintenance->start_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($maintenance->cost, 0, ',', ' ') }} DH</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    En cours
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Garage: {{ $maintenance->garage_name ?? 'Non spécifié' }}</span>
                                <span>Est. fin: {{ $maintenance->end_date ? $maintenance->end_date->format('d M Y') : 'Non définie' }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('agence.maintenances.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Voir détails</a>
                            <a href="{{ route('agence.maintenances.edit', $maintenance) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">Modifier</a>
                            <form method="POST" action="{{ route('agence.maintenances.destroy', $maintenance) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune maintenance en cours</h3>
                        <p class="mt-1 text-sm text-gray-500">Aucun véhicule n'est actuellement en maintenance.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Scheduled Maintenance -->
            <div id="scheduled-content" class="tab-content p-6 hidden">
                <div class="space-y-4">
                    @forelse($maintenances->where('status', 'scheduled') as $maintenance)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $maintenance->car->brand }} {{ $maintenance->car->model }} {{ $maintenance->car->year }}</h3>
                                    <p class="text-sm text-gray-500">{{ $maintenance->title }}</p>
                                    <p class="text-xs text-gray-400">Programmée pour le {{ $maintenance->scheduled_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($maintenance->cost, 0, ',', ' ') }} DH</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Programmé
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Garage: {{ $maintenance->garage_name ?? 'Non spécifié' }}</span>
                                <span>Type: {{ $maintenance->type_label }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('agence.maintenances.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Voir détails</a>
                            <a href="{{ route('agence.maintenances.edit', $maintenance) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">Modifier</a>
                            <form method="POST" action="{{ route('agence.maintenances.update-status', $maintenance) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="text-orange-600 hover:text-orange-900 text-sm font-medium">Démarrer</button>
                            </form>
                            <form method="POST" action="{{ route('agence.maintenances.destroy', $maintenance) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune maintenance programmée</h3>
                        <p class="mt-1 text-sm text-gray-500">Aucune maintenance n'est actuellement programmée.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Completed Maintenance -->
            <div id="completed-content" class="tab-content p-6 hidden">
                <div class="space-y-4">
                    @forelse($maintenances->where('status', 'completed') as $maintenance)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $maintenance->car->brand }} {{ $maintenance->car->model }} {{ $maintenance->car->year }}</h3>
                                    <p class="text-sm text-gray-500">{{ $maintenance->title }}</p>
                                    <p class="text-xs text-gray-400">Terminée le {{ $maintenance->end_date ? $maintenance->end_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ number_format($maintenance->cost, 0, ',', ' ') }} DH</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Terminé
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Garage: {{ $maintenance->garage_name ?? 'Non spécifié' }}</span>
                                <span>Durée: {{ $maintenance->start_date && $maintenance->end_date ? $maintenance->start_date->diffInDays($maintenance->end_date) . ' jour(s)' : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('agence.maintenances.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Voir détails</a>
                            <a href="{{ route('agence.maintenances.edit', $maintenance) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">Modifier</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune maintenance terminée</h3>
                        <p class="mt-1 text-sm text-gray-500">Aucune maintenance n'a été terminée récemment.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endpush
@endsection