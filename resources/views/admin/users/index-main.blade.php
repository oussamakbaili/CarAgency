@extends('layouts.admin')

@section('header', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Clients -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Clients</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Client::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Actifs -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Clients Actifs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Client::whereHas('rentals', function($query) { $query->where('status', 'active'); })->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nouveaux ce mois -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nouveaux ce mois</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Client::whereMonth('created_at', now()->month)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des Clients -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Gestion des Clients</h3>
                <p class="text-sm text-gray-500">Gérez les comptes clients et leurs réservations</p>
            </div>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('admin.customers.index') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Tous les clients</p>
                        <p class="text-xs text-gray-500">Voir et gérer tous les clients</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Clients actifs</p>
                        <p class="text-xs text-gray-500">Clients avec réservations en cours</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.bookings.main') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Historique de réservations</p>
                        <p class="text-xs text-gray-500">Voir toutes les réservations</p>
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

