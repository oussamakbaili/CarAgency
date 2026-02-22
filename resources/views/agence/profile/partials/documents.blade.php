<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Documents de l'Agence</h3>
        <p class="text-sm text-gray-600 mb-4">Téléchargez et gérez les documents officiels de votre agence (licence, assurance, etc.).</p>
    </div>
    
    <!-- Upload New Document -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-medium text-gray-900 mb-4">Télécharger un nouveau document</h4>
        
        <form action="{{ route('agence.profile.documents.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">Type de document</label>
                    <select name="document_type" id="document_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('document_type') border-red-500 @enderror" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="license" {{ old('document_type') == 'license' ? 'selected' : '' }}>Licence d'exploitation</option>
                        <option value="insurance" {{ old('document_type') == 'insurance' ? 'selected' : '' }}>Assurance</option>
                        <option value="registration" {{ old('document_type') == 'registration' ? 'selected' : '' }}>Enregistrement</option>
                        <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('document_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">Fichier</label>
                    <input type="file" name="document" id="document" accept=".pdf,.jpg,.jpeg,.png" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('document') border-red-500 @enderror" required>
                    @error('document')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Formats acceptés: PDF, JPG, PNG (max 10MB)</p>
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                       placeholder="Description du document...">
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Télécharger
                </button>
            </div>
        </form>
    </div>
    
    <!-- Documents List -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-900">Documents téléchargés</h4>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($agency->documents ?? [] as $index => $document)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if(str_contains($document['path'], '.pdf'))
                                <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                @switch($document['type'])
                                    @case('license') Licence d'exploitation @break
                                    @case('insurance') Assurance @break
                                    @case('registration') Enregistrement @break
                                    @default {{ ucfirst($document['type']) }} @break
                                @endswitch
                            </p>
                            <p class="text-sm text-gray-500">{{ $document['filename'] }}</p>
                            @if($document['description'])
                                <p class="text-xs text-gray-400">{{ $document['description'] }}</p>
                            @endif
                            <p class="text-xs text-gray-400">
                                Téléchargé le {{ \Carbon\Carbon::parse($document['uploaded_at'])->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <a href="{{ Storage::url($document['path']) }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir
                        </a>
                        <form action="{{ route('agence.profile.documents.delete', $index) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par télécharger un document.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
