@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#0A66C2] text-sm font-medium leading-5 text-[#0A66C2] focus:outline-none focus:border-[#0A66C2] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 hover:text-[#0A66C2] hover:border-[#0A66C2]/30 focus:outline-none focus:text-[#0A66C2] focus:border-[#0A66C2]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot ?? '' }}
</a>
