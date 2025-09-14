@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Rapports Financiers</h1>
        <p class="text-gray-600">Générez et consultez vos rapports financiers détaillés</p>
    </div>

    <!-- Report Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenus Totaux</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports->sum('total_revenue'), 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Frais Totaux</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports->sum('total_fees'), 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Bénéfices Nets</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($reports->sum('net_profit'), 0, ',', ' ') }} MAD</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Croissance</p>
                    <p class="text-2xl font-semibold text-gray-900">+12.5%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Generation -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Générer un Rapport</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de Rapport</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Sélectionner un type</option>
                    <option value="revenue">Rapport de Revenus</option>
                    <option value="expenses">Rapport de Dépenses</option>
                    <option value="profit_loss">Bénéfices et Pertes</option>
                    <option value="tax">Rapport Fiscal</option>
                    <option value="custom">Rapport Personnalisé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Sélectionner une période</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="quarter">Ce trimestre</option>
                    <option value="year">Cette année</option>
                    <option value="custom">Période personnalisée</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Générer le Rapport
                </button>
            </div>
        </div>
    </div>

    <!-- Financial Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des Revenus</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <p class="text-gray-500">Graphique des revenus (à implémenter)</p>
            </div>
        </div>

        <!-- Expenses Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition des Dépenses</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <p class="text-gray-500">Graphique des dépenses (à implémenter)</p>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Rapports Récents</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($reports as $report)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <h4 class="text-sm font-medium text-gray-900">{{ $report->name ?? 'Rapport Financier' }}</h4>
                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if(($report->status ?? '') == 'completed') bg-green-100 text-green-800
                                @elseif(($report->status ?? '') == 'processing') bg-yellow-100 text-yellow-800
                                @elseif(($report->status ?? '') == 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($report->status ?? 'Complété') }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $report->description ?? 'Description du rapport financier' }}</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span>Généré le {{ $report->created_at->format('d/m/Y H:i') ?? 'N/A' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $report->format ?? 'PDF' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $report->period ?? 'Mensuel' }}</span>
                        </div>
                    </div>
                    <div class="ml-4 flex space-x-2">
                        <button class="text-sm text-blue-600 hover:text-blue-900">Télécharger</button>
                        <button class="text-sm text-gray-600 hover:text-gray-900">Partager</button>
                        <button class="text-sm text-red-600 hover:text-red-900">Supprimer</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rapport généré</h3>
                <p class="mt-1 text-sm text-gray-500">Générez votre premier rapport financier.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
