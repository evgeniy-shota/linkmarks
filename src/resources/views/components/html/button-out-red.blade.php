@props(['action' => null, 'type' => 'button'])

@php
    $classes = "border-box btn bg-gray-500 border-rose-700 hover:border-rose-600 text-gray-100 shadow-md";
@endphp


<button @@click="{{ $action }}" type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
