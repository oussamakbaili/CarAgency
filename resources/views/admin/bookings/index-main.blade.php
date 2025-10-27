@extends('layouts.admin')

@section('header', 'Gestion des Réservations')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Réservations -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Réservations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actives -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Actives</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terminées -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terminées</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- En attente -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des Réservations -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Gestion des Réservations</h3>
                <p class="text-sm text-gray-500">Gérez toutes les réservations</p>
            </div>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Toutes les réservations</p>
                        <p class="text-xs text-gray-500">Voir et gérer</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.bookings.active') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Locations actives</p>
                        <p class="text-xs text-gray-500">En cours</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.bookings.calendar') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Vue Calendrier</p>
                        <p class="text-xs text-gray-500">Calendrier des locations</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.bookings.analytics') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Analyses</p>
                        <p class="text-xs text-gray-500">Statistiques</p>
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

