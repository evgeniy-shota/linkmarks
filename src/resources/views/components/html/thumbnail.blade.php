@props([
    'id' => null,
    'size' => '32',
    'src' => null,
    'xSrc' => null,
    'onclick' => null,
])

@php
    $classes = "relative box-border flex justify-center items-center min-w-$size h-$size rounded-sm border-1 border-gray-800/0 transition";
@endphp

<div @@click="{{ $onclick }}"
    {{ $attributes->merge(['class' => $classes]) }}>
    <div
        class="absolute top-0 left-0 w-full h-full border-box rounded-sm p-1 flex-none">
        {{ $topLeftContainer ?? null }}
        {{-- <div class="bg-gray-600/30 backdrop-blur-md text-gray-100">1</div> --}}
    </div>

    <img x-ref="{{ $id }}" id="{{ $id }}"
        src="{{ $src }}" :src="{{ $xSrc }}" alt=""
        class="flex-none max-w-full max-h-full">

</div>
