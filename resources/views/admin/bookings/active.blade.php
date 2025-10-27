@extends('layouts.admin')

@section('header', 'Locations Actives')

@section('content')
<!-- Search -->
<div class="bg-white border border-gray-200 rounded-lg p-5 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Rechercher par client, véhicule..." 
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            Rechercher
        </button>
        <a href="{{ route('admin.bookings.active') }}" class="bg-gray-600 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            Réinitialiser
        </a>
    </form>
</div>

<!-- Active Bookings -->
<div class="space-y-4">
    @forelse($bookings as $booking)
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center border border-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Réservation #{{ $booking->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $booking->user->name }} • {{ $booking->user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        Active
                    </span>
                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                       class="inline-flex items-center gap-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                        Voir Détails
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 rounded-lg p-4">
                <!-- Vehicle Information -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Véhicule</h4>
                    <div class="flex items-center gap-3">
                        @if($booking->car->image)
                        <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="{{ Storage::url($booking->car->image) }}" alt="{{ $booking->car->brand }}">
                        @else
                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center border border-gray-300">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $booking->car->brand }} {{ $booking->car->model }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->car->year }} • {{ $booking->car->registration_number }}</div>
                        </div>
                    </div>
                </div>

                <!-- Rental Period -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Période de Location</h4>
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
                        <div class="text-xs text-gray-500 font-medium">
                            {{ $booking->start_date->diffInDays($booking->end_date) }} jour{{ $booking->start_date->diffInDays($booking->end_date) > 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>

                <!-- Agency and Price -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Agence & Prix</h4>
                    <div class="space-y-1">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->agency->agency_name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->agency->city ?? '' }}</div>
                        <div class="text-lg font-bold text-gray-900 mt-2">
                            {{ number_format($booking->total_price, 0, ',', ' ') }} MAD
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-xs font-medium text-gray-600 mb-2">
                    <span>Progression de la location</span>
                    <span class="text-gray-900">{{ getProgressPercentage($booking) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ getProgressPercentage($booking) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-200 rounded-lg p-12">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">Aucune location active</h3>
            <p class="text-sm text-gray-500">Il n'y a actuellement aucune location en cours.</p>
        </div>
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
