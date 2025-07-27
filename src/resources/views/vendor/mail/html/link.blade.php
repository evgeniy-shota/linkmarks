@props([
    'url',
    'color' => 'primary',
])
<a href="{{ $url }}" class="link link-{{ $color }}" target="_blank" rel="noopener">{!! $slot !!}</a>

