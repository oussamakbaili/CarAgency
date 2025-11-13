<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wishlists - ToubCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-10 px-4">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">
                @if(app()->getLocale() === 'fr')
                    Favoris
                @else
                    Wishlists
                @endif
            </h1>

            @guest
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <h2 class="text-2xl font-bold mb-4">
                        @if(app()->getLocale() === 'fr')
                            Connectez-vous
                        @else
                            Log in
                        @endif
                    </h2>
                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg">
                        @if(app()->getLocale() === 'fr')
                            Se connecter
                        @else
                            Log in
                        @endif
                    </a>
                </div>
            @else
                @php
                    $userWishlists = collect([]);
                    try {
                        if (auth()->check() && method_exists(auth()->user(), 'isClient') && auth()->user()->isClient()) {
                            if (method_exists(auth()->user(), 'wishlists')) {
                                $userWishlists = auth()->user()->wishlists()->withCount('items')->get();
                            }
                        }
                    } catch (\Exception $e) {
                        $userWishlists = collect([]);
                    }
                @endphp

                @if($userWishlists->isEmpty())
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <p class="text-gray-600 mb-4">
                            @if(app()->getLocale() === 'fr')
                                Aucun favori pour le moment
                            @else
                                No wishlists yet
                            @endif
                        </p>
                        <a href="{{ route('public.home') }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-3 rounded-lg">
                            @if(app()->getLocale() === 'fr')
                                Retour à l'accueil
                            @else
                                Back to home
                            @endif
                        </a>
                    </div>
                @else
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($userWishlists as $wishlist)
                            <div class="bg-white rounded-lg shadow p-5">
                                <h3 class="font-semibold text-lg">{{ $wishlist->name }}</h3>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ $wishlist->items_count ?? 0 }} 
                                    @if(app()->getLocale() === 'fr')
                                        élément(s)
                                    @else
                                        item(s)
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endguest
        </div>
    </div>
</body>
</html>
