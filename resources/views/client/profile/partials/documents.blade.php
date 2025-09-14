<div class="space-y-6">
    <!-- Documents Upload Section -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Télécharger des Documents</h3>
        <p class="text-gray-600 mb-6">Téléchargez vos documents d'identité et de conduite pour faciliter vos réservations.</p>
        
        <form action="{{ route('client.profile.documents') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- CIN Document -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Carte d'Identité Nationale</h4>
                    <div class="space-y-3">
                        <input type="file" name="cin_document" id="cin_document" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @if(isset($client->documents['cin_document']))
                            <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                <span class="text-sm text-green-700">✓ Document téléchargé</span>
                                <button type="button" onclick="deleteDocument('cin_document')" 
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Driving License Document -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Permis de Conduire</h4>
                    <div class="space-y-3">
                        <input type="file" name="driving_license_document" id="driving_license_document" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @if(isset($client->documents['driving_license_document']))
                            <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                <span class="text-sm text-green-700">✓ Document téléchargé</span>
                                <button type="button" onclick="deleteDocument('driving_license_document')" 
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Identity Document -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Document d'Identité</h4>
                    <div class="space-y-3">
                        <input type="file" name="identity_document" id="identity_document" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.jpg,.jpeg,.png">
                        @if(isset($client->documents['identity_document']))
                            <div class="flex items-center justify-between bg-green-50 p-2 rounded">
                                <span class="text-sm text-green-700">✓ Document téléchargé</span>
                                <button type="button" onclick="deleteDocument('identity_document')" 
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Télécharger les Documents
                </button>
            </div>
        </form>
    </div>

    <!-- Document Requirements -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-4">Exigences des Documents</h3>
        <div class="space-y-3 text-sm text-blue-800">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Formats acceptés : PDF, JPG, JPEG, PNG</span>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Taille maximale : 2 MB par document</span>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Documents doivent être lisibles et en couleur</span>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Les documents sont vérifiés par notre équipe</span>
            </div>
        </div>
    </div>

    <!-- Current Documents Status -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Statut des Documents</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(isset($client->documents['cin_document']))
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Carte d'Identité Nationale</h4>
                        <p class="text-sm text-gray-500">
                            @if(isset($client->documents['cin_document']))
                                Document téléchargé
                            @else
                                Document requis
                            @endif
                        </p>
                    </div>
                </div>
                <div>
                    @if(isset($client->documents['cin_document']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Téléchargé
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Manquant
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(isset($client->documents['driving_license_document']))
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Permis de Conduire</h4>
                        <p class="text-sm text-gray-500">
                            @if(isset($client->documents['driving_license_document']))
                                Document téléchargé
                            @else
                                Document requis pour la location
                            @endif
                        </p>
                    </div>
                </div>
                <div>
                    @if(isset($client->documents['driving_license_document']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Téléchargé
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Recommandé
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(isset($client->documents['identity_document']))
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900">Document d'Identité Supplémentaire</h4>
                        <p class="text-sm text-gray-500">
                            @if(isset($client->documents['identity_document']))
                                Document téléchargé
                            @else
                                Document optionnel
                            @endif
                        </p>
                    </div>
                </div>
                <div>
                    @if(isset($client->documents['identity_document']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Téléchargé
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Optionnel
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDocument(documentType) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
        fetch(`{{ route('client.profile.documents.delete', '') }}/${documentType}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression du document');
            }
        });
    }
}
</script>


