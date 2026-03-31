@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#0A66C2] text-start text-base font-medium text-[#0A66C2] bg-orange-50 focus:outline-none focus:text-[#0A66C2] focus:bg-orange-100 focus:border-[#0A66C2] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-[#0A66C2] hover:bg-orange-50 hover:border-[#0A66C2]/30 focus:outline-none focus:text-[#0A66C2] focus:bg-orange-50 focus:border-[#0A66C2]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot ?? '' }}
</a>
