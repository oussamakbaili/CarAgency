@extends('layouts.agence')

@section('title', 'Contact Support')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contact Support</h1>
            <p class="text-gray-600">Contactez directement notre équipe support</p>
        </div>
        <a href="{{ route('agence.support.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            ← Retour au Support
        </a>
    </div>

    <!-- Contact Methods -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Phone Support -->
        <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Support Téléphonique</h3>
                    <p class="text-sm text-gray-600">Disponible 24/7</p>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-gray-700">
                    <span class="font-medium">Maroc :</span> +212 5XX-XXXXXX
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">International :</span> +212 5XX-XXXXXX
                </p>
                <p class="text-sm text-gray-500">
                    Temps de réponse moyen : 2 minutes
                </p>
            </div>
        </div>

        <!-- Email Support -->
        <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Support Email</h3>
                    <p class="text-sm text-gray-600">Réponse garantie</p>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-gray-700">
                    <span class="font-medium">Général :</span> support@rentacar.ma
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Urgent :</span> urgent@rentacar.ma
                </p>
                <p class="text-sm text-gray-500">
                    Temps de réponse : 2-4 heures
                </p>
            </div>
        </div>

        <!-- Live Chat -->
        <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Chat en Direct</h3>
                    <p class="text-sm text-gray-600">Disponible maintenant</p>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-gray-700">
                    <span class="font-medium">Statut :</span> 
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        En ligne
                    </span>
                </p>
                <p class="text-sm text-gray-500">
                    Temps de réponse : Instantané
                </p>
                <button onclick="startLiveChat()" class="w-full mt-3 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    Démarrer le Chat
                </button>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Formulaire de Contact</h2>
            <p class="text-gray-600">Envoyez-nous un message et nous vous répondrons rapidement</p>
        </div>
        <div class="p-6">
            <form action="{{ route('agence.support.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                        <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                    <input type="text" name="subject" id="subject" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                    <select name="priority" id="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low">Faible - Question générale</option>
                        <option value="medium" selected>Moyenne - Problème technique</option>
                        <option value="high">Élevée - Problème urgent</option>
                    </select>
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" id="message" rows="6" required 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Décrivez votre problème ou votre question en détail..."></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Envoyer le Message
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Business Hours -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Heures d'Ouverture du Support</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-3">Support Standard</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Lundi - Vendredi</span>
                            <span>8h00 - 18h00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Samedi</span>
                            <span>9h00 - 16h00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Dimanche</span>
                            <span>Fermé</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-3">Support Urgent</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>24h/24 - 7j/7</span>
                            <span class="text-green-600 font-medium">Disponible</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Pour les problèmes critiques affectant vos opérations
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function startLiveChat() {
    alert('Fonctionnalité de chat en direct bientôt disponible !\n\nEn attendant, utilisez le formulaire de contact ou appelez-nous directement.');
}
</script>
@endsection
