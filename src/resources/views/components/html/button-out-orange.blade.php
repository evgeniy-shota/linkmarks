@props(['action' => null, 'type' => 'button'])

@php
    $classes = "border-box btn bg-gray-500 border-orange-700 hover:border-orange-600 text-gray-100 shadow-md";
@endphp


<button @@click="{{ $action }}" type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
