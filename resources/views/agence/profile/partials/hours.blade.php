<form action="{{ route('agence.profile.hours') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Heures d'Ouverture</h3>
            <p class="text-sm text-gray-600 mb-4">Définissez les heures d'ouverture pour chaque jour de la semaine.</p>
        </div>
        
        <div class="space-y-4">
            @php
                $days = [
                    'monday' => 'Lundi',
                    'tuesday' => 'Mardi', 
                    'wednesday' => 'Mercredi',
                    'thursday' => 'Jeudi',
                    'friday' => 'Vendredi',
                    'saturday' => 'Samedi',
                    'sunday' => 'Dimanche'
                ];
                $openingHours = $agency->opening_hours ?? [];
            @endphp
            
            @foreach($days as $dayKey => $dayName)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <div class="w-24">
                        <label class="block text-sm font-medium text-gray-700">{{ $dayName }}</label>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="opening_hours[{{ $dayKey }}][is_closed]" value="1" 
                               {{ isset($openingHours[$dayKey]['is_closed']) && $openingHours[$dayKey]['is_closed'] ? 'checked' : '' }}
                               onchange="toggleDayHours('{{ $dayKey }}')" 
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Fermé</span>
                    </div>
                    
                    <div class="flex items-center space-x-2" id="hours-{{ $dayKey }}">
                        <input type="hidden" name="opening_hours[{{ $dayKey }}][day]" value="{{ $dayName }}">
                        
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Ouverture</label>
                            <input type="time" name="opening_hours[{{ $dayKey }}][open_time]" 
                                   value="{{ $openingHours[$dayKey]['open_time'] ?? '09:00' }}"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Fermeture</label>
                            <input type="time" name="opening_hours[{{ $dayKey }}][close_time]" 
                                   value="{{ $openingHours[$dayKey]['close_time'] ?? '18:00' }}"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Sauvegarder
            </button>
        </div>
    </div>
</form>

<script>
function toggleDayHours(dayKey) {
    const checkbox = document.querySelector(`input[name="opening_hours[${dayKey}][is_closed]"]`);
    const hoursDiv = document.getElementById(`hours-${dayKey}`);
    
    if (checkbox.checked) {
        hoursDiv.style.opacity = '0.5';
        hoursDiv.style.pointerEvents = 'none';
    } else {
        hoursDiv.style.opacity = '1';
        hoursDiv.style.pointerEvents = 'auto';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    @foreach($days as $dayKey => $dayName)
        toggleDayHours('{{ $dayKey }}');
    @endforeach
});
</script>
