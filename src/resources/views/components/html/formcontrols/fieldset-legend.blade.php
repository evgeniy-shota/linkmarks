@props(['text', 'errorText' => null])

@php
    $classes = 'text-gray-100 ' . ($errorText ? 'text-red-200' : '');
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $errorText ? $errorText : $text }}
</div>
