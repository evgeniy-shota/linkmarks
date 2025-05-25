@php
    $classes = 'text-amber-300 bg-amber-300/10 rounded border-amber-300 border-b-2 py-1 px-1 ';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
