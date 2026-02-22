@extends('layouts.agence')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $car->brand }} {{ $car->model }}</h1>
                <p class="text-gray-600 mt-2">{{ $car->registration_number }} • {{ $car->year }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('agence.fleet.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('agence.cars.edit', $car->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Car Images -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Photos du Véhicule</h2>
                    
                    @if($car->pictures && count($car->pictures) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($car->picture_urls as $index => $pictureUrl)
                                <div class="relative group">
                                    <img src="{{ $pictureUrl }}" alt="Photo {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                        <button onclick="openImageModal('{{ $pictureUrl }}')" class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                                            Voir
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($car->image)
                        <div class="relative group">
                            <img src="{{ $car->image_url }}" alt="Photo principale" class="w-full h-64 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <button onclick="openImageModal('{{ $car->image_url }}')" class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                                    Voir
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500">Aucune photo disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Car Details -->
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Générales</h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Marque:</span>
                        <span class="font-medium">{{ $car->brand }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Modèle:</span>
                        <span class="font-medium">{{ $car->model }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Immatriculation:</span>
                        <span class="font-medium">{{ $car->registration_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Année:</span>
                        <span class="font-medium">{{ $car->year }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Catégorie:</span>
                        <span class="font-medium">{{ $car->category->name ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Couleur:</span>
                        <span class="font-medium">{{ $car->color ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Carburant:</span>
                        <span class="font-medium">{{ $car->fuel_type ?? 'Non défini' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transmission:</span>
                        <span class="font-medium">{{ $car->transmission ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Places:</span>
                        <span class="font-medium">{{ $car->seats ?? 'Non définies' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cylindrée:</span>
                        <span class="font-medium">{{ $car->engine_size ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kilométrage:</span>
                        <span class="font-medium">{{ $car->mileage ? number_format($car->mileage, 0, ',', ' ') . ' km' : 'Non défini' }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing & Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tarification & Statut</h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Prix par jour:</span>
                        <span class="font-medium text-green-600">{{ number_format($car->price_per_day, 0, ',', ' ') }} MAD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($car->status === 'available') bg-green-100 text-green-800
                            @elseif($car->status === 'rented') bg-blue-100 text-blue-800
                            @elseif($car->status === 'maintenance') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($car->status === 'available') Disponible
                            @elseif($car->status === 'rented') En location
                            @elseif($car->status === 'maintenance') Maintenance
                            @else {{ ucfirst($car->status) }}
                            @endif
                        </span>
                    </div>
                    @if($car->track_stock)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Stock total:</span>
                            <span class="font-medium">{{ $car->stock_quantity }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Stock disponible:</span>
                            <span class="font-medium">{{ $car->available_stock }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Maintenance -->
            @if($car->maintenance_due || $car->last_maintenance)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Maintenance</h2>
                    <div class="space-y-4">
                        @if($car->last_maintenance)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dernière maintenance:</span>
                                <span class="font-medium">{{ $car->last_maintenance->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        @if($car->maintenance_due)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Prochaine maintenance:</span>
                                <span class="font-medium 
                                    @if($car->maintenance_due->isPast()) text-red-600
                                    @elseif($car->maintenance_due->diffInDays() <= 7) text-yellow-600
                                    @else text-gray-900
                                    @endif">
                                    {{ $car->maintenance_due->format('d/m/Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Features -->
            @if($car->features && count($car->features) > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Équipements</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($car->features as $feature)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $feature }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Description -->
    @if($car->description)
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
            <p class="text-gray-700 leading-relaxed">{{ $car->description }}</p>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Image" class="max-w-full max-h-full rounded-lg">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection
