@extends('layouts.client')

@section('header', $car->brand . ' ' . $car->model)

@section('content')
<!-- Car Header -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h1>
                <p class="text-gray-600 mt-1">{{ $car->registration_number }} • {{ $car->year }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('client.cars.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('client.rentals.create', $car) }}" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Louer
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Photos du Véhicule -->
    <div class="lg:col-span-2">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Photos du Véhicule</h3>
                
                @if($car->image)
                    <div class="relative group">
                        <img src="{{ $car->image_url }}" alt="Photo principale" class="w-full h-64 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <button onclick="openImageModal('{{ $car->image_url }}')" class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                                Voir
                            </button>
                        </div>
                    </div>
                @else
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500">Aucune photo disponible</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Car Details -->
    <div class="space-y-6">
        <!-- Informations Générales -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Générales</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Marque:</span>
                        <span class="font-medium text-gray-900">{{ $car->brand }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Modèle:</span>
                        <span class="font-medium text-gray-900">{{ $car->model }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Immatriculation:</span>
                        <span class="font-medium text-gray-900">{{ $car->registration_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Année:</span>
                        <span class="font-medium text-gray-900">{{ $car->year }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Catégorie:</span>
                        <span class="font-medium text-gray-900">{{ $car->category->name ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Couleur:</span>
                        <span class="font-medium text-gray-900">{{ $car->color ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Carburant:</span>
                        <span class="font-medium text-gray-900">{{ $car->fuel_type ?? 'Non défini' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transmission:</span>
                        <span class="font-medium text-gray-900">{{ $car->transmission ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Places:</span>
                        <span class="font-medium text-gray-900">{{ $car->seats ?? 'Non définies' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cylindrée:</span>
                        <span class="font-medium text-gray-900">{{ $car->engine_size ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kilométrage:</span>
                        <span class="font-medium text-gray-900">{{ $car->mileage ? number_format($car->mileage, 0, ',', ' ') . ' km' : 'Non défini' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarification & Statut -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tarification & Statut</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Prix par jour:</span>
                        <span class="font-medium text-orange-600">{{ number_format($car->price_per_day, 0) }} MAD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($car->status === 'available') bg-green-100 text-green-800
                            @elseif($car->status === 'rented') bg-blue-100 text-blue-800
                            @elseif($car->status === 'maintenance') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($car->status === 'available') Disponible
                            @elseif($car->status === 'rented') En location
                            @elseif($car->status === 'maintenance') Maintenance
                            @else {{ ucfirst($car->status) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Équipements -->
        @if($car->features && count($car->features) > 0)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Équipements</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($car->features as $feature)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ $feature }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Description -->
@if($car->description)
    <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
            <p class="text-gray-700 leading-relaxed">{{ $car->description }}</p>
        </div>
    </div>
@endif

<!-- Reviews Section -->
<div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Avis des clients</h3>
        
        <!-- Review Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">
                    {{ number_format($car->getAverageRating(), 1) }}/5
                </div>
                <div class="text-sm text-gray-600">Note moyenne</div>
                <div class="flex justify-center mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($car->getAverageRating()) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">
                    {{ $car->getTotalReviews() }}
                </div>
                <div class="text-sm text-gray-600">Total avis</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">
                    {{ $car->getRatingDistribution()[5] ?? 0 }}
                </div>
                <div class="text-sm text-gray-600">5 étoiles</div>
            </div>
        </div>

        <!-- Review Form (only for clients who have completed rentals) -->
        @php
            $hasCompletedRental = auth()->user()->client && 
                auth()->user()->client->rentals()
                    ->where('car_id', $car->id)
                    ->where('status', 'completed')
                    ->exists();
            $hasExistingReview = auth()->user()->client && 
                \App\Models\Avis::whereHas('rental', function($query) use ($car) {
                    $query->where('car_id', $car->id);
                })
                ->where('client_id', auth()->user()->client->id)
                ->exists();
        @endphp

        @if($hasCompletedRental && !$hasExistingReview)
        <div class="bg-orange-50 rounded-lg p-6 mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Donnez votre avis</h4>
            <form id="reviewForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <div class="flex space-x-1" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-rating w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0" required>
                </div>
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre (optionnel)</label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                    <textarea name="comment" id="comment" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Partagez votre expérience avec ce véhicule..."></textarea>
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                    Publier l'avis
                </button>
            </form>
        </div>
        @endif

        <!-- Reviews List -->
        <div id="reviewsList">
            @forelse($car->getRecentReviews(10) as $review)
            <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold text-orange-600">
                                {{ substr($review->client->user->name ?? 'C', 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-900">{{ $review->client->user->name ?? 'Client anonyme' }}</h5>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="text-sm text-gray-500">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                </div>
                @if($review->title)
                    <h6 class="font-medium text-gray-900 mb-2">{{ $review->title }}</h6>
                @endif
                @if($review->comment)
                    <p class="text-gray-700">{{ $review->comment }}</p>
                @endif
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun avis disponible</h3>
                <p class="mt-1 text-sm text-gray-500">Soyez le premier à donner votre avis sur ce véhicule !</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Image" class="max-w-full max-h-full rounded-lg">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Rating stars functionality
    const stars = document.querySelectorAll('.star-rating');
    const ratingInput = document.getElementById('ratingInput');
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            
            // Update star colors
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('text-yellow-400');
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value);
            stars.forEach((s, i) => {
                if (i < currentRating) {
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });

    // Review form submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const rating = formData.get('rating');
            
            if (rating === '0') {
                alert('Veuillez sélectionner une note');
                return;
            }
            
            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Publication...';
            
            fetch(`{{ route('client.cars.reviews.store', $car) }}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide the form
                    this.parentElement.style.display = 'none';
                    
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'bg-green-50 rounded-lg p-4 mb-6';
                    successDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-green-800">Votre avis a été publié avec succès !</p>
                        </div>
                    `;
                    this.parentElement.parentElement.insertBefore(successDiv, this.parentElement);
                    
                    // Reload the page to show the new review
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert(data.error || 'Une erreur est survenue');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Publier l\'avis';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Publier l\'avis';
            });
        });
    }
});
</script>
@endsection 