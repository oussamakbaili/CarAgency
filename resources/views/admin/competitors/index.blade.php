@extends('layouts.admin')

@section('title', 'Analyse Concurrentielle')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Analyse Concurrentielle Maroc</h1>
        <p class="text-gray-600 mt-2">Positionnement de ToubCar face aux leaders du marché</p>
    </div>

    <!-- Statistiques ToubCar vs Concurrence -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Fleet ToubCar</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($toubcarStats['total_cars']) }} véhicules</p>
                    <p class="text-sm text-gray-500">vs {{ collect($competitors)->sum('fleet_size') }} total concurrence</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Prix Moyen</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($toubcarStats['price_range']['avg'], 0) }} MAD/jour</p>
                    <p class="text-sm text-gray-500">vs {{ number_format(collect($competitors)->avg(function($c) { return ($c['price_range_min'] + $c['price_range_max']) / 2; }), 0) }} MAD concurrence</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Part de Marché</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $marketAnalysis['toubcar_share'] }}%</p>
                    <p class="text-sm text-gray-500">Objectif: 15%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Croissance</h3>
                    <p class="text-2xl font-bold text-orange-600">+25%</p>
                    <p class="text-sm text-gray-500">vs +8% marché</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h3 class="text-lg font-semibold text-gray-900">Actions Rapides</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.competitors.report') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>Rapport Détaillé
                </a>
                <a href="{{ route('admin.competitors.swot') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-balance-scale mr-2"></i>Analyse SWOT
                </a>
                <a href="{{ route('admin.competitors.pricing') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-tags mr-2"></i>Benchmark Prix
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Concurrents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 6 Concurrents Maroc</h3>
            <div class="space-y-4">
                @foreach($comparisons as $index => $comparison)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-bold text-gray-600">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-900">{{ $comparison['competitor']['name'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $comparison['competitor']['market_share'] }}% marché</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold {{ $comparison['analysis']['overall_score']['score'] >= 70 ? 'text-green-600' : ($comparison['analysis']['overall_score']['score'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $comparison['analysis']['overall_score']['score'] }}/100
                            </div>
                            <div class="text-xs text-gray-500">Score ToubCar</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Fleet:</span>
                            <span class="font-medium">{{ number_format($comparison['competitor']['fleet_size']) }} véhicules</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Prix:</span>
                            <span class="font-medium">{{ number_format($comparison['competitor']['price_range_min']) }}-{{ number_format($comparison['competitor']['price_range_max']) }} MAD</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Rating:</span>
                            <span class="font-medium">{{ $comparison['competitor']['rating'] }}/5</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Commission:</span>
                            <span class="font-medium">{{ $comparison['competitor']['commission_rate'] }}%</span>
                        </div>
                    </div>
                    
                    <!-- Avantages ToubCar -->
                    @if($comparison['analysis']['price_advantage']['direction'] === 'lower')
                    <div class="mt-3 p-2 bg-green-50 border border-green-200 rounded">
                        <div class="text-sm text-green-700">
                            <i class="fas fa-check-circle mr-1"></i>
                            ToubCar est {{ abs($comparison['analysis']['price_advantage']['percentage']) }}% moins cher
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Analyse de Marché -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Analyse de Marché</h3>
            
            <!-- Concentration du marché -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-2">Concentration du Marché</h4>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $marketAnalysis['market_concentration']['top3_concentration'] }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Top 3: {{ $marketAnalysis['market_concentration']['top3_concentration'] }}%</span>
                    <span class="{{ $marketAnalysis['market_concentration']['level'] === 'high' ? 'text-red-600' : ($marketAnalysis['market_concentration']['level'] === 'medium' ? 'text-yellow-600' : 'text-green-600') }}">
                        {{ $marketAnalysis['market_concentration']['level'] === 'high' ? 'Élevée' : ($marketAnalysis['market_concentration']['level'] === 'medium' ? 'Modérée' : 'Faible') }}
                    </span>
                </div>
            </div>

            <!-- Top 3 concurrents -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 mb-3">Top 3 Leaders</h4>
                <div class="space-y-2">
                    @foreach($marketAnalysis['top_3_competitors'] as $competitor)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="font-medium">{{ $competitor['name'] }}</span>
                        <span class="text-sm text-gray-600">{{ $competitor['market_share'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Opportunités de croissance -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Opportunités de Croissance</h4>
                <div class="space-y-2">
                    @foreach($marketAnalysis['growth_opportunities']['underserved_segments'] as $segment)
                    <div class="flex items-center p-2 bg-blue-50 border border-blue-200 rounded">
                        <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                        <span class="text-sm text-blue-800">{{ $segment }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recommandations Stratégiques -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommandations Stratégiques</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-bold">
                        1
                    </div>
                    <h4 class="font-semibold text-gray-900 ml-3">Prix Compétitifs</h4>
                </div>
                <p class="text-sm text-gray-600 mb-3">Maintenir notre avantage prix de 15-25% face à la concurrence</p>
                <div class="text-xs text-gray-500">Priorité: Haute | Impact: Élevé</div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-bold">
                        2
                    </div>
                    <h4 class="font-semibold text-gray-900 ml-3">Notoriété Marque</h4>
                </div>
                <p class="text-sm text-gray-600 mb-3">Renforcer la reconnaissance de ToubCar sur le marché marocain</p>
                <div class="text-xs text-gray-500">Priorité: Haute | Impact: Moyen</div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-bold">
                        3
                    </div>
                    <h4 class="font-semibold text-gray-900 ml-3">Innovation Techno</h4>
                </div>
                <p class="text-sm text-gray-600 mb-3">Développer des fonctionnalités uniques pour se différencier</p>
                <div class="text-xs text-gray-500">Priorité: Moyenne | Impact: Élevé</div>
            </div>
        </div>
    </div>
</div>
@endsection
