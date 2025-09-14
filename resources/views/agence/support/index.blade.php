@extends('layouts.agence')

@section('title', 'Support & Aide')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Support & Aide</h1>
            <p class="text-gray-600">Obtenez de l'aide et contactez notre équipe support</p>
        </div>
    </div>

    <!-- Quick Help Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">FAQ</h3>
            </div>
            <p class="text-gray-600 mb-4">Trouvez des réponses aux questions les plus fréquentes</p>
            <button onclick="showFAQ()" class="text-blue-600 hover:text-blue-800 font-medium">
                Consulter la FAQ →
            </button>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Nouveau Ticket</h3>
            </div>
            <p class="text-gray-600 mb-4">Créez un ticket de support pour obtenir de l'aide</p>
            <button onclick="showNewTicketModal()" class="text-green-600 hover:text-green-800 font-medium">
                Créer un ticket →
            </button>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow cursor-pointer">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">Chat en Direct</h3>
            </div>
            <p class="text-gray-600 mb-4">Discutez en temps réel avec notre équipe</p>
            <button onclick="startChat()" class="text-purple-600 hover:text-purple-800 font-medium">
                Démarrer le chat →
            </button>
        </div>
    </div>

    <!-- Support Tickets -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Mes Tickets de Support</h2>
                <button onclick="showNewTicketModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Nouveau Ticket
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets ?? [] as $ticket)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $ticket->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $ticket->subject }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->status == 'open')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Ouvert
                                    </span>
                                @elseif($ticket->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Fermé
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->priority == 'high')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Élevée
                                    </span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Moyenne
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Faible
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $ticket->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewTicket({{ $ticket->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    Voir
                                </button>
                                <button onclick="replyTicket({{ $ticket->id }})" class="text-green-600 hover:text-green-900">
                                    Répondre
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Aucun ticket de support trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Questions Fréquentes</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleFAQ('faq1')" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-medium text-gray-900">Comment ajouter un nouveau véhicule à ma flotte ?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="faq1-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq1-content" class="hidden px-6 pb-4 text-gray-600">
                        <p>Pour ajouter un nouveau véhicule, allez dans "Gestion de la Flotte" > "Ajouter un véhicule" et remplissez le formulaire avec les informations du véhicule. N'oubliez pas d'ajouter des photos et de définir le prix de location.</p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleFAQ('faq2')" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-medium text-gray-900">Comment gérer les réservations de mes clients ?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="faq2-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq2-content" class="hidden px-6 pb-4 text-gray-600">
                        <p>Dans la section "Gestion des Réservations", vous pouvez voir toutes les demandes de réservation, les approuver, les rejeter, ou les modifier. Vous pouvez également suivre l'état des réservations actives.</p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleFAQ('faq3')" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-medium text-gray-900">Comment configurer les tarifs de mes véhicules ?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="faq3-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq3-content" class="hidden px-6 pb-4 text-gray-600">
                        <p>Utilisez la section "Tarification & Disponibilité" pour définir les prix de base, configurer la tarification dynamique, créer des tarifs saisonniers et des offres promotionnelles.</p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg">
                    <button onclick="toggleFAQ('faq4')" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                        <span class="font-medium text-gray-900">Comment consulter mes rapports de performance ?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="faq4-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq4-content" class="hidden px-6 pb-4 text-gray-600">
                        <p>Dans "Rapports & Analytiques", vous trouverez des analyses détaillées sur vos revenus, la performance de votre flotte, vos clients, et bien plus encore. Vous pouvez également exporter ces rapports en CSV ou PDF.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Ticket Modal -->
<div id="newTicketModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nouveau Ticket de Support</h3>
                <button onclick="closeNewTicketModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('agence.support.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                        <input type="text" name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="low">Faible</option>
                            <option value="medium">Moyenne</option>
                            <option value="high">Élevée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeNewTicketModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showFAQ() {
    document.querySelector('.bg-white.rounded-lg.shadow-sm.border:last-child').scrollIntoView({ behavior: 'smooth' });
}

function showNewTicketModal() {
    document.getElementById('newTicketModal').classList.remove('hidden');
}

function closeNewTicketModal() {
    document.getElementById('newTicketModal').classList.add('hidden');
}

function startChat() {
    alert('Fonctionnalité de chat en direct bientôt disponible !');
}

function viewTicket(ticketId) {
    window.location.href = `/agence/support/tickets/${ticketId}`;
}

function replyTicket(ticketId) {
    window.location.href = `/agence/support/tickets/${ticketId}/reply`;
}

function toggleFAQ(faqId) {
    const content = document.getElementById(faqId + '-content');
    const icon = document.getElementById(faqId + '-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Close modal when clicking outside
document.getElementById('newTicketModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNewTicketModal();
    }
});
</script>
@endsection
