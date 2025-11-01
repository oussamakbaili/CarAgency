@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#C2410C] text-start text-base font-medium text-[#C2410C] bg-orange-50 focus:outline-none focus:text-[#9A3412] focus:bg-orange-100 focus:border-[#9A3412] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-[#C2410C] hover:bg-orange-50 hover:border-[#C2410C]/30 focus:outline-none focus:text-[#C2410C] focus:bg-orange-50 focus:border-[#C2410C]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot ?? '' }}
</a>
