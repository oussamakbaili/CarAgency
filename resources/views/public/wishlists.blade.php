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
            <div class="modal-card relative w-full max-w-sm rounded-[28px] overflow-hidden">
                <button id="wishlist-intro-close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition" aria-label="Close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="px-7 pt-8 pb-7 text-center space-y-6">
                    <div class="modal-illustration mx-auto w-28 h-28 rounded-3xl flex items-center justify-center">
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

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 md:pb-8 reveal-section">
        @guest
            <!-- Not Logged In State -->
            <div class="text-center py-10 sm:py-12 md:py-16">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">
                    Log in to view your wishlists
                </h2>
                <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                    You can create, view, or edit wishlists once you've logged in.
                </p>
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-1.5 sm:gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-colors shadow-lg">
                    Log in
                </a>
            </div>
        @else
            @if(auth()->user()->isClient())
                <!-- Logged In - Show Wishlists -->
                @if($wishlists->count() > 0)
                    <div class="space-y-4 sm:space-y-6">
                        @foreach($wishlists as $wishlist)
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-4 sm:p-6">
                                    <div class="flex items-start justify-between mb-3 sm:mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-1">{{ $wishlist->name }}</h3>
                                            @if($wishlist->description)
                                                <p class="text-xs sm:text-sm text-gray-600">{{ $wishlist->description }}</p>
                                            @endif
                                            <p class="text-xs sm:text-sm text-gray-500 mt-2">{{ $wishlist->items_count }} {{ $wishlist->items_count == 1 ? 'car' : 'cars' }}</p>
                                        </div>
                                        <button onclick="deleteWishlist({{ $wishlist->id }})" 
                                                class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    @if($wishlist->items->count() > 0)
                                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 mt-3 sm:mt-4">
                                            @foreach($wishlist->items->take(8) as $item)
                                                @if($item->car)
                                                    <div onclick="window.location='{{ route('public.car.show', [$item->car->agency, $item->car]) }}'" 
                                                         class="group cursor-pointer">
                                                        <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                                            @if($item->car->image_url)
                                                                <img src="{{ $item->car->image_url }}" 
                                                                     alt="{{ $item->car->brand }} {{ $item->car->model }}" 
                                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                            @else
                                                                <div class="w-full h-full flex items-center justify-center">
                                                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs sm:text-sm font-medium text-gray-900 mt-2 truncate">
                                                            {{ $item->car->brand }} {{ $item->car->model }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ number_format($item->car->client_price_per_day, 0) }} MAD/jour
                                                        </p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        
                                        @if($wishlist->items_count > 8)
                                            <a href="{{ route('public.wishlists') }}?wishlist={{ $wishlist->id }}" 
                                               class="inline-block mt-4 text-sm text-orange-600 hover:text-orange-700 font-medium">
                                                View all {{ $wishlist->items_count }} cars →
                                            </a>
                                        @endif
                                    @else
                                        <div class="text-center py-8 text-gray-500">
                                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            <p class="text-sm">This wishlist is empty</p>
                                        </div>
                                    @endif
                                </div>
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
                            No wishlists yet
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                            Start saving your favorite cars by clicking the heart icon on any car listing.
                        </p>
                        <a href="{{ route('public.home') }}" 
                           class="inline-flex items-center gap-1.5 sm:gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-colors shadow-lg">
                            Explore Cars
                        </a>
                    </div>
                @endif
            @else
                <!-- Not a Client -->
                <div class="text-center py-10 sm:py-12 md:py-16">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">
                        Wishlists are for clients only
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                        Please log in with a client account to use wishlists.
                    </p>
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-1.5 sm:gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-colors shadow-lg">
                        Log in
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
        if (!confirm('Are you sure you want to delete this wishlist?')) {
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
</script>
@endif
@endauth

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('wishlist-intro-modal');
            if (!modal) return;

            const storageKey = 'toubcar_wishlist_intro_shown_v1';
            const isMobile = window.matchMedia('(max-width: 768px)').matches;

            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('modal-open');
                localStorage.setItem(storageKey, '1');
            }

            const closeBtn = document.getElementById('wishlist-intro-close');
            const confirmBtn = document.getElementById('wishlist-intro-confirm');

            if (isMobile && !localStorage.getItem(storageKey)) {
                modal.classList.remove('hidden');
                document.body.classList.add('modal-open');
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

