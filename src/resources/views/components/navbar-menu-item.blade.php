@props(['route', 'isActive'])

@php
    // dump($route);
    // dump($isActive);
    $classes = $isActive ? 'underline text-white' : '';
@endphp

<li>
    <a href="{{ $route }}"
        {{ $attributes->merge(['class' => 'text-base ' . $classes]) }}>
        {{ $slot }}
    </a>
</li>
