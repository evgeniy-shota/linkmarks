@props([
    'background' => '#50d71e',
    'color' => '#4b5563',
    'closeClick' => null,
    'state' => null,
    'xText' => null,
])

@php
    $colors = 'bg-gray-500 text-gray-200 hover:bg-gray-500/85 ';

    $classes =
        'flex gap-1 justify-start items-center rounded-sm px-1 font-normal transition truncate ' .
        $colors;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>

    <div x-text='{{ $xText }}' x-bind:title="{{ $xText }}">
        {{ $slot ?? '' }}
    </div>

    <div class="border-s-1 hover:text-amber-300 cursor-pointer transition"
        @@click="{{ $closeClick }}">
        <x-html.icons.x />
    </div>
</div>
