@props(['id' => null, 'size' => '32', 'src' => null, 'xSrc' => null, 'onclick' => null])

@php
    $classes = "relative flex justify-center w-$size h-$size items-center flex-none  rounded-sm";
@endphp

<div @@click="{{ $onclick }}" {{ $attributes->merge(['class' => $classes]) }}>
    <div class="absolute top-0 left-0 w-full h-full border-box rounded-sm p-1">
        {{ $topLeftContainer ?? null }}
        {{-- <div class="bg-gray-600/30 backdrop-blur-md text-gray-100">1</div> --}}
    </div>

    <picture>
        <img x-ref="{{ $id }}" id="{{ $id }}" src="{{ $src }}" :src="{{ $xSrc }}"
            alt="" class="flex-none ">
    </picture>
    {{-- <span class="loading loading-spinner loading-xl"></span> --}}
</div>
