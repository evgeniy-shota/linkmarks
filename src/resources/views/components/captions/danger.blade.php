@php
    $classes = 'text-rose-300 bg-rose-300/10 rounded border-rose-300 border-b-2 py-1 px-1 ';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
