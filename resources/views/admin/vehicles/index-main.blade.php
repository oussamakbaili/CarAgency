@extends('layouts.admin')

@section('header', 'Gestion des Véhicules')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Véhicules -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Véhicules</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Car::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disponibles -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Disponibles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Car::where('status', 'available')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- En location -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">En location</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Car::where('status', 'rented')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catégories -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Catégories</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Category::count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des Véhicules -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Gestion des Véhicules</h3>
                <p class="text-sm text-gray-500">Gérez la flotte de véhicules de la plateforme</p>
            </div>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('admin.vehicles.index') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Tous les véhicules</p>
                        <p class="text-xs text-gray-500">Voir et gérer tous les véhicules</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.vehicles.categories') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Catégories de véhicules</p>
                        <p class="text-xs text-gray-500">Gérer les catégories et types</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.vehicles.fleet-analytics') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Analyses de flotte</p>
                        <p class="text-xs text-gray-500">Statistiques et performances</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

