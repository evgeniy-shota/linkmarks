@props(['condition' => false, 'action' => '', 'disable' => 'false'])

@php
    $classes =
        'flex justify-start items-center border-b-1 border-gray-500 hover:border-gray-400 gap-1 py-1 px-1 flex-none cursor-pointer mb-1 transition';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}
    @@click="!{{ $disable }} ? {{ $action }}:''"
    x-bind:class="{ 'text-gray-400': {{ $disable }} }">
    <template x-if="{{ $condition }}">
        <x-html.icons.check-square />
    </template>
    <template x-if="!{{ $condition }}">
        <x-html.icons.square />
    </template>

    {{ $label }}
</div>
