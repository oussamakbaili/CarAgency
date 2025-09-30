@props(['agency'])

@php
    $stats = [
        'cancellation_count' => $agency->cancellation_count ?? 0,
        'max_cancellations' => $agency->max_cancellations ?? 3,
        'remaining_cancellations' => ($agency->max_cancellations ?? 3) - ($agency->cancellation_count ?? 0),
        'is_suspended' => $agency->isSuspended(),
        'warning_message' => $agency->getCancellationWarningMessage()
    ];
@endphp

@if($stats['cancellation_count'] > 0 || $stats['is_suspended'])
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">
            @if($stats['is_suspended'])
                <span class="text-red-600">🚫 Compte Suspendu</span>
            @else
                <span class="text-yellow-600">⚠️ Avertissement d'Annulation</span>
            @endif
        </h3>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Annulations:</span>
            <span class="text-lg font-bold {{ $stats['remaining_cancellations'] <= 1 ? 'text-red-600' : 'text-yellow-600' }}">
                {{ $stats['cancellation_count'] }}/{{ $stats['max_cancellations'] }}
            </span>
        </div>
    </div>

    @if($stats['warning_message'])
    <div class="bg-{{ $stats['is_suspended'] ? 'red' : 'yellow' }}-50 border-l-4 border-{{ $stats['is_suspended'] ? 'red' : 'yellow' }}-400 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-{{ $stats['is_suspended'] ? 'red' : 'yellow' }}-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-{{ $stats['is_suspended'] ? 'red' : 'yellow' }}-700 font-medium">
                    {{ $stats['warning_message'] }}
                </p>
            </div>
        </div>
    </div>
    @endif

    @if($stats['is_suspended'])
    <div class="bg-red-50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-red-800 mb-2">Actions requises:</h4>
        <ul class="text-sm text-red-700 space-y-1">
            <li>• Contactez l'administrateur pour réactiver votre compte</li>
            <li>• Expliquez les raisons des annulations</li>
            <li>• Fournissez un plan d'amélioration</li>
        </ul>
        <div class="mt-4">
            <a href="mailto:support@rentcarplatform.com" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                Contacter le support
            </a>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-yellow-800 mb-2">Recommandations:</h4>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Évitez d'annuler des réservations sauf en cas d'urgence</li>
            <li>• Contactez vos clients directement pour résoudre les problèmes</li>
            <li>• Mettez à jour la disponibilité de vos véhicules régulièrement</li>
        </ul>
    </div>
    @endif

    <!-- Progress Bar -->
    <div class="mt-4">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Annulations restantes</span>
            <span>{{ $stats['remaining_cancellations'] }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="h-2 rounded-full {{ $stats['remaining_cancellations'] <= 1 ? 'bg-red-500' : 'bg-yellow-500' }}" 
                 style="width: {{ ($stats['cancellation_count'] / $stats['max_cancellations']) * 100 }}%"></div>
        </div>
    </div>
</div>
@endif
