@extends('layouts.agence')

@section('title', 'Profil de l\'Agence')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil de l'Agence</h1>
            <p class="text-gray-600">Gérez les informations de votre agence</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm text-gray-500">Statut</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($agency->status == 'approved') bg-green-100 text-green-800
                    @elseif($agency->status == 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    @if($agency->status == 'approved') Approuvée
                    @elseif($agency->status == 'pending') En attente
                    @else Rejetée
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Profile Picture Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                @if($agency->profile_picture)
                    <img class="h-20 w-20 rounded-full object-cover" src="{{ Storage::url($agency->profile_picture) }}" alt="Photo de profil">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                        <svg class="h-10 w-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ $agency->agency_name }}</h3>
                <p class="text-gray-600">{{ $agency->email }}</p>
                <p class="text-sm text-gray-500">{{ $agency->phone }}</p>
            </div>
            <div>
                <button onclick="showProfilePictureModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Changer la photo
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('general')" id="tab-general" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                    Informations Générales
                </button>
                <button onclick="showTab('hours')" id="tab-hours" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Heures d'Ouverture
                </button>
                <button onclick="showTab('locations')" id="tab-locations" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Localisations
                </button>
                <button onclick="showTab('documents')" id="tab-documents" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Documents
                </button>
                <button onclick="showTab('security')" id="tab-security" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Sécurité
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- General Information Tab -->
            <div id="content-general" class="tab-content">
                @include('agence.profile.partials.general')
            </div>

            <!-- Opening Hours Tab -->
            <div id="content-hours" class="tab-content hidden">
                @include('agence.profile.partials.hours')
            </div>

            <!-- Locations Tab -->
            <div id="content-locations" class="tab-content hidden">
                @include('agence.profile.partials.locations')
            </div>

            <!-- Documents Tab -->
            <div id="content-documents" class="tab-content hidden">
                @include('agence.profile.partials.documents')
            </div>

            <!-- Security Tab -->
            <div id="content-security" class="tab-content hidden">
                @include('agence.profile.partials.security')
            </div>
        </div>
    </div>
</div>

<!-- Profile Picture Modal -->
<div id="profilePictureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Changer la photo de profil</h3>
                <button onclick="closeProfilePictureModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('agence.profile.picture') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouvelle photo</label>
                    <input type="file" name="profile_picture" accept="image/*" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeProfilePictureModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
}

function showProfilePictureModal() {
    document.getElementById('profilePictureModal').classList.remove('hidden');
}

function closeProfilePictureModal() {
    const modal = document.getElementById('profilePictureModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('profilePictureModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfilePictureModal();
            }
        });
    }
});
</script>
@endsection