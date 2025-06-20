@extends('layouts.admin')

@section('header')
    Ajouter une nouvelle agence
@endsection

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.agencies.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Agency Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Informations de l'agence</h3>

                        <div>
                            <label for="agency_name" class="block text-sm font-medium text-gray-700">
                                Nom de l'agence
                            </label>
                            <div class="mt-1">
                                <input type="text" name="agency_name" id="agency_name" value="{{ old('agency_name') }}"
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
                                <input type="text" name="responsable_name" id="responsable_name" value="{{ old('responsable_name') }}"
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
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
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
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Mot de passe
                            </label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 @enderror"
                                    required>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmer le mot de passe
                            </label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    required>
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
                            Créer l'agence
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

