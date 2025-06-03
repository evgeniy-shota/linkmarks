@props([
    'background' => '#50d71e',
    'color' => '#4b5563',
    'state' => null,
    'click' => null,
    'xText' => null,
])

@php
    $colors = 'bg-gray-500 text-gray-200 hover:bg-gray-500/85 ';

    $classes =
        'flex gap-1 justify-start items-center rounded-sm px-1 font-normal transition truncate ' .
        $colors;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}
    x-on:click="{{ $click }}">
    <div class="flex-none">
        {{ $prefix ?? '' }}
    </div>

    <div class="flex-none" x-text='{{ $xText }}'
        x-bind:title="{{ $xText }}">
        {{ $content ?? '' }}
    </div>

    <div class="flex-none">
        {{ $postfix ?? '' }}
    </div>
</div>
