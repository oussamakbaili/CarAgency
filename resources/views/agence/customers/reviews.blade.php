@extends('layouts.agence')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Avis des Clients</h1>
        <p class="text-gray-600">Gérez les avis et évaluations de vos clients</p>
    </div>

    <!-- Reviews Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Note Moyenne</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['average_rating'] ?? 0 }}/5</p>
                    <div class="flex items-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($stats['average_rating'] ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Avis</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_reviews'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">5 Étoiles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['five_star_reviews'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">4+ Étoiles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ ($stats['five_star_reviews'] ?? 0) + ($stats['four_star_reviews'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('agence.customers.reviews') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, commentaire, nom du client..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                <select name="rating" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="">Toutes les notes</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 étoiles</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 étoile</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tri</label>
                <select name="sort" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                    <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Note élevée</option>
                    <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Note faible</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Filtrer
                </button>
                <a href="{{ route('agence.customers.reviews') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 text-sm">
                    Effacer
                </a>
            </div>
        </form>
    </div>

    <!-- Reviews List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Avis des Clients</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($reviews as $review)
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">
                                {{ strtoupper(substr($review->client->user->name ?? 'U', 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $review->client->user->name ?? 'Client anonyme' }}</h4>
                                @if($review->title)
                                    <p class="text-sm font-medium text-gray-700 mt-1">{{ $review->title }}</p>
                                @endif
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700">{{ $review->comment ?? 'Aucun commentaire' }}</p>
                        </div>
                        @if($review->rental && $review->rental->car)
                        <div class="mt-3 flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ $review->rental->car->brand }} {{ $review->rental->car->model }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <button onclick="showReplyModal({{ $review->id }})" class="text-sm text-blue-600 hover:text-blue-900">Répondre</button>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun avis disponible</h3>
                <p class="mt-1 text-sm text-gray-500">Les avis de vos clients apparaîtront ici.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Répondre à l'avis</h3>
                <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="reviewInfo" class="mb-4 p-3 bg-gray-50 rounded-lg">
                <!-- Review info will be loaded here -->
            </div>
            
            <form id="replyForm">
                @csrf
                <input type="hidden" id="reviewId" name="review_id">
                
                <div class="mb-4">
                    <label for="replyMessage" class="block text-sm font-medium text-gray-700 mb-2">Votre réponse</label>
                    <textarea id="replyMessage" name="message" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tapez votre réponse ici..." required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReplyModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        Envoyer la réponse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showReplyModal(reviewId) {
    // Get review data
    fetch(`/agence/customers/reviews/${reviewId}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('reviewId').value = reviewId;
            document.getElementById('reviewInfo').innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-xs font-medium text-blue-600">
                                ${data.client_name ? data.client_name.substring(0, 2).toUpperCase() : 'U'}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-medium text-gray-900">${data.client_name || 'Client anonyme'}</h4>
                        ${data.title ? `<p class="text-sm font-medium text-gray-700 mt-1">${data.title}</p>` : ''}
                        <div class="flex items-center mt-1">
                            ${generateStars(data.rating)}
                            <span class="ml-2 text-sm text-gray-500">${data.rating}/5</span>
                        </div>
                        <p class="text-sm text-gray-700 mt-2">${data.comment || 'Aucun commentaire'}</p>
                    </div>
                </div>
            `;
            document.getElementById('replyModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading review data:', error);
            alert('Erreur lors du chargement des données de l\'avis');
        });
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    document.getElementById('replyForm').reset();
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        const color = i <= rating ? 'text-yellow-400' : 'text-gray-300';
        stars += `<svg class="w-4 h-4 ${color}" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>`;
    }
    return stars;
}

// Handle form submission
document.getElementById('replyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const reviewId = formData.get('review_id');
    const message = formData.get('message');
    
    // Send reply
    fetch(`/agence/customers/reviews/${reviewId}/reply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Réponse envoyée avec succès!');
            closeReplyModal();
            // Optionally refresh the page or update the UI
            location.reload();
        } else {
            alert('Erreur lors de l\'envoi de la réponse: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error sending reply:', error);
        alert('Erreur lors de l\'envoi de la réponse');
    });
});
</script>
@endsection