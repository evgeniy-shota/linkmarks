@props(['tip', 'direction' => 'top'])

@php
    $tooltipDirection = match ($direction) {
        'left' => 'tooltip-left',
        'right' => 'tooltip-right',
        'bottom' => 'tooltip-bottom',
        default => 'tooltip-top',
    };
@endphp

<div {{ $attributes->merge(['class' => 'w-full tooltip ' . $tooltipDirection]) }}>
    <div class="tooltip-content bg-gray-600">
        {{ $tip }}
    </div>
    {{ $slot }}
</div>
