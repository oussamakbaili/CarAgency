@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#C2410C] text-sm font-medium leading-5 text-[#C2410C] focus:outline-none focus:border-[#9A3412] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 hover:text-[#C2410C] hover:border-[#C2410C]/30 focus:outline-none focus:text-[#C2410C] focus:border-[#C2410C]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot ?? '' }}
</a>
