@extends('layouts.agence')

@section('content')
<div class="p-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        @if($view === 'day')
                            Revenus du jour
                        @elseif($view === 'week')
                            Revenus de la semaine
                        @else
                            Revenus du mois
                        @endif
                    </p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($revenue, 0) }} DH</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Locations actives</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeRentals }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En attente</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingRentals }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Terminées</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completedRentals }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Calendrier des Réservations</h1>
        <p class="text-gray-600">Visualisez et gérez les réservations sur le calendrier</p>
    </div>

    <!-- Calendar Controls -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($view === 'month')
                    <a href="{{ route('agence.bookings.calendar', ['view' => 'month', 'year' => $currentDate->copy()->subMonth()->year, 'month' => $currentDate->copy()->subMonth()->month]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Précédent
                    </a>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $currentDate->format('F Y') }}</h2>
                    <a href="{{ route('agence.bookings.calendar', ['view' => 'month', 'year' => $currentDate->copy()->addMonth()->year, 'month' => $currentDate->copy()->addMonth()->month]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Suivant
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @elseif($view === 'week')
                    <a href="{{ route('agence.bookings.calendar', ['view' => 'week', 'year' => $currentDate->copy()->subWeek()->year, 'month' => $currentDate->copy()->subWeek()->month, 'day' => $currentDate->copy()->subWeek()->day]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Précédent
                    </a>
                    <h2 class="text-lg font-semibold text-gray-900">Semaine du {{ $currentDate->copy()->startOfWeek()->format('d/m') }} au {{ $currentDate->copy()->endOfWeek()->format('d/m/Y') }}</h2>
                    <a href="{{ route('agence.bookings.calendar', ['view' => 'week', 'year' => $currentDate->copy()->addWeek()->year, 'month' => $currentDate->copy()->addWeek()->month, 'day' => $currentDate->copy()->addWeek()->day]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Suivant
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('agence.bookings.calendar', ['view' => 'day', 'year' => $currentDate->copy()->subDay()->year, 'month' => $currentDate->copy()->subDay()->month, 'day' => $currentDate->copy()->subDay()->day]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Précédent
                    </a>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $currentDate->format('l d F Y') }}</h2>
                    <a href="{{ route('agence.bookings.calendar', ['view' => 'day', 'year' => $currentDate->copy()->addDay()->year, 'month' => $currentDate->copy()->addDay()->month, 'day' => $currentDate->copy()->addDay()->day]) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Suivant
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('agence.bookings.calendar', ['view' => 'month', 'year' => $currentDate->year, 'month' => $currentDate->month]) }}" 
                   class="px-3 py-1 text-sm font-medium {{ $view === 'month' ? 'text-blue-600 bg-blue-100' : 'text-gray-600 hover:text-gray-900' }} rounded-md">Mois</a>
                <a href="{{ route('agence.bookings.calendar', ['view' => 'week', 'year' => $currentDate->year, 'month' => $currentDate->month, 'day' => $currentDate->day]) }}" 
                   class="px-3 py-1 text-sm font-medium {{ $view === 'week' ? 'text-blue-600 bg-blue-100' : 'text-gray-600 hover:text-gray-900' }} rounded-md">Semaine</a>
                <a href="{{ route('agence.bookings.calendar', ['view' => 'day', 'year' => $currentDate->year, 'month' => $currentDate->month, 'day' => $currentDate->day]) }}" 
                   class="px-3 py-1 text-sm font-medium {{ $view === 'day' ? 'text-blue-600 bg-blue-100' : 'text-gray-600 hover:text-gray-900' }} rounded-md">Jour</a>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                @if($view === 'day')
                    Réservations du jour
                @elseif($view === 'week')
                    Réservations de la semaine
                @else
                    Calendrier des Réservations
                @endif
            </h3>
        </div>
        
        @if($view === 'month')
            <!-- Month View -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Lun</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Mar</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Mer</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jeu</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ven</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sam</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Dim</div>
            </div>
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                @foreach($calendarData as $dayData)
                    @if($dayData)
                        <div class="bg-white min-h-[120px] p-2 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium {{ $dayData['isToday'] ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : 'text-gray-900' }}">
                                    {{ $dayData['day'] }}
                                </span>
                                @if($dayData['rentalCount'] > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $dayData['rentalCount'] > 2 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $dayData['rentalCount'] }} réservation{{ $dayData['rentalCount'] > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($dayData['rentalCount'] > 0)
                                <div class="space-y-1">
                                    @foreach($dayData['rentals'] as $rental)
                                        @php
                                            $statusColor = match($rental->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'active' => 'bg-green-100 text-green-800',
                                                'completed' => 'bg-blue-100 text-blue-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <div class="text-xs p-1 {{ $statusColor }} rounded truncate cursor-pointer hover:opacity-80" 
                                             title="{{ $rental->car->brand }} {{ $rental->car->model }} - {{ $rental->user->name }} ({{ ucfirst($rental->status) }})">
                                            {{ $rental->car->brand }} {{ $rental->car->model }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-50 min-h-[120px] p-2"></div>
                    @endif
                @endforeach
            </div>
        @elseif($view === 'week')
            <!-- Week View -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                @foreach($calendarData as $dayData)
                    <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                        {{ $dayData['dayNameShort'] }}
                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                @foreach($calendarData as $dayData)
                    <div class="bg-white min-h-[200px] p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg font-semibold {{ $dayData['isToday'] ? 'bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center' : 'text-gray-900' }}">
                                {{ $dayData['day'] }}
                            </span>
                            @if($dayData['rentalCount'] > 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $dayData['rentalCount'] > 3 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $dayData['rentalCount'] }} réservation{{ $dayData['rentalCount'] > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </div>
                        
                        @if($dayData['rentalCount'] > 0)
                            <div class="space-y-2">
                                @foreach($dayData['rentals'] as $rental)
                                    @php
                                        $statusColor = match($rental->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'active' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <div class="text-sm p-2 {{ $statusColor }} rounded cursor-pointer hover:opacity-80" 
                                         title="{{ $rental->car->brand }} {{ $rental->car->model }} - {{ $rental->user->name }} ({{ ucfirst($rental->status) }})">
                                        <div class="font-medium">{{ $rental->car->brand }} {{ $rental->car->model }}</div>
                                        <div class="text-xs opacity-75">{{ $rental->user->name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- Day View -->
            <div class="grid grid-cols-1 gap-px bg-gray-200">
                <div class="bg-gray-50 px-6 py-4 text-center text-sm font-medium text-gray-500 uppercase">
                    {{ $calendarData['dayName'] }} {{ $calendarData['day'] }} {{ $calendarData['date']->format('F Y') }}
                </div>
            </div>
            <div class="grid grid-cols-1 gap-px bg-gray-200">
                @foreach($calendarData['hourlyData'] as $hourData)
                    <div class="bg-white min-h-[60px] p-4 hover:bg-gray-50 transition-colors {{ $hourData['isBusinessHours'] ? 'bg-blue-50' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-medium text-gray-900 w-16">{{ $hourData['time'] }}</span>
                                @if($hourData['rentalCount'] > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $hourData['rentalCount'] }} réservation{{ $hourData['rentalCount'] > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                            @if($hourData['rentalCount'] > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($hourData['rentals'] as $rental)
                                        @if(is_object($rental) && isset($rental->status))
                                            @php
                                                $statusColor = match($rental->status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'active' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-blue-100 text-blue-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <div class="text-xs px-2 py-1 {{ $statusColor }} rounded cursor-pointer hover:opacity-80" 
                                                 title="{{ $rental->car->brand ?? 'N/A' }} {{ $rental->car->model ?? 'N/A' }} - {{ $rental->user->name ?? 'N/A' }} ({{ ucfirst($rental->status) }})">
                                                {{ $rental->car->brand ?? 'N/A' }} {{ $rental->car->model ?? 'N/A' }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Légende</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Locations actives</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Réservations en attente</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Locations terminées</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Réservations rejetées</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Réservations annulées</span>
            </div>
        </div>
    </div>
</div>
@endsection