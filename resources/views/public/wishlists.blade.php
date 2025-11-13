@extends('layouts.public')

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

    .wishlist-collage {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        grid-template-rows: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
    }

    .wishlist-card-shadow {
        box-shadow: 0 18px 45px rgba(15, 59, 99, 0.12);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white via-gray-50 to-gray-100 pb-24 md:pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="pt-10 pb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">{{ __('wishlists.page_title') }}</h1>
                @auth
                    @if(auth()->user()->isClient())
                        <p class="text-sm sm:text-base text-gray-500 mt-1">
                            {{ __('wishlists.cards.create_description') }}
                        </p>
                    @endif
                @endauth
            </div>
            @auth
                @if(auth()->user()->isClient())
                    <button onclick="openPageWishlistModal()"
                            class="inline-flex items-center gap-2 bg-[#0F3B63] hover:bg-[#0d3456] text-white px-4 py-2.5 rounded-xl shadow-lg shadow-blue-900/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="font-semibold text-sm">{{ __('wishlists.cards.cta_create') }}</span>
                    </button>
                @endif
            @endauth
        </div>

        @guest
            <div class="mt-16 bg-white rounded-3xl shadow-xl shadow-blue-900/10 px-8 py-12 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('wishlists.guest.title') }}</h2>
                <p class="text-gray-500 text-sm sm:text-base max-w-md mx-auto mb-8">{{ __('wishlists.guest.subtitle') }}</p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-[#0F3B63] hover:bg-[#0d3456] text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-blue-900/15 transition">
                    {{ __('wishlists.guest.button') }}
                </a>
            </div>
        @else
            @if(auth()->check() && auth()->user()->isClient())
                @if(isset($wishlists) && $wishlists->isEmpty())
                    <div class="mt-16 bg-white rounded-3xl shadow-xl shadow-blue-900/10 px-8 py-12 text-center">
                        <div class="w-20 h-20 rounded-3xl bg-orange-50 border border-orange-200 mx-auto flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('wishlists.empty_state.title') }}</h2>
                        <p class="text-gray-500 text-sm sm:text-base max-w-md mx-auto mb-8">{{ __('wishlists.empty_state.subtitle') }}</p>
                        <div class="flex flex-col sm:flex-row sm:justify-center gap-3">
                            <button onclick="openPageWishlistModal()" class="inline-flex items-center gap-2 bg-[#0F3B63] hover:bg-[#0d3456] text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-blue-900/15 transition">{{ __('wishlists.cards.cta_create') }}</button>
                            <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">{{ __('wishlists.empty_state.button') }}</a>
                        </div>
                    </div>
                @elseif(isset($wishlists) && $wishlists->isNotEmpty())
                    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <button onclick="openPageWishlistModal()" class="relative group overflow-hidden rounded-3xl border-2 border-dashed border-gray-300 bg-white px-6 py-8 flex flex-col items-center justify-center text-center shadow-lg shadow-blue-900/5 hover:border-orange-400 hover:bg-orange-50/30 transition">
                            <div class="w-16 h-16 rounded-2xl bg-orange-100 border border-orange-200 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('wishlists.cards.create_title') }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ __('wishlists.cards.create_description') }}</p>
                        </button>

                        @foreach($wishlists as $wishlist)
                            @php
                                $previewItems = $wishlist->items ? $wishlist->items->take(4) : collect([]);
                                $previewCount = $previewItems->count();
                                $updatedTime = optional($wishlist->updated_at)->diffForHumans();
                            @endphp
                            <div class="wishlist-card-shadow bg-white rounded-3xl overflow-hidden border border-gray-100 transition hover:-translate-y-1 hover:shadow-2xl">
                                <div class="p-5 pb-4">
                                    <div class="wishlist-collage rounded-2xl overflow-hidden mb-4">
                                        @foreach($previewItems as $item)
                                            @php
                                                $car = $item->car ?? null;
                                                $imagePath = $car && $car->image ? asset('storage/'.$car->image) : asset('images/black-sedan-car-driving-bridge-road.png');
                                                $carBrand = $car ? ($car->brand ?? 'Car') : 'Car';
                                            @endphp
                                            <div class="relative aspect-square overflow-hidden">
                                                <img src="{{ $imagePath }}" alt="{{ $carBrand }}"
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            </div>
                                        @endforeach
                                        @for($i = $previewCount; $i < 4; $i++)
                                            <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                                </svg>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $wishlist->name }}</h3>
                                            <p class="text-xs text-gray-500 mt-1">{{ __('wishlists.items.saved', ['count' => $wishlist->items_count ?? 0]) }}</p>
                                            @if($updatedTime)
                                                <p class="text-xs text-gray-400 mt-1">{{ __('wishlists.cards.last_updated', ['time' => $updatedTime]) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('public.wishlists') }}"
                                               class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:text-orange-500 hover:border-orange-300 transition"
                                               title="{{ __('wishlists.items.view_all', ['count' => $wishlist->items_count]) }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <button onclick="deleteWishlist({{ $wishlist->id }})"
                                                    class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-300 transition"
                                                    title="Delete wishlist">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="mt-16 bg-white rounded-3xl shadow-xl shadow-blue-900/10 px-8 py-12 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('wishlists.clients_only.title') }}</h2>
                    <p class="text-gray-500 text-sm sm:text-base max-w-md mx-auto mb-8">{{ __('wishlists.clients_only.subtitle') }}</p>
                    <a href="{{ route('logout') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition">{{ __('wishlists.clients_only.button') }}</a>
                </div>
            @endif
        @endguest
    </div>
