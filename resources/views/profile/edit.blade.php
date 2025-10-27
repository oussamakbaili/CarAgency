@extends(
    auth()->user()->role === 'agence' ? 'layouts.agence' : (
        auth()->user()->role === 'admin' ? 'layouts.admin' : (
            auth()->user()->role === 'client' ? 'layouts.client' : 'layouts.app'
        )
    )
)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Profil Utilisateur</h1>
                        <p class="mt-2 text-gray-600">Gérez vos informations de profil et paramètres de sécurité</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Rôle</p>
                            <p class="text-lg font-semibold text-blue-600 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Informations du Profil</h2>
                    <p class="text-gray-600">Mettez à jour les informations de votre compte et votre adresse e-mail.</p>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Password Update Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Mise à Jour du Mot de Passe</h2>
                    <p class="text-gray-600">Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.</p>
                </div>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Suppression du Compte</h2>
                    <p class="text-gray-600">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées.</p>
                </div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
