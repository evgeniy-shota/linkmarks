@props(['id' => null, 'required' => true])

@php
    $classes =
        'text-sm w-full file:bg-gray-500 file:border-1 file:border-gray-600 file:btn file:text-gray-100 hover:file:border-gray-500 file:rounded-sm cursor-pointer file:py-3 file:px-4 file:me-3 rounded-sm bg-gray-700 text-gray-100';
@endphp

<input x-ref="{{ $id }}" @required($required) type="file" name="{{ $id }}" id="{{ $id }}"
    {{ $attributes->merge(['class' => $classes]) }}>
