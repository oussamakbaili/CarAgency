@extends('layouts.agence')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 reveal-section">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Gestion de la Flotte</h1>
            <p class="text-xs sm:text-sm text-gray-500">Gérez vos véhicules, photos et disponibilités</p>
        </div>
        <div class="flex flex-wrap gap-2 sm:gap-3">
            <a href="{{ route('agence.fleet.categories') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Catégories
            </a>
            <a href="{{ route('agence.fleet.maintenance') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Maintenance
            </a>
            <a href="{{ route('agence.cars.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un Véhicule
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 sm:p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs sm:text-sm reveal-section">
            {{ session('success') }}
        </div>
    @endif

    <!-- Fleet Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 reveal-section">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-blue-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Véhicules</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $cars->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-green-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Disponibles</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $cars->where('status', 'available')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-orange-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">En Maintenance</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $cars->where('status', 'maintenance')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-lg bg-purple-50">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                    <div class="ml-3 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Prix Moyen</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($cars->avg('price_per_day'), 0) }} DH</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fleet Table -->
    @if($cars->isEmpty())
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-8 sm:p-12 text-center reveal-section">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-3 sm:mb-4">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1 sm:mb-2">Aucun véhicule</h3>
            <p class="text-sm text-gray-500 mb-4 sm:mb-6">Commencez par ajouter votre premier véhicule à la flotte.</p>
            <a href="{{ route('agence.cars.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un véhicule
            </a>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-sm rounded-lg reveal-section">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900">Mes Véhicules</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix/jour</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cars as $car)
                        <tr onclick="window.location='{{ route('agence.cars.show', $car) }}'" 
                            class="hover:bg-gray-50 cursor-pointer transition-colors">
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                                        @if($car->image_url)
                                        <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-cover border border-gray-200" src="{{ $car->image_url }}" alt="{{ $car->brand }}">
                                        @else
                                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</div>
                                        <div class="text-xs text-gray-500">{{ $car->year }} • {{ $car->registration_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-semibold text-gray-900">
                                {{ number_format($car->price_per_day, 0) }} DH
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $car->status === 'available' ? 'bg-green-100 text-green-700' : 
                                       ($car->status === 'rented' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                                    {{ $car->status === 'available' ? 'Disponible' : 
                                       ($car->status === 'rented' ? 'En location' : 'Maintenance') }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                @if($car->track_stock)
                                    {{ $car->available_stock }}/{{ $car->stock_quantity }}
                                @else
                                    <span class="text-gray-500">Illimité</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium" onclick="event.stopPropagation()">
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                    <a href="{{ route('agence.cars.edit', $car) }}" 
                                       class="text-indigo-600 hover:text-indigo-700 font-medium">Modifier</a>
                                    <button onclick="updateStatus({{ $car->id }}, '{{ $car->status }}')" 
                                            class="text-orange-600 hover:text-orange-700 font-medium">Statut</button>
                                    <button onclick="deleteCar({{ $car->id }})" 
                                            class="text-red-600 hover:text-red-700 font-medium">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Modifier le Statut</h3>
            </div>
            <form id="statusForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nouveau Statut
                        </label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-orange-500 focus:border-orange-500">
                            <option value="available">Disponible</option>
                            <option value="rented">En location</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (optionnel)
                        </label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Ajoutez des notes sur le changement de statut..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(carId, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `/agence/cars/${carId}/status`;
    
    // Set current status as selected
    const statusSelect = form.querySelector('select[name="status"]');
    statusSelect.value = currentStatus;
    
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function deleteCar(carId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/agence/cars/${carId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
