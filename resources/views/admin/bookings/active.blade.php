@extends('layouts.admin')

@section('header', 'Locations Actives')

@section('content')
<!-- Search -->
<div class="bg-white p-4 rounded-lg shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par client, véhicule..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Rechercher
        </button>
        <a href="{{ route('admin.bookings.active') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
            Réinitialiser
        </a>
    </form>
</div>

<!-- Active Bookings -->
<div class="space-y-6">
    @forelse($bookings as $booking)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Réservation #{{ $booking->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $booking->user->name }} • {{ $booking->user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                        Voir Détails
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vehicle Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Véhicule</h4>
                    <div class="flex items-center">
                        @if($booking->car->image)
                        <img class="h-12 w-12 rounded-lg object-cover mr-3" src="{{ Storage::url($booking->car->image) }}" alt="{{ $booking->car->brand }}">
                        @else
                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->car->year }} • {{ $booking->car->registration_number }}</div>
                        </div>
                    </div>
                </div>

                <!-- Rental Period -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Période de Location</h4>
                    <div class="space-y-1">
                        <div class="flex items-center text-sm">
                            <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-900">Début: {{ $booking->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-900">Fin: {{ $booking->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $booking->start_date->diffInDays($booking->end_date) }} jour{{ $booking->start_date->diffInDays($booking->end_date) > 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>

                <!-- Agency and Price -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Agence & Prix</h4>
                    <div class="space-y-1">
                        <div class="text-sm text-gray-900">{{ $booking->agency->agency_name ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->agency->city ?? '' }}</div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ number_format($booking->total_price, 0, ',', ' ') }} MAD
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                    <span>Progression de la location</span>
                    <span>{{ getProgressPercentage($booking) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ getProgressPercentage($booking) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune location active</h3>
        <p class="mt-1 text-sm text-gray-500">Il n'y a actuellement aucune location en cours.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $bookings->links() }}
</div>
@endsection

@php
function getProgressPercentage($booking) {
    $start = $booking->start_date;
    $end = $booking->end_date;
    $now = now();
    
    if ($now < $start) {
        return 0;
    }
    
    if ($now > $end) {
        return 100;
    }
    
    $totalDays = $start->diffInDays($end);
    $elapsedDays = $start->diffInDays($now);
    
    return $totalDays > 0 ? round(($elapsedDays / $totalDays) * 100) : 0;
}
@endphp
