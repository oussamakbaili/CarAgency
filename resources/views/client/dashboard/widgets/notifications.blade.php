<!-- Smart Notifications Widget -->
@if($notifications->count() > 0)
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5a3 3 0 01-3-3v-8a3 3 0 013-3h9a3 3 0 013 3v8a3 3 0 01-3 3h-9z"/>
                </svg>
                Notifications Intelligentes
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">{{ $notifications->count() }} notification{{ $notifications->count() > 1 ? 's' : '' }}</span>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Marquer tout comme lu
                </button>
            </div>
        </div>
        
        <div class="space-y-3">
            @foreach($notifications as $notification)
            <div class="flex items-start p-4 rounded-lg {{ $notification['priority'] === 'high' ? 'bg-red-50 border border-red-200' : 'bg-gray-50' }}">
                <div class="flex-shrink-0">
                    @if($notification['priority'] === 'high')
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @elseif($notification['type'] === 'rental_reminder')
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @elseif($notification['type'] === 'status_update')
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5a3 3 0 01-3-3v-8a3 3 0 013-3h9a3 3 0 013 3v8a3 3 0 01-3 3h-9z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1 ml-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</h3>
                        <div class="flex items-center space-x-2">
                            @if($notification['priority'] === 'high')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Urgent
                                </span>
                            @elseif($notification['priority'] === 'medium')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Important
                                </span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $notification['date']->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                    
                    @if(isset($notification['action_url']))
                    <div class="mt-3">
                        <a href="{{ $notification['action_url'] }}" 
                           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                            Voir les détails
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>
                
                <div class="flex-shrink-0 ml-3">
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Dernière mise à jour: {{ now()->format('d/m/Y à H:i') }}
                </div>
                <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Paramètres de notification
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Status Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Profile Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if($quickActions['profileComplete'])
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-900">Profil</h3>
                <p class="text-sm text-gray-500">
                    {{ $quickActions['profileComplete'] ? 'Complet' : 'À compléter' }}
                </p>
            </div>
        </div>
        @if(!$quickActions['profileComplete'])
        <div class="mt-3">
            <a href="{{ route('client.profile.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Compléter maintenant →
            </a>
        </div>
        @endif
    </div>

    <!-- Active Rentals Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if($quickActions['hasActiveRentals'])
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-900">Locations Actives</h3>
                <p class="text-sm text-gray-500">
                    {{ $activeRentals > 0 ? $activeRentals . ' en cours' : 'Aucune' }}
                </p>
            </div>
        </div>
        @if($activeRentals > 0)
        <div class="mt-3">
            <a href="{{ route('client.rentals.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Voir mes locations →
            </a>
        </div>
        @endif
    </div>

    <!-- Support Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if($quickActions['hasOpenSupportTickets'])
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-900">Support</h3>
                <p class="text-sm text-gray-500">
                    {{ $openSupportTickets > 0 ? $openSupportTickets . ' tickets ouverts' : 'Aucun problème' }}
                </p>
            </div>
        </div>
        @if($openSupportTickets > 0)
        <div class="mt-3">
            <a href="#" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Voir mes tickets →
            </a>
        </div>
        @endif
    </div>
</div>
