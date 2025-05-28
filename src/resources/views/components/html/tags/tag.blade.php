@props([
    'background' => '#50d71e',
    'color' => '#4b5563',
    'action' => null,
    'state' => null,
    'xText' => null,
])

@php
    $colors = 'bg-gray-500 text-gray-200 hover:bg-gray-500/85 ';

    $classes =
        'flex gap-1 justify-start items-center cursor-pointer rounded-sm px-1 font-normal transition truncate ' .
        $colors;
@endphp

{{-- x-bind:style="{background-color: '{{ $background }}', color: '{{ $color }}'}" --}}

<div {{ $attributes->merge(['class' => $classes]) }}>
    <template x-if="{{ $state }}==null">
        <div class="flex-none">
            <x-html.icons.square />
        </div>
    </template>

    <template x-if="{{ $state }}==false">
        <div class="flex-none">
            <x-html.icons.x-square />
        </div>
    </template>

    <template x-if="{{ $state }}==true">
        <div class="flex-none">
            <x-html.icons.check-square />
        </div>
    </template>
    <div x-text='{{ $xText }}' x-bind:title="{{ $xText }}">
        {{ $slot ?? '' }}
    </div>
</div>
