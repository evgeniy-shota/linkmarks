@props(['justify' => 'center', 'width' => 'w-full'])

<div class = 'flex justify-{{ $justify }} items-center'>
    <div {{ $attributes->merge(['class' => 'rounded-sm bg-gray-600 px-4 py-3 ' . $width]) }}>
        {{ $slot }}
    </div>
</div>
