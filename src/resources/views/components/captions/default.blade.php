@php
    $classes = 'text-gray-300 bg-gray-300/10 rounded border-gray-300 border-b-2 py-1 px-1 ';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
