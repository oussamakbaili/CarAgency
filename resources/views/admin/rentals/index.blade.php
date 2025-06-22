@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Gestion des Locations</h2>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-blue-600 text-2xl font-bold">{{ $rentals->total() }}</div>
                        <div class="text-blue-800 text-sm">Total des Locations</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-green-600 text-2xl font-bold">
                            {{ $rentals->where('status', 'approved')->count() }}
                        </div>
                        <div class="text-green-800 text-sm">Locations Approuvées</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-yellow-600 text-2xl font-bold">
                            {{ $rentals->where('status', 'pending')->count() }}
                        </div>
                        <div class="text-yellow-800 text-sm">En Attente</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-red-600 text-2xl font-bold">
                            {{ $rentals->where('status', 'rejected')->count() }}
                        </div>
                        <div class="text-red-800 text-sm">Refusées</div>
                    </div>
                </div>

                <!-- Rentals Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Voiture
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Agence
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($rentals as $rental)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #{{ $rental->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $rental->client->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $rental->car->brand ?? 'N/A' }} {{ $rental->car->model ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $rental->car->agency->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($rental->status ?? 'pending')
                                            @case('approved')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('rejected')
                                                bg-red-100 text-red-800
                                                @break
                                            @default
                                                bg-yellow-100 text-yellow-800
                                        @endswitch">
                                        {{ ucfirst($rental->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $rental->total_price ?? 'N/A' }}€
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune location trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rentals->hasPages())
                <div class="mt-6">
                    {{ $rentals->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 