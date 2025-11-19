@extends('layouts.public')

@section('title', 'Wishlists - ToubCar')

@push('styles')
<style>
    .wishlist-nav-active {
        color: #ec4899;
    }
    .wishlist-nav-active svg {
        color: #ec4899;
    }
    /* Hide footer on wishlists page */
    footer {
        display: none;
    }

    #wishlist-intro-modal {
        backdrop-filter: blur(10px);
    }

    #wishlist-intro-modal .modal-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 25px 45px rgba(15, 59, 99, 0.15);
    }

    #wishlist-intro-modal .modal-illustration {
        background: linear-gradient(135deg, #f97316 0%, #fb923c 50%, #f97316 100%);
        box-shadow: 0 15px 30px rgba(249, 115, 22, 0.25);
    }

    body.modal-open {
        overflow: hidden;
    }

    .animate-modal-in {
        animation: modalFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .animate-illustration {
        animation: illustrationFloat 1.2s ease-in-out;
    }

    @keyframes modalFadeIn {
        0% {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        60% {
            opacity: 1;
            transform: translateY(-6px) scale(1.02);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes illustrationFloat {
        0% {
            transform: translateY(30px) scale(0.85);
            opacity: 0;
        }
        100% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    /* Create Wishlist Modal Styles */
    #create-wishlist-modal {
        backdrop-filter: blur(8px);
    }

    .wishlist-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }

    .wishlist-card-grid .wishlist-image {
        aspect-ratio: 1;
    }

    .wishlist-card-grid .wishlist-image:nth-child(1) {
        grid-column: 1 / 2;
        grid-row: 1 / 2;
    }

    .wishlist-card-grid .wishlist-image:nth-child(2) {
        grid-column: 2 / 3;
        grid-row: 1 / 2;
    }

    .wishlist-card-grid .wishlist-image:nth-child(3) {
        grid-column: 1 / 2;
        grid-row: 2 / 3;
    }

    .wishlist-card-grid .wishlist-image:nth-child(4) {
        grid-column: 2 / 3;
        grid-row: 2 / 3;
    }

    @media (min-width: 640px) {
        .wishlist-card-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .wishlist-card-grid .wishlist-image:nth-child(1) {
            grid-column: 1 / 2;
            grid-row: 1 / 2;
        }

        .wishlist-card-grid .wishlist-image:nth-child(2) {
            grid-column: 2 / 3;
            grid-row: 1 / 2;
        }

        .wishlist-card-grid .wishlist-image:nth-child(3) {
            grid-column: 3 / 4;
            grid-row: 1 / 2;
        }

        .wishlist-card-grid .wishlist-image:nth-child(4) {
            grid-column: 4 / 5;
            grid-row: 1 / 2;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white pb-20 md:pb-0">
    <!-- Header -->
    <div class="text-center py-6 sm:py-8 md:py-10 reveal-section">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('wishlists.page_title') }}</h1>
    </div>

    <!-- Intro Modal (First Time Mobile Users) -->
    <div id="wishlist-intro-modal" class="hidden md:hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-gray-900/50"></div>
        <div class="relative flex items-center justify-center min-h-screen px-6">
            <div class="modal-card relative w-full max-w-sm rounded-[28px] overflow-hidden animate-modal-in">
                <button id="wishlist-intro-close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition" aria-label="Close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="px-7 pt-8 pb-7 text-center space-y-6">
                    <div class="modal-illustration mx-auto w-28 h-28 rounded-3xl flex items-center justify-center animate-illustration">
                        <div class="bg-white/95 w-24 h-24 rounded-2xl flex items-center justify-center shadow-inner">
                            <svg class="w-10 h-10 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.1 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4 18 4 20 6 20 8.5c0 3.78-3.4 6.86-8.55 11.54l-.35.31z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold text-gray-900">{{ __('wishlists.intro.title') }}</h2>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ __('wishlists.intro.subtitle') }}</p>
                    </div>
                    <button id="wishlist-intro-confirm" class="w-full bg-[#0F3B63] hover:bg-[#0d3456] text-white py-3.5 rounded-2xl font-semibold text-sm transition">{{ __('wishlists.intro.button') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Wishlist Modal -->
    <div id="create-wishlist-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-gray-900/50" onclick="closeCreateWishlistModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md animate-modal-in">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('wishlists.modal.title') }}</h2>
                    <button onclick="closeCreateWishlistModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Existing Wishlists List -->
                <div id="existing-wishlists" class="mb-6 max-h-64 overflow-y-auto space-y-2">
                    <!-- Wishlists will be loaded here -->
                </div>

                <!-- Create New Wishlist Button -->
                <button id="show-create-form" onclick="showCreateForm()" class="w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-orange-500 hover:bg-orange-50 transition-colors mb-4">
                    <span class="text-gray-700 font-medium">{{ __('wishlists.modal.create_new') }}</span>
                </button>

                <!-- Create Form (Hidden by default) -->
                <div id="create-wishlist-form" class="hidden mb-4">
                    <input type="text" id="wishlist-name" placeholder="{{ __('wishlists.actions.name_placeholder') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                    <div class="flex gap-2 mt-3">
                        <button id="save-wishlist-btn" onclick="createWishlist(event)" class="flex-1 bg-[#0F3B63] hover:bg-[#0d3456] text-white py-2.5 rounded-lg font-medium transition">
                            {{ __('wishlists.actions.save') }}
                        </button>
                        <button onclick="hideCreateForm()" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            {{ __('common.cancel') }}
                        </button>
                    </div>
                </div>

                <!-- Done Button -->
                <button onclick="closeCreateWishlistModal()" class="w-full bg-[#0F3B63] hover:bg-[#0d3456] text-white py-3 rounded-lg font-semibold transition">
                    {{ __('wishlists.modal.done') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 reveal-section">
        @guest
            <!-- Not Logged In State -->
            <div class="text-center py-10 sm:py-12 md:py-16">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">
                    {{ __('wishlists.guest.title') }}
                </h2>
                <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                    {{ __('wishlists.guest.subtitle') }}
                </p>
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-colors shadow-lg">
                    {{ __('wishlists.guest.button') }}
                </a>
            </div>
        @else
            @if(auth()->user()->isClient())
                <!-- Create Wishlist Button (Floating) -->
                <div class="fixed bottom-24 md:bottom-8 right-4 md:right-8 z-40">
                    <button onclick="openCreateWishlistModal()" class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>

                <!-- Logged In - Show Wishlists -->
                @if($wishlists->count() > 0)
                    <div class="space-y-6 md:space-y-8">
                        @foreach($wishlists as $wishlist)
                            <div onclick="window.location='{{ route('client.wishlists.show', $wishlist) }}'"
                                 class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                <!-- Wishlist Header -->
                                <div class="p-4 sm:p-6 border-b border-gray-100">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-1">{{ $wishlist->name }}</h3>
                                            @if($wishlist->description)
                                                <p class="text-sm text-gray-600 mb-2">{{ $wishlist->description }}</p>
                                            @endif
                                            <p class="text-xs sm:text-sm text-gray-500">
                                                @php
                                                    $itemsCount = $wishlist->items_count ?? $wishlist->items->count() ?? 0;
                                                @endphp
                                                {{ trans_choice('wishlists.items.count', $itemsCount, ['count' => $itemsCount]) }}
                                                @if($wishlist->updated_at)
                                                    • {{ $wishlist->updated_at->diffForHumans() }}
                                                @endif
                                            </p>
                                        </div>
                                        <button onclick="event.stopPropagation(); deleteWishlist({{ $wishlist->id }})" 
                                                class="text-gray-400 hover:text-red-600 transition-colors ml-4">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Wishlist Images Grid -->
                                @if($wishlist->items->count() > 0)
                                    <div class="p-4 sm:p-6">
                                        <div class="wishlist-card-grid">
                                            @foreach($wishlist->items->take(4) as $item)
                                                @if($item->car && $item->car->agency)
                                                    <div onclick="window.location='{{ route('public.car.show', [$item->car->agency, $item->car]) }}'" 
                                                         class="wishlist-image cursor-pointer group relative overflow-hidden rounded-lg bg-gray-100">
                                                        @if($item->car->image_url)
                                                            <img src="{{ $item->car->image_url }}" 
                                                                 alt="{{ $item->car->brand }} {{ $item->car->model }}" 
                                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center">
                                                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        @php
                                            $itemsCount = $wishlist->items_count ?? $wishlist->items->count() ?? 0;
                                        @endphp
                                        @if($itemsCount > 4)
                                            <div class="mt-4 text-center">
                                                <a href="{{ route('public.wishlists') }}?wishlist={{ $wishlist->id }}" 
                                                   class="inline-block text-sm text-orange-600 hover:text-orange-700 font-medium">
                                                    {{ __('wishlists.items.view_all', ['count' => $itemsCount]) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="p-8 sm:p-12 text-center text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <p class="text-sm">{{ __('wishlists.items.empty') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- No Wishlists Yet -->
                    <div class="text-center py-10 sm:py-12 md:py-16">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">
                            {{ __('wishlists.empty_state.title') }}
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                            {{ __('wishlists.empty_state.subtitle') }}
                        </p>
                        <button onclick="openCreateWishlistModal()" class="inline-flex items-center gap-1.5 sm:gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-colors shadow-lg">
                            {{ __('wishlists.actions.create') }}
                        </button>
                    </div>
                @endif
            @else
                <!-- Not a Client -->
                <div class="text-center py-10 sm:py-12 md:py-16">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">
                        {{ __('wishlists.clients_only.title') }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                        {{ __('wishlists.clients_only.subtitle') }}
                    </p>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-1.5 sm:gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-colors shadow-lg">
                        {{ __('wishlists.clients_only.button') }}
                    </a>
                </div>
            @endif
        @endguest
    </div>
    @include('components.mobile-bottom-nav')
</div>

@auth
@if(auth()->user()->isClient())
<script>
    function deleteWishlist(wishlistId) {
        if (!confirm("{{ __('wishlists.actions.delete_confirm') }}")) {
            return;
        }
        
        fetch(`{{ url('client/wishlists') }}/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    function openCreateWishlistModal() {
        const modal = document.getElementById('create-wishlist-modal');
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        loadWishlists();
    }

    function closeCreateWishlistModal() {
        const modal = document.getElementById('create-wishlist-modal');
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
        hideCreateForm();
    }

    function showCreateForm() {
        document.getElementById('create-wishlist-form').classList.remove('hidden');
        document.getElementById('show-create-form').classList.add('hidden');
    }

    function hideCreateForm() {
        document.getElementById('create-wishlist-form').classList.add('hidden');
        document.getElementById('show-create-form').classList.remove('hidden');
        document.getElementById('wishlist-name').value = '';
    }

    function loadWishlists() {
        fetch('{{ url('client/wishlists') }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('existing-wishlists');
            if (data.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">{{ __('wishlists.empty_state.title') }}</p>';
            } else {
                container.innerHTML = data.map(wishlist => `
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectWishlist(${wishlist.id})">
                        <span class="text-sm font-medium text-gray-900">${wishlist.name}</span>
                        <span class="text-xs text-gray-500">${wishlist.items_count || 0} {{ __('wishlists.items.count', ['count' => 0]) }}</span>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error loading wishlists:', error);
        });
    }

    function selectWishlist(wishlistId) {
        // This will be used when adding a car to a wishlist
        closeCreateWishlistModal();
    }

    function createWishlist(event) {
        if (event) {
            event.preventDefault();
        }
        
        const name = document.getElementById('wishlist-name').value.trim();
        if (!name) {
            alert('{{ __('wishlists.actions.name_placeholder') }}');
            return;
        }

        // Show loading state
        const saveButton = document.getElementById('save-wishlist-btn');
        const originalText = saveButton ? saveButton.textContent : '{{ __('wishlists.actions.save') }}';
        if (saveButton) {
            saveButton.disabled = true;
            saveButton.textContent = '{{ __('common.loading') }}...';
        }

        fetch('{{ route('client.wishlists.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                name: name,
                description: null,
                is_public: false
            })
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            let errorData = null;
            
            if (!response.ok) {
                if (contentType && contentType.includes('application/json')) {
                    try {
                        errorData = await response.json();
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                }
                
                // Gérer les erreurs spécifiques
                if (response.status === 401) {
                    throw new Error('Vous devez être connecté pour créer une wishlist.');
                } else if (response.status === 403) {
                    throw new Error('Vous n\'avez pas la permission de créer une wishlist.');
                } else if (response.status === 422 && errorData && errorData.errors) {
                    // Erreur de validation
                    const firstError = Object.values(errorData.errors)[0];
                    throw new Error(Array.isArray(firstError) ? firstError[0] : firstError);
                } else {
                    throw new Error(errorData?.message || errorData?.error || 'Erreur lors de la création de la wishlist. Veuillez réessayer.');
                }
            }
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            throw new Error('Réponse invalide du serveur');
        })
        .then(data => {
            if (data && (data.id || data.name)) {
                // Success - reload page
                location.reload();
            } else {
                throw new Error('Réponse invalide du serveur');
            }
        })
        .catch(error => {
            console.error('Error creating wishlist:', error);
            alert(error.message || 'Une erreur est survenue. Veuillez réessayer.');
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.textContent = originalText;
            }
        });
    }
</script>
@endif
@endauth

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('wishlist-intro-modal');
            if (!modal) return;

            const isMobile = window.matchMedia('(max-width: 768px)').matches;

            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }

            const closeBtn = document.getElementById('wishlist-intro-close');
            const confirmBtn = document.getElementById('wishlist-intro-confirm');

            if (isMobile) {
                setTimeout(() => {
                    modal.classList.remove('hidden');
                    document.body.classList.add('modal-open');
                }, 200);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeModal);
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', closeModal);
            }

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
@endpush

@endsection
