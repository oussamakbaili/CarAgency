@extends('layouts.agence')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $car->brand }} {{ $car->model }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $car->registration_number }} • {{ $car->year }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('agence.fleet.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('agence.cars.edit', $car->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Car Images -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-base font-semibold text-gray-900">Photos du Véhicule</h2>
                </div>
                <div class="p-6">
                    @if($car->pictures && count($car->pictures) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($car->picture_urls as $index => $pictureUrl)
                                <div class="relative group overflow-hidden rounded-lg border border-gray-200">
                                    <img src="{{ $pictureUrl }}" alt="Photo {{ $index + 1 }}" class="w-full h-40 object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 flex items-center justify-center">
                                        <button onclick="openImageModal('{{ $pictureUrl }}')" class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Agrandir
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($car->image)
                        <div class="relative group overflow-hidden rounded-lg border border-gray-200">
                            <img src="{{ $car->image_url }}" alt="Photo principale" class="w-full h-96 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 flex items-center justify-center">
                                <button onclick="openImageModal('{{ $car->image_url }}')" class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Agrandir
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-96 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Aucune photo disponible</p>
                                <p class="text-xs text-gray-400 mt-1">Ajoutez des photos lors de la modification</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Car Details -->
        <div class="space-y-4">
            <!-- Basic Information -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-base font-semibold text-gray-900">Informations Générales</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Marque:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->brand }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Modèle:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->model }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Immatriculation:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->registration_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Année:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->year }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Catégorie:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->category->name ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Couleur:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->color ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Carburant:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->fuel_type ?? 'Non défini' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Transmission:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->transmission ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Places:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->seats ?? 'Non définies' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Cylindrée:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->engine_size ?? 'Non définie' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-600">Kilométrage:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $car->mileage ? number_format($car->mileage, 0, ',', ' ') . ' km' : 'Non défini' }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing & Status -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-base font-semibold text-gray-900">Tarification & Statut</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Prix par jour:</span>
                        <span class="text-sm font-bold text-green-600">{{ number_format($car->price_per_day, 0, ',', ' ') }} DH</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Statut:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            @if($car->status === 'available') bg-green-100 text-green-700
                            @elseif($car->status === 'rented') bg-purple-100 text-purple-700
                            @elseif($car->status === 'maintenance') bg-orange-100 text-orange-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            @if($car->status === 'available') Disponible
                            @elseif($car->status === 'rented') En location
                            @elseif($car->status === 'maintenance') Maintenance
                            @else {{ ucfirst($car->status) }}
                            @endif
                        </span>
                    </div>
                    @if($car->track_stock)
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Stock total:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $car->stock_quantity }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-600">Stock disponible:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $car->available_stock }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Maintenance -->
            @if($car->maintenance_due || $car->last_maintenance)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900">Maintenance</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($car->last_maintenance)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Dernière maintenance:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $car->last_maintenance->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        @if($car->maintenance_due)
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-600">Prochaine maintenance:</span>
                                <span class="text-sm font-semibold
                                    @if($car->maintenance_due->isPast()) text-red-600
                                    @elseif($car->maintenance_due->diffInDays() <= 7) text-orange-600
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
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900">Équipements</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($car->features as $feature)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Description -->
    @if($car->description)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-base font-semibold text-gray-900">Description</h2>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-700 leading-relaxed">{{ $car->description }}</p>
            </div>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-6xl max-h-full" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="Image" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors bg-black bg-opacity-50 rounded-lg p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageUrl) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    if (modal && modalImage) {
        modalImage.src = imageUrl;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
});
</script>
@endpush
@endsection
