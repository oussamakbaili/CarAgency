<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        
                        <h2 class="mt-4 text-2xl font-bold">{{ __('Compte en attente d\'approbation') }}</h2>
                        
                        <p class="mt-4 text-gray-600">
                            {{ __('Votre compte agence est actuellement en attente d\'approbation par notre équipe administrative.') }}
                        </p>
                        
                        <p class="mt-2 text-gray-600">
                            {{ __('Vous recevrez un email dès que votre compte sera approuvé.') }}
                        </p>
                        
                        <div class="mt-8 space-y-4">
                            <p class="text-sm text-gray-500">
                                {{ __('Pour toute question, n\'hésitez pas à nous contacter :') }}
                            </p>
                            <a href="mailto:support@example.com" class="text-indigo-600 hover:text-indigo-900">
                                support@example.com
                            </a>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="mt-8">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Se déconnecter') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 