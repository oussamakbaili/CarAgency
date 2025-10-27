@extends('layouts.agence')

@section('content')
<div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Facture</h1>
                    <p class="mt-2 text-gray-600">Réservation #{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('agence.rentals.invoice.download', $rental) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger PDF
                    </a>
                    <a href="{{ route('agence.rentals.show', $rental) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Invoice Header -->
            <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">FACTURE</h2>
                        <p class="text-sm text-gray-600">N° {{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-600">Date: {{ \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $rental->agency->name ?? 'Agence de Location' }}</h3>
                        <p class="text-sm text-gray-600">{{ $rental->agency->address ?? 'Adresse de l\'agence' }}</p>
                        <p class="text-sm text-gray-600">{{ $rental->agency->phone ?? 'Téléphone' }}</p>
                        <p class="text-sm text-gray-600">{{ $rental->agency->email ?? 'Email' }}</p>
                    </div>
                </div>
            </div>

            <!-- Client Information -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Facturé à:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $rental->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $rental->user->email ?? 'N/A' }}</p>
                        @if($rental->user->phone)
                            <p class="text-sm text-gray-600">{{ $rental->user->phone }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">ID Client: #{{ $rental->user->id ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Date de réservation: {{ \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Details -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails du Véhicule</h3>
                <div class="flex items-start space-x-4">
                    @if($rental->car->image_url)
                        <img class="h-24 w-24 rounded-lg object-cover" src="{{ $rental->car->image_url }}" alt="{{ $rental->car->brand }} {{ $rental->car->model }}">
                    @else
                        <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900">{{ $rental->car->brand }} {{ $rental->car->model }} {{ $rental->car->year }}</h4>
                        <p class="text-sm text-gray-600">Immatriculation: {{ $rental->car->registration_number }}</p>
                        @if($rental->car->color)
                            <p class="text-sm text-gray-600">Couleur: {{ $rental->car->color }}</p>
                        @endif
                        @if($rental->car->category)
                            <p class="text-sm text-gray-600">Catégorie: {{ $rental->car->category->name ?? 'N/A' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails de la Location</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de début</p>
                        <p class="text-sm text-gray-900">{{ $rental->start_date ? \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de fin</p>
                        <p class="text-sm text-gray-900">{{ $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Durée</p>
                        <p class="text-sm text-gray-900">
                            @if($rental->start_date && $rental->end_date)
                                {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) }} jours
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pricing Breakdown -->
            <div class="px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détail des Tarifs</h3>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Location {{ $rental->car->brand }} {{ $rental->car->model }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($rental->start_date && $rental->end_date)
                                        {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) }} jours
                                    @else
                                        1 jour
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($rental->car->price_per_day, 0) }} DH
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($rental->total_price, 0) }} DH
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Total TTC
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ number_format($rental->total_price, 0) }} DH
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-gray-50">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Merci pour votre confiance!</p>
                    <p class="text-xs text-gray-500 mt-2">
                        Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
