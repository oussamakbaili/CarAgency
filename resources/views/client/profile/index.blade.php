@extends('layouts.client')

@section('header', 'Mon Profil')

@section('content')
<!-- Page Header -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Mon Profil</h1>
                <p class="text-gray-600 mt-1">Gérez vos informations personnelles et préférences</p>
            </div>
            <a href="{{ route('client.dashboard') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour au Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Profile Overview -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if($client->profile_picture)
                    <img class="h-20 w-20 rounded-full object-cover border-4 border-orange-100" 
                         src="{{ Storage::url($client->profile_picture) }}" 
                         alt="{{ $client->user->name }}">
                @else
                    <div class="h-20 w-20 rounded-full bg-orange-100 border-4 border-orange-200 flex items-center justify-center">
                        <span class="text-2xl font-bold text-orange-600">{{ substr($client->user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $client->user->name }}</h2>
                <p class="text-gray-600 mt-1">{{ $client->user->email }}</p>
                <p class="text-gray-600">{{ $client->phone }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
    <div class="p-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('general')" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm {{ request()->get('tab') == 'general' || !request()->get('tab') ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Informations Générales
                </button>
                <button onclick="showTab('documents')" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm {{ request()->get('tab') == 'documents' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Documents
                </button>
                <button onclick="showTab('preferences')" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm {{ request()->get('tab') == 'preferences' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Préférences
                </button>
                <button onclick="showTab('security')" 
                        class="tab-button py-2 px-1 border-b-2 font-medium text-sm {{ request()->get('tab') == 'security' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Sécurité
                </button>
            </nav>
        </div>
    </div>
</div>

<!-- Tab Content -->
<div id="general-tab" class="tab-content {{ request()->get('tab') == 'general' || !request()->get('tab') ? '' : 'hidden' }}">
    @include('client.profile.partials.general')
</div>

<div id="documents-tab" class="tab-content {{ request()->get('tab') == 'documents' ? '' : 'hidden' }}">
    @include('client.profile.partials.documents')
</div>

<div id="preferences-tab" class="tab-content {{ request()->get('tab') == 'preferences' ? '' : 'hidden' }}">
    @include('client.profile.partials.preferences')
</div>

<div id="security-tab" class="tab-content {{ request()->get('tab') == 'security' ? '' : 'hidden' }}">
    @include('client.profile.partials.security')
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-blue-500', 'text-blue-600');
    
    // Update URL without page reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

// Initialize tab based on URL parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        showTab(tab);
    }
});
</script>
@endsection


