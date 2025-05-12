@php
    $classes =
        'relative inline-block bg-gray-600 text-gray-100 hover:bg-gray-500 hover:text-gray-50 box-border rounded-md w-full px-1 py-1 transition duration-150';
@endphp
<div x-data {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
