@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white via-gray-50 to-gray-100 pb-24 md:pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="pt-10 pb-6">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">
                @if(app()->getLocale() === 'fr')
                    Favoris
                @else
                    Wishlists
                @endif
            </h1>
        </div>

        @guest
            <div class="mt-16 bg-white rounded-3xl shadow-xl px-8 py-12 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">
                    @if(app()->getLocale() === 'fr')
                        Connectez-vous pour voir vos favoris
                    @else
                        Log in to view your wishlists
                    @endif
                </h2>
                <p class="text-gray-500 mb-8">
                    @if(app()->getLocale() === 'fr')
                        Vous pourrez créer, consulter ou modifier des listes de favoris après connexion.
                    @else
                        You can create, view, or edit wishlists once you have logged in.
                    @endif
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-[#0F3B63] hover:bg-[#0d3456] text-white px-6 py-3 rounded-xl font-semibold">
                    @if(app()->getLocale() === 'fr')
                        Se connecter
                    @else
                        Log in
                    @endif
                </a>
            </div>
        @else
            @if(auth()->check() && method_exists(auth()->user(), 'isClient') && auth()->user()->isClient())
                @php
                    $userWishlists = collect([]);
                    try {
                        if (method_exists(auth()->user(), 'wishlists')) {
                            $userWishlists = auth()->user()->wishlists()->withCount('items')->get();
                        }
                    } catch (\Exception $e) {
                        $userWishlists = collect([]);
                    }
                @endphp

                @if($userWishlists->isEmpty())
                    <div class="mt-16 bg-white rounded-3xl shadow-xl px-8 py-12 text-center">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">
                            @if(app()->getLocale() === 'fr')
                                Aucun favori pour le moment
                            @else
                                No wishlists yet
                            @endif
                        </h2>
                        <p class="text-gray-500 mb-8">
                            @if(app()->getLocale() === 'fr')
                                Ajoutez vos voitures préférées en touchant le cœur sur une annonce.
                            @else
                                Start saving your favourite cars by clicking the heart icon on any listing.
                            @endif
                        </p>
                        <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">
                            @if(app()->getLocale() === 'fr')
                                Explorer les voitures
                            @else
                                Explore Cars
                            @endif
                        </a>
                    </div>
                @else
                    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($userWishlists as $wishlist)
                            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-lg">
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $wishlist->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if(app()->getLocale() === 'fr')
                                            {{ $wishlist->items_count ?? 0 }} sauvegardé(s)
                                        @else
                                            {{ $wishlist->items_count ?? 0 }} saved
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="mt-16 bg-white rounded-3xl shadow-xl px-8 py-12 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        @if(app()->getLocale() === 'fr')
                            Les favoris sont réservés aux clients
                        @else
                            Wishlists are for clients only
                        @endif
                    </h2>
                    <p class="text-gray-500 mb-8">
                        @if(app()->getLocale() === 'fr')
                            Connectez-vous avec un compte client pour utiliser les favoris.
                        @else
                            Please log in with a client account to use wishlists.
                        @endif
                    </p>
                    <a href="{{ route('logout') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">
                        @if(app()->getLocale() === 'fr')
                            Se déconnecter
                        @else
                            Log out
                        @endif
                    </a>
                </div>
            @endif
        @endguest
    </div>
</div>

@include('components.mobile-bottom-nav')
@endsection
