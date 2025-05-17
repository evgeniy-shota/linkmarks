@props(['action' => null, 'type' => 'button'])

@php
    $classes = "border-box btn bg-gray-500 border-slate-500 hover:border-slate-400 text-gray-100 shadow-md";
@endphp


<button @@click="{{ $action }}" type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
