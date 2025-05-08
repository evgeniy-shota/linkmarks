@props(['route'])

@php
    $classes = Request::routeIs($route) ? 'underline text-white' : '';
@endphp

<li>
    <a href="{{ $route }}" {{ $attributes->merge(['class' => '' . $classes]) }}>
        {{ $slot }}
    </a>
</li>
