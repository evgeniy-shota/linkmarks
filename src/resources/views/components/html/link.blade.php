@props(['link', 'active' => false])

@php
    $classes =
        'border-1 rounded hover:border-gray-400 px-2 py-1 transition duration-300 ' .
        ($active ? 'border-gray-300' : 'border-gray-500');
@endphp

<div class="btn bg-gray-500 border-gray-600 text-gray-100 shadow-md py-2 px-1">
    <a href="{{ $link }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
</div>
{{-- bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md --}}