</div>

@include('components.mobile-bottom-nav')

<!-- Intro Modal -->
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

<!-- Page Create Wishlist Modal -->
<div id="page-wishlist-modal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closePageWishlistModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-5">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('wishlists.cards.create_title') }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ __('wishlists.cards.create_description') }}</p>
                </div>
                <button onclick="closePageWishlistModal()" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label for="page-wishlist-name" class="text-sm font-medium text-gray-700">{{ __('wishlists.modal.create_placeholder') }}</label>
                    <input type="text" id="page-wishlist-name" class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="{{ __('wishlists.modal.create_placeholder') }}">
                </div>
                <div class="flex gap-3">
                    <button onclick="submitPageWishlist()" class="flex-1 bg-[#0F3B63] text-white py-3 rounded-xl font-semibold hover:bg-[#0d3456] transition">{{ __('wishlists.modal.create_submit') }}</button>
                    <button onclick="closePageWishlistModal()" class="px-5 py-3 border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition">{{ __('wishlists.modal.create_cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@auth
@if(auth()->user()->isClient())
@push('scripts')
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
        .then(() => {
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    function openPageWishlistModal() {
        document.getElementById('page-wishlist-modal').classList.remove('hidden');
        document.body.classList.add('modal-open');
        setTimeout(() => {
            document.getElementById('page-wishlist-name').focus();
        }, 150);
    }

    function closePageWishlistModal() {
        document.getElementById('page-wishlist-modal').classList.add('hidden');
        document.body.classList.remove('modal-open');
        document.getElementById('page-wishlist-name').value = '';
    }

    function submitPageWishlist() {
        const name = document.getElementById('page-wishlist-name').value.trim();
        if (!name) {
            alert('{{ __('wishlists.modal.create_placeholder') }}');
            return;
        }
        
        fetch('{{ route("client.wishlists.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(() => {
            closePageWishlistModal();
            window.location.reload();
        })
        .catch(error => {
            console.error('Error creating wishlist:', error);
            alert('An error occurred while creating the wishlist.');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('wishlist-intro-modal');
        if (!modal) return;

        const isMobile = window.matchMedia('(max-width: 768px)').matches;

        function closeIntroModal() {
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
            closeBtn.addEventListener('click', closeIntroModal);
        }

        if (confirmBtn) {
            confirmBtn.addEventListener('click', closeIntroModal);
        }

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeIntroModal();
            }
        });
    });
</script>
@endpush
@endif
@endauth
@endsection

