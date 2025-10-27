@extends('layouts.admin')

@section('header', 'Gestion des Utilisateurs & Véhicules')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des Utilisateurs & Véhicules</h1>
                    <p class="mt-2 text-gray-600">Gérez les clients, agences et véhicules de la plateforme</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Clients</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Client::count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Agences Actives</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Agency::where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Véhicules</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Car::count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Clients Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Gestion des Clients</h3>
                            <p class="text-sm text-gray-600">Gérez les comptes clients et leurs réservations</p>
                        </div>
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    
                    <div class="space-y-4">
                        <a href="{{ route('admin.customers.index') }}" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-blue-900">Tous les clients</h4>
                                    <p class="text-sm text-blue-700">Voir et gérer tous les clients</p>
                                </div>
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.bookings.main') }}" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-green-900">Réservations</h4>
                                    <p class="text-sm text-green-700">Gérer toutes les réservations</p>
                                </div>
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.agencies.index') }}" class="block p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-yellow-900">Gestion des agences</h4>
                                    <p class="text-sm text-yellow-700">Voir toutes les agences</p>
                                </div>
                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Vehicles Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Gestion des Véhicules</h3>
                            <p class="text-sm text-gray-600">Gérez la flotte de véhicules de la plateforme</p>
                        </div>
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    
                    <div class="space-y-4">
                        <a href="{{ route('admin.vehicles.index') }}" class="block p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-purple-900">Tous les véhicules</h4>
                                    <p class="text-sm text-purple-700">Voir et gérer tous les véhicules</p>
                                </div>
                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.vehicles.categories') }}" class="block p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-indigo-900">Catégories de véhicules</h4>
                                    <p class="text-sm text-indigo-700">Gérer les catégories et types</p>
                                </div>
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.vehicles.fleet-analytics') }}" class="block p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-pink-900">Analyses de flotte</h4>
                                    <p class="text-sm text-pink-700">Statistiques et performances</p>
                                </div>
                                <svg class="h-5 w-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
