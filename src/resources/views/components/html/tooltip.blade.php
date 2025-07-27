@props(['tip', 'direction' => 'top', 'bgColor' => 'bg-gray-600'])

@php
    $tooltipDirection = match ($direction) {
        'left' => 'tooltip-left',
        'right' => 'tooltip-right',
        'bottom' => 'tooltip-bottom',
        default => 'tooltip-top',
    };
@endphp

<div {{ $attributes->merge(['class' => 'w-full tooltip ' . $tooltipDirection]) }}>
    <div class="tooltip-content {{ $bgColor }}">
        {{ $tip }}
    </div>
    {{ $slot }}
</div>
