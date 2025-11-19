@extends('layouts.public')

@section('title', $wishlist->name . ' - Wishlists')

@php
    $heroCar = $cars->first();
    $shareUrl = route('client.wishlists.show', $wishlist);
@endphp

@section('content')
    @push('styles')
        <style>
            body.single-wishlist-page footer {
                display: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.body.classList.add('single-wishlist-page');
            });

            window.addEventListener('beforeunload', function() {
                document.body.classList.remove('single-wishlist-page');
            });
        </script>
    @endpush

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 pt-6 pb-24 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-gray-200 transition">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Wishlists</div>
                <button id="wishlistOptionsBtn" class="p-2 rounded-full hover:bg-gray-200 transition">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM12 13.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM12 20.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                <div>
                    <h1 id="wishlistTitle" class="text-3xl font-semibold text-gray-900">{{ $wishlist->name }}</h1>
                    @if($wishlist->description)
                        <p class="text-gray-500 mt-1">{{ $wishlist->description }}</p>
                    @endif
                    <p class="text-sm text-gray-500 mt-2">{{ $cars->count() }} {{ \Illuminate\Support\Str::plural('saved', $cars->count()) }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600">
                        Dates • Guests
                    </button>
                    <button id="shareWishlistBtn"
                            class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 text-sm font-medium text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 12v.01M12 4h.01M20 12v.01M12 20h.01M7.757 7.757l.007.007M16.243 7.757l.007-.007M7.757 16.243l.007-.007M16.243 16.243l.007.007" />
                        </svg>
                        Share
                    </button>
                </div>
            </div>

            <!-- Cars -->
            <div class="space-y-6">
                @forelse($cars as $car)
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition">
                        <a href="{{ route('public.car.show', [$car->agency, $car]) }}" class="block">
                            <div class="relative h-64 bg-gray-100">
                                @if($car->image_url)
                                    <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}"
                                         class="w-full h-full object-cover">
                                @endif
                                <div class="absolute top-4 left-4 bg-white/90 rounded-full px-3 py-1 text-xs font-semibold text-gray-900 shadow">
                                    {{ $car->agency->agency_name ?? 'ToubCar' }}
                                </div>
                                <div class="absolute top-4 right-4">
                                    <button class="w-10 h-10 rounded-full bg-white/90 flex items-center justify-center text-pink-500 shadow-md">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-5 space-y-3">
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $car->agency->city ?? 'Morocco' }}</span>
                                    <span class="flex items-center gap-1 font-semibold text-gray-900">
                                        <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($car->getAverageRating(), 2) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ \Illuminate\Support\Str::limit($car->description ?? 'Great choice for your next trip.', 120) }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-gray-900">
                                        <span class="text-lg font-semibold">{{ number_format($car->client_price_per_day, 0) }}</span>
                                        <span class="text-sm">MAD / day</span>
                                    </div>
                                    <span class="text-sm text-gray-500">1 bed</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-3xl shadow">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No cars yet</h3>
                        <p class="text-gray-500">Add cars from the Explore tab to fill this wishlist.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Settings Bottom Sheet -->
        <div id="wishlistSettingsModal" class="hidden fixed inset-0 z-50 flex flex-col justify-end">
            <div class="absolute inset-0 bg-black/50" onclick="closeWishlistSettings()"></div>
            <div class="relative bg-white rounded-t-3xl shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    <button onclick="closeWishlistSettings()" class="p-2 rounded-full hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="divide-y divide-gray-100">
                    <button onclick="shareWishlist()" class="w-full flex items-center justify-between py-3 text-left">
                        <span class="text-gray-900 font-medium">Share wishlist</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button onclick="toggleRenameSection()" class="w-full flex items-center justify-between py-3 text-left">
                        <span class="text-gray-900 font-medium">Rename</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button onclick="deleteWishlist()" class="w-full flex items-center justify-between py-3 text-left text-red-600">
                        <span class="font-medium">Delete</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <div id="renameSection" class="hidden pt-4">
                    <label for="renameWishlistInput" class="text-sm font-medium text-gray-700 mb-2 block">Wishlist name</label>
                    <input id="renameWishlistInput" type="text" value="{{ $wishlist->name }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <button onclick="submitWishlistRename()"
                            class="mt-3 w-full bg-gray-900 text-white py-3 rounded-2xl font-semibold hover:bg-gray-800">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const wishlistId = {{ $wishlist->id }};
        const wishlistTitle = document.getElementById('wishlistTitle');
        const settingsModal = document.getElementById('wishlistSettingsModal');
        const renameSection = document.getElementById('renameSection');
        const renameInput = document.getElementById('renameWishlistInput');
        const shareData = {
            title: '{{ $wishlist->name }}',
            text: 'Check out my wishlist on ToubCar',
            url: "{{ $shareUrl }}"
        };

        document.getElementById('wishlistOptionsBtn').addEventListener('click', openWishlistSettings);
        document.getElementById('shareWishlistBtn').addEventListener('click', shareWishlist);

        function openWishlistSettings() {
            settingsModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeWishlistSettings() {
            settingsModal.classList.add('hidden');
            document.body.style.overflow = '';
            renameSection.classList.add('hidden');
        }

        function toggleRenameSection() {
            renameSection.classList.toggle('hidden');
            if (!renameSection.classList.contains('hidden')) {
                renameInput.focus();
            }
        }

        function shareWishlist() {
            if (navigator.share) {
                navigator.share(shareData).catch(() => {});
                return;
            }

            navigator.clipboard.writeText(shareData.url)
                .then(() => alert('Share link copied to clipboard!'))
                .catch(() => alert(shareData.url));
        }

        function submitWishlistRename() {
            const newName = renameInput.value.trim();
            if (!newName) {
                alert('Please enter a name.');
                return;
            }

            fetch(`{{ route('client.wishlists.update', $wishlist) }}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: newName })
            })
            .then(response => response.json())
            .then(data => {
                wishlistTitle.textContent = newName;
                closeWishlistSettings();
            })
            .catch(() => alert('Unable to rename wishlist right now. Please try again.'));
        }

        function deleteWishlist() {
            if (!confirm('Delete this wishlist?')) {
                return;
            }

            fetch(`{{ route('client.wishlists.destroy', $wishlist) }}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(() => window.location.href = '{{ route('public.wishlists') }}')
            .catch(() => alert('Unable to delete wishlist. Please try again.'));
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeWishlistSettings();
            }
        });
    </script>
@endsection

