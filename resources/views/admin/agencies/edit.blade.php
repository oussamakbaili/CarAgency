@extends('layouts.admin')

@section('header')
    Modifier l'agence {{ $agency->agency_name }}
@endsection

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.agencies.update', $agency) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Agency Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Informations de l'agence</h3>

                        <div>
                            <label for="agency_name" class="block text-sm font-medium text-gray-700">
                                Nom de l'agence
                            </label>
                            <div class="mt-1">
                                <input type="text" name="agency_name" id="agency_name" 
                                    value="{{ old('agency_name', $agency->agency_name) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('agency_name') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    required>
                                @error('agency_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="responsable_name" class="block text-sm font-medium text-gray-700">
                                Nom du responsable
                            </label>
                            <div class="mt-1">
                                <input type="text" name="responsable_name" id="responsable_name" 
                                    value="{{ old('responsable_name', $agency->responsable_name) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('responsable_name') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    required>
                                @error('responsable_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Téléphone
                            </label>
                            <div class="mt-1">
                                <input type="tel" name="phone" id="phone" 
                                    value="{{ old('phone', $agency->phone) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    required>
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Informations du compte</h3>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" 
                                    value="{{ old('email', $agency->email) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-500">
                                        Pour changer le mot de passe, demandez à l'agence d'utiliser la fonction "Mot de passe oublié" sur la page de connexion.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Statut
                            </label>
                            <div class="mt-1">
                                <select name="status" id="status" 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="approved" {{ $agency->status === 'approved' ? 'selected' : '' }}>Approuvée</option>
                                    <option value="pending" {{ $agency->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="rejected" {{ $agency->status === 'rejected' ? 'selected' : '' }}>Rejetée</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agency Statistics -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques de l'agence</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total des voitures
                                </dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                    {{ $agency->cars->count() }}
                                </dd>
                            </div>
                        </div>

                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Date d'inscription
                                </dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $agency->created_at->format('d/m/Y') }}
                                </dd>
                            </div>
                        </div>

                        <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Dernière modification
                                </dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $agency->updated_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.agencies.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

