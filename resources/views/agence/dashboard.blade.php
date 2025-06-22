<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Check Agency Status -->
        @if(!auth()->user()->agency)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Aucune agence associée à ce compte
                        </h3>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->agency->status === 'pending')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Votre demande d'inscription est en cours de traitement
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Nous examinerons votre demande dans les plus brefs délais. Vous recevrez une notification par email dès qu'une décision sera prise.</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->agency->status === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Votre demande d'inscription a été rejetée
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ auth()->user()->agency->rejection_reason }}</p>
                        </div>
                        <div class="mt-4">
                            <div class="-mx-2 -my-1.5 flex">
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'update-info')" class="bg-red-50 px-2 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
                                    Mettre à jour les informations
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Approved Agency Dashboard -->
        @if(auth()->user()->agency && auth()->user()->agency->status === 'approved')
            <!-- Welcome Header -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord - {{ auth()->user()->agency->agency_name }}</h1>
                    <p class="mt-2 text-gray-600">Gérez votre flotte de véhicules et vos locations</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Cars -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Véhicules</h2>
                                <p class="text-gray-600">{{ $totalCars ?? 0 }} au total</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('agence.cars.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Gérer les véhicules →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Locations Actives</h2>
                                <p class="text-gray-600">{{ $activeRentals ?? 0 }} en cours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Rentals -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">En Attente</h2>
                                <p class="text-gray-600">{{ $pendingRentals ?? 0 }} à traiter</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('agence.rentals.pending') }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                Voir les demandes →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Revenus Mensuels</h2>
                                <p class="text-gray-600">{{ number_format($monthlyRevenue ?? 0, 2) }}€</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('agence.cars.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Ajouter un Véhicule</h3>
                                <p class="text-sm text-gray-600">Ajouter un nouveau véhicule à votre flotte</p>
                            </div>
                        </a>

                        <a href="{{ route('agence.cars.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Gérer les Véhicules</h3>
                                <p class="text-sm text-gray-600">Voir et modifier vos véhicules</p>
                            </div>
                        </a>

                        <a href="{{ route('agence.rentals.pending') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                            <svg class="h-8 w-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900">Demandes de Location</h3>
                                <p class="text-sm text-gray-600">Traiter les demandes de location</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            @if(isset($recentActivities) && $recentActivities->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Activités Récentes</h2>
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $activity->type }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @else
            <!-- Basic welcome message for non-approved agencies -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-semibold mb-4">Bienvenue dans votre espace agence</h2>
                    <p>Votre compte d'agence est en cours de traitement. Une fois approuvé, vous pourrez accéder à toutes les fonctionnalités de gestion.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Update Information Modal for Rejected Agencies -->
@if(auth()->user()->agency && auth()->user()->agency->status === 'rejected')
    <x-modal name="update-info" focusable>
        <form method="POST" action="{{ route('agence.update') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Mettre à jour les informations') }}
            </h2>

            <!-- Agency Information -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="agency_name" value="Nom de l'Agence" />
                    <x-text-input id="agency_name" name="agency_name" type="text" class="mt-1 block w-full" :value="old('agency_name', auth()->user()->agency->agency_name)" required />
                    <x-input-error :messages="$errors->get('agency_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="commercial_register_doc" value="Registre de Commerce (PDF)" />
                    <input type="file" id="commercial_register_doc" name="commercial_register_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('commercial_register_doc')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="identity_doc" value="Document d'Identité (PDF)" />
                    <input type="file" id="identity_doc" name="identity_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('identity_doc')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tax_doc" value="Document Fiscal (PDF)" />
                    <input type="file" id="tax_doc" name="tax_doc" class="mt-1 block w-full" accept=".pdf" />
                    <x-input-error :messages="$errors->get('tax_doc')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Mettre à jour') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
@endif
</x-app-layout>
