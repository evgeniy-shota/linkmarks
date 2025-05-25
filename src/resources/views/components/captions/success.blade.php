@php
    $classes =
        'text-green-400 bg-green-400/10 rounded border-green-400 border-b-2 py-1 px-1 ';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
