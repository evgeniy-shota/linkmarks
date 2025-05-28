@props(['outline' => false])

@php
    $defaultClasses = 'bg-amber-300 text-gray-700 ';
    $outlineClasses = 'border-1 border-amber-300 text-amber-300 ';

    $classes =
        'rounded px-1 font-normal ' .
        ($outline ? $outlineClasses : $defaultClasses);

@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
