@props(['rating' => 0, 'size' => 'w-5 h-5', 'interactive' => false, 'name' => 'rating'])

<div class="flex items-center space-x-1" x-data="{ 
    rating: {{ $rating }}, 
    hoverRating: 0,
    isInteractive: {{ $interactive ? 'true' : 'false' }}
}">
    @for($i = 1; $i <= 5; $i++)
        <svg 
            class="{{ $size }} transition-colors duration-150 {{ $interactive ? 'cursor-pointer hover:scale-110' : '' }}"
            :class="{
                'text-yellow-400': (isInteractive ? hoverRating : rating) >= {{ $i }},
                'text-gray-300': (isInteractive ? hoverRating : rating) < {{ $i }}
            }"
            @if($interactive)
                @click="rating = {{ $i }}; $dispatch('rating-changed', {{ $i }})"
                @mouseenter="hoverRating = {{ $i }}"
                @mouseleave="hoverRating = 0"
            @endif
            fill="currentColor" 
            viewBox="0 0 20 20"
        >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    @endfor
    
    @if($interactive)
        <input type="hidden" name="{{ $name }}" x-model="rating" />
    @endif
</div>
