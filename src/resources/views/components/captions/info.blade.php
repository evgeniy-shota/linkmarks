@php
    $classes =
        'text-sky-300 bg-sky-300/10 rounded border-sky-300 border-b-2 py-1 px-1 ';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
