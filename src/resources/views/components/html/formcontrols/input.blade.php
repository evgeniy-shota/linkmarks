@props(['id', 'type' => 'text', 'placeholder' => null, 'state', 'value' => null])

@php
    $classes = 'input w-full bg-gray-700 ' . ($state ? '' : 'border-red-300');
@endphp

<input id="{{ $id }}" name="{{ $id }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => $classes]) }} value="{{ $value }}" />
