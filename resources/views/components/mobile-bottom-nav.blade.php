@unless(request()->routeIs('client.dashboard') || request()->routeIs('agence.dashboard') || request()->routeIs('admin.dashboard'))
<div id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg md:hidden">
    <div class="flex items-center justify-around h-16 px-2">
        <!-- Explore Button -->
        <button onclick="handleExploreClick(event)" class="flex flex-col items-center justify-center flex-1 h-full active-nav-item">
            <svg class="w-6 h-6 mb-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-xs font-semibold text-red-600">{{ __('common.mobile_nav.explore') }}</span>
        </button>

        <!-- Wishlists Button -->
        <a href="{{ route('public.wishlists') }}" class="flex flex-col items-center justify-center flex-1 h-full">
            <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span class="text-xs font-medium text-gray-500">{{ __('common.mobile_nav.wishlists') }}</span>
        </a>

        <!-- Messages Button (only for authenticated clients) -->
        @auth
            @if(auth()->user()->isClient())
                <a href="{{ route('public.messages') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                    <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-xs font-medium text-gray-500">{{ __('common.mobile_nav.messages') }}</span>
                </a>
            @endif
        @endauth

        <!-- Account / Login Button -->
        @auth
            <a href="{{ route('client.dashboard') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500">{{ __('common.mobile_nav.account') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center flex-1 h-full">
                <svg class="w-6 h-6 mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500">{{ __('common.mobile_nav.login') }}</span>
            </a>
        @endauth
    </div>
</div>

@once
    @push('styles')
        <style>
            #bottom-nav {
                transform: translateY(0);
                transition: transform 0.3s ease-in-out;
                will-change: transform;
            }
            
            #bottom-nav.hidden {
                transform: translateY(100%);
            }
            
            .active-nav-item:active {
                opacity: 0.7;
                transform: scale(0.95);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            window.bottomNavScriptInitialized = window.bottomNavScriptInitialized || false;

            function handleExploreClick(event) {
                event.preventDefault();
                if (typeof scrollToTopPicks === 'function') {
                    scrollToTopPicks(event);
                } else {
                    window.location.href = "{{ route('public.home') }}";
                }
            }

            if (!window.bottomNavScriptInitialized) {
                window.bottomNavScriptInitialized = true;

                document.addEventListener('DOMContentLoaded', function() {
                    let lastScrollTop = 0;
                    const bottomNav = document.getElementById('bottom-nav');
                    if (!bottomNav) {
                        return;
                    }

                    window.addEventListener('scroll', function() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        if (scrollTop > lastScrollTop && scrollTop > 120) {
                            bottomNav.classList.add('hidden');
                        } else {
                            bottomNav.classList.remove('hidden');
                        }
                        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                    });
                });
            }
        </script>
    @endpush
@endonce
@endunless
